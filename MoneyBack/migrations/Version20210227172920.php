<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210227172920 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agence ADD adminagence_id INT NOT NULL');
        $this->addSql('ALTER TABLE agence ADD CONSTRAINT FK_64C19AA96D45716B FOREIGN KEY (adminagence_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_64C19AA96D45716B ON agence (adminagence_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agence DROP FOREIGN KEY FK_64C19AA96D45716B');
        $this->addSql('DROP INDEX IDX_64C19AA96D45716B ON agence');
        $this->addSql('ALTER TABLE agence DROP adminagence_id');
    }
}
