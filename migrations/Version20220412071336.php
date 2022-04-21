<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220412071336 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lesson_doc ADD course_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE lesson_doc ADD CONSTRAINT FK_F4345B87591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('CREATE INDEX IDX_F4345B87591CC992 ON lesson_doc (course_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lesson_doc DROP FOREIGN KEY FK_F4345B87591CC992');
        $this->addSql('DROP INDEX IDX_F4345B87591CC992 ON lesson_doc');
        $this->addSql('ALTER TABLE lesson_doc DROP course_id');
    }
}
