<?php
declare(strict_types=1);

namespace stk2k\util;

use Throwable;
use Traversable;
use Countable;

final class Util
{
    /* length of output in string conversion methods */
    const TOSTRING_MAX_LENGTH     = 9999;
    
    /* length of output in dump methods */
    const DUMP_MAX_LENGTH         = 4096;
    
    /* Used at isBitSet(), means test if any of bit field is set */
    const BITTEST_MODE_ALL = 1;
    
    /* Used at isBitSet(), means test if any of bit field is set */
    const BITTEST_MODE_ANY = 2;

    /* Dump exception default format */
    const DUMP_EXCEPTION_DEFAULT_FORMAT = '[%INDEX%][%EXCEPTION%]%MESSAGE%  @%FILE%(%LINE%)';

    /* Dump backtrace default format */
    const DUMP_BACKTRACE_DEFAULT_FORMAT = '[%INDEX%]%CLASS%%TYPE%%FUNCTION%() in %FILE%(%LINE%)';

    /**
     * Print error message
     *
     * @param string $message
     */
    public static function printError(string $message)
    {
        if (defined('STDERR')){
            fputs(STDERR, $message . "\n");
        }
        error_log($message);
    }

    /**
     * Dump exceptions
     *
     * @param Throwable $e
     * @param callable|null $line_renderer
     * @param string $format
     */
    public static function dumpException(Throwable $e, callable $line_renderer = null, string $format = self::DUMP_EXCEPTION_DEFAULT_FORMAT)
    {
        self::walkException($e, function($index, $ex, $file, $line, $message, $code) use($line_renderer, $format){
            $line = strtr($format, [
                '%INDEX%' => $index + 1,
                '%EXCEPTION%' => get_class($ex),
                '%FILE%' => $file,
                '%LINE%' => $line,
                '%MESSAGE%' => $message,
                '%CODE%' => $code,
            ]);
            if ($line_renderer){
                ($line_renderer)($line);
            }
            else{
                echo $line . PHP_EOL;
            }
        });
    }
    
    /**
     * Walk exceptions
     *
     * @param Throwable $e
     * @param callable $callback
     */
    public static function walkException(Throwable $e, callable $callback)
    {
        $index = 0;
        while($e){
            ($callback)($index ++, $e, $e->getFile(), $e->getLine(), $e->getMessage(), $e->getCode());
            $e = $e->getPrevious();
        }
    }

    /**
     * Dump back traces
     *
     * @param array $backtraces
     * @param callable|null $line_renderer
     * @param string $format
     */
    public static function dumpBacktrace(array $backtraces, callable $line_renderer = null, string $format = self::DUMP_BACKTRACE_DEFAULT_FORMAT)
    {
        self::walkBacktrace($backtraces, function($index, $file, $line, $class, $type, $func) use($line_renderer, $format){
            $line = strtr($format, [
                '%INDEX%' => $index + 1,
                '%FILE%' => $file,
                '%LINE%' => $line,
                '%CLASS%' => $class,
                '%TYPE%' => $type,
                '%FUNCTION%' => $func,
            ]);
            if ($line_renderer){
                ($line_renderer)($line);
            }
            else{
                echo $line . PHP_EOL;
            }
        });
    }
    
    /**
     * Walk back traces
     *
     * @param array|Traversable $backtraces
     * @param callable $callback
     */
    public static function walkBacktrace($backtraces, callable $callback)
    {
        foreach($backtraces as $index => $item){
            $func  = $item['function'] ?? '';
            $class = $item['class'] ?? '';
            $type  = $item['type'] ?? '';
            $file  = $item['file'] ?? '';
            $line  = $item['line'] ?? '';
            ($callback)($index, $file, $line, $class, $type, $func);
        }
    }
    
    /**
     * Get PHP Types
     *
     * @return array
     */
    public static function phpTypes() : array
    {
        return [
            'boolean',
            'integer',
            'double',
            'string',
            'array',
            'object',
            'resource',
            'NULL',
            'unknown type',
        ];
    }

