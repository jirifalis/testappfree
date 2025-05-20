<?php declare(strict_types=1);

namespace App\Controller;

interface ResourceInterface
{
    public function getResourceName(): string;
}