<?php
declare(strict_types=1);

namespace stk2k\util\handler;

use stk2k\util\MacroHandlerInterface;

class DateMacroHandler implements MacroHandlerInterface
{
    /**
     * Process macro
     *
     * @param string $keyword
     *
     * @return string|NULL
     */
    public function process(string $keyword){
        switch($keyword){
            case 'DATE_NOW':
                return strval(time());
            case 'DATE_Y4':
            case 'DATE_YEAR':
                return date('Y');
            case 'DATE_Y2':
                return date('y');
            case 'DATE_M':
            case 'DATE_MONTH':
                return date('m');
            case 'DATE_N':
                return date('n');
            case 'DATE_D':
            case 'DATE_DAY':
                return date('d');
            case 'DATE_J':
                return date('j');
            case 'DATE_H24':
            case 'DATE_HOUR':
                return date('H');
            case 'DATE_H12':
                return date('h');
            case 'DATE_I':
            case 'DATE_MINUTE':
                return date('i');
            case 'DATE_S':
            case 'DATE_SECOND':
                return date('s');
            case 'DATE_U':
            case 'DATE_MSEC':
                $t = microtime(true);
                return sprintf("%06d",($t - floor($t)) * 1000000);
        }
        return false;
    }
}