<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpStatus;

trait Response
{
    /**
     * Success.
     *
     * @param  array|object  $data  Data response
     * @param  string|null  $message  Message
     * @param  int  $code  HTTP code
     * @return JsonResponse Data format json
     */
    public function ok(array|object $data = [], ?string $message = null, int $code = HttpStatus::HTTP_OK): JsonResponse
    {
        return response()->json([
            'DATA' => $data,
            'MESSAGE' => $message,
        ], $code);
    }

    /**
     * Error.
     *
     * @param  string|null  $message  Message
     * @param  int  $code  HTTP code
     * @param  array|object  $data  Data response
     * @return JsonResponse Data format json
     */
    public function error(?string $message = null, int $code = HttpStatus::HTTP_INTERNAL_SERVER_ERROR, array|object $data = []): JsonResponse
    {
        return response()->json([
            'DATA' => $data,
            'MESSAGE' => $message,
        ], $code);
    }
}
