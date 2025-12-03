<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251203215500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE exam (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, exam_date DATETIME NOT NULL, subject_id INT NOT NULL, classroom_id INT NOT NULL, teacher_id INT NOT NULL, INDEX IDX_38BBA6C623EDC87 (subject_id), INDEX IDX_38BBA6C66278D5A8 (classroom_id), INDEX IDX_38BBA6C641807E1D (teacher_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE grade (id INT AUTO_INCREMENT NOT NULL, score DOUBLE PRECISION NOT NULL, student_id INT NOT NULL, exam_id INT NOT NULL, grade_id INT DEFAULT NULL, INDEX IDX_595AAE34CB944F1A (student_id), INDEX IDX_595AAE34578D5E91 (exam_id), INDEX IDX_595AAE34FE19A1A8 (grade_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE exam ADD CONSTRAINT FK_38BBA6C623EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id)');
        $this->addSql('ALTER TABLE exam ADD CONSTRAINT FK_38BBA6C66278D5A8 FOREIGN KEY (classroom_id) REFERENCES classroom (id)');
        $this->addSql('ALTER TABLE exam ADD CONSTRAINT FK_38BBA6C641807E1D FOREIGN KEY (teacher_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE grade ADD CONSTRAINT FK_595AAE34CB944F1A FOREIGN KEY (student_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE grade ADD CONSTRAINT FK_595AAE34578D5E91 FOREIGN KEY (exam_id) REFERENCES exam (id)');
        $this->addSql('ALTER TABLE grade ADD CONSTRAINT FK_595AAE34FE19A1A8 FOREIGN KEY (grade_id) REFERENCES grade (id)');
        $this->addSql('ALTER TABLE subject ADD CONSTRAINT FK_FBCE3E7A6278D5A8 FOREIGN KEY (classroom_id) REFERENCES classroom (id)');
        $this->addSql('ALTER TABLE subject ADD CONSTRAINT FK_FBCE3E7A41807E1D FOREIGN KEY (teacher_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6496278D5A8 FOREIGN KEY (classroom_id) REFERENCES classroom (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exam DROP FOREIGN KEY FK_38BBA6C623EDC87');
        $this->addSql('ALTER TABLE exam DROP FOREIGN KEY FK_38BBA6C66278D5A8');
        $this->addSql('ALTER TABLE exam DROP FOREIGN KEY FK_38BBA6C641807E1D');
        $this->addSql('ALTER TABLE grade DROP FOREIGN KEY FK_595AAE34CB944F1A');
        $this->addSql('ALTER TABLE grade DROP FOREIGN KEY FK_595AAE34578D5E91');
        $this->addSql('ALTER TABLE grade DROP FOREIGN KEY FK_595AAE34FE19A1A8');
        $this->addSql('DROP TABLE exam');
        $this->addSql('DROP TABLE grade');
        $this->addSql('ALTER TABLE subject DROP FOREIGN KEY FK_FBCE3E7A6278D5A8');
        $this->addSql('ALTER TABLE subject DROP FOREIGN KEY FK_FBCE3E7A41807E1D');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6496278D5A8');
    }
}
