<?php
declare(strict_types=1);

namespace stk2k\Util\Test;


use PHPUnit\Framework\TestCase;
use stk2k\Util\MacroProcessor;
use stk2k\Util\Enum\EnumMacroHandler;

class ServerMacroHandlerTest extends TestCase
{
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