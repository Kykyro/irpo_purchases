<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230222110924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE photos_version (id INT AUTO_INCREMENT NOT NULL, repair_id INT DEFAULT NULL, created_at DATE NOT NULL, INDEX IDX_3CAD5C8343833CFF (repair_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE repair_photos (id INT AUTO_INCREMENT NOT NULL, version_id INT DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, INDEX IDX_D7B6D0DE4BBC2705 (version_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE photos_version ADD CONSTRAINT FK_3CAD5C8343833CFF FOREIGN KEY (repair_id) REFERENCES zone_repair (id)');
        $this->addSql('ALTER TABLE repair_photos ADD CONSTRAINT FK_D7B6D0DE4BBC2705 FOREIGN KEY (version_id) REFERENCES photos_version (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE photos_version DROP FOREIGN KEY FK_3CAD5C8343833CFF');
        $this->addSql('ALTER TABLE repair_photos DROP FOREIGN KEY FK_D7B6D0DE4BBC2705');
        $this->addSql('DROP TABLE photos_version');
        $this->addSql('DROP TABLE repair_photos');
    }
}
