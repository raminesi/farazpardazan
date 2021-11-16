<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Traits\ApiResponseTrait;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    use ApiResponseTrait;
    /**
     * @OA\Post(
     *   path="/api/register",
     *   tags={"Auth"},
     *   summary="Register",
     *   operationId="Register",
     *
     *   @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(
     *      @OA\Property(property="name", type="string", format="string", example="name"),
     *      @OA\Property(property="email", type="string", format="emai", example="user@gmail.com"),
     *      @OA\Property(property="password", type="string", format="password", example="123456"),
     *      @OA\Property(property="confirm_password", type="string", format="password", example="123456"),
     *      )
     *   ),
     *
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *    response=401,
     *    description="Returns when user is not authenticated",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example=""),
     *    )
     *   ),
     *)
     **/
    /**
     * register api
     *
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(AuthRequest $request)
    {
        DB::beginTransaction();
        try
        {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);
            $tokenResult = $user->createToken('Personal Access Token');
            DB::commit();
            return $this->apiResponse(
                [
                    'success' => true,
                    'result' => [
                    'token' => $tokenResult->plainTextToken,
                    'user' => $user
                    ]
                ]
            );
        }catch (\Exception $e){
            DB::rollBack();
            return $e;
            return $this->respondError('Registration failed');
        }
    }

    /**
     * @OA\Post(
     *   path="/api/login",
     *   tags={"Auth"},
     *   summary="Login",
     *   operationId="Login",
     *
     *   @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      example="user@gmail.com",
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      example="123456",
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *    response=401,
     *    description="Returns when user is not authenticated",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example=""),
     *    )
     *   ),
     *)
     **/
    /**
     * login api
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(AuthRequest $request)
    {
        if(Auth::attempt(['email' => $request->email , 'password' => $request->password])){
            $user = User::where('email' , $request->email)->first();
            $tokenResult = $user->createToken('Personal Access Token');
            return $this->apiResponse(
                [
                    'success' => true,
                    'result' => [
                        'token' => $tokenResult->plainTextToken,
                        'user' => $user
                    ]
                ]
            );
        }else{
            return $this->respondUnAuthorized();
        }
    }
}
