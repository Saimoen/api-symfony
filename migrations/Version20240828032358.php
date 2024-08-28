<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240828032358 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE todo (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description_longue VARCHAR(255) NOT NULL, resume VARCHAR(100) NOT NULL, due_at DATE NOT NULL, created_at DATE NOT NULL, updated_at DATE NOT NULL, UNIQUE INDEX UNIQ_5A0EB6A060C1D0A0 (resume), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('DROP TABLE tache');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tache (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, descriptionLongue VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, resume VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, dueAt DATETIME DEFAULT NULL, createdAt DATETIME DEFAULT NULL, updatedAt DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE todo');
    }
}
