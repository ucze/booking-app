<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200202211507 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE time_slot (id INT AUTO_INCREMENT NOT NULL, starts_at TIME NOT NULL, ends_at TIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chair (id INT AUTO_INCREMENT NOT NULL, owner VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE appointment (id INT AUTO_INCREMENT NOT NULL, chair_id INT NOT NULL, client VARCHAR(255) NOT NULL, date DATE NOT NULL, INDEX IDX_FE38F8448CA3C745 (chair_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE appointment_time_slot (appointment_id INT NOT NULL, time_slot_id INT NOT NULL, INDEX IDX_94394743E5B533F9 (appointment_id), INDEX IDX_94394743D62B0FA (time_slot_id), PRIMARY KEY(appointment_id, time_slot_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F8448CA3C745 FOREIGN KEY (chair_id) REFERENCES chair (id)');
        $this->addSql('ALTER TABLE appointment_time_slot ADD CONSTRAINT FK_94394743E5B533F9 FOREIGN KEY (appointment_id) REFERENCES appointment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE appointment_time_slot ADD CONSTRAINT FK_94394743D62B0FA FOREIGN KEY (time_slot_id) REFERENCES time_slot (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE appointment_time_slot DROP FOREIGN KEY FK_94394743D62B0FA');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F8448CA3C745');
        $this->addSql('ALTER TABLE appointment_time_slot DROP FOREIGN KEY FK_94394743E5B533F9');
        $this->addSql('DROP TABLE time_slot');
        $this->addSql('DROP TABLE chair');
        $this->addSql('DROP TABLE appointment');
        $this->addSql('DROP TABLE appointment_time_slot');
    }
}
