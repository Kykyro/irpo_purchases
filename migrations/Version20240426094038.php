<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240426094038 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE uavs_certificate ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE uavs_certificate ADD CONSTRAINT FK_A326FD33A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A326FD33A76ED395 ON uavs_certificate (user_id)');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

        $this->addSql('DROP INDEX UNIQ_A326FD33A76ED395 ON uavs_certificate');
        $this->addSql('ALTER TABLE uavs_certificate DROP user_id');

    }
}
