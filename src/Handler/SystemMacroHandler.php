<?php
namespace Stk2k\Util\Handler;

use Stk2k\Util\MacroHandlerInterface;

class SystemMacroHandler implements MacroHandlerInterface
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
            case 'SYS_TEMP_DIR':
                return sys_get_temp_dir();
            case 'PHP_VERSION':
                return phpversion();
        }
        return false;
    }
}