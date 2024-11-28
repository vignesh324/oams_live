<?php

namespace App\Helpers;

class CodeHelper
{
    public static function generateUniqueCode($model, $codeColumn)
    {
        $lastData = $model->orderBy('id', 'DESC')->first();
        $lastId = $lastData ? intval($lastData[$codeColumn]) : 0;
        $nextId = $lastId + 1;
        return str_pad($nextId, 2, '0', STR_PAD_LEFT);
    }
}
