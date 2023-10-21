<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JWTController extends Controller
{


    public function login(Request $request)
    {
//        $validateUser = Validator::make($request->all(),
//            [
//                'email' => 'required|email',
//                'password' => 'required'
//            ]);
//
//        if(!Auth::attempt($request->only(['email', 'password']))){
//            return response()->json([
//                'status' => false,
//                'message' => 'Email & Password does not match with our record.',
//            ], 401);
//        }


//        $user = User::where('email', $request->email)->first();

        $credentials = request(['email', 'password']);
//        dd($token);
        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
