<?php

namespace App\Exceptions;

use App\Traits\JsonResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class HttpException extends Exception
{
    use JsonResponseTrait;

    /**
     * @var mixed
     */
    public $data;

    /**
     * @param string|null $message
     * @param int $code
     * @param array|null|mixed $data
     */
    public function __construct(?string $message, int $code = Response::HTTP_BAD_REQUEST, $data = null)
    {
        parent::__construct($message, $code);

        $this->data = $data;
    }

    public function render(): JsonResponse
    {
        return $this->errorResponse($this->data, $this->message, $this->code);
    }
}
