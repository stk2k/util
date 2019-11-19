<?php

use PHPUnit\Framework\TestCase;
use Stk2k\Util\MacroProcessor;
use Stk2k\Util\Enum\EnumMacroHandler;

class ServerMacroHandlerTest extends TestCase
{
    protected function setUp()
    {
    }

    /**
     * @throws
     */
    public function testDefaultDateHander()
    {
        $exp = new MacroProcessor([ EnumMacroHandler::HANDLER_KEY_SERVER ]);
    
        $_SERVER['SERVER_NAME'] = 'test';
    
        $result = $exp->process('TmpDir=%SERVER_NAME%');
        $this->assertEquals('TmpDir='.$_SERVER['SERVER_NAME'],$result);
    }
    
}