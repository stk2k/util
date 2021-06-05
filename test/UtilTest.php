<?php
declare(strict_types=1);

namespace stk2k\util\test;

use Exception;
use Throwable;

use PHPUnit\Framework\TestCase;

use stk2k\util\Util;

class MyClass
{
    public function methodA(){}
    public function methodB($a){}
    public function methodC($a, $b){}
}

class UtilTest extends TestCase
{
    public function testWalkException()
    {
        $ex1 = new Exception('exception 1', 1);            $line1 = __LINE__;
        $ex2 = new Exception('exception 2', 2, $ex1);      $line2 = __LINE__;
        $ex3 = new Exception('exception 3', 3, $ex2);      $line3 = __LINE__;

        Util::walkException($ex3, function($index, Throwable $ex, $file, $line, $message, $code) use($line1,$line2,$line3){
            if ($index === 0){
                $this->assertEquals(3, $ex->getCode());
                $this->assertEquals(__FILE__, $file);
                $this->assertEquals($line3, $line);
                $this->assertEquals('exception 3', $message);
                $this->assertEquals(3, $code);
            }
            else if ($index === 1){
                $this->assertEquals(2, $ex->getCode());
                $this->assertEquals(__FILE__, $file);
                $this->assertEquals($line2, $line);
                $this->assertEquals('exception 2', $message);
                $this->assertEquals(2, $code);
            }
            else if ($index === 2){
                $this->assertEquals(1, $ex->getCode());
                $this->assertEquals(__FILE__, $file);
                $this->assertEquals($line1, $line);
                $this->assertEquals('exception 1', $message);
                $this->assertEquals(1, $code);
            }
        });
    }
    public function testDumpException()
    {
        $ex1 = new Exception('exception 1', 1);            $line1 = __LINE__;
        $ex2 = new Exception('exception 2', 2, $ex1);      $line2 = __LINE__;
        $ex3 = new Exception('exception 3', 3, $ex2);      $line3 = __LINE__;

        // default output format
        ob_start();
        Util::dumpException($ex3);
        $output = ob_get_clean();

        $expected = <<<OUTPUT_EXPECTED
[1][Exception]exception 3  @%FILENAME%(%LINE3%)
[2][Exception]exception 2  @%FILENAME%(%LINE2%)
[3][Exception]exception 1  @%FILENAME%(%LINE1%)

OUTPUT_EXPECTED;
        $expected = str_replace("\n", PHP_EOL, $expected);
        $expected = str_replace('%LINE1%', (string)$line1, $expected);
        $expected = str_replace('%LINE2%', (string)$line2, $expected);
        $expected = str_replace('%LINE3%', (string)$line3, $expected);
        $expected = str_replace('%FILENAME%', __FILE__, $expected);

        $this->assertEquals($expected, $output);

        // custom output format
        $my_format = '%INDEX%/%FILE%/%LINE%: %MESSAGE%(%EXCEPTION%)';

        ob_start();
        Util::dumpException($ex3, null, $my_format);
        $output = ob_get_clean();

        $expected = <<<OUTPUT_EXPECTED
1/%FILENAME%/%LINE3%: exception 3(Exception)
2/%FILENAME%/%LINE2%: exception 2(Exception)
3/%FILENAME%/%LINE1%: exception 1(Exception)

OUTPUT_EXPECTED;
        $expected = str_replace("\n", PHP_EOL, $expected);
        $expected = str_replace('%LINE1%', (string)$line1, $expected);
        $expected = str_replace('%LINE2%', (string)$line2, $expected);
        $expected = str_replace('%LINE3%', (string)$line3, $expected);
        $expected = str_replace('%FILENAME%', __FILE__, $expected);

        $this->assertEquals($expected, $output);

        // custom line renderer
        $my_format = '%INDEX%/%FILE%/%LINE%: %MESSAGE%(%EXCEPTION%)';

        ob_start();
        Util::dumpException($ex3, function($line){ echo $line . '<br>' . PHP_EOL; }, $my_format);
        $output = ob_get_clean();

        $expected = <<<OUTPUT_EXPECTED
1/%FILENAME%/%LINE3%: exception 3(Exception)<br>
2/%FILENAME%/%LINE2%: exception 2(Exception)<br>
3/%FILENAME%/%LINE1%: exception 1(Exception)<br>

OUTPUT_EXPECTED;
        $expected = str_replace("\n", PHP_EOL, $expected);
        $expected = str_replace('%LINE1%', (string)$line1, $expected);
        $expected = str_replace('%LINE2%', (string)$line2, $expected);
        $expected = str_replace('%LINE3%', (string)$line3, $expected);
        $expected = str_replace('%FILENAME%', __FILE__, $expected);

        $this->assertEquals($expected, $output);
    }
    public function testCaller()
    {
        list($file, $line) = Util::caller();    $_file = __FILE__; $_line = __LINE__;
        $this->assertSame($_file, $file);
        $this->assertSame($_line, $line);

        list($file, $line) = Util::caller(-1);
        $this->assertSame('', $file);
        $this->assertSame(-1, $line);
    }
    public function testWalkBacktrace()
    {
        $backtraces = [
            [
                'file' => 'foo.php',
                'line' => 123,
                'class' => 'Foo',
                'type' => '::',
                'function' => 'CallMeDave',
            ],
            [
                'file' => 'bar.php',
                'line' => 67,
                'class' => 'Bar',
                'type' => '->',
                'function' => 'AskYourName',
            ],
        ];
        
        Util::walkBacktrace($backtraces, function($index, $file, $line, $class, $type, $func){
            if ($index === 0){
                $this->assertEquals('foo.php', $file);
                $this->assertEquals(123, $line);
                $this->assertEquals('Foo', $class);
                $this->assertEquals('::', $type);
                $this->assertEquals('CallMeDave', $func);
            }
            else if ($index === 1){
                $this->assertEquals('bar.php', $file);
                $this->assertEquals(67, $line);
                $this->assertEquals('Bar', $class);
                $this->assertEquals('->', $type);
                $this->assertEquals('AskYourName', $func);
            }
            else{
                $this->fail();
            }
        });
    }
    
