<?php

namespace App\Templates;

use App\Models\Enums\SCConstants;

class SCResponse
{
    public $data, $status, $type, $message;

    public function __construct($status, $message, $type, $data)
    {
        $this->data = $data;
        $this->status = $status;
        $this->message = $message;
        $this->type = $type;
    }

    public static function getErrorResponse($message, $data)
    {
        return new self(false, $message, SCConstants::ERROR_MESSAGE, $data);
    }

    public static function getSuccessResponse($message, $data)
    {
        return new self(true, $message, SCConstants::SUCCESS_MESSAGE, $data);
    }

    public static function getInfoResponse($message, $data)
    {
        return new self(true, $message, SCConstants::INFO_MESSAGE, $data);
    }

    public static function getWarningResponse($message, $data)
    {
        return new self(false, $message, SCConstants::WARNING_MESSAGE, $data);
    }
}
