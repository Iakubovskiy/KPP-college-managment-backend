<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250202004842 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE grade (id INT AUTO_INCREMENT NOT NULL, student_id INT DEFAULT NULL, subject_id INT DEFAULT NULL, grade INT NOT NULL, INDEX IDX_595AAE34CB944F1A (student_id), INDEX IDX_595AAE3423EDC87 (subject_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `group` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scheduale (id INT AUTO_INCREMENT NOT NULL, subject_id INT DEFAULT NULL, group_id INT DEFAULT NULL, day VARCHAR(50) NOT NULL, time TIME NOT NULL, INDEX IDX_1CF541C523EDC87 (subject_id), INDEX IDX_1CF541C5FE54D947 (group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subject (id INT AUTO_INCREMENT NOT NULL, teacher_id INT DEFAULT NULL, name VARCHAR(70) NOT NULL, hours_per_week INT NOT NULL, INDEX IDX_FBCE3E7A41807E1D (teacher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, group_id INT DEFAULT NULL, login VARCHAR(50) NOT NULL, password VARCHAR(50) NOT NULL, name VARCHAR(50) NOT NULL, surname VARCHAR(50) NOT NULL, discriminator VARCHAR(255) NOT NULL, date_of_birth DATETIME DEFAULT NULL, INDEX IDX_8D93D649FE54D947 (group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE grade ADD CONSTRAINT FK_595AAE34CB944F1A FOREIGN KEY (student_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE grade ADD CONSTRAINT FK_595AAE3423EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id)');
        $this->addSql('ALTER TABLE scheduale ADD CONSTRAINT FK_1CF541C523EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id)');
        $this->addSql('ALTER TABLE scheduale ADD CONSTRAINT FK_1CF541C5FE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id)');
        $this->addSql('ALTER TABLE subject ADD CONSTRAINT FK_FBCE3E7A41807E1D FOREIGN KEY (teacher_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649FE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE grade DROP FOREIGN KEY FK_595AAE34CB944F1A');
        $this->addSql('ALTER TABLE grade DROP FOREIGN KEY FK_595AAE3423EDC87');
        $this->addSql('ALTER TABLE scheduale DROP FOREIGN KEY FK_1CF541C523EDC87');
        $this->addSql('ALTER TABLE scheduale DROP FOREIGN KEY FK_1CF541C5FE54D947');
        $this->addSql('ALTER TABLE subject DROP FOREIGN KEY FK_FBCE3E7A41807E1D');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649FE54D947');
        $this->addSql('DROP TABLE grade');
        $this->addSql('DROP TABLE `group`');
        $this->addSql('DROP TABLE scheduale');
        $this->addSql('DROP TABLE subject');
        $this->addSql('DROP TABLE user');
    }
}
