<?php

namespace App\Helpers;

class MapResources
{
    public static function map(string $resourceName)
    {
        if ($resourceName === 'planets') {
            return 'homeworld';
        }
        if (in_array($resourceName, ['vehicles', 'films', 'starships', 'species'])) {
            return $resourceName;
        }
        return '';
    }
}
