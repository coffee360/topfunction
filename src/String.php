<?php

namespace Topfunction\Topfunction;

/**
 * 字符串
 * Class String
 * @package Topfunction\Topfunction
 */
class String
{

    /**
     * 移除表情符号
     * @param $text
     * @return string
     */
    public function removeEmoji($text)
    {
        if (empty($text)) {
            return '';
        }

        $len     = mb_strlen($text);
        $newText = '';
        for ($i = 0; $i < $len; $i++) {
            $str = mb_substr($text, $i, 1, 'utf-8');
            if (strlen($str) >= 4)
                continue;//emoji表情为4个字节
            $newText .= $str;
        }

        return $newText;
    }


    /**
     * 随机颜色值（16位）
     * @return string
     */
    public function getColorRandom()
    {
        $str = '0123456789ABCDE';

        $color = '#';
        for ($i = 0; $i < 6; $i++) {
            $color .= $str[rand(0, strlen($str) - 1)];
        }

        return $color;
    }


}