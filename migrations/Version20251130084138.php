<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251130084138 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE subject_classroom');
        $this->addSql('ALTER TABLE subject ADD classroom_id INT NOT NULL');
        $this->addSql('ALTER TABLE subject ADD CONSTRAINT FK_FBCE3E7A6278D5A8 FOREIGN KEY (classroom_id) REFERENCES classroom (id)');
        $this->addSql('ALTER TABLE subject ADD CONSTRAINT FK_FBCE3E7A41807E1D FOREIGN KEY (teacher_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_FBCE3E7A6278D5A8 ON subject (classroom_id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6496278D5A8 FOREIGN KEY (classroom_id) REFERENCES classroom (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE subject_classroom (subject_id INT NOT NULL, classroom_id INT NOT NULL, INDEX IDX_A2CA4B723EDC87 (subject_id), INDEX IDX_A2CA4B76278D5A8 (classroom_id), PRIMARY KEY (subject_id, classroom_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('ALTER TABLE subject DROP FOREIGN KEY FK_FBCE3E7A6278D5A8');
        $this->addSql('ALTER TABLE subject DROP FOREIGN KEY FK_FBCE3E7A41807E1D');
        $this->addSql('DROP INDEX IDX_FBCE3E7A6278D5A8 ON subject');
        $this->addSql('ALTER TABLE subject DROP classroom_id');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6496278D5A8');
    }
}
