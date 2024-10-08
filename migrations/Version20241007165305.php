<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241007165305 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(/** @lang MySQL */'
            CREATE TABLE IF NOT EXISTS document (
                uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\',
                status varchar(255) NOT NULL DEFAULT \'draft\',
                payload JSON,
                created_at DATETIME NOT NULL,
                modified_at DATETIME NOT NULL
            )
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE document');
    }
}
