<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250520134112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE groups (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_F06D39705E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE invoices (id INT AUTO_INCREMENT NOT NULL, number VARCHAR(255) NOT NULL, date DATETIME NOT NULL, amount NUMERIC(10, 2) NOT NULL, company_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE permissions (group_id INT NOT NULL, resource_id INT NOT NULL, permission VARCHAR(100) NOT NULL, INDEX IDX_2DEDCC6FFE54D947 (group_id), INDEX IDX_2DEDCC6F89329D25 (resource_id), PRIMARY KEY(group_id, resource_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE resources (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_EF66EBAE5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT '(DC2Type:json)', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE users_groups (user_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_FF8AB7E0A76ED395 (user_id), INDEX IDX_FF8AB7E0FE54D947 (group_id), PRIMARY KEY(user_id, group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE permissions ADD CONSTRAINT FK_2DEDCC6FFE54D947 FOREIGN KEY (group_id) REFERENCES groups (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE permissions ADD CONSTRAINT FK_2DEDCC6F89329D25 FOREIGN KEY (resource_id) REFERENCES resources (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users_groups ADD CONSTRAINT FK_FF8AB7E0A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users_groups ADD CONSTRAINT FK_FF8AB7E0FE54D947 FOREIGN KEY (group_id) REFERENCES groups (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE permissions DROP FOREIGN KEY FK_2DEDCC6FFE54D947
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE permissions DROP FOREIGN KEY FK_2DEDCC6F89329D25
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users_groups DROP FOREIGN KEY FK_FF8AB7E0A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users_groups DROP FOREIGN KEY FK_FF8AB7E0FE54D947
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE groups
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE invoices
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE permissions
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE resources
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE users
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE users_groups
        SQL);
    }
}
