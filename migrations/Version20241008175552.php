<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241008175552 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates user table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(/** @lang MySQL */'
            CREATE TABLE IF NOT EXISTS user (
                uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\',
                user_name varchar(255) NOT NULL,
                PRIMARY KEY (uuid)
            );

            CREATE INDEX `uuid` ON user (uuid);
            CREATE INDEX user_name ON user (user_name);

            CREATE TABLE IF NOT EXISTS document (
                uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\',
                user_uuid CHAR(36) NOT NULL,
                status varchar(255) NOT NULL DEFAULT \'draft\',
                payload JSON,
                created_at DATETIME NOT NULL,
                modified_at DATETIME NOT NULL,
                FOREIGN KEY (user_uuid) REFERENCES user(uuid)
            );

            CREATE INDEX document_uuid ON document (uuid); 

            CREATE TABLE IF NOT EXISTS tokens (
                user_uuid CHAR(36) NOT NULL,
                token varchar(255) NOT NULL,
                refresh_token varchar(255) NOT NULL,
                FOREIGN KEY (user_uuid) REFERENCES user(uuid)
            );

            CREATE INDEX token ON tokens (token);
            CREATE INDEX refresh_token ON tokens (refresh_token);
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE tokens');
        $this->addSql('DROP TABLE user');
    }
}
