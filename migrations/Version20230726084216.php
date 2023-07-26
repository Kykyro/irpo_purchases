<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230726084216 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cluster_perfomance_indicators (id INT AUTO_INCREMENT NOT NULL, user_info_id INT DEFAULT NULL, year INT DEFAULT NULL, student_count INT DEFAULT NULL, program_count INT DEFAULT NULL, teacher_count INT DEFAULT NULL, worker_count INT DEFAULT NULL, student_count_with_mentor INT DEFAULT NULL, job_security_count INT DEFAULT NULL, amount_of_funding NUMERIC(10, 2) DEFAULT NULL, amount_of_extra_funds NUMERIC(10, 2) DEFAULT NULL, INDEX IDX_7CF6D654586DFF2 (user_info_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cluster_perfomance_indicators ADD CONSTRAINT FK_7CF6D654586DFF2 FOREIGN KEY (user_info_id) REFERENCES user_info (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cluster_perfomance_indicators DROP FOREIGN KEY FK_7CF6D654586DFF2');
        $this->addSql('DROP TABLE cluster_perfomance_indicators');
    }
}
