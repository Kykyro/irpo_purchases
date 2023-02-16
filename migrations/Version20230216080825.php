<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230216080825 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE purchase_note (id INT AUTO_INCREMENT NOT NULL, purchase_id INT DEFAULT NULL, curator_id INT DEFAULT NULL, note LONGTEXT DEFAULT NULL, is_read TINYINT(1) DEFAULT NULL, INDEX IDX_BF0E3277558FBEB9 (purchase_id), INDEX IDX_BF0E3277733D5B5D (curator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE purchase_note ADD CONSTRAINT FK_BF0E3277558FBEB9 FOREIGN KEY (purchase_id) REFERENCES procurement_procedures (id)');
        $this->addSql('ALTER TABLE purchase_note ADD CONSTRAINT FK_BF0E3277733D5B5D FOREIGN KEY (curator_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE purchase_note DROP FOREIGN KEY FK_BF0E3277558FBEB9');
        $this->addSql('ALTER TABLE purchase_note DROP FOREIGN KEY FK_BF0E3277733D5B5D');
        $this->addSql('DROP TABLE purchase_note');
    }
}
