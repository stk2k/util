<?php
namespace Stk2k\Util\Handler;

use Stk2k\Util\MacroHandlerInterface;

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