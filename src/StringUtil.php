<?php
declare(strict_types=1);

namespace stk2k\Util;

final class StringUtil
{
    /**
     * Make camel case
     *
     * @param string $str
     *
     * @return string
     */
    public static function camelize(string $str) : string
    {
        return lcfirst(strtr(ucwords(strtr($str, ['_' => ' '])), [' ' => '']));
    }
    
    /**
     * Make snake case
     *
     * @param string $str
     *
     * @return string
     */
    public static function snake(string $str) : string
    {
        return ltrim(strtolower(preg_replace('/[A-Z]/', '_\0', $str)), '_');
    }
    
    /**
     * Make pascal case
     *
     * @param string $str
     *
     * @return string
     */
    public static function pascalize(string $str) : string
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $str)));
    }
    
    /**
     * Test if the string is camel case
     *
     * @param string $str
     *
     * @return bool
     */
    public static function isCamelCase(string $str) : bool
    {
        return preg_match('/^[a-z]+[a-zA-Z0-9]*$/', $str) !== 0;
    }
    
    /**
     * Test if the string is camel case
     *
     * @param string $str
     *
     * @return bool
     */
    public static function isSnakelCase(string $str) : bool
    {
        return preg_match('/^[a-z]+[a-z0-9_]*$/', $str) !== 0;
    }
    
    /**
     * Test if the string is camel case
     *
     * @param string $str
     *
     * @return bool
     */
    public static function isPascalCase(string $str) : bool
    {
        return preg_match('/^[A-Z]+[a-zA-Z0-9]*$/', $str) !== 0;
    }
}