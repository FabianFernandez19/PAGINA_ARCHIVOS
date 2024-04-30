<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function signUp(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string'
        ]);

        $user =
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        // Asignación del rol
        $user->assignRole('User');

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;  // Accede correctamente al objeto token
        
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);  // Establece la fecha de expiración
        }
        $token->save();  // Guarda los cambios en la base de datos
        

        return response()->json([
            'rol' => 'User',
            'user' => $user,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($token->expires_at)->toDateTimeString()
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        $credentials = request(['email', 'password']);
        
        if (!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);

        $user = $request->user();

        $roles=$user->getRoleNames();

        $tokenResult = $user->createToken('Personal Access Token');

        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        return response()->json([
            'rol' => $roles[0],
            'user' => $user,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($token->expires_at)->toDateTimeString()
        ]);
    }
    /*public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
    
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
    
        $user = Auth::user();
        $tokenResult = $user->createToken('Personal Access Token');
        $tokenModel = $tokenResult->token;  // Acceder al modelo de token
    
        if ($request->remember_me) {
            $tokenModel->expires_at = Carbon::now()->addWeeks(1);
        }
    
        $tokenModel->save();  // Guardar los cambios en el modelo de token en la base de datos
    
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => $tokenModel->expires_at ? $tokenModel->expires_at->toDateTimeString() : null
        ]);
    }*/
    


    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

        /**
     * Obtener el objeto User como json
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function update(Request $request, $id)
    {
        $users = User::find($id);
        $users->name =$request->name;
        $users->email =$request->email;
        $users->update();
        return response()->json($users, 200);
    }

    public function signUpadmin(Request $request)
    {

        $request->validate([
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string'
        ]);

        Admin::create([
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        // Asignación del rol

        $user->assignRole('Administrator');

        $tokenResult = $user->createToken('Personal Access Token');

        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        return response()->json([
            'rol' => 'Administrator',
            'user' => $user,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($token->expires_at)->toDateTimeString()
        ]);
    }










}
