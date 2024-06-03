<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240603125256 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE sessions');
        $this->addSql('ALTER TABLE another_document ADD CONSTRAINT FK_991A8989559939B3 FOREIGN KEY (purchases_id) REFERENCES procurement_procedures (id)');
        $this->addSql('ALTER TABLE api_token ADD CONSTRAINT FK_7BA2F5EBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE article_files ADD CONSTRAINT FK_718232487294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE certificate_funds ADD CONSTRAINT FK_2FC29DF4586DFF2 FOREIGN KEY (user_info_id) REFERENCES user_info (id)');
        $this->addSql('ALTER TABLE cluster_addresses ADD CONSTRAINT FK_79080B84A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE cluster_document ADD CONSTRAINT FK_76D85DA9586DFF2 FOREIGN KEY (user_info_id) REFERENCES user_info (id)');
        $this->addSql('ALTER TABLE cluster_perfomance_indicators ADD CONSTRAINT FK_7CF6D654586DFF2 FOREIGN KEY (user_info_id) REFERENCES user_info (id)');
        $this->addSql('ALTER TABLE cluster_zone ADD CONSTRAINT FK_2B0BDA4771FB0059 FOREIGN KEY (addres_id) REFERENCES cluster_addresses (id)');
        $this->addSql('ALTER TABLE cluster_zone ADD CONSTRAINT FK_2B0BDA47C54C8C93 FOREIGN KEY (type_id) REFERENCES zone_type (id)');
        $this->addSql('ALTER TABLE contact_info ADD CONSTRAINT FK_E376B3A8586DFF2 FOREIGN KEY (user_info_id) REFERENCES user_info (id)');
        $this->addSql('ALTER TABLE contact_info ADD CONSTRAINT FK_E376B3A8899FB366 FOREIGN KEY (director_id) REFERENCES cluter_director (id)');
        $this->addSql('ALTER TABLE contract_certification ADD CONSTRAINT FK_ED67ECAD586DFF2 FOREIGN KEY (user_info_id) REFERENCES user_info (id)');
        $this->addSql('ALTER TABLE cron_job_result ADD CONSTRAINT FK_2CD346EE79099ED8 FOREIGN KEY (cron_job_id) REFERENCES cron_job (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE employers_category_employers ADD CONSTRAINT FK_3322444B487560FE FOREIGN KEY (employers_category_id) REFERENCES employers_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE employers_contact ADD CONSTRAINT FK_9A2BDA4C7A4E079 FOREIGN KEY (contact_info_id) REFERENCES contact_info (id)');
        $this->addSql('ALTER TABLE equipment_log ADD CONSTRAINT FK_BEBFA0A4517FE9FE FOREIGN KEY (equipment_id) REFERENCES workzone_equipment (id)');
        $this->addSql('ALTER TABLE event_result RENAME INDEX fk_21f3b641fb176a84 TO IDX_21F3B641FB176A84');
        $this->addSql('ALTER TABLE favorites_clusters ADD CONSTRAINT FK_5D386E93733ADD1 FOREIGN KEY (inspector_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE favorites_clusters ADD CONSTRAINT FK_5D386E939133FDD FOREIGN KEY (cluster_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE favorites_clusters ADD CONSTRAINT FK_5D386E93C36A3328 FOREIGN KEY (cluster_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE infrastructure_sheet_files ADD CONSTRAINT FK_7933BE312B19A734 FOREIGN KEY (industry_id) REFERENCES industry (id)');
        $this->addSql('ALTER TABLE infrastructure_sheet_files ADD CONSTRAINT FK_7933BE312F95EC8F FOREIGN KEY (ugps_id) REFERENCES ugps (id)');
        $this->addSql('ALTER TABLE infrastructure_sheet_region_file ADD CONSTRAINT FK_3C5C329DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE login_journal ADD CONSTRAINT FK_D18AF930A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE photos_version ADD CONSTRAINT FK_3CAD5C8343833CFF FOREIGN KEY (repair_id) REFERENCES zone_repair (id)');
        $this->addSql('ALTER TABLE procurement_procedures ADD CONSTRAINT FK_31045326A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE procurement_procedures ADD CONSTRAINT FK_31045326FDC46870 FOREIGN KEY (conract_status_id) REFERENCES contract_status (id)');
        $this->addSql('ALTER TABLE prof_edu_org ADD CONSTRAINT FK_1FA1D7C798260155 FOREIGN KEY (region_id) REFERENCES rf_subject (id)');
        $this->addSql('ALTER TABLE purchase_note ADD CONSTRAINT FK_BF0E3277558FBEB9 FOREIGN KEY (purchase_id) REFERENCES procurement_procedures (id)');
        $this->addSql('ALTER TABLE purchase_note ADD CONSTRAINT FK_BF0E3277733D5B5D FOREIGN KEY (curator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE purchases_dump ADD CONSTRAINT FK_BEEC4759A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE purchases_dump ADD CONSTRAINT FK_BEEC47592AE724B8 FOREIGN KEY (dump_id) REFERENCES purchases_dump_data (id)');
        $this->addSql('ALTER TABLE readiness_map_archive ADD CONSTRAINT FK_ED289F11A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE repair_dump ADD CONSTRAINT FK_924AA5C5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE repair_dump ADD CONSTRAINT FK_924AA5C570BCAEB7 FOREIGN KEY (repair_dump_group_id) REFERENCES repair_dump_group (id)');
        $this->addSql('ALTER TABLE repair_photos ADD CONSTRAINT FK_D7B6D0DE4BBC2705 FOREIGN KEY (version_id) REFERENCES photos_version (id)');
        $this->addSql('ALTER TABLE responsible_contact ADD CONSTRAINT FK_56A12C35C7A4E079 FOREIGN KEY (contact_info_id) REFERENCES contact_info (id)');
        $this->addSql('ALTER TABLE responsible_contact_type_responsible_contact ADD CONSTRAINT FK_EBE2804B11279B3F FOREIGN KEY (responsible_contact_type_id) REFERENCES responsible_contact_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE responsible_contact_type_responsible_contact ADD CONSTRAINT FK_EBE2804BDC011ACD FOREIGN KEY (responsible_contact_id) REFERENCES responsible_contact (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user DROP INDEX FK_8D93D649586DFF2, ADD UNIQUE INDEX UNIQ_8D93D649586DFF2 (user_info_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649D17F50A6 ON user (uuid)');
        $this->addSql('ALTER TABLE user_info ADD CONSTRAINT FK_B1087D9E1841DBB6 FOREIGN KEY (rf_subject_id) REFERENCES rf_subject (id)');
        $this->addSql('CREATE INDEX IDX_B1087D9E1841DBB6 ON user_info (rf_subject_id)');
        $this->addSql('ALTER TABLE user_info_employers ADD PRIMARY KEY (user_info_id, employers_id)');
        $this->addSql('ALTER TABLE user_info_employers ADD CONSTRAINT FK_79F425F2586DFF2 FOREIGN KEY (user_info_id) REFERENCES user_info (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_79F425F2586DFF2 ON user_info_employers (user_info_id)');
        $this->addSql('CREATE INDEX IDX_79F425F257E0E899 ON user_info_employers (employers_id)');
        $this->addSql('ALTER TABLE user_tags_user ADD PRIMARY KEY (user_tags_id, user_id)');
        $this->addSql('ALTER TABLE user_tags_user ADD CONSTRAINT FK_82D4F7FDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_82D4F7FDD57651BE ON user_tags_user (user_tags_id)');
        $this->addSql('CREATE INDEX IDX_82D4F7FDA76ED395 ON user_tags_user (user_id)');
        $this->addSql('ALTER TABLE workzone_equipment RENAME INDEX fk_b223bfecd6e855da TO IDX_B223BFECD6E855DA');
        $this->addSql('ALTER TABLE zone_repair ADD CONSTRAINT FK_B1F71C53FF91AA43 FOREIGN KEY (cluster_zone_id) REFERENCES cluster_zone (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B1F71C53FF91AA43 ON zone_repair (cluster_zone_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sessions (sess_id VARBINARY(128) NOT NULL, sess_data LONGBLOB NOT NULL, sess_lifetime INT UNSIGNED NOT NULL, sess_time INT UNSIGNED NOT NULL) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_bin` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE another_document DROP FOREIGN KEY FK_991A8989559939B3');
        $this->addSql('ALTER TABLE api_token DROP FOREIGN KEY FK_7BA2F5EBA76ED395');
        $this->addSql('ALTER TABLE article_files DROP FOREIGN KEY FK_718232487294869C');
        $this->addSql('ALTER TABLE certificate_funds DROP FOREIGN KEY FK_2FC29DF4586DFF2');
        $this->addSql('ALTER TABLE cluster_addresses DROP FOREIGN KEY FK_79080B84A76ED395');
        $this->addSql('ALTER TABLE cluster_document DROP FOREIGN KEY FK_76D85DA9586DFF2');
        $this->addSql('ALTER TABLE cluster_perfomance_indicators DROP FOREIGN KEY FK_7CF6D654586DFF2');
        $this->addSql('ALTER TABLE cluster_zone DROP FOREIGN KEY FK_2B0BDA4771FB0059');
        $this->addSql('ALTER TABLE cluster_zone DROP FOREIGN KEY FK_2B0BDA47C54C8C93');
        $this->addSql('ALTER TABLE contact_info DROP FOREIGN KEY FK_E376B3A8586DFF2');
        $this->addSql('ALTER TABLE contact_info DROP FOREIGN KEY FK_E376B3A8899FB366');
        $this->addSql('ALTER TABLE contract_certification DROP FOREIGN KEY FK_ED67ECAD586DFF2');
        $this->addSql('ALTER TABLE cron_job_result DROP FOREIGN KEY FK_2CD346EE79099ED8');
        $this->addSql('ALTER TABLE employers_category_employers DROP FOREIGN KEY FK_3322444B487560FE');
        $this->addSql('ALTER TABLE employers_contact DROP FOREIGN KEY FK_9A2BDA4C7A4E079');
        $this->addSql('ALTER TABLE equipment_log DROP FOREIGN KEY FK_BEBFA0A4517FE9FE');
        $this->addSql('ALTER TABLE event_result RENAME INDEX idx_21f3b641fb176a84 TO FK_21F3B641FB176A84');
        $this->addSql('ALTER TABLE favorites_clusters DROP FOREIGN KEY FK_5D386E93733ADD1');
        $this->addSql('ALTER TABLE favorites_clusters DROP FOREIGN KEY FK_5D386E939133FDD');
        $this->addSql('ALTER TABLE favorites_clusters DROP FOREIGN KEY FK_5D386E93C36A3328');
        $this->addSql('ALTER TABLE infrastructure_sheet_files DROP FOREIGN KEY FK_7933BE312B19A734');
        $this->addSql('ALTER TABLE infrastructure_sheet_files DROP FOREIGN KEY FK_7933BE312F95EC8F');
        $this->addSql('ALTER TABLE infrastructure_sheet_region_file DROP FOREIGN KEY FK_3C5C329DA76ED395');
        $this->addSql('ALTER TABLE login_journal DROP FOREIGN KEY FK_D18AF930A76ED395');
        $this->addSql('ALTER TABLE photos_version DROP FOREIGN KEY FK_3CAD5C8343833CFF');
        $this->addSql('ALTER TABLE procurement_procedures DROP FOREIGN KEY FK_31045326A76ED395');
        $this->addSql('ALTER TABLE procurement_procedures DROP FOREIGN KEY FK_31045326FDC46870');
        $this->addSql('ALTER TABLE prof_edu_org DROP FOREIGN KEY FK_1FA1D7C798260155');
        $this->addSql('ALTER TABLE purchase_note DROP FOREIGN KEY FK_BF0E3277558FBEB9');
        $this->addSql('ALTER TABLE purchase_note DROP FOREIGN KEY FK_BF0E3277733D5B5D');
        $this->addSql('ALTER TABLE purchases_dump DROP FOREIGN KEY FK_BEEC4759A76ED395');
        $this->addSql('ALTER TABLE purchases_dump DROP FOREIGN KEY FK_BEEC47592AE724B8');
        $this->addSql('ALTER TABLE readiness_map_archive DROP FOREIGN KEY FK_ED289F11A76ED395');
        $this->addSql('ALTER TABLE repair_dump DROP FOREIGN KEY FK_924AA5C5A76ED395');
        $this->addSql('ALTER TABLE repair_dump DROP FOREIGN KEY FK_924AA5C570BCAEB7');
        $this->addSql('ALTER TABLE repair_photos DROP FOREIGN KEY FK_D7B6D0DE4BBC2705');
        $this->addSql('ALTER TABLE responsible_contact DROP FOREIGN KEY FK_56A12C35C7A4E079');
        $this->addSql('ALTER TABLE responsible_contact_type_responsible_contact DROP FOREIGN KEY FK_EBE2804B11279B3F');
        $this->addSql('ALTER TABLE responsible_contact_type_responsible_contact DROP FOREIGN KEY FK_EBE2804BDC011ACD');
        $this->addSql('ALTER TABLE user DROP INDEX UNIQ_8D93D649586DFF2, ADD INDEX FK_8D93D649586DFF2 (user_info_id)');
        $this->addSql('DROP INDEX UNIQ_8D93D649D17F50A6 ON user');
        $this->addSql('ALTER TABLE user_info DROP FOREIGN KEY FK_B1087D9E1841DBB6');
        $this->addSql('DROP INDEX IDX_B1087D9E1841DBB6 ON user_info');
        $this->addSql('ALTER TABLE user_info_employers DROP FOREIGN KEY FK_79F425F2586DFF2');
        $this->addSql('DROP INDEX IDX_79F425F2586DFF2 ON user_info_employers');
        $this->addSql('DROP INDEX IDX_79F425F257E0E899 ON user_info_employers');
        $this->addSql('DROP INDEX `primary` ON user_info_employers');
        $this->addSql('ALTER TABLE user_tags_user DROP FOREIGN KEY FK_82D4F7FDA76ED395');
        $this->addSql('DROP INDEX IDX_82D4F7FDD57651BE ON user_tags_user');
        $this->addSql('DROP INDEX IDX_82D4F7FDA76ED395 ON user_tags_user');
        $this->addSql('DROP INDEX `primary` ON user_tags_user');
        $this->addSql('ALTER TABLE workzone_equipment RENAME INDEX idx_b223bfecd6e855da TO FK_B223BFECD6E855DA');
        $this->addSql('ALTER TABLE zone_repair DROP FOREIGN KEY FK_B1F71C53FF91AA43');
        $this->addSql('DROP INDEX UNIQ_B1F71C53FF91AA43 ON zone_repair');
    }
}
