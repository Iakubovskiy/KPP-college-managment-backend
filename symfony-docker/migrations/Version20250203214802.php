<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250203214802 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE schedule (id INT AUTO_INCREMENT NOT NULL, subject_id INT DEFAULT NULL, group_id INT DEFAULT NULL, day VARCHAR(50) NOT NULL, time TIME NOT NULL, INDEX IDX_5A3811FB23EDC87 (subject_id), INDEX IDX_5A3811FBFE54D947 (group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FB23EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id)');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FBFE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id)');
        $this->addSql('ALTER TABLE scheduale DROP FOREIGN KEY FK_1CF541C523EDC87');
        $this->addSql('ALTER TABLE scheduale DROP FOREIGN KEY FK_1CF541C5FE54D947');
        $this->addSql('DROP TABLE scheduale');
        $this->addSql('ALTER TABLE grade ADD teacher_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE grade ADD CONSTRAINT FK_595AAE3441807E1D FOREIGN KEY (teacher_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_595AAE3441807E1D ON grade (teacher_id)');
        $this->addSql('ALTER TABLE user ADD first_name VARCHAR(50) NOT NULL, ADD surname VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE scheduale (id INT AUTO_INCREMENT NOT NULL, subject_id INT DEFAULT NULL, group_id INT DEFAULT NULL, day VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, time TIME NOT NULL, INDEX IDX_1CF541C523EDC87 (subject_id), INDEX IDX_1CF541C5FE54D947 (group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE scheduale ADD CONSTRAINT FK_1CF541C523EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE scheduale ADD CONSTRAINT FK_1CF541C5FE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE schedule DROP FOREIGN KEY FK_5A3811FB23EDC87');
        $this->addSql('ALTER TABLE schedule DROP FOREIGN KEY FK_5A3811FBFE54D947');
        $this->addSql('DROP TABLE schedule');
        $this->addSql('ALTER TABLE user DROP first_name, DROP surname');
        $this->addSql('ALTER TABLE grade DROP FOREIGN KEY FK_595AAE3441807E1D');
        $this->addSql('DROP INDEX IDX_595AAE3441807E1D ON grade');
        $this->addSql('ALTER TABLE grade DROP teacher_id');
    }
}
