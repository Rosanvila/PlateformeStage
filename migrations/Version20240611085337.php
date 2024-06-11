<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240611085337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company ADD postal_code VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE company ADD city VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE company RENAME COLUMN business_address TO address');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company ADD business_address VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE company DROP address');
        $this->addSql('ALTER TABLE company DROP postal_code');
        $this->addSql('ALTER TABLE company DROP city');
    }
}
