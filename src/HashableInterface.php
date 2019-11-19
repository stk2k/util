<?php
namespace Stk2k\Util;

interface HashableInterface
{
    /**
     *  make hash code of this object
     *
     * @return string   unique string of this object
     */
    public function hash() : string;
}

