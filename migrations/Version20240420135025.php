<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240420135025 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE réservation DROP FOREIGN KEY fk_4');
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, Libelle VARCHAR(50) NOT NULL, description VARCHAR(1000) NOT NULL, date DATE NOT NULL, frais DOUBLE PRECISION NOT NULL, photo VARCHAR(1000) NOT NULL, localisation VARCHAR(100) NOT NULL, siteweb VARCHAR(1000) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY fk_azd');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY fk_7');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY fk_8');
        $this->addSql('DROP TABLE évenement');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE commandes');
        $this->addSql('DROP TABLE commandes_produits');
        $this->addSql('DROP TABLE livreur');
        $this->addSql('DROP TABLE mission');
        $this->addSql('DROP TABLE my_sequence');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE type_reclamation');
        $this->addSql('ALTER TABLE adresses DROP FOREIGN KEY user_adress');
        $this->addSql('ALTER TABLE adresses CHANGE country country VARCHAR(100) NOT NULL, CHANGE state state VARCHAR(100) DEFAULT NULL, CHANGE city city VARCHAR(100) NOT NULL, CHANGE street street VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE produit CHANGE image image VARCHAR(500) NOT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27C9486A13 FOREIGN KEY (id_categorie) REFERENCES categorie_produit (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27C9486A13 ON produit (id_categorie)');
        $this->addSql('ALTER TABLE réservation DROP FOREIGN KEY fk_3');
        $this->addSql('ALTER TABLE réservation DROP FOREIGN KEY fk_3');
        $this->addSql('ALTER TABLE réservation DROP FOREIGN KEY fk_4');
        $this->addSql('ALTER TABLE réservation CHANGE id_client id_client INT DEFAULT NULL, CHANGE id_evenement id_evenement INT DEFAULT NULL');
        $this->addSql('ALTER TABLE réservation ADD CONSTRAINT FK_666D6ED1E173B1B8 FOREIGN KEY (id_client) REFERENCES user (id)');
        $this->addSql('ALTER TABLE réservation ADD CONSTRAINT FK_666D6ED18B13D439 FOREIGN KEY (id_evenement) REFERENCES evenement (id)');
        $this->addSql('DROP INDEX fk_3 ON réservation');
        $this->addSql('CREATE INDEX IDX_666D6ED1E173B1B8 ON réservation (id_client)');
        $this->addSql('DROP INDEX fk_4 ON réservation');
        $this->addSql('CREATE INDEX IDX_666D6ED18B13D439 ON réservation (id_evenement)');
        $this->addSql('ALTER TABLE réservation ADD CONSTRAINT fk_3 FOREIGN KEY (id_client) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE réservation ADD CONSTRAINT fk_4 FOREIGN KEY (id_evenement) REFERENCES évenement (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP INDEX adresse ON user');
        $this->addSql('ALTER TABLE user CHANGE photo photo VARCHAR(100) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE réservation DROP FOREIGN KEY FK_666D6ED18B13D439');
        $this->addSql('CREATE TABLE évenement (id INT AUTO_INCREMENT NOT NULL, Libelle VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description VARCHAR(1000) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date DATE NOT NULL, frais DOUBLE PRECISION NOT NULL, photo VARCHAR(1000) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, localisation VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, siteweb VARCHAR(1000) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, prenom VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, photo VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, adresse VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, adresse_mail VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, mdp VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, id_client INT NOT NULL, id_produit INT DEFAULT NULL, id_mission INT NOT NULL, date DATE NOT NULL, prixtotal DOUBLE PRECISION NOT NULL, reference VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX fk_azd (id_mission), INDEX fk_7 (id_client), INDEX fk_8 (id_produit), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE commandes (id INT AUTO_INCREMENT NOT NULL, refernce VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date DATE NOT NULL, id_produit INT NOT NULL, id_client INT DEFAULT NULL, id_mission INT DEFAULT NULL, prix DOUBLE PRECISION DEFAULT NULL, INDEX ck_mission (id_mission), INDEX ck_produit (id_produit), INDEX ck_idClient (id_client), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE commandes_produits (id INT AUTO_INCREMENT NOT NULL, reference VARCHAR(11) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, quantite INT NOT NULL, prixtotal DOUBLE PRECISION DEFAULT NULL, UNIQUE INDEX reference (reference), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE livreur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, prenom VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, photo VARCHAR(500) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, mail VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, mdp VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE mission (id_mission INT AUTO_INCREMENT NOT NULL, etat VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id_mission)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE my_sequence (next_not_cached_value BIGINT NOT NULL, minimum_value BIGINT NOT NULL, maximum_value BIGINT NOT NULL, start_value BIGINT NOT NULL COMMENT \'start value when sequences is created or value if RESTART is used\', increment BIGINT NOT NULL COMMENT \'increment value\', cache_size BIGINT UNSIGNED NOT NULL, cycle_option TINYINT(1) NOT NULL COMMENT \'0 if no cycles are allowed, 1 if the sequence should begin a new cycle when maximum_value is passed\', cycle_count BIGINT NOT NULL COMMENT \'How many cycles have been done\') DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, des VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, état TINYINT(1) NOT NULL, reply VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, id_client INT DEFAULT NULL, id_type INT DEFAULT NULL, id_commande INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE type_reclamation (id INT AUTO_INCREMENT NOT NULL, Libelle VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, NumUtilisateurs INT DEFAULT 0, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT fk_azd FOREIGN KEY (id_mission) REFERENCES mission (id_mission)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT fk_7 FOREIGN KEY (id_client) REFERENCES client (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT fk_8 FOREIGN KEY (id_produit) REFERENCES produit (ref) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE adresses CHANGE country country VARCHAR(50) NOT NULL, CHANGE state state VARCHAR(50) DEFAULT NULL, CHANGE city city VARCHAR(50) NOT NULL, CHANGE street street VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE adresses ADD CONSTRAINT user_adress FOREIGN KEY (id) REFERENCES user (adresse) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27C9486A13');
        $this->addSql('DROP INDEX IDX_29A5EC27C9486A13 ON produit');
        $this->addSql('ALTER TABLE produit CHANGE image image VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE réservation DROP FOREIGN KEY FK_666D6ED1E173B1B8');
        $this->addSql('ALTER TABLE réservation DROP FOREIGN KEY FK_666D6ED1E173B1B8');
        $this->addSql('ALTER TABLE réservation DROP FOREIGN KEY FK_666D6ED18B13D439');
        $this->addSql('ALTER TABLE réservation CHANGE id_client id_client INT NOT NULL, CHANGE id_evenement id_evenement INT NOT NULL');
        $this->addSql('ALTER TABLE réservation ADD CONSTRAINT fk_3 FOREIGN KEY (id_client) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE réservation ADD CONSTRAINT fk_4 FOREIGN KEY (id_evenement) REFERENCES évenement (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_666d6ed18b13d439 ON réservation');
        $this->addSql('CREATE INDEX fk_4 ON réservation (id_evenement)');
        $this->addSql('DROP INDEX idx_666d6ed1e173b1b8 ON réservation');
        $this->addSql('CREATE INDEX fk_3 ON réservation (id_client)');
        $this->addSql('ALTER TABLE réservation ADD CONSTRAINT FK_666D6ED1E173B1B8 FOREIGN KEY (id_client) REFERENCES user (id)');
        $this->addSql('ALTER TABLE réservation ADD CONSTRAINT FK_666D6ED18B13D439 FOREIGN KEY (id_evenement) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE user CHANGE photo photo VARCHAR(100) NOT NULL');
        $this->addSql('CREATE INDEX adresse ON user (adresse)');
    }
}
