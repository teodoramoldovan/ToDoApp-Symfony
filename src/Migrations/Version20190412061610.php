<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190412061610 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE check_point (id INT AUTO_INCREMENT NOT NULL, to_do_item_id INT NOT NULL, name VARCHAR(255) NOT NULL, done TINYINT(1) NOT NULL, slug VARCHAR(100) NOT NULL, INDEX IDX_2DFFC0E69C6A0C3E (to_do_item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE check_point ADD CONSTRAINT FK_2DFFC0E69C6A0C3E FOREIGN KEY (to_do_item_id) REFERENCES to_do_item (id)');
        $this->addSql('ALTER TABLE to_do_item ADD description LONGTEXT DEFAULT NULL, ADD deadline DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE check_point');
        $this->addSql('ALTER TABLE to_do_item DROP description, DROP deadline');
    }
}
