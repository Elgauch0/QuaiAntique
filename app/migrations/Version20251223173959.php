<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251223173959 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE restaurant_settings (id SERIAL NOT NULL, lunch_start TIME(0) WITHOUT TIME ZONE NOT NULL, dinner_start TIME(0) WITHOUT TIME ZONE NOT NULL, max_convives SMALLINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN restaurant_settings.lunch_start IS \'(DC2Type:time_immutable)\'');
        $this->addSql('COMMENT ON COLUMN restaurant_settings.dinner_start IS \'(DC2Type:time_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE restaurant_settings');
    }
}
