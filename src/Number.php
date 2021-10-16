<?php

namespace Topfunction\Topfunction;

/**
 * 数字
 * Class Number
 * @package Topfunction\Topfunction
 */
class Number
{

    /**
     * 整型转两位小数
     */
    function num2point($num, $chu_100_is = false)
    {
        if ($chu_100_is) {
            $num = $num / 100;
        }

        return sprintf("%.2f", $num);
    }

}