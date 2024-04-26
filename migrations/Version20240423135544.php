<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240423135544 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" ADD is_verified BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD firstname VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD lastname VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD photo VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD job VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD expertise TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD business_address TEXT NOT NULL');
        $this->addSql('COMMENT ON COLUMN "user".expertise IS \'(DC2Type:array)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" DROP is_verified');
        $this->addSql('ALTER TABLE "user" DROP firstname');
        $this->addSql('ALTER TABLE "user" DROP lastname');
        $this->addSql('ALTER TABLE "user" DROP photo');
        $this->addSql('ALTER TABLE "user" DROP job');
        $this->addSql('ALTER TABLE "user" DROP expertise');
        $this->addSql('ALTER TABLE "user" DROP business_address');
    }
}
