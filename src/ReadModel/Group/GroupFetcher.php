<?php

declare(strict_types=1);

namespace App\ReadModel\Group;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;

class GroupFetcher
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function all()
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                'g.id',
                'g.name'
            )
            ->from('work_members_groups', 'g')
            ->orderBy('name')
            ->execute();

        return $stmt->fetchAll(FetchMode::ASSOCIATIVE);
    }
}