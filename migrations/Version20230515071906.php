<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230515071906 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_tags (id INT AUTO_INCREMENT NOT NULL, tag VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_tags_user (user_tags_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_82D4F7FDD57651BE (user_tags_id), INDEX IDX_82D4F7FDA76ED395 (user_id), PRIMARY KEY(user_tags_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_tags_user ADD CONSTRAINT FK_82D4F7FDD57651BE FOREIGN KEY (user_tags_id) REFERENCES user_tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_tags_user ADD CONSTRAINT FK_82D4F7FDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_tags_user DROP FOREIGN KEY FK_82D4F7FDD57651BE');
        $this->addSql('ALTER TABLE user_tags_user DROP FOREIGN KEY FK_82D4F7FDA76ED395');
        $this->addSql('DROP TABLE user_tags');
        $this->addSql('DROP TABLE user_tags_user');
    }
}
