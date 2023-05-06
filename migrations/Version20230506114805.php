<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
final class Version20230506114805 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add break point table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE break_point (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, fee NUMERIC(10, 2) NOT NULL, amount NUMERIC(10, 2) NOT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE break_point');
    }
}
