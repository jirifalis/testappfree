<?php declare(strict_types=1);

namespace App\Utils;

class ResourceNameUtils
{
    static public function getNameFromClass(string $controller): string
    {
        $parts = explode('\\', $controller);
        $class_name = array_slice($parts, -1)[0];
        $name = str_replace('Controller', '', $class_name);
        return strtolower($name);
    }
}