<?php

namespace App\Exceptions;

use App\Traits\Response;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Override;
use Symfony\Component\HttpFoundation\Response as HttpStatus;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use Response;

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

    /**
     * Handle exception common.
     *
     * @param  Request  $request  Request
     * @param  Throwable  $e  Throwable
     *
     * @throws AppException MethodNotAllowedHttpException | TooManyRequestsHttpException | TooManyRequestsHttpException | QueryException
     * @throws Throwable Throwable
     *
     * @return HttpStatus Response
     */
    #[Override]
    public function render($request, Throwable $e)
    {
        if ($e instanceof NotFoundHttpException) {
            throw new AppException($e->getMessage(), $e, HttpStatus::HTTP_NOT_FOUND);
        }
        if ($e instanceof MethodNotAllowedHttpException) {
            throw new AppException($e->getMessage(), $e, HttpStatus::HTTP_METHOD_NOT_ALLOWED);
        }
        if ($e instanceof TooManyRequestsHttpException) {
            throw new AppException($e->getMessage(), $e, HttpStatus::HTTP_TOO_MANY_REQUESTS);
        }
        if ($e instanceof QueryException) {
            throw new AppException($e->getMessage(), $e, HttpStatus::HTTP_INTERNAL_SERVER_ERROR);
        }

        return parent::render($request, $e);
    }

    /**
     * Custom response error validation.
     *
     * @param  $request
     * @param  ValidationException  $exception  ValidationException
     * @return JsonResponse Error validation
     */
    protected function invalidJson($request, ValidationException $exception): JsonResponse
    {
        return $this->error($exception->getMessage(), HttpStatus::HTTP_UNPROCESSABLE_ENTITY);
    }
}
