<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240715145300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invitation ADD sender_id INT NOT NULL');
        $this->addSql('ALTER TABLE invitation ADD receiver_email VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE invitation ADD message VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE invitation DROP email');
        $this->addSql('ALTER TABLE invitation ADD CONSTRAINT FK_F11D61A2F624B39D FOREIGN KEY (sender_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F11D61A25F37A13B ON invitation (token)');
        $this->addSql('CREATE INDEX IDX_F11D61A2F624B39D ON invitation (sender_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invitation DROP CONSTRAINT FK_F11D61A2F624B39D');
        $this->addSql('DROP INDEX UNIQ_F11D61A25F37A13B');
        $this->addSql('DROP INDEX IDX_F11D61A2F624B39D');
        $this->addSql('ALTER TABLE invitation ADD email VARCHAR(180) NOT NULL');
        $this->addSql('ALTER TABLE invitation DROP sender_id');
        $this->addSql('ALTER TABLE invitation DROP receiver_email');
        $this->addSql('ALTER TABLE invitation DROP message');
    }
}
