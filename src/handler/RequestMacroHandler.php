<?php
declare(strict_types=1);

namespace stk2k\util\handler;

use stk2k\util\MacroHandlerInterface;

class RequestMacroHandler implements MacroHandlerInterface
{
    /**
     * Process macro
     *
     * @param string $keyword
     *
     * @return string|NULL
     */
    public function process(string $keyword){
        return $_REQUEST[$keyword] ?? false;
    }
}