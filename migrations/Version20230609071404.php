<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230609071404 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_info_employers (user_info_id INT NOT NULL, employers_id INT NOT NULL, INDEX IDX_79F425F2586DFF2 (user_info_id), INDEX IDX_79F425F257E0E899 (employers_id), PRIMARY KEY(user_info_id, employers_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_info_employers ADD CONSTRAINT FK_79F425F2586DFF2 FOREIGN KEY (user_info_id) REFERENCES user_info (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_info_employers ADD CONSTRAINT FK_79F425F257E0E899 FOREIGN KEY (employers_id) REFERENCES employers (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_info_employers DROP FOREIGN KEY FK_79F425F2586DFF2');
        $this->addSql('ALTER TABLE user_info_employers DROP FOREIGN KEY FK_79F425F257E0E899');
        $this->addSql('DROP TABLE user_info_employers');
    }
}
