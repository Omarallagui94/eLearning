<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251217215212 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE exam_answer (id INT AUTO_INCREMENT NOT NULL, answer LONGTEXT DEFAULT NULL, points_awarded DOUBLE PRECISION NOT NULL, attempt_id INT NOT NULL, question_id INT NOT NULL, INDEX IDX_11EE1CAFB191BE6B (attempt_id), INDEX IDX_11EE1CAF1E27F6BF (question_id), UNIQUE INDEX unique_attempt_question (attempt_id, question_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE exam_attempt (id INT AUTO_INCREMENT NOT NULL, started_at DATETIME NOT NULL, submitted_at DATETIME DEFAULT NULL, status VARCHAR(50) NOT NULL, score DOUBLE PRECISION DEFAULT NULL, exam_id INT NOT NULL, student_id INT NOT NULL, INDEX IDX_154A5B0E578D5E91 (exam_id), INDEX IDX_154A5B0ECB944F1A (student_id), UNIQUE INDEX unique_exam_student (exam_id, student_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE exam_question (id INT AUTO_INCREMENT NOT NULL, question_text LONGTEXT NOT NULL, type VARCHAR(255) NOT NULL, choices JSON DEFAULT NULL, correct_answer LONGTEXT NOT NULL, points DOUBLE PRECISION NOT NULL, position INT DEFAULT NULL, exam_id INT NOT NULL, INDEX IDX_F593067D578D5E91 (exam_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE exam_answer ADD CONSTRAINT FK_11EE1CAFB191BE6B FOREIGN KEY (attempt_id) REFERENCES exam_attempt (id)');
        $this->addSql('ALTER TABLE exam_answer ADD CONSTRAINT FK_11EE1CAF1E27F6BF FOREIGN KEY (question_id) REFERENCES exam_question (id)');
        $this->addSql('ALTER TABLE exam_attempt ADD CONSTRAINT FK_154A5B0E578D5E91 FOREIGN KEY (exam_id) REFERENCES exam (id)');
        $this->addSql('ALTER TABLE exam_attempt ADD CONSTRAINT FK_154A5B0ECB944F1A FOREIGN KEY (student_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE exam_question ADD CONSTRAINT FK_F593067D578D5E91 FOREIGN KEY (exam_id) REFERENCES exam (id)');
        $this->addSql('ALTER TABLE exam ADD duration_minutes INT DEFAULT 60 NOT NULL');
        $this->addSql('ALTER TABLE exam ADD CONSTRAINT FK_38BBA6C66278D5A8 FOREIGN KEY (classroom_id) REFERENCES classroom (id)');
        $this->addSql('ALTER TABLE exam ADD CONSTRAINT FK_38BBA6C641807E1D FOREIGN KEY (teacher_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE exam ADD CONSTRAINT FK_38BBA6C623EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id)');
        $this->addSql('ALTER TABLE grade ADD CONSTRAINT FK_595AAE34CB944F1A FOREIGN KEY (student_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE grade ADD CONSTRAINT FK_595AAE34578D5E91 FOREIGN KEY (exam_id) REFERENCES exam (id)');
        $this->addSql('ALTER TABLE grade ADD CONSTRAINT FK_595AAE34FE19A1A8 FOREIGN KEY (grade_id) REFERENCES grade (id)');
        $this->addSql('ALTER TABLE lesson ADD description LONGTEXT DEFAULT NULL, ADD file_path VARCHAR(255) DEFAULT NULL, ADD created_at DATETIME NOT NULL, CHANGE content content LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE lesson ADD CONSTRAINT FK_F87474F323EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E578D5E91 FOREIGN KEY (exam_id) REFERENCES exam (id)');
        $this->addSql('ALTER TABLE subject ADD CONSTRAINT FK_FBCE3E7A6278D5A8 FOREIGN KEY (classroom_id) REFERENCES classroom (id)');
        $this->addSql('ALTER TABLE subject ADD CONSTRAINT FK_FBCE3E7A41807E1D FOREIGN KEY (teacher_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6496278D5A8 FOREIGN KEY (classroom_id) REFERENCES classroom (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exam_answer DROP FOREIGN KEY FK_11EE1CAFB191BE6B');
        $this->addSql('ALTER TABLE exam_answer DROP FOREIGN KEY FK_11EE1CAF1E27F6BF');
        $this->addSql('ALTER TABLE exam_attempt DROP FOREIGN KEY FK_154A5B0E578D5E91');
        $this->addSql('ALTER TABLE exam_attempt DROP FOREIGN KEY FK_154A5B0ECB944F1A');
        $this->addSql('ALTER TABLE exam_question DROP FOREIGN KEY FK_F593067D578D5E91');
        $this->addSql('DROP TABLE exam_answer');
        $this->addSql('DROP TABLE exam_attempt');
        $this->addSql('DROP TABLE exam_question');
        $this->addSql('ALTER TABLE exam DROP FOREIGN KEY FK_38BBA6C66278D5A8');
        $this->addSql('ALTER TABLE exam DROP FOREIGN KEY FK_38BBA6C641807E1D');
        $this->addSql('ALTER TABLE exam DROP FOREIGN KEY FK_38BBA6C623EDC87');
        $this->addSql('ALTER TABLE exam DROP duration_minutes');
        $this->addSql('ALTER TABLE grade DROP FOREIGN KEY FK_595AAE34CB944F1A');
        $this->addSql('ALTER TABLE grade DROP FOREIGN KEY FK_595AAE34578D5E91');
        $this->addSql('ALTER TABLE grade DROP FOREIGN KEY FK_595AAE34FE19A1A8');
        $this->addSql('ALTER TABLE lesson DROP FOREIGN KEY FK_F87474F323EDC87');
        $this->addSql('ALTER TABLE lesson DROP description, DROP file_path, DROP created_at, CHANGE content content LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E578D5E91');
        $this->addSql('ALTER TABLE subject DROP FOREIGN KEY FK_FBCE3E7A6278D5A8');
        $this->addSql('ALTER TABLE subject DROP FOREIGN KEY FK_FBCE3E7A41807E1D');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6496278D5A8');
    }
}
