<?php

namespace Sokeio\Theme;

class ThemeParser
{
    /**
     * Parse expression.
     *
     * @param  string  $expression
     * @return \Illuminate\Support\Collection
     */
    public static function multipleArgs($expression)
    {
        // Bỏ các dấu nháy đơn ngoài cùng
        // $expression = trim($expression, "'");

        // Tách chuỗi thành các phần tử
        // Sử dụng regex để tách các phần tử
        preg_match_all("/'([^']+)'|(\[\])|(\[.*?\])/", $expression, $matches);

        // Lấy các phần tử đã tách ra
        return collect($matches[0])->map(function ($item) {
            // Trim và trả về các phần tử
            return trim($item);
        });
    }

    /**
     * Strip quotes.
     *
     * @param  string  $expression
     * @return string
     */
    public static function stripQuotes($expression)
    {
        return str_replace(["'", '"'], '', $expression);
    }
}
