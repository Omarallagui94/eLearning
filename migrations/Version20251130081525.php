<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251130081525 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE classroom_user');
        $this->addSql('DROP INDEX IDX_497D309D41807E1D ON classroom');
        $this->addSql('ALTER TABLE classroom DROP teacher_id');
        $this->addSql('ALTER TABLE user ADD classroom_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6496278D5A8 FOREIGN KEY (classroom_id) REFERENCES classroom (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6496278D5A8 ON user (classroom_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE classroom_user (classroom_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_7499B21D6278D5A8 (classroom_id), INDEX IDX_7499B21DA76ED395 (user_id), PRIMARY KEY (classroom_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('ALTER TABLE classroom ADD teacher_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_497D309D41807E1D ON classroom (teacher_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6496278D5A8');
        $this->addSql('DROP INDEX IDX_8D93D6496278D5A8 ON user');
        $this->addSql('ALTER TABLE user DROP classroom_id');
    }
}
