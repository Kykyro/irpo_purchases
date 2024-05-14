<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240404130829 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE file_check (id INT AUTO_INCREMENT NOT NULL, purchases_id INT DEFAULT NULL, filename VARCHAR(500) DEFAULT NULL, checked TINYINT(1) DEFAULT NULL, INDEX IDX_960D1C24559939B3 (purchases_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE file_check ADD CONSTRAINT FK_960D1C24559939B3 FOREIGN KEY (purchases_id) REFERENCES procurement_procedures (id)');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE file_check DROP FOREIGN KEY FK_960D1C24559939B3');
        $this->addSql('DROP TABLE file_check');

    }
}
