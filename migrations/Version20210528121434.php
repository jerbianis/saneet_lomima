<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210528121434 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE status ADD status_order_id INT NOT NULL');
        $this->addSql('ALTER TABLE status ADD CONSTRAINT FK_7B00651C1045CAE0 FOREIGN KEY (status_order_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_7B00651C1045CAE0 ON status (status_order_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE status DROP FOREIGN KEY FK_7B00651C1045CAE0');
        $this->addSql('DROP INDEX IDX_7B00651C1045CAE0 ON status');
        $this->addSql('ALTER TABLE status DROP status_order_id');
    }
}
