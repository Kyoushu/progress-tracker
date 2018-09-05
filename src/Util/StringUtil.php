<?php

namespace Kyoushu\ProgressTracker\Util;

class StringUtil
{

    /**
     * str_pad can't handle multi-byte chars
     *
     * https://3v4l.org/UnXTF
     *
     * @param string $str
     * @param int $pad_len
     * @param string $pad_str
     * @param int $dir
     * @param mixed $encoding
     * @return string
     */
    public static function mb_str_pad(string $str, int $pad_len, string $pad_str = ' ', int $dir = STR_PAD_RIGHT, $encoding = null): string
    {
        $encoding = $encoding === NULL ? mb_internal_encoding() : $encoding;
        $padBefore = $dir === STR_PAD_BOTH || $dir === STR_PAD_LEFT;
        $padAfter = $dir === STR_PAD_BOTH || $dir === STR_PAD_RIGHT;
        $pad_len -= mb_strlen($str, $encoding);
        $targetLen = $padBefore && $padAfter ? $pad_len / 2 : $pad_len;
        $strToRepeatLen = mb_strlen($pad_str, $encoding);
        $repeatTimes = ceil($targetLen / $strToRepeatLen);
        $repeatedString = str_repeat($pad_str, max(0, $repeatTimes)); // safe if used with valid unicode sequences (any charset)
        $before = $padBefore ? mb_substr($repeatedString, 0, (int)floor($targetLen), $encoding) : '';
        $after = $padAfter ? mb_substr($repeatedString, 0, (int)ceil($targetLen), $encoding) : '';
        return $before . $str . $after;
    }

}