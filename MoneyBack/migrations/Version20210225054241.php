<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210225054241 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agence ADD appartient_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE agence ADD CONSTRAINT FK_64C19AA9D35BF833 FOREIGN KEY (appartient_id) REFERENCES compte (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_64C19AA9D35BF833 ON agence (appartient_id)');
        $this->addSql('ALTER TABLE client ADD faire_id INT DEFAULT NULL, ADD nom_client VARCHAR(255) NOT NULL, ADD nom_beneficiaire VARCHAR(255) NOT NULL, ADD cni_client VARCHAR(255) NOT NULL, ADD cni_beneficiaire VARCHAR(255) NOT NULL, ADD phone_client VARCHAR(255) NOT NULL, ADD phone_beneficiaire VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C74404556776C72A FOREIGN KEY (faire_id) REFERENCES transaction (id)');
        $this->addSql('CREATE INDEX IDX_C74404556776C72A ON client (faire_id)');
        $this->addSql('ALTER TABLE user ADD caissier_id INT NOT NULL, ADD transact_id INT NOT NULL, ADD agence_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649B514973B FOREIGN KEY (caissier_id) REFERENCES compte (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649E45ED26B FOREIGN KEY (transact_id) REFERENCES transaction (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649B514973B ON user (caissier_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649E45ED26B ON user (transact_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649D725330D ON user (agence_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agence DROP FOREIGN KEY FK_64C19AA9D35BF833');
        $this->addSql('DROP INDEX UNIQ_64C19AA9D35BF833 ON agence');
        $this->addSql('ALTER TABLE agence DROP appartient_id');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C74404556776C72A');
        $this->addSql('DROP INDEX IDX_C74404556776C72A ON client');
        $this->addSql('ALTER TABLE client DROP faire_id, DROP nom_client, DROP nom_beneficiaire, DROP cni_client, DROP cni_beneficiaire, DROP phone_client, DROP phone_beneficiaire');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649B514973B');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649E45ED26B');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D725330D');
        $this->addSql('DROP INDEX IDX_8D93D649B514973B ON user');
        $this->addSql('DROP INDEX IDX_8D93D649E45ED26B ON user');
        $this->addSql('DROP INDEX IDX_8D93D649D725330D ON user');
        $this->addSql('ALTER TABLE user DROP caissier_id, DROP transact_id, DROP agence_id');
    }
}
