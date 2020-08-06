<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; 

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required', 
            'email' => 'required|email', 
            'password' => 'required|min:6'
        ]);


        $user = User::create([
            'name' => $request->name, 
            'email' => $request->email, 
            'password' => bcrypt($request->password)
        ]);

        return response()->json($user);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ]);
    
        if( Auth::attempt(['email'=>$request->email, 'password'=>$request->password]) ) {
    
            $user = Auth::user();
            $userRole = $user->role()->first();
    
            if ($userRole) {
                $this->scope = $userRole->role;
            }
    
            $token = $user->createToken($user->email.'-'.now(), [$this->scope]);
    
            return response()->json([
                'token' => $token->accessToken
            ]);
        }
    }

    public function logout(){
        $accessToken = auth()->user()->token();

        $refreshToken = DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]);

        $accessToken->revoke();

        return response()->json(['status' => 200]);
    }
}
