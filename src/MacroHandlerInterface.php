<?php
declare(strict_types=1);

namespace stk2k\Util;

interface MacroHandlerInterface
{
    /**
     * Process macro
     *
     * @param string $keyword
     *
     * @return string|NULL
     */
    public function process(string $keyword);
}