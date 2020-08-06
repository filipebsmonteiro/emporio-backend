<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        /*//Verifica se a exception vem de uma Query
        if (
            $exception instanceof QueryException &&
            env('APP_DEBUG') == false
        ) {

            //Redireciona de Volta com a mensagem de Erro
            if ($request->ajax() ){//|| $request->wantsJson()) {
                $json = [
                    'message' => $exception->errorInfo[2],
                ];

                return response()->json($json, 400);
            }else{
                flash($exception->errorInfo[2], 'danger')->important();
                return redirect()->back()->withInput( $request->except('email') );
            }
        }*/

        //Verifica se a exception vem de um Erro Forbidden retorna view personalizada
        //if($e instanceof HttpException && $e->getStatusCode() == 403){
        //    return new JsonResponse($e->getMessage(), 403);
        //}

        //Verifica se a exception vem de um Erro NotFound retorna view personalizada
        //if($e instanceof HttpException && $e->getStatusCode() == 404){
        //    return new JsonResponse($e->getMessage(), 403);
        //}

        return parent::render($request, $exception);
    }
}
