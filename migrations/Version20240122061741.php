<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240122061741 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sheet_workzone ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sheet_workzone ADD CONSTRAINT FK_22B23D45A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
//        $this->addSql('CREATE INDEX IDX_22B23D45A76ED395 ON sheet_workzone (user_id)');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
//        $this->addSql('ALTER TABLE building_type CHANGE id id INT NOT NULL');
//        $this->addSql('ALTER TABLE certificate_funds DROP FOREIGN KEY FK_2FC29DF4586DFF2');
//        $this->addSql('ALTER TABLE cluster_addresses DROP FOREIGN KEY FK_79080B84A76ED395');
//        $this->addSql('ALTER TABLE cluster_document DROP FOREIGN KEY FK_76D85DA9586DFF2');
//        $this->addSql('ALTER TABLE cluster_perfomance_indicators DROP FOREIGN KEY FK_7CF6D654586DFF2');
//        $this->addSql('ALTER TABLE cluster_zone DROP FOREIGN KEY FK_2B0BDA4771FB0059');
//        $this->addSql('ALTER TABLE cluster_zone DROP FOREIGN KEY FK_2B0BDA47C54C8C93');
//        $this->addSql('ALTER TABLE contact_info DROP FOREIGN KEY FK_E376B3A8586DFF2');
//        $this->addSql('ALTER TABLE contact_info DROP FOREIGN KEY FK_E376B3A8899FB366');
//        $this->addSql('ALTER TABLE contract_certification DROP FOREIGN KEY FK_ED67ECAD586DFF2');
//        $this->addSql('ALTER TABLE cron_job_result DROP FOREIGN KEY FK_2CD346EE79099ED8');
//        $this->addSql('ALTER TABLE employers_category_employers DROP FOREIGN KEY FK_3322444B487560FE');
//        $this->addSql('ALTER TABLE employers_category_employers DROP FOREIGN KEY FK_3322444B57E0E899');
//        $this->addSql('ALTER TABLE employers_contact DROP FOREIGN KEY FK_9A2BDA441CD9E7A');
//        $this->addSql('ALTER TABLE employers_contact DROP FOREIGN KEY FK_9A2BDA4C7A4E079');
//        $this->addSql('ALTER TABLE favorites_clusters DROP FOREIGN KEY FK_5D386E93733ADD1');
//        $this->addSql('ALTER TABLE favorites_clusters DROP FOREIGN KEY FK_5D386E939133FDD');
//        $this->addSql('ALTER TABLE favorites_clusters DROP FOREIGN KEY FK_5D386E93C36A3328');
//        $this->addSql('ALTER TABLE infrastructure_sheet_files DROP FOREIGN KEY FK_7933BE312B19A734');
//        $this->addSql('ALTER TABLE infrastructure_sheet_files DROP FOREIGN KEY FK_7933BE312F95EC8F');
//        $this->addSql('ALTER TABLE infrastructure_sheet_region_file DROP FOREIGN KEY FK_3C5C329DA76ED395');
//        $this->addSql('ALTER TABLE login_journal DROP FOREIGN KEY FK_D18AF930A76ED395');
//        $this->addSql('ALTER TABLE photos_version DROP FOREIGN KEY FK_3CAD5C8343833CFF');
//        $this->addSql('ALTER TABLE procurement_procedures DROP FOREIGN KEY FK_31045326A76ED395');
//        $this->addSql('ALTER TABLE procurement_procedures DROP FOREIGN KEY FK_31045326FDC46870');
//        $this->addSql('ALTER TABLE prof_edu_org DROP FOREIGN KEY FK_1FA1D7C798260155');
//        $this->addSql('ALTER TABLE purchase_note DROP FOREIGN KEY FK_BF0E3277558FBEB9');
//        $this->addSql('ALTER TABLE purchase_note DROP FOREIGN KEY FK_BF0E3277733D5B5D');
//        $this->addSql('ALTER TABLE purchases_dump DROP FOREIGN KEY FK_BEEC4759A76ED395');
//        $this->addSql('ALTER TABLE purchases_dump DROP FOREIGN KEY FK_BEEC47592AE724B8');
//        $this->addSql('ALTER TABLE readiness_map_archive DROP FOREIGN KEY FK_ED289F11A76ED395');
//        $this->addSql('ALTER TABLE repair_dump DROP FOREIGN KEY FK_924AA5C543833CFF');
//        $this->addSql('ALTER TABLE repair_dump DROP FOREIGN KEY FK_924AA5C5A76ED395');
//        $this->addSql('ALTER TABLE repair_dump DROP FOREIGN KEY FK_924AA5C570BCAEB7');
//        $this->addSql('ALTER TABLE repair_photos DROP FOREIGN KEY FK_D7B6D0DE4BBC2705');
//        $this->addSql('ALTER TABLE responsible_contact DROP FOREIGN KEY FK_56A12C35C7A4E079');
//        $this->addSql('ALTER TABLE responsible_contact_type_responsible_contact DROP FOREIGN KEY FK_EBE2804B11279B3F');
//        $this->addSql('ALTER TABLE responsible_contact_type_responsible_contact DROP FOREIGN KEY FK_EBE2804BDC011ACD');
//        $this->addSql('ALTER TABLE sheet_workzone DROP FOREIGN KEY FK_22B23D45A76ED395');
//        $this->addSql('DROP INDEX IDX_22B23D45A76ED395 ON sheet_workzone');
//        $this->addSql('ALTER TABLE sheet_workzone DROP user_id');
//        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649586DFF2');
//        $this->addSql('DROP INDEX UNIQ_8D93D649D17F50A6 ON user');
//        $this->addSql('DROP INDEX UNIQ_8D93D649586DFF2 ON user');
//        $this->addSql('ALTER TABLE user_info DROP FOREIGN KEY FK_B1087D9E1841DBB6');
//        $this->addSql('DROP INDEX IDX_B1087D9E1841DBB6 ON user_info');
//        $this->addSql('ALTER TABLE user_info_employers DROP FOREIGN KEY FK_79F425F2586DFF2');
//        $this->addSql('ALTER TABLE user_info_employers DROP FOREIGN KEY FK_79F425F257E0E899');
//        $this->addSql('DROP INDEX IDX_79F425F2586DFF2 ON user_info_employers');
//        $this->addSql('DROP INDEX IDX_79F425F257E0E899 ON user_info_employers');
//        $this->addSql('DROP INDEX `primary` ON user_info_employers');
//        $this->addSql('ALTER TABLE user_tags_user DROP FOREIGN KEY FK_82D4F7FDD57651BE');
//        $this->addSql('ALTER TABLE user_tags_user DROP FOREIGN KEY FK_82D4F7FDA76ED395');
//        $this->addSql('DROP INDEX IDX_82D4F7FDD57651BE ON user_tags_user');
//        $this->addSql('DROP INDEX IDX_82D4F7FDA76ED395 ON user_tags_user');
//        $this->addSql('DROP INDEX `primary` ON user_tags_user');
//        $this->addSql('ALTER TABLE zone_infrastructure_sheet DROP FOREIGN KEY FK_4EDC9A8C9F2C3FAB');
//        $this->addSql('DROP INDEX IDX_4EDC9A8C9F2C3FAB ON zone_infrastructure_sheet');
//        $this->addSql('ALTER TABLE zone_repair DROP FOREIGN KEY FK_B1F71C53FF91AA43');
//        $this->addSql('DROP INDEX UNIQ_B1F71C53FF91AA43 ON zone_repair');
    }
}
