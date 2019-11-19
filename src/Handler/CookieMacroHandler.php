<?php
namespace Stk2k\Util\Handler;

use Stk2k\Util\MacroHandlerInterface;

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