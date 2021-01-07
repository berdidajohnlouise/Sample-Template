<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api',['except'=> ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function user()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    public function getUserEmail(){
        return response()->json([
            'status'=> true,
            'email'=> auth()->user()->email
        ],200);
    }

    public function updateProfile(Request $request){
        $validator = $this->validateInput($request->all());
        if($validator->fails() && auth()->user()->email == $request->email){
            if(!is_null($request->password)){
                auth()->user()->update([
                    'password'=>bcrypt($request->password)
                ]);
            }


            return response()->json([
                'status'=>true
            ],200);
        }

        if(!$validator->fails() && auth()->user()->email != $request->email){

            auth()->user()->update([
                'email'=>$request->email
            ]);

            if(!is_null($request->password)){
                auth()->user()->update([
                    'password'=>bcrypt($request->password)
                ]);
            }

            return response()->json([
                'status'=>true
            ],200);
        }


        return response()->json([
            'status'=>false,
            'errors'=>$validator->errors()
        ],422);

    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    private function validateInput(array $data){
        return Validator::make($data,[
            'email' => 'required|string|unique:users',
        ]);
    }

}
