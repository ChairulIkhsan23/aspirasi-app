<?php

namespace App\Helpers;

class TextHelper
{
    public static function filterKasar($text)
    {
        $kataKasar = config('kata_kasar');

        foreach ($kataKasar as $kata) {
            $pattern = '/' . preg_quote($kata, '/') . '/i';
            $replacement = str_repeat('*', strlen($kata));
            $text = preg_replace($pattern, $replacement, $text);
        }

        return $text;
    }
}
