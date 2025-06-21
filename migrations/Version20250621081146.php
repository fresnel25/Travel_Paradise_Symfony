<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250621081146 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE visit ADD guide_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE visit ADD CONSTRAINT FK_437EE939D7ED1D4B FOREIGN KEY (guide_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_437EE939D7ED1D4B ON visit (guide_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE visit DROP FOREIGN KEY FK_437EE939D7ED1D4B
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_437EE939D7ED1D4B ON visit
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE visit DROP guide_id
        SQL);
    }
}
