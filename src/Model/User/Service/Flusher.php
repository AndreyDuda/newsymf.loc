<?php
declare(strict_types=1);

namespace App\Model\User\Service;

interface Flusher
{
    public function flush(): void;
}