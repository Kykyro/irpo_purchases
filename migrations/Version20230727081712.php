<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230727081712 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cluter_director (id INT AUTO_INCREMENT NOT NULL, fio VARCHAR(300) DEFAULT NULL, phone_number VARCHAR(50) DEFAULT NULL, email VARCHAR(100) DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact_info (id INT AUTO_INCREMENT NOT NULL, user_info_id INT DEFAULT NULL, director_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_E376B3A8586DFF2 (user_info_id), UNIQUE INDEX UNIQ_E376B3A8899FB366 (director_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contact_info ADD CONSTRAINT FK_E376B3A8586DFF2 FOREIGN KEY (user_info_id) REFERENCES user_info (id)');
        $this->addSql('ALTER TABLE contact_info ADD CONSTRAINT FK_E376B3A8899FB366 FOREIGN KEY (director_id) REFERENCES cluter_director (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contact_info DROP FOREIGN KEY FK_E376B3A8586DFF2');
        $this->addSql('ALTER TABLE contact_info DROP FOREIGN KEY FK_E376B3A8899FB366');
        $this->addSql('DROP TABLE cluter_director');
        $this->addSql('DROP TABLE contact_info');
    }
}
