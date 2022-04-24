<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220424065618 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE notification_notification_template (notification_id INT NOT NULL, notification_template_id INT NOT NULL, INDEX IDX_2FA533EAEF1A9D84 (notification_id), INDEX IDX_2FA533EAD0413CF9 (notification_template_id), PRIMARY KEY(notification_id, notification_template_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE notification_notification_template ADD CONSTRAINT FK_2FA533EAEF1A9D84 FOREIGN KEY (notification_id) REFERENCES notification (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notification_notification_template ADD CONSTRAINT FK_2FA533EAD0413CF9 FOREIGN KEY (notification_template_id) REFERENCES notification_template (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notification DROP source_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE notification_notification_template');
        $this->addSql('ALTER TABLE notification ADD source_id BIGINT NOT NULL');
    }
}
