<?php
declare(strict_types=1);

namespace stk2k\Util;

use InvalidArgumentException;

use stk2k\Util\Enum\EnumMemoryUnit as EnumMemoryUnit;

/**
* Utility class of memory calculations
*
* PHP version 5
*
* @package    class.util
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

final class MemoryUtil
{
    const DEFAULT_PRECISION    = 4;
    
    const BYTES_KB    = 1024.0;                    // 1MB = 1024 Bytes
    const BYTES_MB    = 1048576.0;                // 1MB = 1024 * 1024 Bytes = 1048576 Bytes
    const BYTES_GB    = 1073741824.0;            // 1MB = 1024 * 1024 * 1024 Bytes = 1073741824 Bytes
    const BYTES_TB    = 1099511627776.0;        // 1MB = 1024 * 1024 * 1024 * 1024 Bytes = 1099511627776 Bytes
    
    /**
     *    convert memory size
     *
     * @param int $value           memory size in bytes to be converted
     * @param int $unit            memory unit to be converted
     * @param int $precision       precision
     *
     * @return float     converted size
     */
    public static function convertSize( int $value, int $unit, int $precision = self::DEFAULT_PRECISION ) : float
    {
        switch ( $unit ){
        case EnumMemoryUnit::UNIT_B:
            return (float)$value;
        case EnumMemoryUnit::UNIT_KB:
            return round( ((float)$value) / self::BYTES_KB, $precision );
        case EnumMemoryUnit::UNIT_MB:
            return round( ((float)$value) / self::BYTES_MB, $precision );
        case EnumMemoryUnit::UNIT_GB:
            return round( ((float)$value) / self::BYTES_GB, $precision );
        case EnumMemoryUnit::UNIT_TB:
            return round( ((float)$value) / self::BYTES_TB, $precision );
        }
        return (float)$value;
    }

    /**
     *    get byte size from string
     *
     * @param string $size_string       string expression of byte size. ex) 2MB, 100KB, 3.5GB
     *
     * @return float     size in bytes
     * 
     * @throws
     */
    public static function getByteSizeFromString( string $size_string ) : float
    {
        if ( ($pos=strpos($size_string,'TB')) > 0 ){
            // TB
            $number = substr($size_string,0,$pos);
            if ( is_numeric($number) ){
                return intval(self::BYTES_TB * $number);
            }
        }
        else if ( ($pos=strpos($size_string,'GB')) > 0 ){
            // GB
            $number = substr($size_string,0,$pos);
            if ( is_numeric($number) ){
                return intval(self::BYTES_GB * $number);
            }
        }
        else if ( ($pos=strpos($size_string,'MB')) > 0 ){
            // MB
            $number = substr($size_string,0,$pos);
            if ( is_numeric($number) ){
                return intval(self::BYTES_MB * $number);
            }
        }
        else if ( ($pos=strpos($size_string,'KB')) > 0 ){
            // KB
            $number = substr($size_string,0,$pos);
            if ( is_numeric($number) ){
                return intval(self::BYTES_KB * $number);
            }
        }
        else if ( ($pos=strpos($size_string,'B')) > 0 ){
            // B
            $number = substr($size_string,0,$pos);
            if ( is_numeric($number) ){
                return intval($number);
            }
        }
        else if ( is_numeric($size_string) ){
            return intval($size_string);
        }
    
        throw( new InvalidArgumentException(1,$size_string) );
    }

}


