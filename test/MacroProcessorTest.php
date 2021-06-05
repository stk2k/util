<?php
declare(strict_types=1);

namespace stk2k\Util\Test;


use PHPUnit\Framework\TestCase;
use stk2k\Util\MacroProcessor;

class MacroProcessorTest extends TestCase
{
    /**
     * @throws
     */
    public function testConstructor()
    {
        $exp = new MacroProcessor([
            function($keyword){
                switch($keyword){
                    case 'FOO':
                        return 'foo';
                    case 'FRUIT':
                        return 'apple';
                    case 'NAME':
                        return 'David';
                }
                return false;
            }
        ]);
    
        $result = $exp->process('Hello, %FOO%!');
        $this->assertEquals('Hello, foo!',$result);
    
        $result = $exp->process('I like %FRUIT%.');
        $this->assertEquals('I like apple.',$result);
    
        $result = $exp->process('%NAME% like %FRUIT%.');
        $this->assertEquals('David like apple.',$result);
    
        $exp = new MacroProcessor([
            [
                'FOO' => 'foo',
                'FRUIT' => 'apple',
                'NAME' => 'David',
            ],
            [
                'NAME' => 'George',
                'FRUIT' => 'banana',
                'PLACE' => 'Tokyo',
            ]
        ]);
    
        $result = $exp->process('Hello, %FOO%!');
        $this->assertEquals('Hello, foo!',$result);
    
        $result = $exp->process('I like %FRUIT%.');
        $this->assertEquals('I like apple.',$result);

        $result = $exp->process('%NAME% like %FRUIT%.');
        $this->assertEquals('David like apple.',$result);
    }

    /**
     * @throws
     */
    public function testSingleHander()
    {
        $exp = new MacroProcessor();
    
        $exp->addHandler(
            function($keyword){
                switch($keyword){
                    case 'FOO':
                        return 'foo';
                    case 'FRUIT':
                        return 'apple';
                    case 'NAME':
                        return 'David';
                }
                return false;
            }
        );
        
        $result = $exp->process('Hello, %FOO%!');
        $this->assertEquals('Hello, foo!',$result);
    
        $result = $exp->process('I like %FRUIT%.');
        $this->assertEquals('I like apple.',$result);
    
        $result = $exp->process('%NAME% like %FRUIT%.');
        $this->assertEquals('David like apple.',$result);
    }


    /**
     * @throws
     */
    public function testSingleArrayHandler()
    {
        $exp = new MacroProcessor();
        
        $exp->addHandler(
            [
                'FOO' => 'foo',
                'FRUIT' => 'apple',
                'NAME' => 'David',
            ]
        );
        
        $result = $exp->process('Hello, %FOO%!');
        $this->assertEquals('Hello, foo!',$result);
        
        $result = $exp->process('I like %FRUIT%.');
        $this->assertEquals('I like apple.',$result);
        
        $result = $exp->process('%NAME% like %FRUIT%.');
        $this->assertEquals('David like apple.',$result);
    }

    /**
     * @throws
     */
    public function testMultipleHander()
    {
        $exp = new MacroProcessor();
    
        $exp->addHandler(
            function($keyword){
                switch($keyword){
                    case 'FOO':
                        return 'foo';
                    case 'FRUIT':
                        return 'apple';
                    case 'NAME':
                        return 'David';
                }
                return false;
            }
        );
        $exp->addHandler(
            function($keyword){
                switch($keyword){
                    case 'NAME':
                        return 'George';
                    case 'FRUIT':
                        return 'banana';
                    case 'PLACE':
                        return 'Tokyo';
                }
                return false;
            }
        );
        
        $result = $exp->process('Hello, %FOO%!');
        $this->assertEquals('Hello, foo!',$result);
        
        $result = $exp->process('I like %FRUIT%.');
        $this->assertEquals('I like apple.',$result);

        $result = $exp->process('%NAME% like %FRUIT%.');
        $this->assertEquals('David like apple.',$result);
    }

    /**
     * @throws
     */
    public function testMultipleArrayHander()
    {
        $exp = new MacroProcessor();
        
        $exp->addHandler(
            [
                'FOO' => 'foo',
                'FRUIT' => 'apple',
                'NAME' => 'David',
            ]
        );
        $exp->addHandler(
            [
                'NAME' => 'George',
                'FRUIT' => 'banana',
                'PLACE' => 'Tokyo',
            ]
        );
        
        $result = $exp->process('Hello, %FOO%!');
        $this->assertEquals('Hello, foo!',$result);
        
        $result = $exp->process('I like %FRUIT%.');
        $this->assertEquals('I like apple.',$result);

        $result = $exp->process('%NAME% like %FRUIT%.');
        $this->assertEquals('David like apple.',$result);
    }


    /**
     * @throws
     */
    public function testAdhocHandler()
    {
        $exp = new MacroProcessor();

        $exp->addHandler(
            function($keyword){
                switch($keyword){
                    case 'FOO':
                        return 'foo';
                    case 'FRUIT':
                        return 'apple';
                    case 'NAME':
                        return 'David';
                }
                return false;
            }
        );
        $result = $exp->process('%NAME% like %FRUIT%.', function($keyword){
                switch($keyword){
                    case 'NAME':
                        return 'George';
                    case 'FRUIT':
                        return 'banana';
                    case 'PLACE':
                        return 'Tokyo';
                }
                return false;
            });
        $this->assertEquals('George like banana.',$result);


        $exp = new MacroProcessor();

        $result = $exp->process('I like %APPLE% and %MANGO%.', function($keyword){
            switch($keyword){
                case 'APPLE':
                    return 'apple';
                case 'MANGO':
                    return 'mango';
            }
            return false;
        });
        $this->assertEquals('I like apple and mango.',$result);
    }

}