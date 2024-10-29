<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241029152907 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart (id INT AUTO_INCREMENT NOT NULL, accessoire_id INT DEFAULT NULL, utilisateur_id INT DEFAULT NULL, quantite INT NOT NULL, INDEX IDX_BA388B7D23B67ED (accessoire_id), INDEX IDX_BA388B7FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, creer_le DATETIME NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_F5299398FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7D23B67ED FOREIGN KEY (accessoire_id) REFERENCES accessory (id)');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7D23B67ED');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7FB88E14F');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398FB88E14F');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE `order`');
    }
}
