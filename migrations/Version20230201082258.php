<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230201082258 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_info ADD students_count INT DEFAULT NULL, ADD program_count INT DEFAULT NULL, ADD teacher_count INT DEFAULT NULL, ADD worker_count INT DEFAULT NULL, ADD students_count_with_mentor INT DEFAULT NULL, ADD job_security_count INT DEFAULT NULL, ADD amount_of_funding NUMERIC(20, 2) DEFAULT NULL, ADD amount_of_extra_funds NUMERIC(20, 2) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_info DROP students_count, DROP program_count, DROP teacher_count, DROP worker_count, DROP students_count_with_mentor, DROP job_security_count, DROP amount_of_funding, DROP amount_of_extra_funds');
    }
}
