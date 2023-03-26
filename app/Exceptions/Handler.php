<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{

    public function render($request, Throwable $exception)
    {
        switch (true) {
            case $exception instanceof ModelNotFoundException:
                $statusCode = 404;
                $message = $exception->getMessage() ?? 'The requested resource was not found';
                break;
            case $exception instanceof RelationNotFoundException:
                $statusCode = 400;
                $message = $exception->getMessage() ?? 'Invalid relationship requested';
                break;
            case $exception instanceof AuthorizationException:
                $statusCode = 400;
                $message = $exception->getMessage() ?? 'you do not have permission';
                break;
            default:
                $statusCode = 500;
                $message = 'Something went wrong';
                Log::error($exception->getMessage(), ['exception' => $exception]);
                break;
        }

        return response()->json([
            'message' => $message,
            'status' => $statusCode,
        ], $statusCode);
    }

    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
