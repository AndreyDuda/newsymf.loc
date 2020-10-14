<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201014165809 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE work_projects_roles (id UUID NOT NULL, name VARCHAR(255) NOT NULL, permissions JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_24B53355E237E06 ON work_projects_roles (name)');
        $this->addSql('COMMENT ON COLUMN work_projects_roles.id IS \'(DC2Type:work_projects_role_id)\'');
        $this->addSql('COMMENT ON COLUMN work_projects_roles.permissions IS \'(DC2Type:work_projects_role_permissions)\'');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE work_projects_roles');
    }
}
