<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\User;
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'role' => 'required|string|in:client,shopkeeper',
            'document' => ['required', 'string', 'unique:users'],
            'email' => 'required|email|unique:users',
            'password' => 'required|string',
        ]);

 
        if ($validator->fails()) {
            return response()->json([
                'status' => 404,
                'data' => $validator->errors()->first(),
                'error' => true
            ]);
        }
        $validator = $validator->validate();

        try {

            DB::transaction(function() use ($validator) {
                $user = new $this->model();
                $user->name = $validator['name'];
                $user->role = $validator['role'];
                $user->document = $validator['document'];
                $user->email = $validator['email'];
                $user->password = bcrypt($validator['password']);

                $user->save();

                $account = new Account();
                $account->fill([
                    'account_key' => $user->email,
                    'user_id' => $user->id
                ])->save();
            });
            
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $user = $this->model::findOrFail($id);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'role' => 'required|string|in:client,shopkeeper',
            'document' => ['required', 'string', Rule::unique('users')->ignore($id)],
            'email' => ['required', 'email', Rule::unique('users')->ignore($id)],
            'password' => 'nullable|string',
        ]);
 
        if ($validator->fails()) {
            return response()->json([
                'status' => 404,
                'data' => $validator->errors()->first(),
                'error' => true
            ]);
        }

        try {
            $user = $this->model::find($id);

            $user->name = $request->get('name');
            $user->role = $request->get('role');
            $user->document = $request->get('document');
            $user->email = $request->get('email');
            $user->password = bcrypt($request->get('password'));

            $user->save();

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $user = $this->model::findOrFail($id);
            $user->delete();
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
            'data' => 'Logout realizado com sucesso!',
            'error' => false
        ]);
    }
}
