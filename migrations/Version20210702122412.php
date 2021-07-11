<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210702122412 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE before_after ADD CONSTRAINT FK_4459C2111BC38CF0 FOREIGN KEY (price_model_id) REFERENCES price__model (id)');
        $this->addSql('CREATE INDEX IDX_4459C2111BC38CF0 ON before_after (price_model_id)');
        $this->addSql('ALTER TABLE config CHANGE name name VARCHAR(255) NOT NULL, CHANGE value value VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE INDEX name ON config (name)');
        $this->addSql('ALTER TABLE content CHANGE name name VARCHAR(250) DEFAULT NULL, CHANGE page_type page_type VARCHAR(255) NOT NULL, CHANGE h1 h1 VARCHAR(250) DEFAULT NULL, CHANGE meta_description meta_description TEXT DEFAULT NULL, CHANGE text text TEXT DEFAULT NULL, CHANGE published published TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE sort sort INT NOT NULL, CHANGE rating_value rating_value DOUBLE PRECISION DEFAULT \'4.8\' NOT NULL');
        $this->addSql('ALTER TABLE content ADD CONSTRAINT FK_FEC530A9727ACA70 FOREIGN KEY (parent_id) REFERENCES content (id)');
        $this->addSql('ALTER TABLE content ADD CONSTRAINT FK_FEC530A944F5D008 FOREIGN KEY (brand_id) REFERENCES price__brand (id)');
        $this->addSql('ALTER TABLE content ADD CONSTRAINT FK_FEC530A97975B7E7 FOREIGN KEY (model_id) REFERENCES price__model (id)');
        $this->addSql('ALTER TABLE content ADD CONSTRAINT FK_FEC530A9ED5CA9E6 FOREIGN KEY (service_id) REFERENCES price__services (id)');
        $this->addSql('ALTER TABLE content ADD CONSTRAINT FK_FEC530A9159FD1F4 FOREIGN KEY (price_category_id) REFERENCES price__categories (id)');
        $this->addSql('CREATE INDEX IDX_FEC530A944F5D008 ON content (brand_id)');
        $this->addSql('CREATE INDEX IDX_FEC530A97975B7E7 ON content (model_id)');
        $this->addSql('DROP INDEX parent_id ON content');
        $this->addSql('CREATE INDEX IDX_FEC530A9727ACA70 ON content (parent_id)');
        $this->addSql('DROP INDEX service_id ON content');
        $this->addSql('CREATE INDEX IDX_FEC530A9ED5CA9E6 ON content (service_id)');
        $this->addSql('DROP INDEX price_category_id ON content');
        $this->addSql('CREATE INDEX IDX_FEC530A9159FD1F4 ON content (price_category_id)');
        $this->addSql('ALTER TABLE naschiraboty CHANGE name name VARCHAR(255) NOT NULL, CHANGE meta_title meta_title VARCHAR(255) NOT NULL, CHANGE meta_description meta_description VARCHAR(255) NOT NULL, CHANGE sort sort INT NOT NULL, CHANGE short_text short_text LONGTEXT NOT NULL, CHANGE client_name client_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE news CHANGE name name VARCHAR(255) NOT NULL, CHANGE meta_title meta_title VARCHAR(255) NOT NULL, CHANGE meta_description meta_description VARCHAR(255) NOT NULL, CHANGE count_views count_views INT NOT NULL, CHANGE count_like count_like INT NOT NULL, CHANGE count_dislike count_dislike INT NOT NULL, CHANGE sort sort INT NOT NULL');
        $this->addSql('ALTER TABLE our_works ADD CONSTRAINT FK_378B4B511BC38CF0 FOREIGN KEY (price_model_id) REFERENCES price__model (id)');
        $this->addSql('CREATE INDEX IDX_378B4B511BC38CF0 ON our_works (price_model_id)');
        $this->addSql('DROP INDEX name ON price__brand');
        $this->addSql('ALTER TABLE price__categories ADD CONSTRAINT FK_5E61FA06727ACA70 FOREIGN KEY (parent_id) REFERENCES price__categories (id)');
        $this->addSql('CREATE INDEX IDX_5E61FA06727ACA70 ON price__categories (parent_id)');
        $this->addSql('ALTER TABLE price__model CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE name name VARCHAR(255) NOT NULL, CHANGE code code VARCHAR(255) NOT NULL, CHANGE name_rus name_rus VARCHAR(255) DEFAULT NULL, CHANGE brand_id brand_id INT DEFAULT NULL, CHANGE class class INT NOT NULL, CHANGE increase increase DOUBLE PRECISION NOT NULL');
        $this->addSql('CREATE INDEX IDX_10343615ED4B199F ON price__model (class)');
        $this->addSql('CREATE INDEX brand_id ON price__model (brand_id)');
        $this->addSql('ALTER TABLE price__services DROP FOREIGN KEY price__services_ibfk_1');
        $this->addSql('ALTER TABLE price__services DROP FOREIGN KEY price__services_ibfk_1');
        $this->addSql('ALTER TABLE price__services CHANGE price_category_id price_category_id INT NOT NULL, CHANGE published published TINYINT(1) NOT NULL, CHANGE pagetitle pagetitle VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE price__services ADD CONSTRAINT FK_DE4397F5159FD1F4 FOREIGN KEY (price_category_id) REFERENCES price__categories (id)');
        $this->addSql('DROP INDEX price_category_id ON price__services');
        $this->addSql('CREATE INDEX IDX_DE4397F5159FD1F4 ON price__services (price_category_id)');
        $this->addSql('ALTER TABLE price__services ADD CONSTRAINT price__services_ibfk_1 FOREIGN KEY (price_category_id) REFERENCES price__categories (id) ON UPDATE CASCADE ON DELETE SET NULL');
        $this->addSql('ALTER TABLE salon_price_model ADD CONSTRAINT FK_994B77421BC38CF0 FOREIGN KEY (price_model_id) REFERENCES price__model (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2C1BC38CF0 FOREIGN KEY (price_model_id) REFERENCES price__model (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE before_after DROP FOREIGN KEY FK_4459C2111BC38CF0');
        $this->addSql('DROP INDEX IDX_4459C2111BC38CF0 ON before_after');
        $this->addSql('DROP INDEX name ON config');
        $this->addSql('ALTER TABLE config CHANGE name name VARCHAR(191) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE value value TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE content DROP FOREIGN KEY FK_FEC530A9727ACA70');
        $this->addSql('ALTER TABLE content DROP FOREIGN KEY FK_FEC530A944F5D008');
        $this->addSql('ALTER TABLE content DROP FOREIGN KEY FK_FEC530A97975B7E7');
        $this->addSql('ALTER TABLE content DROP FOREIGN KEY FK_FEC530A9ED5CA9E6');
        $this->addSql('ALTER TABLE content DROP FOREIGN KEY FK_FEC530A9159FD1F4');
        $this->addSql('DROP INDEX IDX_FEC530A944F5D008 ON content');
        $this->addSql('DROP INDEX IDX_FEC530A97975B7E7 ON content');
        $this->addSql('ALTER TABLE content DROP FOREIGN KEY FK_FEC530A9727ACA70');
        $this->addSql('ALTER TABLE content DROP FOREIGN KEY FK_FEC530A9ED5CA9E6');
        $this->addSql('ALTER TABLE content DROP FOREIGN KEY FK_FEC530A9159FD1F4');
        $this->addSql('ALTER TABLE content CHANGE text text TEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE sort sort INT DEFAULT 0 NOT NULL, CHANGE published published TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE name name VARCHAR(250) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE h1 h1 VARCHAR(191) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE meta_description meta_description VARCHAR(1024) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE rating_value rating_value DOUBLE PRECISION UNSIGNED DEFAULT \'4.8\' NOT NULL, CHANGE page_type page_type VARCHAR(15) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`');
        $this->addSql('DROP INDEX idx_fec530a9ed5ca9e6 ON content');
        $this->addSql('CREATE INDEX service_id ON content (service_id)');
        $this->addSql('DROP INDEX idx_fec530a9159fd1f4 ON content');
        $this->addSql('CREATE INDEX price_category_id ON content (price_category_id)');
        $this->addSql('DROP INDEX idx_fec530a9727aca70 ON content');
        $this->addSql('CREATE INDEX parent_id ON content (parent_id)');
        $this->addSql('ALTER TABLE content ADD CONSTRAINT FK_FEC530A9727ACA70 FOREIGN KEY (parent_id) REFERENCES content (id)');
        $this->addSql('ALTER TABLE content ADD CONSTRAINT FK_FEC530A9ED5CA9E6 FOREIGN KEY (service_id) REFERENCES price__services (id)');
        $this->addSql('ALTER TABLE content ADD CONSTRAINT FK_FEC530A9159FD1F4 FOREIGN KEY (price_category_id) REFERENCES price__categories (id)');
        $this->addSql('ALTER TABLE naschiraboty CHANGE name name VARCHAR(250) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE meta_title meta_title VARCHAR(250) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE meta_description meta_description VARCHAR(250) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE sort sort INT DEFAULT 0 NOT NULL, CHANGE short_text short_text VARCHAR(250) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE client_name client_name VARCHAR(150) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`');
        $this->addSql('ALTER TABLE news CHANGE name name VARCHAR(250) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE meta_title meta_title VARCHAR(250) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE meta_description meta_description VARCHAR(250) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE sort sort INT DEFAULT 0 NOT NULL, CHANGE count_views count_views INT DEFAULT 0 NOT NULL, CHANGE count_like count_like INT DEFAULT 0 NOT NULL, CHANGE count_dislike count_dislike INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE our_works DROP FOREIGN KEY FK_378B4B511BC38CF0');
        $this->addSql('DROP INDEX IDX_378B4B511BC38CF0 ON our_works');
        $this->addSql('CREATE UNIQUE INDEX name ON price__brand (name)');
        $this->addSql('ALTER TABLE price__categories DROP FOREIGN KEY FK_5E61FA06727ACA70');
        $this->addSql('DROP INDEX IDX_5E61FA06727ACA70 ON price__categories');
        $this->addSql('ALTER TABLE price__model DROP FOREIGN KEY FK_10343615ED4B199F');
        $this->addSql('ALTER TABLE price__model DROP FOREIGN KEY FK_1034361544F5D008');
        $this->addSql('DROP INDEX IDX_10343615ED4B199F ON price__model');
        $this->addSql('DROP INDEX brand_id ON price__model');
        $this->addSql('ALTER TABLE price__model CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE class class INT DEFAULT 1 NOT NULL, CHANGE brand_id brand_id INT NOT NULL, CHANGE name name VARCHAR(200) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE code code VARCHAR(200) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE name_rus name_rus VARCHAR(200) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE increase increase DOUBLE PRECISION DEFAULT \'0.00\' NOT NULL');
        $this->addSql('ALTER TABLE price__services DROP FOREIGN KEY FK_DE4397F5159FD1F4');
        $this->addSql('ALTER TABLE price__services DROP FOREIGN KEY FK_DE4397F5159FD1F4');
        $this->addSql('ALTER TABLE price__services CHANGE price_category_id price_category_id INT DEFAULT NULL, CHANGE published published TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE pagetitle pagetitle VARCHAR(255) CHARACTER SET utf8 DEFAULT \'\'\'\'\'\' NOT NULL COLLATE `utf8_general_ci`');
        $this->addSql('ALTER TABLE price__services ADD CONSTRAINT price__services_ibfk_1 FOREIGN KEY (price_category_id) REFERENCES price__categories (id) ON UPDATE CASCADE ON DELETE SET NULL');
        $this->addSql('DROP INDEX idx_de4397f5159fd1f4 ON price__services');
        $this->addSql('CREATE INDEX price_category_id ON price__services (price_category_id)');
        $this->addSql('ALTER TABLE price__services ADD CONSTRAINT FK_DE4397F5159FD1F4 FOREIGN KEY (price_category_id) REFERENCES price__categories (id)');
        $this->addSql('ALTER TABLE salon_price_model DROP FOREIGN KEY FK_994B77421BC38CF0');
        $this->addSql('ALTER TABLE video DROP FOREIGN KEY FK_7CC7DA2C1BC38CF0');
    }
}
