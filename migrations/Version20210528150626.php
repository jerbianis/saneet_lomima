<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210528150626 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart_item ADD cart_id INT NOT NULL, ADD product_id INT NOT NULL');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE25271AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE25274584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_F0FE25271AD5CDBF ON cart_item (cart_id)');
        $this->addSql('CREATE INDEX IDX_F0FE25274584665A ON cart_item (product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE25271AD5CDBF');
        $this->addSql('ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE25274584665A');
        $this->addSql('DROP INDEX IDX_F0FE25271AD5CDBF ON cart_item');
        $this->addSql('DROP INDEX IDX_F0FE25274584665A ON cart_item');
        $this->addSql('ALTER TABLE cart_item DROP cart_id, DROP product_id');
    }
}
