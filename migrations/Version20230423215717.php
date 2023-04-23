<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230423215717 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA2396D72B15C');
        $this->addSql('DROP INDEX IDX_4DA2396D72B15C ON reservations');
        $this->addSql('ALTER TABLE reservations ADD total DOUBLE PRECISION DEFAULT NULL, DROP coupons_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservations ADD coupons_id INT DEFAULT NULL, DROP total');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA2396D72B15C FOREIGN KEY (coupons_id) REFERENCES coupons (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_4DA2396D72B15C ON reservations (coupons_id)');
    }
}
