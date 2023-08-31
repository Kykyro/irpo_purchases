<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230831132109 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE certificate_funds (id INT AUTO_INCREMENT NOT NULL, user_info_id INT DEFAULT NULL, economic_funds NUMERIC(20, 2) DEFAULT NULL, subject_funds NUMERIC(20, 2) DEFAULT NULL, extra_funds NUMERIC(20, 2) DEFAULT NULL, UNIQUE INDEX UNIQ_2FC29DF4586DFF2 (user_info_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE certificate_funds DROP FOREIGN KEY FK_2FC29DF4586DFF2');

    }
}
