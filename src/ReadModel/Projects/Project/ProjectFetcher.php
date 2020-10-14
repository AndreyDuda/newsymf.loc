<?php

declare(strict_types=1);

namespace App\ReadModel\Projects\Project;

use App\ReadModel\Projects\Project\Filter\Filter;
use Doctrine\DBAL\Connection;

class ProjectFetcher
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function all(
        Filter $filter,
        int $page,
        int $per,
        string $sort,
        string $direction
    ): array
    {

    }
}