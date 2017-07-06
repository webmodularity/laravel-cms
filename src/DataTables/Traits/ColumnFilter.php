<?php

namespace WebModularity\LaravelCms\DataTables\Traits;

use Carbon\Carbon;
use DB;

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
        } elseif (strpos($keyword, '/') !== false
            && preg_match("/(\d{1,2})\/(\d{1,2})\/(\d{4})(\d{6})?/", $keyword, $keywordMatch)) {
            if (isset($keywordMatch[4]) && !empty($keywordMatch[4])) {
                $time = str_split($keywordMatch[4], 2);
                return Carbon::create(
                    $keywordMatch[3],
                    $keywordMatch[1],
                    $keywordMatch[2],
                    $time[0],
                    $time[1],
                    $time[2]
                )->format('Y-m-d H:i:s');
            }
            return Carbon::createFromDate($keywordMatch[3], $keywordMatch[1], $keywordMatch[2])->format('Y-m-d');
        }
        return strtolower($operator) == 'like' || strtolower($operator) == 'not like'
            ? "%$keyword%"
            : $keyword;
    }

    public static function columnFilterGetDbOperator($operator)
    {
        if ($operator == '!') {
            return 'NOT LIKE';
        } elseif (!empty($operator) && in_array($operator, static::$columnFilterDbOperators)) {
            return $operator;
        }
        return 'LIKE';
    }

    public static function columnFilterAddQuery($query, $columnNames, $columnFilter, $whereExists = [])
    {
        $columnNames = !is_array($columnNames) ? [$columnNames] : $columnNames;
        $singleColumnName = count($columnNames) > 1 ? false : true;

        if (!empty($whereExists)) {
            $query->whereExists(function ($query) use ($whereExists, $columnNames, $columnFilter, $singleColumnName) {
                $query->select(DB::raw(1))
                    ->from($whereExists['table'])
                    ->whereRaw($whereExists['where'])
                    ->where(function ($query) use ($columnNames, $columnFilter, $singleColumnName) {
                        foreach ($columnNames as $columnName) {
                            static::addColumnFilterWhere($query, $columnName, $columnFilter, $singleColumnName);
                        }
                    });
            });
        } else {
            foreach ($columnNames as $columnName) {
                static::addColumnFilterWhere($query, $columnName, $columnFilter, $singleColumnName);
            }
        }
    }

    public static function addColumnFilterWhere($query, $columnName, $columnFilter, $singleColumnName = true)
    {
        if (is_array($columnFilter['keyword'])) {
            if (strtolower($columnFilter['operator']) == 'not like') {
                $query->whereNotIn($columnName, $columnFilter['keyword']);
            } else {
                $query->whereIn($columnName, $columnFilter['keyword']);
            }
        } else {
            if (!$singleColumnName && !in_array($columnFilter['operator'], ['NOT LIKE', '!='])) {
                $query->orWhere($columnName, $columnFilter['operator'], $columnFilter['keyword']);
            } else {
                $query->where($columnName, $columnFilter['operator'], $columnFilter['keyword']);
            }
        }
    }
}