<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210311001143 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agence DROP FOREIGN KEY FK_64C19AA96D45716B');
        $this->addSql('DROP INDEX UNIQ_64C19AA96D45716B ON agence');
        $this->addSql('ALTER TABLE agence DROP adminagence_id');
        $this->addSql('ALTER TABLE user ADD agence_partenaire_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D2F1AFD6 FOREIGN KEY (agence_partenaire_id) REFERENCES agence (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649D2F1AFD6 ON user (agence_partenaire_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agence ADD adminagence_id INT NOT NULL');
        $this->addSql('ALTER TABLE agence ADD CONSTRAINT FK_64C19AA96D45716B FOREIGN KEY (adminagence_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_64C19AA96D45716B ON agence (adminagence_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D2F1AFD6');
        $this->addSql('DROP INDEX IDX_8D93D649D2F1AFD6 ON user');
        $this->addSql('ALTER TABLE user DROP agence_partenaire_id');
    }
}
