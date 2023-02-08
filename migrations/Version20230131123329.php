<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230131123329 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE purchases_dump ADD dump_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE purchases_dump ADD CONSTRAINT FK_BEEC47592AE724B8 FOREIGN KEY (dump_id) REFERENCES purchases_dump_data (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BEEC47592AE724B8 ON purchases_dump (dump_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE purchases_dump DROP FOREIGN KEY FK_BEEC47592AE724B8');
        $this->addSql('DROP INDEX UNIQ_BEEC47592AE724B8 ON purchases_dump');
        $this->addSql('ALTER TABLE purchases_dump DROP dump_id');
    }
}
