<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240325115047 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE compitence_profile (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', file VARCHAR(255) DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, comment LONGTEXT DEFAULT NULL, INDEX IDX_BFEA8408A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE compitence_profile ADD CONSTRAINT FK_BFEA8408A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE compitence_profile DROP FOREIGN KEY FK_BFEA8408A76ED395');
        $this->addSql('DROP TABLE compitence_profile');

    }
}
