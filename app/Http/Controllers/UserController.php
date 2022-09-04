<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\User;
use App\Rules\CPF;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    protected $model = User::class;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $users = $this->model::all();
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 404,
                'data' => $th->getMessage(),
                'error' => true
            ]);
        }

        return response()->json([
            'status' => 200,
            'data' => $users,
            'error' => false
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'role' => 'required|string|in:client,shopkeeper',
                'cpf' => ['nullable', 'string', 'unique:users', 'required_if:role,client', new CPF],
                'cnpj' => 'nullable|string|unique:users|required_if:role,shopkeeper',
                'email' => 'required|email|unique:users',
                'password' => 'required|string',
            ]);
            
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(), 404); 
            }

            $validator = $validator->validate();

            $user = new $this->model();
            $user->fill([
                'name' => $validator['name'],
                'role' => $validator['role'],
                'cpf' => $validator['role'] === 'client' ? $validator['cpf'] : null,
                'cnpj' => $validator['role'] === 'shopkeeper' ? $validator['cnpj'] : null,
                'email' => $validator['email'],
                'password' => bcrypt($validator['password'])
            ])->save();
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 404,
                'data' => $th->getMessage(),
                'error' => true
            ]);
        }

        return $this->login($request); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function show($userId)
    {
        try {
            $user = $this->model::findOrFail($userId);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 404,
                'data' => $th->getMessage(),
                'error' => true
            ]);
        }

        return response()->json([
            'status' => 200,
            'data' => $user,
            'error' => false
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $userId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'role' => 'required|string|in:client,shopkeeper',
                'cpf' => ['nullable', 'string', Rule::unique('users')->ignore($userId), 'required_if:role,client', new CPF],
                'cnpj' => ['nullable', 'string', Rule::unique('users')->ignore($userId), 'required_if:role,shopkeeper'],
                'email' => ['required', 'email', Rule::unique('users')->ignore($userId)],
                'password' => 'nullable|string',
            ]);
     
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(), 404); 
            }
            $validator = $validator->validate();
            
            $user = $this->model::find($userId);
            $user->update([
                'name' => $validator['name'],
                'role' => $validator['role'],
                'cpf' => $validator['role'] === 'client' ? $validator['cpf'] : null,
                'cnpj' => $validator['role'] === 'shopkeeper' ? $validator['cnpj'] : null,
                'email' => $validator['email'],
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 404,
                'data' => $th->getMessage(),
                'error' => true
            ]);
        }

        return response()->json([
            'status' => 200,
            'data' => $user,
            'error' => false
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function destroy($userId)
    {
        //
    }

    public function login(Request $request)
    {
        try {
            $credentials = request()->only('email', 'password');
            if(!auth()->attempt($credentials)) abort(401, 'Invalid crendentials.');
            $token = auth()->user()->createToken('auth_token');

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 404,
                'data' => [
                    'message' => $th->getMessage()
                ],
                'error' => true
            ]);
        }

        return response()->json([
            'status' => 200,
            'data' => [
                'token' => $token->plainTextToken
            ],
            'error' => false
        ]);
    }

    public function logout()
    {
        try {
            auth()->user()->tokens()->delete();
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 404,
                'data' => $th->getMessage(),
                'error' => true
            ]);
        }

        return response()->json([
            'status' => 200,
            'data' => 'Success',
            'error' => false
        ]);
    }
}
