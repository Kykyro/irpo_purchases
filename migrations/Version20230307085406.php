<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230307085406 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cluster_document (id INT AUTO_INCREMENT NOT NULL, user_info_id INT DEFAULT NULL, partnership_agreement VARCHAR(255) DEFAULT NULL, financial_agreement VARCHAR(255) DEFAULT NULL, infrastructure_sheet VARCHAR(255) DEFAULT NULL, design_project VARCHAR(255) DEFAULT NULL, activity_program VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_76D85DA9586DFF2 (user_info_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cluster_document ADD CONSTRAINT FK_76D85DA9586DFF2 FOREIGN KEY (user_info_id) REFERENCES user_info (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cluster_document DROP FOREIGN KEY FK_76D85DA9586DFF2');
        $this->addSql('DROP TABLE cluster_document');
    }
}
