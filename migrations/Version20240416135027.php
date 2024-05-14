<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240416135027 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE uavs_type_equipment (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, delivered_count INT DEFAULT NULL, delivered_sum NUMERIC(20, 2) DEFAULT NULL, contracted_count INT DEFAULT NULL, contracted_sum NUMERIC(20, 2) DEFAULT NULL, purchase_count INT DEFAULT NULL, purchase_sum NUMERIC(20, 2) DEFAULT NULL, plan_count INT DEFAULT NULL, plan_sum NUMERIC(20, 2) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, INDEX IDX_CDAF2F39A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE uavs_type_equipment ADD CONSTRAINT FK_CDAF2F39A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE uavs_type_equipment DROP FOREIGN KEY FK_CDAF2F39A76ED395');
        $this->addSql('DROP TABLE uavs_type_equipment');

    }
}
