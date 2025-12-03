<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251123104845 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE classroom (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, teacher_id INT DEFAULT NULL, INDEX IDX_497D309D41807E1D (teacher_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE classroom_user (classroom_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_7499B21D6278D5A8 (classroom_id), INDEX IDX_7499B21DA76ED395 (user_id), PRIMARY KEY (classroom_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE classroom ADD CONSTRAINT FK_497D309D41807E1D FOREIGN KEY (teacher_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE classroom_user ADD CONSTRAINT FK_7499B21D6278D5A8 FOREIGN KEY (classroom_id) REFERENCES classroom (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE classroom_user ADD CONSTRAINT FK_7499B21DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classroom DROP FOREIGN KEY FK_497D309D41807E1D');
        $this->addSql('ALTER TABLE classroom_user DROP FOREIGN KEY FK_7499B21D6278D5A8');
        $this->addSql('ALTER TABLE classroom_user DROP FOREIGN KEY FK_7499B21DA76ED395');
        $this->addSql('DROP TABLE classroom');
        $this->addSql('DROP TABLE classroom_user');
    }
}
