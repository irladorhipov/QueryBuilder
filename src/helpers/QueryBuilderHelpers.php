<?php

namespace App\helpers;

class QueryBuilderHelpers
{
    static public function selectColumns(array $columns): string
    {
        $stringColumns = '';

        foreach ($columns as $column) {
            $stringColumns .= $column . ', ';
        }

        return rtrim($stringColumns, ' ,');
    }

    public static function formatValue($value): string
    {
        if (is_numeric($value)) {
            return $value;
        } else {
            return "'" . $value . "'";
        }
    }

    public static function getColumns(array $data): string
    {
        $columns = implode(',', array_keys($data));
        return $columns;
    }
}