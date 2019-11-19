<?php

use PHPUnit\Framework\TestCase;
use Stk2k\Util\MacroProcessor;
use Stk2k\Util\Enum\EnumMacroHandler;

class DateMacroHandlerTest extends TestCase
{
    protected function setUp()
    {
    }

    /**
     * @throws
     */
    public function testDefaultDateHander()
    {
        $exp = new MacroProcessor([ EnumMacroHandler::HANDLER_KEY_DATE ]);
        
        $result = $exp->process('Year=%DATE_YEAR%');
        $this->assertEquals('Year='.date('Y'),$result);
    
        $result = $exp->process('Month=%DATE_MONTH%');
        $this->assertEquals('Month='.date('m'),$result);
    
        $result = $exp->process('Day=%DATE_DAY%');
        $this->assertEquals('Day='.date('d'),$result);
    
        $result = $exp->process('Hour=%DATE_HOUR%');
        $this->assertEquals('Hour='.date('H'),$result);
    
        $result = $exp->process('Minute=%DATE_MINUTE%');
        $this->assertEquals('Minute='.date('i'),$result);
    
        $result = $exp->process('Second=%DATE_SECOND%');
        $this->assertEquals('Second='.date('s'),$result);
    }
    
}