<?php

namespace App\Http\Controllers;

use App\Constants\TransactionStatus;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\HasExternalQuery;
use App\Traits\TransactionAutorization;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    use HasExternalQuery;

    protected $model = Account::class;
    /**
     *  Returns the balance of the account linked to the authenticated user
     *
     * @return Integer
     */
    protected function getBalance()
    {
        try {
            $account = $this->model::where('user_id', auth()->id())->firstOrFail();
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 404,
                'data' => $th->getMessage(),
                'error' => true
            ]);
        }

        return response()->json([
            'status' => 200,
            'data' => $account,
            'error' => false
        ]);
    }

    /**
     * Performs the transfer if the authenticated user has the client role
     *
     * @return \Illuminate\Http\Response
     */
    protected function doTransaction(Request $request)
    {
        try {
            $transaction = DB::transaction(function ($query) use ($request) {
                
                if(auth()->user()->role != 'client' || auth()->user()->id == request('payee')) {
                    throw new Exception('This transaction is not possible.', 404); 
                }
            
                $validator = Validator::make($request->all(), [
                    'value' => 'required|integer',
                    'payee' => 'required|exists:users,id',
                ]);
            
                if ($validator->fails()) {
                    throw new Exception($validator->errors()->first(), 404); 
                }
            
                $validator = $validator->validated();
                $accountPayer = $this->model::where('user_id', auth()->id())->firstOrFail();
                $accountPayee = $this->model::where('user_id', $validator['payee'])->firstOrFail();
    
                if(!$accountPayer->balance >= $validator['value']) {
                    throw new Exception("Saldo insuficiente.", 404);                
                }
    
                $transaction = new Transaction();
                $transaction->fill([
                    'account_id' => $accountPayer->account->id,
                    'user_id' => $accountPayee->id,
                    'amount' => $validator['value'],
                    'status' => TransactionStatus::IN_ANALYSIS
                ])->save();
    
                 // Consultando serviço autorizador externo
                $authorization = $this->getQuery('https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6');
                if(!is_object($authorization) || $authorization->message != 'Autorizado') {
                    throw new Exception("Transação não autorizada.", 404);  
                }
    
                $newPayerBalance = $accountPayer->balance - $validator['value'];
                $accountPayer->update(['balance' => $newPayerBalance]);
    
                $newPayeeBalance = $accountPayee->balance + $validator['value'];
                $accountPayee->update(['balance' => $newPayeeBalance]);
    
                $transaction->update(['status' => TransactionStatus::PAID]);
                $this->sendNotification($accountPayer);
                $this->sendNotification($accountPayee);
    
                return $transaction;
            });
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 404,
                'data' => $th->getMessage(),
                'error' => true
            ]);
        }

        return response()->json([
            'status' => 200,
            'data' => [
                'transaction' => $transaction
            ],
            'error' => false
        ]);
    }

    protected function sendNotification(Account $user) 
    {
        return $this->getQuery('http://o4d9z.mocklab.io/notify');
    }
}
