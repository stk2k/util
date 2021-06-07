<?php
declare(strict_types=1);

namespace stk2k\util\handler;

use stk2k\util\MacroHandlerInterface;

class SystemMacroHandler implements MacroHandlerInterface
{
    /**
     * Process macro
     *
     * @param string $keyword
     *
     * @return string
     */
    public function process(string $keyword) : string
    {
        switch($keyword){
            case 'SYS_TEMP_DIR':
                return sys_get_temp_dir();
            case 'PHP_VERSION':
                return phpversion();
        }
        return $keyword;
    }
}