<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230727140529 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO responsible_contact_type (name) VALUES ('информационную систему')");
        $this->addSql("INSERT INTO responsible_contact_type (name) VALUES ('закупочные процедуры')");
        $this->addSql("INSERT INTO responsible_contact_type (name) VALUES ('ремонтные работы')");

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
