<?php
declare(strict_types=1);

namespace stk2k\Util\Handler;

use stk2k\Util\MacroHandlerInterface;

class GetMacroHandler implements MacroHandlerInterface
{
    /**
     * Process macro
     *
     * @param string $keyword
     *
     * @return string|NULL
     */
    public function process(string $keyword){
        return $_GET[$keyword] ?? false;
    }
}