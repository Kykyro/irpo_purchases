<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230614063613 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE employers_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(500) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employers_category_employers (employers_category_id INT NOT NULL, employers_id INT NOT NULL, INDEX IDX_3322444B487560FE (employers_category_id), INDEX IDX_3322444B57E0E899 (employers_id), PRIMARY KEY(employers_category_id, employers_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE employers_category_employers ADD CONSTRAINT FK_3322444B487560FE FOREIGN KEY (employers_category_id) REFERENCES employers_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE employers_category_employers ADD CONSTRAINT FK_3322444B57E0E899 FOREIGN KEY (employers_id) REFERENCES employers (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employers_category_employers DROP FOREIGN KEY FK_3322444B487560FE');
        $this->addSql('ALTER TABLE employers_category_employers DROP FOREIGN KEY FK_3322444B57E0E899');
        $this->addSql('DROP TABLE employers_category');
        $this->addSql('DROP TABLE employers_category_employers');
    }
}
