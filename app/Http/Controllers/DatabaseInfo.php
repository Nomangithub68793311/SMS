<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DatabaseInfo extends Controller
{
    public function totalMemorygetDBSizeInKB()
    {
         $result = DB::select(DB::raw('SELECT table_name AS "Table",
                ((data_length + index_length) / 1024) AS "Size"
                FROM information_schema.TABLES
                WHERE table_schema ="'.'sms'. '"
                ORDER BY (data_length + index_length) DESC'));
            $size = array_sum(array_column($result, 'Size'));
            $db_size = number_format((float)$size, 2, '.', '');
            return response()->json(['db_size_in_kB' => $db_size]);

            // dd($db_size);

}
public function eachMemorygetDBSizeInMB()
    {
         $result = DB::select(DB::raw('SELECT table_name AS "Table",
                ((data_length + index_length) / 1024 / 1024) AS "Size"
                FROM information_schema.TABLES
                WHERE table_schema ="'.'sms'. '"
                ORDER BY (data_length + index_length) DESC'));
            $size = array_sum(array_column($result, 'Size'));
            $db_size = number_format((float)$size, 2, '.', '');
            return response()->json(['db_size_in_mB' => $db_size]);

    }
}