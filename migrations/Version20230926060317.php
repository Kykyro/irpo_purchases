<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230926060317 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE monitoring_check_out (id INT AUTO_INCREMENT NOT NULL, user_info_id INT DEFAULT NULL, file VARCHAR(255) DEFAULT NULL, date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_371AA2E9586DFF2 (user_info_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE monitoring_check_out ADD CONSTRAINT FK_371AA2E9586DFF2 FOREIGN KEY (user_info_id) REFERENCES user_info (id)');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE monitoring_check_out DROP FOREIGN KEY FK_371AA2E9586DFF2');
        $this->addSql('DROP TABLE monitoring_check_out');

    }
}
