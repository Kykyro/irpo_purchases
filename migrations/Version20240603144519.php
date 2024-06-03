<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240603144519 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('SET FOREIGN_KEY_CHECKS = 0');
        $this->addSql('SET GLOBAL FOREIGN_KEY_CHECKS=0');

        $this->addSql('ALTER TABLE building_type CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE employers_category_employers ADD CONSTRAINT FK_3322444B57E0E899 FOREIGN KEY (employers_id) REFERENCES employers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE employers_contact ADD CONSTRAINT FK_9A2BDA441CD9E7A FOREIGN KEY (employer_id) REFERENCES employers (id)');
        $this->addSql('ALTER TABLE repair_dump ADD CONSTRAINT FK_924AA5C543833CFF FOREIGN KEY (repair_id) REFERENCES zone_repair (id)');
        $this->addSql('ALTER TABLE user_info_employers ADD CONSTRAINT FK_79F425F257E0E899 FOREIGN KEY (employers_id) REFERENCES employers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_tags_user ADD CONSTRAINT FK_82D4F7FDD57651BE FOREIGN KEY (user_tags_id) REFERENCES user_tags (id) ON DELETE CASCADE');

        $this->addSql('SET FOREIGN_KEY_CHECKS = 1');
        $this->addSql('SET GLOBAL FOREIGN_KEY_CHECKS=1');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE building_type CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE employers_category_employers DROP FOREIGN KEY FK_3322444B57E0E899');
        $this->addSql('ALTER TABLE employers_contact DROP FOREIGN KEY FK_9A2BDA441CD9E7A');
        $this->addSql('ALTER TABLE repair_dump DROP FOREIGN KEY FK_924AA5C543833CFF');
        $this->addSql('ALTER TABLE user_info_employers DROP FOREIGN KEY FK_79F425F257E0E899');
        $this->addSql('ALTER TABLE user_tags_user DROP FOREIGN KEY FK_82D4F7FDD57651BE');
    }
}
