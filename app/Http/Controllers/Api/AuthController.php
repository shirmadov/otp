<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Enums\TokenAbility;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{

    public function register(Request $request){

//        dd(date("H:i:s", 120));

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $request['password']=Hash::make($request['password']);
        $request['remember_token'] = Str::random(10);
        $user = User::create($request->toArray());
//        return response()->json([
//            'status' => true,
//            'message' => 'User Created Successfully',
//            'token' => $user->createToken("API TOKEN")->plainTextToken
//        ], 200);



        $accessToken = $user->createToken('access_token', [TokenAbility::ACCESS_API->value], config('sanctum.expiration'));
        $refreshToken = $user->createToken('refresh_token', [TokenAbility::ISSUE_ACCESS_TOKEN->value], config('sanctum.rt_expiration'));

         return [
             'token' => $accessToken->plainTextToken,
             'refresh_token' => $refreshToken->plainTextToken,
         ];
    }

    public function refresh(Request $request){
        $accessToken = $request->user()->createToken('access_token', [TokenAbility::ACCESS_API->value], config('sanctum.expiration'));

        return ['token' => $accessToken->plainTextToken];
    }

    public function loginUser(Request $request)
    {
        $validateUser = Validator::make($request->all(),
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);

        if(!Auth::attempt($request->only(['email', 'password']))){
            return response()->json([
                'status' => false,
                'message' => 'Email & Password does not match with our record.',
            ], 401);
        }


        $user = User::where('email', $request->email)->first();

        return response()->json([
            'status' => true,
            'message' => 'User Logged In Successfully',
            'token' => $user->createToken("API TOKEN")->plainTextToken,
            'refresh_token' => $user->createToken("API TOKEN")->accessToken->refresh_token,
        ], 200);
    }
}
