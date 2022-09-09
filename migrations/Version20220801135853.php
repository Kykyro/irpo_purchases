<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220801135853 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contract_price (id INT AUTO_INCREMENT NOT NULL, federal_funds NUMERIC(10, 0) DEFAULT NULL, funds_of_the_subject NUMERIC(10, 0) DEFAULT NULL, employers_funds NUMERIC(10, 0) DEFAULT NULL, educational_organization_funds NUMERIC(10, 0) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE deadline (id INT AUTO_INCREMENT NOT NULL, date_of_publication DATE DEFAULT NULL, deadline_date DATE DEFAULT NULL, summing_up DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE initial_contract_price (id INT AUTO_INCREMENT NOT NULL, federal_funds NUMERIC(10, 0) DEFAULT NULL, funds_of_the_subject NUMERIC(10, 0) DEFAULT NULL, employers_funds NUMERIC(10, 0) DEFAULT NULL, educational_organization_funds NUMERIC(10, 0) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE postponement (id INT AUTO_INCREMENT NOT NULL, date DATE DEFAULT NULL, comment LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE procurement_procedures (id INT AUTO_INCREMENT NOT NULL, purchase_object LONGTEXT DEFAULT NULL, method_of_determining VARCHAR(255) DEFAULT NULL, purchase_link VARCHAR(255) DEFAULT NULL, purchase_number INT DEFAULT NULL, date_of_conclusion DATE DEFAULT NULL, delivery_time DATE DEFAULT NULL, comments LONGTEXT DEFAULT NULL, file_dir VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rf_subject (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE supplier (id INT AUTO_INCREMENT NOT NULL, name LONGTEXT DEFAULT NULL, inn INT DEFAULT NULL, kpp INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, user_info_id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649586DFF2 (user_info_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_info (id INT AUTO_INCREMENT NOT NULL, rf_subject_id INT DEFAULT NULL, organization LONGTEXT DEFAULT NULL, educational_organization LONGTEXT DEFAULT NULL, cluster LONGTEXT DEFAULT NULL, declared_industry LONGTEXT DEFAULT NULL, INDEX IDX_B1087D9E1841DBB6 (rf_subject_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649586DFF2 FOREIGN KEY (user_info_id) REFERENCES user_info (id)');
        $this->addSql('ALTER TABLE user_info ADD CONSTRAINT FK_B1087D9E1841DBB6 FOREIGN KEY (rf_subject_id) REFERENCES rf_subject (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_info DROP FOREIGN KEY FK_B1087D9E1841DBB6');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649586DFF2');
        $this->addSql('DROP TABLE contract_price');
        $this->addSql('DROP TABLE deadline');
        $this->addSql('DROP TABLE initial_contract_price');
        $this->addSql('DROP TABLE postponement');
        $this->addSql('DROP TABLE procurement_procedures');
        $this->addSql('DROP TABLE rf_subject');
        $this->addSql('DROP TABLE supplier');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_info');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
