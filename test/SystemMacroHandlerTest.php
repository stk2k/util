<?php

use PHPUnit\Framework\TestCase;
use Stk2k\Util\MacroProcessor;
use Stk2k\Util\Enum\EnumMacroHandler;

class SystemMacroHandlerTest extends TestCase
{
    /**
     * @throws
     */
    public function testDefaultDateHander()
    {
        $exp = new MacroProcessor([ EnumMacroHandler::HANDLER_KEY_SYSTEM ]);
    
        $result = $exp->process('TmpDir=%SYS_TEMP_DIR%');
        $this->assertEquals('TmpDir='.sys_get_temp_dir(),$result);
    
        $result = $exp->process('PhpVersion=%PHP_VERSION%');
        $this->assertEquals('PhpVersion='.phpversion(),$result);
    }
    
}