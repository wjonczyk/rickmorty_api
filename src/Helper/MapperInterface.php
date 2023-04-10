<?php

declare(strict_types=1);

namespace App\Helper;
interface MapperInterface
{
    public function map(array $data): object;
}
