<?php

namespace App\Http\Controllers\API\Site;

use App\Models\Cliente;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function resetPassword(Request $request, Cliente $model)
    {
        if(!$request->email || !$request->nascimento || !$request->password || !$request->confirm_password) {
            throw new BadRequestHttpException('Parâmetros Incorretos!');
        }
        if ($request->password !== $request->confirm_password) {
            throw new BadRequestHttpException('Senhas não são iguais!');
        }

        $cliente = $model->where([
            ['email', '=', $request->email],
            ['nascimento', '=', $request->nascimento]
        ])->first();

        if (!$cliente){
            throw new UnprocessableEntityHttpException('Sistema não encontrou Cliente com dados Informados!');
        }

        $cliente->update(['password' => bcrypt($request->password)]);

        return response()->json($cliente);
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me()
    {
        return response()->json(auth('api')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
