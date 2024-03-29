<?php
declare(strict_types=1);

namespace stk2k\util;

interface MacroHandlerInterface
{
    /**
     * Process macro
     *
     * @param string $keyword
     *
     * @return string
     */
    public function process(string $keyword): string;
}