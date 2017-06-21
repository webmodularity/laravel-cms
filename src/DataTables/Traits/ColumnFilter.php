<?php

namespace WebModularity\LaravelCms\DataTables\Traits;

trait ColumnFilter
{
    /**
     * Create a columnFilter collection based on passed keyword
     * Format: [column_name]:(=|!=|!|>|<|>=|<=)?[keyword]
     * If keyword does not contain a : it will be used as the keyword and all columns assumed
     * @param string $keyword
     * @param array $allowedColumnNames
     * @return \Illuminate\Support\Collection
     */
    public static function getColumnFilter($keyword, $allowedColumnNames = [])
    {
        if (strpos($keyword, ':') !== false
            && preg_match('/^([a-zA-Z_]+):(=|!=|!|>|<|>=|<=)?([^<>=!]+)$/', $keyword, $keywordMatch)
            && in_array($keywordMatch[1], $allowedColumnNames)) {
            return collect([
                'column' => $keywordMatch[1],
                'operator' => static::columnFilterGetDbOperator($keywordMatch[2]),
                'keyword' => static::columnFilterGetKeyword(
                    $keywordMatch[3],
                    static::columnFilterGetDbOperator($keywordMatch[2])
                )
            ]);
        }

        return collect([
            'operator' => static::columnFilterGetDbOperator(null),
            'keyword' => static::columnFilterGetKeyword(
                $keyword,
                static::columnFilterGetDbOperator(null)
            )
        ]);
    }

    public static function columnFilterGetKeyword($keyword, $operator)
    {
        if (strpos($keyword, ',') !== false) {
            return explode(',', $keyword);
        }
        return strtolower($operator) == 'like' || strtolower($operator) == 'not like'
            ? "%$keyword%"
            : $keyword;
    }

    public static function columnFilterGetDbOperator($inputOperator)
    {
        if ($inputOperator == '!') {
            return 'NOT LIKE';
        } elseif (!empty($inputOperator) && in_array($inputOperator, static::$columnFilterDbOperators)) {
            return $inputOperator;
        }
        return 'LIKE';
    }
}