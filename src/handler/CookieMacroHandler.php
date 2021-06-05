<?php
declare(strict_types=1);

namespace stk2k\util\handler;

use stk2k\util\MacroHandlerInterface;

class CookieMacroHandler implements MacroHandlerInterface
{
    /** @var array */
    private $keys;
    
    /**
     * EnvMacroHandler constructor.
     */
    public function __construct()
    {
        $this->keys = array_keys($_COOKIE);
    }
    
    /**
     * Process macro
     *
     * @param string $keyword
     *
     * @return string|NULL
     */
    public function process(string $keyword){
        return $this->keys[$keyword] ?? null;
    }
}