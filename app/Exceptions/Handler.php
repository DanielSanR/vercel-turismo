<?php

namespace App\Exceptions;

use Throwable;
use BadMethodCallException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Http\Response as HttpResponse;

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
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }



    /**
     * Exception ValidationException
     */
    protected function invalidJson($request, ValidationException $exception)
    {
        return response()->json([
            'ok' => false,
            'errores' => $exception->errors(),
        ], $exception->status);
    }

    /**
     * Exceptions
     */
    public function render($request, Throwable $exception)
    {

        if($request->expectsJson()) {
            if($exception instanceof ModelNotFoundException) {
                $model = class_basename($exception->getModel());

                return $this->responseJson("$model no encontrado");
            }

            if($exception instanceof NotFoundHttpException) {
                return $this->responseJson("URL no encontrada");
            }

            if($exception instanceof ValidationException) {
                return $this->responseJson(response()->errors($exception));
            }

            if($exception instanceof AuthenticationException) {
                return $this->responseJson("No logueado", HttpResponse::HTTP_UNAUTHORIZED);
            }

            if($exception instanceof AuthorizationException) {
                return $this->responseJson("No puede realizar esta acción", HttpResponse::HTTP_FORBIDDEN);
            }

            if($exception instanceof MethodNotAllowedHttpException) {
                return $this->responseJson("Método no permitido", HttpResponse::HTTP_METHOD_NOT_ALLOWED);
            }

            if($exception instanceof HttpException) {
                return $this->responseJson($exception->getMessage(), $exception->getStatusCode());
            }
        }

        return parent::render($request, $exception);
    }


    private function responseJson($errors, $statusCode = HttpResponse::HTTP_NOT_FOUND)
    {
        return response()->json([
            "ok" => false,
            "errors" => $errors,
        ], $statusCode);
    }


}
