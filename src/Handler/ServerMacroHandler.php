<?php
declare(strict_types=1);

namespace stk2k\Util\Handler;

use stk2k\Util\MacroHandlerInterface;

class ServerMacroHandler implements MacroHandlerInterface
{
    /**
     * Process macro
     *
     * @param string $keyword
     *
     * @return string|NULL
     */
    public function process(string $keyword){
        switch($keyword){
            case 'SERVER_NAME':
            case 'REQUEST_METHOD':
            case 'REQUEST_TIME':
            case 'REQUEST_TIME_FLOAT':
            case 'QUERY_STRING':
            case 'HTTP_ACCEPT':
            case 'HTTP_ACCEPT_CHARSET':
            case 'HTTP_ACCEPT_ENCODING':
            case 'HTTP_ACCEPT_LANGUAGE':
            case 'HTTP_CONNECTION':
            case 'HTTP_HOST':
            case 'HTTP_REFERER':
            case 'HTTP_USER_AGENT':
            case 'HTTPS':
            case 'REMOTE_ADDR':
            case 'REMOTE_HOST':
            case 'REMOTE_PORT':
            case 'REMOTE_USER':
            case 'REDIRECT_REMOTE_USER':
            case 'SCRIPT_FILENAME':
            case 'SERVER_ADMIN':
            case 'SERVER_PORT':
            case 'SERVER_SIGNATURE':
            case 'SCRIPT_NAME':
            case 'REQUEST_URI':
            case 'PHP_AUTH_DIGEST':
            case 'PHP_AUTH_USER':
            case 'PHP_AUTH_PW':
            case 'AUTH_TYPE':
            case 'PATH_INFO':
            case 'ORIG_PATH_INFO':
                return $_SERVER[$keyword] ?? false;
        }
        return false;
    }
}