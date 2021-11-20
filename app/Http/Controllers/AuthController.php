<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Validator;
    use Tymon\JWTAuth\Exceptions\JWTException;
    use Tymon\JWTAuth\Exceptions\TokenExpiredException;
    use Tymon\JWTAuth\Exceptions\TokenInvalidException;
    use Tymon\JWTAuth\Facades\JWTAuth;
class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            Config::set(['auth.defaults.guard' => 'web']);

            if (! $token = JWTAuth::attempt($credentials)) {
                Config::set(['auth.defaults.guard' => 'admin']);
                $token = null;

                    if (! $token = JWTAuth::attempt($credentials)) {
                        Auth::guard('admin');
                        return response()->json(['error' => 'invalid_credentials111'], 401);
                    }

        //                 $user = Auth::user();
            }
            Auth::guard('web');
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return response()->json(compact('token'));
    }


    public function getAuthenticatedUser()
        {
                try {

                        if (! $user = JWTAuth::parseToken()->authenticate()) {
                                return response()->json(['user_not_found'], 404);
                        }

                } catch (TokenExpiredException $e) {

                        return response()->json(['token_expired'], $e->getCode());

                } catch (TokenInvalidException $e) {

                        return response()->json(['token_invalid'], $e->getCode());

                } catch (JWTException $e) {

                        return response()->json(['token_absent'], $e->getCode());

                }

                return response()->json(compact('user'));
        }
        public function logout(Request $request)
        {
            $validator = Validator::make($request->only('token'), [
                'token' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 200);
            }

            //Request is validated, do logout
            try {
                JWTAuth::invalidate($request->token);

                return response()->json([
                    'success' => true,
                    'message' => 'User has been logged out'
                ]);

           } catch (JWTException $exception) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, user cannot be logged out'
                ]);
            }
        }
}