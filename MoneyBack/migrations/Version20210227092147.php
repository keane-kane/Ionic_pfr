<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210227092147 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agence ADD adminsystem_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE agence ADD CONSTRAINT FK_64C19AA96AF56DC3 FOREIGN KEY (adminsystem_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_64C19AA96AF56DC3 ON agence (adminsystem_id)');
        $this->addSql('ALTER TABLE compte ADD usercaissier_id INT DEFAULT NULL, ADD usertransaction_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE compte ADD CONSTRAINT FK_CFF652607138A1AF FOREIGN KEY (usercaissier_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE compte ADD CONSTRAINT FK_CFF652606E1E2671 FOREIGN KEY (usertransaction_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_CFF652607138A1AF ON compte (usercaissier_id)');
        $this->addSql('CREATE INDEX IDX_CFF652606E1E2671 ON compte (usertransaction_id)');
        $this->addSql('ALTER TABLE transaction ADD usertransaction_id INT DEFAULT NULL, ADD clientdepot_id INT DEFAULT NULL, ADD clientretrait_id INT DEFAULT NULL, ADD frais VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D16E1E2671 FOREIGN KEY (usertransaction_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1A36571DE FOREIGN KEY (clientdepot_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1D8443468 FOREIGN KEY (clientretrait_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_723705D16E1E2671 ON transaction (usertransaction_id)');
        $this->addSql('CREATE INDEX IDX_723705D1A36571DE ON transaction (clientdepot_id)');
        $this->addSql('CREATE INDEX IDX_723705D1D8443468 ON transaction (clientretrait_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649B514973B');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D725330D');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649E45ED26B');
        $this->addSql('DROP INDEX IDX_8D93D649B514973B ON user');
        $this->addSql('DROP INDEX IDX_8D93D649D725330D ON user');
        $this->addSql('DROP INDEX IDX_8D93D649E45ED26B ON user');
        $this->addSql('ALTER TABLE user DROP caissier_id, DROP transact_id, DROP agence_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agence DROP FOREIGN KEY FK_64C19AA96AF56DC3');
        $this->addSql('DROP INDEX IDX_64C19AA96AF56DC3 ON agence');
        $this->addSql('ALTER TABLE agence DROP adminsystem_id');
        $this->addSql('ALTER TABLE compte DROP FOREIGN KEY FK_CFF652607138A1AF');
        $this->addSql('ALTER TABLE compte DROP FOREIGN KEY FK_CFF652606E1E2671');
        $this->addSql('DROP INDEX IDX_CFF652607138A1AF ON compte');
        $this->addSql('DROP INDEX IDX_CFF652606E1E2671 ON compte');
        $this->addSql('ALTER TABLE compte DROP usercaissier_id, DROP usertransaction_id');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D16E1E2671');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1A36571DE');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1D8443468');
        $this->addSql('DROP INDEX IDX_723705D16E1E2671 ON transaction');
        $this->addSql('DROP INDEX IDX_723705D1A36571DE ON transaction');
        $this->addSql('DROP INDEX IDX_723705D1D8443468 ON transaction');
        $this->addSql('ALTER TABLE transaction DROP usertransaction_id, DROP clientdepot_id, DROP clientretrait_id, DROP frais');
        $this->addSql('ALTER TABLE user ADD caissier_id INT DEFAULT NULL, ADD transact_id INT DEFAULT NULL, ADD agence_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649B514973B FOREIGN KEY (caissier_id) REFERENCES compte (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D725330D FOREIGN KEY (agence_id) REFERENCES agence (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649E45ED26B FOREIGN KEY (transact_id) REFERENCES transaction (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_8D93D649B514973B ON user (caissier_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649D725330D ON user (agence_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649E45ED26B ON user (transact_id)');
    }
}
