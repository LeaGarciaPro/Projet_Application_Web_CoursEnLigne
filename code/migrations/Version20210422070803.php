<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210422070803 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE etudiant (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, niveau_id INT DEFAULT NULL, niveau_filiere_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_717E22E3FB88E14F (utilisateur_id), INDEX IDX_717E22E3B3E9C81 (niveau_id), INDEX IDX_717E22E34C8627E4 (niveau_filiere_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etudiant_matiere (etudiant_id INT NOT NULL, matiere_id INT NOT NULL, INDEX IDX_44BB19CEDDEAB1A3 (etudiant_id), INDEX IDX_44BB19CEF46CD258 (matiere_id), PRIMARY KEY(etudiant_id, matiere_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE niveau (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE niveau_niveau_filiere (niveau_id INT NOT NULL, niveau_filiere_id INT NOT NULL, INDEX IDX_D46BF4ABB3E9C81 (niveau_id), INDEX IDX_D46BF4AB4C8627E4 (niveau_filiere_id), PRIMARY KEY(niveau_id, niveau_filiere_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE niveau_filiere (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pdf (id INT AUTO_INCREMENT NOT NULL, pdf_name VARCHAR(255) DEFAULT NULL, pdf_size VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE video (id INT AUTO_INCREMENT NOT NULL, video_name VARCHAR(255) DEFAULT NULL, video_size VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE etudiant ADD CONSTRAINT FK_717E22E3FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE etudiant ADD CONSTRAINT FK_717E22E3B3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id)');
        $this->addSql('ALTER TABLE etudiant ADD CONSTRAINT FK_717E22E34C8627E4 FOREIGN KEY (niveau_filiere_id) REFERENCES niveau_filiere (id)');
        $this->addSql('ALTER TABLE etudiant_matiere ADD CONSTRAINT FK_44BB19CEDDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE etudiant_matiere ADD CONSTRAINT FK_44BB19CEF46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE niveau_niveau_filiere ADD CONSTRAINT FK_D46BF4ABB3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE niveau_niveau_filiere ADD CONSTRAINT FK_D46BF4AB4C8627E4 FOREIGN KEY (niveau_filiere_id) REFERENCES niveau_filiere (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE matiere ADD niveau_id INT DEFAULT NULL, ADD niveau_filiere_id INT DEFAULT NULL, DROP id_matiere');
        $this->addSql('ALTER TABLE matiere ADD CONSTRAINT FK_9014574AB3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id)');
        $this->addSql('ALTER TABLE matiere ADD CONSTRAINT FK_9014574A4C8627E4 FOREIGN KEY (niveau_filiere_id) REFERENCES niveau_filiere (id)');
        $this->addSql('CREATE INDEX IDX_9014574AB3E9C81 ON matiere (niveau_id)');
        $this->addSql('CREATE INDEX IDX_9014574A4C8627E4 ON matiere (niveau_filiere_id)');
        $this->addSql('ALTER TABLE question ADD correct_reponse VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE questionnaire DROP INDEX IDX_7A64DAFE455FCC0, ADD UNIQUE INDEX UNIQ_7A64DAFE455FCC0 (enseignant_id)');
        $this->addSql('ALTER TABLE questionnaire CHANGE enseignant_id enseignant_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etudiant_matiere DROP FOREIGN KEY FK_44BB19CEDDEAB1A3');
        $this->addSql('ALTER TABLE etudiant DROP FOREIGN KEY FK_717E22E3B3E9C81');
        $this->addSql('ALTER TABLE matiere DROP FOREIGN KEY FK_9014574AB3E9C81');
        $this->addSql('ALTER TABLE niveau_niveau_filiere DROP FOREIGN KEY FK_D46BF4ABB3E9C81');
        $this->addSql('ALTER TABLE etudiant DROP FOREIGN KEY FK_717E22E34C8627E4');
        $this->addSql('ALTER TABLE matiere DROP FOREIGN KEY FK_9014574A4C8627E4');
        $this->addSql('ALTER TABLE niveau_niveau_filiere DROP FOREIGN KEY FK_D46BF4AB4C8627E4');
        $this->addSql('DROP TABLE etudiant');
        $this->addSql('DROP TABLE etudiant_matiere');
        $this->addSql('DROP TABLE niveau');
        $this->addSql('DROP TABLE niveau_niveau_filiere');
        $this->addSql('DROP TABLE niveau_filiere');
        $this->addSql('DROP TABLE pdf');
        $this->addSql('DROP TABLE video');
        $this->addSql('DROP INDEX IDX_9014574AB3E9C81 ON matiere');
        $this->addSql('DROP INDEX IDX_9014574A4C8627E4 ON matiere');
        $this->addSql('ALTER TABLE matiere ADD id_matiere INT NOT NULL, DROP niveau_id, DROP niveau_filiere_id');
        $this->addSql('ALTER TABLE question DROP correct_reponse');
        $this->addSql('ALTER TABLE questionnaire DROP INDEX UNIQ_7A64DAFE455FCC0, ADD INDEX IDX_7A64DAFE455FCC0 (enseignant_id)');
        $this->addSql('ALTER TABLE questionnaire CHANGE enseignant_id enseignant_id INT NOT NULL');
    }
}
