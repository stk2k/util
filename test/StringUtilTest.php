<?php
declare(strict_types=1);

namespace stk2k\util\test;

use PHPUnit\Framework\TestCase;
use stk2k\util\StringUtil;

class StringUtilTest extends TestCase
{
    public function testCamelize()
    {
        $this->assertEquals('', StringUtil::camelize(''));
        $this->assertEquals('a', StringUtil::camelize('a'));
        $this->assertEquals('abc', StringUtil::camelize('abc'));
        
        // Pascal to Camel
        $this->assertEquals('abcDef', StringUtil::camelize('AbcDef'));
        $this->assertEquals('abcDefGhi', StringUtil::camelize('AbcDefGhi'));
        
        // Snake to Camel
        $this->assertEquals('abcDef', StringUtil::camelize('abc_def'));
        $this->assertEquals('abcDefGhi', StringUtil::camelize('abc_def_ghi'));
        
        // Camel to Camel
        $this->assertEquals('abcDef', StringUtil::camelize('abcDef'));
        $this->assertEquals('abcDefGhi', StringUtil::camelize('abcDefGhi'));
    }
    public function testSnake()
    {
        $this->assertEquals('', StringUtil::snake(''));
        $this->assertEquals('a', StringUtil::snake('a'));
        $this->assertEquals('abc', StringUtil::snake('abc'));
    
        // Pascal to Snake
        $this->assertEquals('abc_def', StringUtil::snake('AbcDef'));
        $this->assertEquals('abc_def_ghi', StringUtil::snake('AbcDefGhi'));
        
        // Snake to Snake
        $this->assertEquals('abc_def', StringUtil::snake('abc_def'));
        $this->assertEquals('abc_def_ghi', StringUtil::snake('abc_def_ghi'));
        
        // Camel to Snake
        $this->assertEquals('abc_def', StringUtil::snake('abcDef'));
        $this->assertEquals('abc_def_ghi', StringUtil::snake('abcDefGhi'));
    }
    public function testPascalize()
    {
        $this->assertEquals('', StringUtil::pascalize(''));
        $this->assertEquals('A', StringUtil::pascalize('a'));
        $this->assertEquals('Abc', StringUtil::pascalize('abc'));
        
        // Pascal to Pascal
        $this->assertEquals('AbcDef', StringUtil::pascalize('AbcDef'));
        $this->assertEquals('AbcDefGhi', StringUtil::pascalize('AbcDefGhi'));
        
        // Snake to Pascal
        $this->assertEquals('AbcDef', StringUtil::pascalize('abc_def'));
        $this->assertEquals('AbcDefGhi', StringUtil::pascalize('abc_def_ghi'));
        
        // Camel to Pascal
        $this->assertEquals('AbcDef', StringUtil::pascalize('abcDef'));
        $this->assertEquals('AbcDefGhi', StringUtil::pascalize('abcDefGhi'));
    }
    public function testIsCamelCase()
    {
        $this->assertEquals(false, StringUtil::isCamelCase(''));
        $this->assertEquals(true, StringUtil::isCamelCase('a'));
        $this->assertEquals(true, StringUtil::isCamelCase('abc'));
        
        // Pascal
        $this->assertEquals(false, StringUtil::isCamelCase('AbcDef'));
        $this->assertEquals(false, StringUtil::isCamelCase('AbcDefGhi'));
        
        // Snake
        $this->assertEquals(false, StringUtil::isCamelCase('abc_def'));
        $this->assertEquals(false, StringUtil::isCamelCase('abc_def_ghi'));
        
        // Camel
        $this->assertEquals(true, StringUtil::isCamelCase('abcDef'));
        $this->assertEquals(true, StringUtil::isCamelCase('abcDefGhi'));
    }
    public function testIsSnakeCase()
    {
        $this->assertEquals(false, StringUtil::isSnakelCase(''));
        $this->assertEquals(true, StringUtil::isSnakelCase('a'));
        $this->assertEquals(true, StringUtil::isSnakelCase('abc'));
        
        // Pascal
        $this->assertEquals(false, StringUtil::isSnakelCase('AbcDef'));
        $this->assertEquals(false, StringUtil::isSnakelCase('AbcDefGhi'));
        
        // Snake
        $this->assertEquals(true, StringUtil::isSnakelCase('abc_def'));
        $this->assertEquals(true, StringUtil::isSnakelCase('abc_def_ghi'));
        
        // Camel
        $this->assertEquals(false, StringUtil::isSnakelCase('abcDef'));
        $this->assertEquals(false, StringUtil::isSnakelCase('abcDefGhi'));
    }
    public function testIsPascalCase()
    {
        $this->assertEquals(false, StringUtil::isPascalCase(''));
        $this->assertEquals(false, StringUtil::isPascalCase('a'));
        $this->assertEquals(false, StringUtil::isPascalCase('abc'));
        
        // Pascal
        $this->assertEquals(true, StringUtil::isPascalCase('AbcDef'));
        $this->assertEquals(true, StringUtil::isPascalCase('AbcDefGhi'));
        
        // Snake
        $this->assertEquals(false, StringUtil::isPascalCase('abc_def'));
        $this->assertEquals(false, StringUtil::isPascalCase('abc_def_ghi'));
        
        // Camel
        $this->assertEquals(false, StringUtil::isPascalCase('abcDef'));
        $this->assertEquals(false, StringUtil::isPascalCase('abcDefGhi'));
    }
}