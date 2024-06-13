<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240612152536 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE post_comment ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE "user" ALTER id DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE post_comment_id_seq');
        $this->addSql('SELECT setval(\'post_comment_id_seq\', (SELECT MAX(id) FROM post_comment))');
        $this->addSql('ALTER TABLE post_comment ALTER id SET DEFAULT nextval(\'post_comment_id_seq\')');
        $this->addSql('CREATE SEQUENCE user_id_seq');
        $this->addSql('SELECT setval(\'user_id_seq\', (SELECT MAX(id) FROM "user"))');
        $this->addSql('ALTER TABLE "user" ALTER id SET DEFAULT nextval(\'user_id_seq\')');
        $this->addSql('CREATE SEQUENCE post_id_seq');
        $this->addSql('SELECT setval(\'post_id_seq\', (SELECT MAX(id) FROM post))');
        $this->addSql('ALTER TABLE post ALTER id SET DEFAULT nextval(\'post_id_seq\')');
    }
}
