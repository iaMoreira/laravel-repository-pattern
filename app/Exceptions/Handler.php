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
        if ($exception instanceof BaseException) {
            return $this->responseJsonWithError($exception->getMessage(), $exception->getCode());
        }

        if ($exception instanceof \InvalidArgumentException) {
            return $this->responseJsonWithError('API.validation_error', 400, json_decode($exception->getMessage()));
        }

        return parent::render($request, $exception);
    }

    public function responseJsonWithError($message, $code = 400, $data = null)
    {
        $data_error['status'] = 'error';
        $data_error['code'] = $code;
        $data_error['data'] = $data;
        $data_error['message'] = $message;
        return response()->json($data_error, $code);
    }
}
