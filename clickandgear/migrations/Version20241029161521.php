<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241029161521 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7D23B67ED');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7FB88E14F');
        $this->addSql('DROP INDEX IDX_BA388B7D23B67ED ON cart');
        $this->addSql('DROP INDEX IDX_BA388B7FB88E14F ON cart');
        $this->addSql('ALTER TABLE cart ADD user_id INT DEFAULT NULL, ADD accessory_id INT DEFAULT NULL, ADD order_id INT DEFAULT NULL, DROP accessoire_id, DROP utilisateur_id, CHANGE quantite quantity INT NOT NULL');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7A76ED395 FOREIGN KEY (user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B727E8CC78 FOREIGN KEY (accessory_id) REFERENCES accessory (id)');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B78D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_BA388B7A76ED395 ON cart (user_id)');
        $this->addSql('CREATE INDEX IDX_BA388B727E8CC78 ON cart (accessory_id)');
        $this->addSql('CREATE INDEX IDX_BA388B78D9F6D38 ON cart (order_id)');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398FB88E14F');
        $this->addSql('DROP INDEX IDX_F5299398FB88E14F ON `order`');
        $this->addSql('ALTER TABLE `order` CHANGE utilisateur_id user_id INT DEFAULT NULL, CHANGE creer_le created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_F5299398A76ED395 ON `order` (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398A76ED395');
        $this->addSql('DROP INDEX IDX_F5299398A76ED395 ON `order`');
        $this->addSql('ALTER TABLE `order` CHANGE user_id utilisateur_id INT DEFAULT NULL, CHANGE created_at creer_le DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_F5299398FB88E14F ON `order` (utilisateur_id)');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7A76ED395');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B727E8CC78');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B78D9F6D38');
        $this->addSql('DROP INDEX IDX_BA388B7A76ED395 ON cart');
        $this->addSql('DROP INDEX IDX_BA388B727E8CC78 ON cart');
        $this->addSql('DROP INDEX IDX_BA388B78D9F6D38 ON cart');
        $this->addSql('ALTER TABLE cart ADD accessoire_id INT DEFAULT NULL, ADD utilisateur_id INT DEFAULT NULL, DROP user_id, DROP accessory_id, DROP order_id, CHANGE quantity quantite INT NOT NULL');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7D23B67ED FOREIGN KEY (accessoire_id) REFERENCES accessory (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_BA388B7D23B67ED ON cart (accessoire_id)');
        $this->addSql('CREATE INDEX IDX_BA388B7FB88E14F ON cart (utilisateur_id)');
    }
}
