<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240719141556 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX uniq_f11d61a25f37a13b');
        $this->addSql('ALTER TABLE invitation ADD uuid VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE invitation DROP token');
        $this->addSql('ALTER TABLE invitation DROP message');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F11D61A2D17F50A6 ON invitation (uuid)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_F11D61A2D17F50A6');
        $this->addSql('ALTER TABLE invitation ADD message VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE invitation RENAME COLUMN uuid TO token');
        $this->addSql('CREATE UNIQUE INDEX uniq_f11d61a25f37a13b ON invitation (token)');
    }
}