    public function testDumpBacktrace()
    {
        $backtraces = [
            [
                'file' => 'foo.php',
                'line' => 123,
                'class' => 'Foo',
                'type' => '::',
                'function' => 'CallMeDave',
            ],
            [
                'file' => 'bar.php',
                'line' => 67,
                'class' => 'Bar',
                'type' => '->',
                'function' => 'AskYourName',
            ],
        ];
        
        // default output format
        ob_start();
        Util::dumpBacktrace($backtraces);
        $output = ob_get_clean();
        
        $expected = <<<OUTPUT_EXPECTED
[1]Foo::CallMeDave() in foo.php(123)
[2]Bar->AskYourName() in bar.php(67)

OUTPUT_EXPECTED;
        $expected = str_replace("\n", PHP_EOL, $expected);
        
        $this->assertEquals($expected, $output);
    
        // custom output format
        $my_format = '%INDEX%/%FILE%/%LINE%: %CLASS%/%TYPE%/%FUNCTION%';
        
        ob_start();
        Util::dumpBacktrace($backtraces, null, $my_format);
        $output = ob_get_clean();
    
        $expected = <<<OUTPUT_EXPECTED
1/foo.php/123: Foo/::/CallMeDave
2/bar.php/67: Bar/->/AskYourName

OUTPUT_EXPECTED;
        $expected = str_replace("\n", PHP_EOL, $expected);
    
        $this->assertEquals($expected, $output);

        // custom line renderer
        $my_format = '%INDEX%/%FILE%/%LINE%: %CLASS%/%TYPE%/%FUNCTION%';

        ob_start();
        Util::dumpBacktrace($backtraces, function($line){ echo $line . '<br>' . PHP_EOL; }, $my_format);
        $output = ob_get_clean();

        $expected = <<<OUTPUT_EXPECTED
1/foo.php/123: Foo/::/CallMeDave<br>
2/bar.php/67: Bar/->/AskYourName<br>

OUTPUT_EXPECTED;
        $expected = str_replace("\n", PHP_EOL, $expected);

        $this->assertEquals($expected, $output);
    }
}