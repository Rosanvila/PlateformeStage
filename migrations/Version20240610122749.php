<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240610122749 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company ADD postal_code VARCHAR(10) NOT NULL');
        $this->addSql('ALTER TABLE company ADD city VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE company ALTER about DROP DEFAULT');
        $this->addSql('ALTER TABLE company ALTER about SET NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD postal_code TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD city TEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company DROP postal_code');
        $this->addSql('ALTER TABLE company DROP city');
        $this->addSql('ALTER TABLE company ALTER about SET DEFAULT \'\'');
        $this->addSql('ALTER TABLE company ALTER about DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" DROP postal_code');
        $this->addSql('ALTER TABLE "user" DROP city');
    }
}
