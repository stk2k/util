<?php
declare(strict_types=1);

namespace stk2k\Util\Test;


use PHPUnit\Framework\TestCase;
use stk2k\Util\MacroProcessor;
use stk2k\Util\Enum\EnumMacroHandler;

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