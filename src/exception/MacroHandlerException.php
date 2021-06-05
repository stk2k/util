<?php
declare(strict_types=1);

namespace stk2k\util\exception;

use Throwable;
use Exception;

final class MacroHandlerException extends Exception implements UtilExceptionInterface
{
    /**
     * RouterConfigFileNotFoundException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|NULL $prev
     */
    public function __construct(string $message, int $code = 0, Throwable $prev = NULL )
    {
        parent::__construct($message, $code, $prev);
    }
}