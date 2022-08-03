<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220803132816 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Республика Адыгея')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Республика Башкортостан')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Республика Бурятия')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Республика Алтай')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Республика Дагестан')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Республика Ингушетия')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Кабардино-Балкарская Республика')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Республика Калмыкия')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Карачаево-Черкесская Республика')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Республика Карелия')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Республика Коми')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Республика Марий Эл')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Республика Мордовия')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Республика Саха (Якутия)')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Республика Северная Осетия — Алания')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Республика Татарстан')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Республика Тыва')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Удмуртская Республика')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Республика Хакасия')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Чеченская Республика')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Чувашская Республика')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Алтайский край')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Краснодарский край')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Красноярский край')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Приморский край')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Ставропольский край')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Хабаровский край')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Амурская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Архангельская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Астраханская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Белгородская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Брянская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Владимирская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Волгоградская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Вологодская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Воронежская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Ивановская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Иркутская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Калининградская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Калужская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Камчатский край')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Кемеровская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Кировская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Костромская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Курганская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Курская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Ленинградская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Липецкая область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Магаданская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Московская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Мурманская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Нижегородская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Новгородская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Новосибирская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Омская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Оренбургская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Орловская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Пензенская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Пермский край')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Псковская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Ростовская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Рязанская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Самарская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Саратовская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Сахалинская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Свердловская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Смоленская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Тамбовская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Тверская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Томская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Тульская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Тюменская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Ульяновская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Челябинская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Забайкальский край')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Ярославская область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Москва')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Санкт-Петербург')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Еврейская автономная область')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Республика Крым')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Ненецкий автономный округ')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Ханты-Мансийский автономный округ — Югра')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Чукотский автономный округ')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Ямало-Ненецкий автономный округ')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Севастополь, Крым')");
        $this->addSql("INSERT INTO rf_subject (name) VALUES ('Территории, находящиеся за пределами РФ и обслуживаемые Управлением режимных объектов МВД России, Байконур')");

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
