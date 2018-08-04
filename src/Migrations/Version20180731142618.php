<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180731142618 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, full_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, created_date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, role SMALLINT NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, uploaded_by_id INT NOT NULL, filename VARCHAR(255) NOT NULL, size INT NOT NULL, mime_type VARCHAR(255) NOT NULL, created_date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, hashed_name VARCHAR(255) NOT NULL, INDEX IDX_C53D045FA2B28FE8 (uploaded_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `like` (image_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_AC6340B33DA5256D (image_id), INDEX IDX_AC6340B3A76ED395 (user_id), PRIMARY KEY(image_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FA2B28FE8 FOREIGN KEY (uploaded_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B33DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FA2B28FE8');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B3A76ED395');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B33DA5256D');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE `like`');
    }
}