    /**
     *  Get all defined constants
     *
     */
    public static function getUserDefinedConstants()
    {
        $all = get_defined_constants(TRUE);
        return $all['user'] ?? array();
    }

    /**
     *  Test if specified bit flag is set
     *
     *  @param int $target              target value to test
     *  @param int $flag                target flag to test
     *  @param int $mode                test mode(see BITTEST_MODE_XXX constants)
     *
     * @return bool
     */
    public static function isBitSet( int $target, int $flag, int $mode ) : bool
    {
        switch($mode){
        case self::BITTEST_MODE_ALL:
            return ($target & $flag) === $flag;
        case self::BITTEST_MODE_ANY:
            return ($target & $flag) != 0;
        }
        return false;
    }

    /**
     *  Test if specified bit flag is set(any bit set returns true)
     *
     *  @param int $target              target value to test
     *  @param int $flag                target flag to test
     *
     * @return bool
     */
    public static function isAnyBitSet( int $target, int $flag ) : bool
    {
        return self::isBitSet( $target, $flag, self::BITTEST_MODE_ANY );
    }

    /**
     *  Test if specified bit flag is set(all bits set returns true)
     *
     *  @param int $target              target value to test
     *  @param int $flag                target flag to test
     *
     * @return bool
     */
    public static function isAllBitSet( int $target, int $flag ) : bool
    {
        return self::isBitSet( $target, $flag, self::BITTEST_MODE_ALL );
    }

    /*
     *    配列の最後に別の配列の要素すべてを追加
     */
    public static function appendArray( $a, $b )
    {
        if ( $a === NULL ){
            $a = array();
        }
        array_splice($a,count($a),0,$b);
        return $a;
    }

    /**
     *    swap two values
     *
     * @param mixed $a
     * @param mixed $b
     *
     * @return array
     */
    public static function swap( $a, $b ): array
    {
        return array( $b, $a );
    }

    /**
     *    format byte size
     *
     * @param int $size
     * @param int $precision
     * @param array|null $symbols
     *
     * @return string
     */
    public static function formatByteSize(int $size, int $precision = 1, array $symbols = null ): string
    {
        if ( $symbols === NULL ){
            $symbols = array('B', 'Kb', 'Mb', 'Gb', 'Tb', 'Pb');
        }
        $i=0;
        while (($size/1024)>1) {
            $size=$size/1024;
            $i++;
        }
        return (round($size,$precision)." ".$symbols[$i]);
    }

    /**
     *  generate hash value
     *
     * @param string $algo
     * @param string|null $data
     *
     * @return string
     */
    public static function hash(string $algo = 'sha1', string $data = null ): string
    {
        if (!$data){
            $data = microtime().uniqid((string)mt_rand(), true);
        }
        return hash( $algo, $data );
    }

    /**
     *  escape variable for HTML
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public static function escape( $value )
    {
        if ( is_string($value) ){
            return htmlspecialchars($value, ENT_QUOTES, mb_internal_encoding());
        }
        elseif ( is_array($value) ){
            $ret = array();
            foreach( $value as $key => $item ){
                $ret[$key] = self::escape( $item );
            }
            return $ret;
        }
        elseif ( is_object($value) ){
            $object = $value;
            $vars = get_object_vars($object);
            foreach( $vars as $key => $value ){
                $object->$key = self::escape( $value );
            }
            return $object;
        }
        return $value;
    }

    /**
     * decode escaped value
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public static function decode( $value )
    {
        if ( is_string($value) ){
            return htmlspecialchars_decode($value, ENT_QUOTES);
        }
        elseif ( is_array($value) ){
            return array_map('Charcoal_System::decode', $value);
        }
        elseif ( is_object($value) ){
            $object = $value;
            $vars = get_object_vars($object);
            foreach( $vars as $key => $value ){
                $object->$key = self::decode( $value );
            }
            return $object;
        }
        return $value;
    }

    /**
     *  remove HTML tags
     *
     * @param mixed $value
     * @param string|null $allowable_tags
     *
     * @return mixed
     */
    public static function stripTags($value, string $allowable_tags = null)
    {
        if ( is_string($value) ){
            return strip_tags($value, $allowable_tags);
        }
        elseif ( is_array($value) ){
            $array = $value;
            foreach( $array as $key => $value ){
                $array[$key] = self::stripTags( $value, $allowable_tags );
            }
            return $array;
        }
        elseif ( is_object($value) ){
            $object = $value;
            $vars = get_object_vars($object);
            foreach( $vars as $key => $value ){
                $object->$key = self::stripTags( $value );
            }
            return $object;
        }
        return $value;
    }

