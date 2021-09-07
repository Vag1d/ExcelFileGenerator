<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201109121906 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `database` (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, full_name_c30 VARCHAR(255) NOT NULL, short_name VARCHAR(255) NOT NULL, fio VARCHAR(255) NOT NULL, spot VARCHAR(255) NOT NULL, adres VARCHAR(255) NOT NULL, ystaw VARCHAR(255) NOT NULL, spot_name_fp VARCHAR(255) DEFAULT NULL, main_fiofp VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, num_gk VARCHAR(255) DEFAULT NULL, protocol SMALLINT DEFAULT NULL, template SMALLINT DEFAULT NULL, spot_genitive VARCHAR(255) DEFAULT NULL, fio_genitive VARCHAR(255) DEFAULT NULL, INDEX IDX_C953062EF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, fio VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `database` ADD CONSTRAINT FK_C953062EF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `database` DROP FOREIGN KEY FK_C953062EF675F31B');
        $this->addSql('DROP TABLE `database`');
        $this->addSql('DROP TABLE user');
    }
}
