<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231214013422 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE absences (id INT AUTO_INCREMENT NOT NULL, employee_id INT NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME DEFAULT NULL, reason VARCHAR(255) DEFAULT NULL, approved TINYINT(1) NOT NULL, type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_F9C0EFFF8C03F15C (employee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ad (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, budget NUMERIC(15, 2) NOT NULL, target_audience VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, img_url LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', modified_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ad_campaign (id INT AUTO_INCREMENT NOT NULL, ad_id INT NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', modified_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_F50D1F0D4F34D596 (ad_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, customer_id INT DEFAULT NULL, date DATETIME NOT NULL, content LONGTEXT NOT NULL, rating NUMERIC(4, 2) DEFAULT NULL, enabled TINYINT(1) DEFAULT NULL, INDEX IDX_9474526C9395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, country VARCHAR(255) DEFAULT NULL, adress LONGTEXT DEFAULT NULL, city LONGTEXT DEFAULT NULL, postal_code VARCHAR(10) DEFAULT NULL, last_command DATETIME DEFAULT NULL, fidelity_points INT DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE email_history (id INT AUTO_INCREMENT NOT NULL, ad_id INT DEFAULT NULL, recipient_email VARCHAR(255) NOT NULL, subject VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, sent_date DATETIME NOT NULL, INDEX IDX_9A7A18844F34D596 (ad_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employee (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, restaurant_id INT NOT NULL, role VARCHAR(255) NOT NULL, sexe VARCHAR(20) NOT NULL, name VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, birthdate DATETIME NOT NULL, hire_date DATETIME NOT NULL, phone VARCHAR(255) NOT NULL, address LONGTEXT DEFAULT NULL, postal_code VARCHAR(10) DEFAULT NULL, social_security_number VARCHAR(45) DEFAULT NULL, contract_type VARCHAR(45) NOT NULL, contract_end_date DATETIME DEFAULT NULL, disability TINYINT(1) NOT NULL, disability_desc LONGTEXT DEFAULT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', modified_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', email VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_5D9F75A1A76ED395 (user_id), INDEX IDX_5D9F75A1B1E7706E (restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE feedback (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, employee_id INT DEFAULT NULL, content LONGTEXT NOT NULL, warning TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_D2294458F675F31B (author_id), INDEX IDX_D22944588C03F15C (employee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE loss_detail (id INT AUTO_INCREMENT NOT NULL, shift_losses_id INT NOT NULL, product_id INT DEFAULT NULL, stock_id INT DEFAULT NULL, quantity NUMERIC(7, 2) NOT NULL, unit VARCHAR(45) NOT NULL, INDEX IDX_B00DC4E3656959D4 (shift_losses_id), INDEX IDX_B00DC4E34584665A (product_id), INDEX IDX_B00DC4E3DCD6110 (stock_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, customer_id INT DEFAULT NULL, date DATETIME NOT NULL, price NUMERIC(10, 2) NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_F52993989395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_product (id INT AUTO_INCREMENT NOT NULL, associated_order_id INT NOT NULL, product_id INT NOT NULL, promotion_campaign_id INT DEFAULT NULL, quantity INT NOT NULL, date DATETIME NOT NULL, INDEX IDX_2530ADE6FC35A14E (associated_order_id), INDEX IDX_2530ADE64584665A (product_id), INDEX IDX_2530ADE627C73EAE (promotion_campaign_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, price INT DEFAULT NULL, img_url LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', modified_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_category (product_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_CDFC73564584665A (product_id), INDEX IDX_CDFC735612469DE2 (category_id), PRIMARY KEY(product_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_stock (id INT AUTO_INCREMENT NOT NULL, stock_id INT NOT NULL, product_id INT NOT NULL, stock_quantity NUMERIC(7, 2) NOT NULL, unit VARCHAR(45) DEFAULT NULL, INDEX IDX_EA6A2D3CDCD6110 (stock_id), INDEX IDX_EA6A2D3C4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion (id INT AUTO_INCREMENT NOT NULL, ad_id INT DEFAULT NULL, reduction INT NOT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', modified_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', title VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_C11D7DD14F34D596 (ad_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion_campaign (id INT AUTO_INCREMENT NOT NULL, promotion_id INT NOT NULL, product_id INT DEFAULT NULL, category_id INT DEFAULT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, enabled TINYINT(1) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_D941B699139DF194 (promotion_id), INDEX IDX_D941B6994584665A (product_id), INDEX IDX_D941B69912469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, adress LONGTEXT NOT NULL, postal_code VARCHAR(10) NOT NULL, city VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(20) NOT NULL, operating_hours VARCHAR(255) DEFAULT NULL, rating NUMERIC(4, 2) DEFAULT NULL, open_date DATETIME DEFAULT NULL, close_date DATETIME DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', enabled TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shift_losses (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL, shift VARCHAR(45) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stock (id INT AUTO_INCREMENT NOT NULL, restaurant_id INT NOT NULL, name VARCHAR(255) NOT NULL, quantity NUMERIC(7, 2) NOT NULL, unit VARCHAR(45) NOT NULL, last_restock_date DATETIME DEFAULT NULL, stock_level_alert NUMERIC(7, 2) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', modified_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_4B365660B1E7706E (restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stock_order (id INT AUTO_INCREMENT NOT NULL, order_date DATETIME NOT NULL, status VARCHAR(45) NOT NULL, expected_delivery_date DATETIME DEFAULT NULL, delivery_date DATETIME DEFAULT NULL, total_cost NUMERIC(10, 2) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stock_order_detail (id INT AUTO_INCREMENT NOT NULL, stock_id INT NOT NULL, stock_order_id INT NOT NULL, quantity NUMERIC(7, 2) NOT NULL, unit VARCHAR(45) NOT NULL, price NUMERIC(10, 2) NOT NULL, INDEX IDX_CCE25555DCD6110 (stock_id), INDEX IDX_CCE25555C259397A (stock_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE time_slot (id INT AUTO_INCREMENT NOT NULL, employee_id INT NOT NULL, start_time DATETIME NOT NULL, end_time DATETIME NOT NULL, INDEX IDX_1B3294A8C03F15C (employee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) DEFAULT NULL, username VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, invitation_token LONGTEXT DEFAULT NULL, invitation_token_expiry DATETIME DEFAULT NULL, reset_token LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_restaurant (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, restaurant_id INT NOT NULL, INDEX IDX_4CF2D4D3A76ED395 (user_id), INDEX IDX_4CF2D4D3B1E7706E (restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE warning (id INT AUTO_INCREMENT NOT NULL, employee_id INT NOT NULL, feedback_id INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_404E9CC68C03F15C (employee_id), UNIQUE INDEX UNIQ_404E9CC6D249A887 (feedback_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE absences ADD CONSTRAINT FK_F9C0EFFF8C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
        $this->addSql('ALTER TABLE ad_campaign ADD CONSTRAINT FK_F50D1F0D4F34D596 FOREIGN KEY (ad_id) REFERENCES ad (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE email_history ADD CONSTRAINT FK_9A7A18844F34D596 FOREIGN KEY (ad_id) REFERENCES ad (id)');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A1B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D2294458F675F31B FOREIGN KEY (author_id) REFERENCES employee (id)');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D22944588C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
        $this->addSql('ALTER TABLE loss_detail ADD CONSTRAINT FK_B00DC4E3656959D4 FOREIGN KEY (shift_losses_id) REFERENCES shift_losses (id)');
        $this->addSql('ALTER TABLE loss_detail ADD CONSTRAINT FK_B00DC4E34584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE loss_detail ADD CONSTRAINT FK_B00DC4E3DCD6110 FOREIGN KEY (stock_id) REFERENCES stock (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993989395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE order_product ADD CONSTRAINT FK_2530ADE6FC35A14E FOREIGN KEY (associated_order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE order_product ADD CONSTRAINT FK_2530ADE64584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE order_product ADD CONSTRAINT FK_2530ADE627C73EAE FOREIGN KEY (promotion_campaign_id) REFERENCES promotion_campaign (id)');
        $this->addSql('ALTER TABLE product_category ADD CONSTRAINT FK_CDFC73564584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_category ADD CONSTRAINT FK_CDFC735612469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_stock ADD CONSTRAINT FK_EA6A2D3CDCD6110 FOREIGN KEY (stock_id) REFERENCES stock (id)');
        $this->addSql('ALTER TABLE product_stock ADD CONSTRAINT FK_EA6A2D3C4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE promotion ADD CONSTRAINT FK_C11D7DD14F34D596 FOREIGN KEY (ad_id) REFERENCES ad (id)');
        $this->addSql('ALTER TABLE promotion_campaign ADD CONSTRAINT FK_D941B699139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id)');
        $this->addSql('ALTER TABLE promotion_campaign ADD CONSTRAINT FK_D941B6994584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE promotion_campaign ADD CONSTRAINT FK_D941B69912469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B365660B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE stock_order_detail ADD CONSTRAINT FK_CCE25555DCD6110 FOREIGN KEY (stock_id) REFERENCES stock (id)');
        $this->addSql('ALTER TABLE stock_order_detail ADD CONSTRAINT FK_CCE25555C259397A FOREIGN KEY (stock_order_id) REFERENCES stock_order (id)');
        $this->addSql('ALTER TABLE time_slot ADD CONSTRAINT FK_1B3294A8C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
        $this->addSql('ALTER TABLE user_restaurant ADD CONSTRAINT FK_4CF2D4D3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_restaurant ADD CONSTRAINT FK_4CF2D4D3B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE warning ADD CONSTRAINT FK_404E9CC68C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
        $this->addSql('ALTER TABLE warning ADD CONSTRAINT FK_404E9CC6D249A887 FOREIGN KEY (feedback_id) REFERENCES feedback (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE absences DROP FOREIGN KEY FK_F9C0EFFF8C03F15C');
        $this->addSql('ALTER TABLE ad_campaign DROP FOREIGN KEY FK_F50D1F0D4F34D596');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C9395C3F3');
        $this->addSql('ALTER TABLE email_history DROP FOREIGN KEY FK_9A7A18844F34D596');
        $this->addSql('ALTER TABLE employee DROP FOREIGN KEY FK_5D9F75A1A76ED395');
        $this->addSql('ALTER TABLE employee DROP FOREIGN KEY FK_5D9F75A1B1E7706E');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D2294458F675F31B');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D22944588C03F15C');
        $this->addSql('ALTER TABLE loss_detail DROP FOREIGN KEY FK_B00DC4E3656959D4');
        $this->addSql('ALTER TABLE loss_detail DROP FOREIGN KEY FK_B00DC4E34584665A');
        $this->addSql('ALTER TABLE loss_detail DROP FOREIGN KEY FK_B00DC4E3DCD6110');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993989395C3F3');
        $this->addSql('ALTER TABLE order_product DROP FOREIGN KEY FK_2530ADE6FC35A14E');
        $this->addSql('ALTER TABLE order_product DROP FOREIGN KEY FK_2530ADE64584665A');
        $this->addSql('ALTER TABLE order_product DROP FOREIGN KEY FK_2530ADE627C73EAE');
        $this->addSql('ALTER TABLE product_category DROP FOREIGN KEY FK_CDFC73564584665A');
        $this->addSql('ALTER TABLE product_category DROP FOREIGN KEY FK_CDFC735612469DE2');
        $this->addSql('ALTER TABLE product_stock DROP FOREIGN KEY FK_EA6A2D3CDCD6110');
        $this->addSql('ALTER TABLE product_stock DROP FOREIGN KEY FK_EA6A2D3C4584665A');
        $this->addSql('ALTER TABLE promotion DROP FOREIGN KEY FK_C11D7DD14F34D596');
        $this->addSql('ALTER TABLE promotion_campaign DROP FOREIGN KEY FK_D941B699139DF194');
        $this->addSql('ALTER TABLE promotion_campaign DROP FOREIGN KEY FK_D941B6994584665A');
        $this->addSql('ALTER TABLE promotion_campaign DROP FOREIGN KEY FK_D941B69912469DE2');
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B365660B1E7706E');
        $this->addSql('ALTER TABLE stock_order_detail DROP FOREIGN KEY FK_CCE25555DCD6110');
        $this->addSql('ALTER TABLE stock_order_detail DROP FOREIGN KEY FK_CCE25555C259397A');
        $this->addSql('ALTER TABLE time_slot DROP FOREIGN KEY FK_1B3294A8C03F15C');
        $this->addSql('ALTER TABLE user_restaurant DROP FOREIGN KEY FK_4CF2D4D3A76ED395');
        $this->addSql('ALTER TABLE user_restaurant DROP FOREIGN KEY FK_4CF2D4D3B1E7706E');
        $this->addSql('ALTER TABLE warning DROP FOREIGN KEY FK_404E9CC68C03F15C');
        $this->addSql('ALTER TABLE warning DROP FOREIGN KEY FK_404E9CC6D249A887');
        $this->addSql('DROP TABLE absences');
        $this->addSql('DROP TABLE ad');
        $this->addSql('DROP TABLE ad_campaign');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE email_history');
        $this->addSql('DROP TABLE employee');
        $this->addSql('DROP TABLE feedback');
        $this->addSql('DROP TABLE loss_detail');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_product');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_category');
        $this->addSql('DROP TABLE product_stock');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('DROP TABLE promotion_campaign');
        $this->addSql('DROP TABLE restaurant');
        $this->addSql('DROP TABLE shift_losses');
        $this->addSql('DROP TABLE stock');
        $this->addSql('DROP TABLE stock_order');
        $this->addSql('DROP TABLE stock_order_detail');
        $this->addSql('DROP TABLE time_slot');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_restaurant');
        $this->addSql('DROP TABLE warning');
    }
}
