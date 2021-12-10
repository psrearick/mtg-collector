<?php

namespace App\Enums\Traits;

trait HasObjectArray
{
    public static function toObjectArray()
    {
        return collect(self::asSelectArray())->map(function ($value, $label) {
            return [
                'value'   => $value,
                'label'   => $label,
            ];
        })->values();
    }
}
