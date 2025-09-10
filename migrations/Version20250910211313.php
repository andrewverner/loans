<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250910211313 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE loan (
              id INT AUTO_INCREMENT NOT NULL,
              client_id INT NOT NULL,
              name VARCHAR(255) NOT NULL,
              amount INT NOT NULL,
              rate INT NOT NULL,
              start_date DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
              end_date DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
              INDEX IDX_C5D30D0319EB6921 (client_id),
              PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              loan
            ADD
              CONSTRAINT FK_C5D30D0319EB6921 FOREIGN KEY (client_id) REFERENCES client (id)
        SQL);
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455B5852DF3 ON client (pin)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE loan DROP FOREIGN KEY FK_C5D30D0319EB6921');
        $this->addSql('DROP TABLE loan');
        $this->addSql('DROP INDEX UNIQ_C7440455B5852DF3 ON client');
    }
}
