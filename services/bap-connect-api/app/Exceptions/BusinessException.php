<?php

namespace App\Exceptions;

use App\Traits\Response;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpCode;
use Throwable;

class BusinessException extends Exception
{
    use Response;

    /**
     * Constructor.
     *
     * @param  string  $message  Message
     * @param  Throwable|null  $previous  Throwable
     * @param  int  $code  HTTP code
     */
    public function __construct(string $message, ?Throwable $previous = null, int $code = HttpCode::HTTP_INTERNAL_SERVER_ERROR)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Throw exception.
     *
     * @return JsonResponse Data format json
     */
    public function render(): JsonResponse
    {
        return $this->error($this->message, $this->code);
    }
}
