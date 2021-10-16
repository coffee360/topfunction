<?php

namespace Topfunction\Topfunction;

/**
 * 数字
 * Class Number
 * @package Topfunction\Topfunction
 */
class NumberApp
{

    /**
     * 整型转两位小数
     * @param      $num
     * @param bool $chu_100_is
     * @return string
     */
    function num2point($num, $chu_100_is = false)
    {
        if ($chu_100_is) {
            $num = $num / 100;
        }

        return sprintf("%.2f", $num);
    }

}