<?php

namespace WannanBigPig\Supports\Exceptions;

class BusinessException extends Exception
{
    /**
     * BusinessException constructor.
     * @param       $message
     * @param array $raw
     */
    public function __construct($message, $raw = [])
    {
        parent::__construct('BUSINESS_ERROR: '.$message, $raw, self::BUSINESS_ERROR);
    }
}
