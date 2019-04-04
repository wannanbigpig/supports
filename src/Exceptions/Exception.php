<?php

namespace WannanBigPig\Supports\Exceptions;

class Exception extends \Exception
{
    /**
     * 未知异常
     * @var int
     */
    const UNKNOWN_ERROR = 99999;

    /**
     * 业务异常
     * @var int
     */
    const BUSINESS_ERROR = 10001;

    /**
     * Raw error info.
     *
     * @var array
     */
    public $raw;

    /**
     * Exception constructor.
     * @param string $message
     * @param array  $raw
     * @param int    $code
     */
    public function __construct($message = '', $raw = [], $code = self::UNKNOWN_ERROR)
    {
        $message = $message === '' ? 'Unknown Error' : $message;
        $this->raw = is_array($raw) ? $raw : [$raw];

        parent::__construct($message, intval($code));
    }
}
