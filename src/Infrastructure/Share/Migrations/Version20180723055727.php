<?php declare(strict_types=1);

namespace App\Infrastructure\Share\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180723055727 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category ADD deleted TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE events (id INT AUTO_INCREMENT NOT NULL, uuid BINARY(16) NOT NULL, playhead INT UNSIGNED NOT NULL, payload LONGTEXT NOT NULL COLLATE utf8_unicode_ci, metadata LONGTEXT NOT NULL COLLATE utf8_unicode_ci, recorded_on VARCHAR(32) NOT NULL COLLATE utf8_unicode_ci, type VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, UNIQUE INDEX UNIQ_5387574AD17F50A634B91FA9 (uuid, playhead), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category DROP deleted');
    }
}