    /**
     *  remove backslashes
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public static function stripSlashes( $value )
    {
        if ( is_string($value) ){
            return stripslashes($value);
        }
        elseif ( is_array($value) ){
            $array = $value;
            foreach( $array as $key => $value ){
                $array[$key] = self::stripSlashes( $value );
            }
            return $array;
        }
        elseif ( is_object($value) ){
            $object = $value;
            $vars = get_object_vars($object);
            foreach( $vars as $key => $value ){
                $object->$key = self::stripSlashes( $value );
            }
            return $object;
        }
        return $value;
    }

    /**
     *   escape string for HTML
     *
     * @param string $string_data
     * @param array|null $options
     *
     * @return string
     */
    public static function escapeString(string $string_data, array $options = null): string
    {
        if ( !$options ){
            $options = array(
                            'quote_style' => 'ENT_QUOTES',
                        );
        }

        $quote_style = ENT_NOQUOTES;
        if ( isset($options['quote_style']) && $options['quote_style'] == 'ENT_QUOTES' ){
            $quote_style = ENT_QUOTES;
        }

        return htmlspecialchars( $string_data, $quote_style );
    }

    /**
     *  make random string
     *
     * @param int $length
     * @param string $char_set
     *
     * @return string
     */
    public static function makeRandomString(int $length, string $char_set = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_' ): string
    {
        $ret = '';
        $char_set_cnt = strlen($char_set);

        mt_srand();
        for($i = 0; $i < $length; $i++){
            $idx = mt_rand(0, $char_set_cnt - 1);
            $ret .= $char_set[ $idx ];
        }

        return $ret;
    }

    /**
     *  return file and line number of called position
     *
     * @param int $back
     *
     * @return array
     */
    public static function caller(int $back = 0): array
    {
        $bt = debug_backtrace();
        $trace = $bt[$back] ?? null;

        $file = $trace['file'] ?? '';
        $line = $trace['line'] ?? -1;

        return array( $file, intval($line) );
    }

    /**
     *  return file and line number of called position as string
     *
     * @param int $back
     * @param bool $fullpath
     *
     * @return string
     */
    public static function callerAsString(int $back = 0, bool $fullpath = false ): string
    {
        list( $file, $line ) = self::caller( $back );

        if ( $fullpath ){
            return "$file($line)";
        }

        $file = basename($file);

        return "$file($line)";
    }

    /**
     *    get type of primitive, resource, array, or object
     *
     * @param mixed $value
     *
     * @return string
     */
    public static function getType($value): string
    {
        $type = gettype($value);
        switch( $type ){
        case 'string':
            return $type . '(' . strlen($value) . ')';
        case 'integer':
        case 'float':
        case 'boolean':
            return $type . '(' . $value . ')';
        case 'NULL':
        case 'unknown type':
            return $type;
        case 'array':
            return $type . '(' . count($value) . ')';
        case 'object':
            if ( $value instanceof Countable ){
                return get_class( $value ) . '(' . count($value) . ')';
            }
            elseif ( $value instanceof HashableInterface ){
                return get_class( $value ) . '(hash=' . $value->hash() . ')';
            }
            return get_class( $value );
        }
        return '';
    }
    
    /**
     *  make string expression about a variable
     *
     * @param mixed $value
     * @param bool $with_type
     * @param int $max_size
     * @param string $tostring_methods
     *
     * @return string
     */
    public static function toString($value, bool $with_type = false, int $max_size = self::TOSTRING_MAX_LENGTH, string $tostring_methods = '__toString,toString' ): string
    {
        $ret = '';

        if ( $value === NULL ){
            $ret = 'NULL';
        }
        else{
            $type = gettype($value);
            switch( $type ){
            case 'string':
            case 'integer':
            case 'double':
            case 'boolean':
            case 'NULL':
            case 'unknown type':
                $ret = strval($value);
                if ( $with_type ){
                    $ret .= '(' . $type . ')';
                }
                break;
            case 'array':
                $ret = '';
                foreach( $value as $k => $v ){
                    if ( strlen($ret) > 0 )        $ret .= '/';
                    $ret .= "$k=" . self::toString( $v );
                    if ( $with_type ){
                        $ret .= '(' . gettype($v) . ')';
                    }
                }
                break;
            case 'object':
                {
                    $methods = explode( ',', $tostring_methods );
                    foreach( $methods as $method ){
                        if ( method_exists($value, $method) ){
                            $ret = $value->{$method}();
                            break;
                        }
                    }
                    if ( $with_type ){
                        $ret .= '(' . get_class($value) . ')';
                    }
                }
                break;
            }
        }

        if ( $max_size > 0 ){
            return strlen($ret) > $max_size ? substr($ret,0,$max_size) . '...' : $ret;
        }
        else{
            return $ret;
        }
    }

    /**
     *  dump a variable
     *
     * @param mixed $var
     * @param string $format
     * @param int $back
     * @param array|null $options
     * @param bool $return
     * @param int $max_depth
     *
     * @return string
     */
    public static function dump($var, string $format = 'html', int $back = 0, array $options = null, bool $return = false, int $max_depth = 6 ): ?string
    {
        list( $file, $line ) = self::caller( $back );

        if ( !$options ){
            $options = array();
        }
        $default_options = array(
                'title' => 'system dump',
                'font_size' => 11,
                'max_string_length' => self::DUMP_MAX_LENGTH,
                'type' => 'textarea',
            );
        $options = array_merge( $default_options, $options );

        $title             = $options['title'];
        $font_size         = $options['font_size'];
        $max_string_length = $options['max_string_length'];
        $type              = $options['type'];

        $lines = array();
        $recursion = array();
        self::_dump( '-', $var, 0, $max_string_length, $lines, $max_depth, $recursion );

        switch( $format )
        {
        case "html":
            switch( $type ){
            case 'div':
                $output  = "<div style=\"font-size:12px; margin: 2px\"> $title:" . implode('',$lines) . " @$file($line)</div>";
                break;
            case 'textarea':
            default:
                $output  = "<h3 style=\"font-size:12px; margin: 0; color:black; background-color:white; text-align: left\"> $title @$file($line)</h3>";
                $output .= "<textarea rows=14 style=\"width:100%; font-size:{$font_size}px; margin: 0; color:black; background-color:white; border: 1px solid silver;\">";
                $output .= implode(PHP_EOL,$lines);
                $output .= "</textarea>";
                break;
            }
            break;
        case "shell":
        default:
            $output  = "$title @$file($line)" . PHP_EOL;
            $output .= implode(PHP_EOL,$lines) . PHP_EOL;
            break;
        }

        if ( $return ){
            return $output;
        }
        else{
            echo $output;
            return null;
        }
    }

    private static function _dump( $key, $value, $depth, $max_string_length, &$lines, $max_depth, &$recursion )
    {
        if ( $depth > $max_depth ){
            $lines[] = str_repeat( '.', $depth * 4 ) . "----(max depth over:$max_depth)";
            return;
        }

        $type = gettype($value);

        switch( $type ){
        case 'string':
            {
                $str = $value;
                if ( strlen($str) > $max_string_length ){
                    $str = substr( $str, 0, $max_string_length ) . '...(total:' . strlen($str) . 'bytes)';
                }
//                $str = htmlspecialchars( $str, ENT_QUOTES );
                $lines[] = str_repeat( '.', $depth * 4 ) . "[$key:$type]$str";
            }
            break;
        case 'integer':
        case 'double':
        case 'boolean':
        case 'NULL':
        case 'unknown type':
            {
                $str = strval($value);
                if ( strlen($str) > $max_string_length ){
                    $str = substr( $str, 0, $max_string_length ) . '...(total:' . strlen($str) . 'bytes)';
                }
//                $str = htmlspecialchars( $str, ENT_QUOTES );
                $lines[] = str_repeat( '.', $depth * 4 ) . "[$key:$type]$str";
            }
            break;
        case 'array':
            {
                $lines[] = str_repeat( '.', $depth * 4 ) . "[$key:array(" . count($value) . ')]';
                foreach( $value as $_key => $_value ){
                    self::_dump( $_key, $_value, $depth + 1, $max_string_length, $lines, $max_depth, $recursion );
                }
            }
            break;
        case 'object':
            {
                $clazz = get_class( $value );
                $id = function_exists('spl_object_hash') ? spl_object_hash($value) : 'unknown';
                $line = str_repeat( '.', $depth * 4 ) . "[$key:object($clazz)@$id]";

                $hash = spl_object_hash( $value );
                if ( isset($recursion[$hash]) ){
                    $lines[] = $line . "----[RECURSION]";
                    return;
                }
                $recursion[$hash] = 1;

                $lines[] = $line;

                if ( $value instanceof Traversable ){
                    foreach( $value as $_key => $_value ){
                        self::_dump( $_key, $_value, $depth + 1, $max_string_length, $lines, $max_depth, $recursion );
                    }
                }
                else{
                    $vars = get_object_vars( $value );
                    foreach( $vars as $_key => $_value ){
                        self::_dump( $_key, $_value, $depth + 1, $max_string_length, $lines, $max_depth, $recursion );
                    }
                }
            }
            break;
        }
    }

    /**
     *  convert encoding
     *
     * @param string $str
     * @param string|null $to_encoding
     * @param string|null $from_encoding
     *
     * @return string
     */
    public static function convertEncoding(string $str, string $to_encoding = null, string $from_encoding = null ): string
    {
        if ( is_string($str) && $to_encoding ){
            // エンコードあり
            return mb_convert_encoding($str,$to_encoding, $from_encoding);
        }
        // エンコード無し
        return $str;
    }

    /**
     *  convert encoding recursively
     *
     * @param mixed $var
     * @param string|null $to_encoding
     * @param string|null $from_encoding
     *
     * @return mixed
     */
    public static function convertEncodingRecursive($var, string $to_encoding = null, string $from_encoding = null)
    {
        $type = gettype($var);
        switch( $type ){
        case 'string':
            return mb_convert_encoding($var,$to_encoding, $from_encoding);
        case 'integer':
        case 'double':
        case 'boolean':
        case 'NULL':
        case 'unknown type':
            break;
        case 'array':
            $newArray = array();
            foreach( $var as $key => $value ){
                $value = self::convertEncodingRecursive( $value, $to_encoding, $from_encoding );
                $newArray[ $key ] = $value;
            }
            return $newArray;
        case 'object':
            $newObject = clone $var;
            if ( $var instanceof Traversable ){
                foreach( $var as $key => $value ){
                    $value = self::convertEncodingRecursive( $value, $to_encoding, $from_encoding );
                    $newObject->$key = $value;
                }
            }
            else{
                $obj_vars = get_object_vars( $var );
                foreach( $obj_vars as $key => $value ){
                    $value = self::convertEncodingRecursive( $value, $to_encoding, $from_encoding );
                    $newObject->$key = $value;
                }
            }
            return $newObject;
        }

        return $var;
    }

}


