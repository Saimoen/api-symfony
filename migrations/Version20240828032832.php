<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240828032832 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE todo CHANGE description_longue description_longue VARCHAR(255) DEFAULT NULL, CHANGE resume resume VARCHAR(100) DEFAULT NULL, CHANGE due_at due_at DATETIME DEFAULT NULL, CHANGE created_at created_at DATETIME NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE todo CHANGE description_longue description_longue VARCHAR(255) NOT NULL, CHANGE resume resume VARCHAR(100) NOT NULL, CHANGE due_at due_at DATE NOT NULL, CHANGE created_at created_at DATE NOT NULL, CHANGE updated_at updated_at DATE NOT NULL');
    }
}
