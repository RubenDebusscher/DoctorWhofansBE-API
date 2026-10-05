<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260910200940 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE V2__API__AttributeValues (AttributeValue_Id INT AUTO_INCREMENT NOT NULL, TextValue LONGTEXT DEFAULT NULL, LibraryValue INT NOT NULL, apiAV_Created_at DATETIME NOT NULL, apiAV_Last_modified_at DATETIME NOT NULL, DateValue DATETIME NOT NULL, LinkValue INT NOT NULL, apiAV_Last_modifier INT DEFAULT NULL, Attribute INT DEFAULT NULL, apiAV_Owner_Id INT DEFAULT NULL, Item INT DEFAULT NULL, INDEX apiAV_Last_modifier (apiAV_Last_modifier), INDEX API_AV_Item_idx (Item), INDEX API_AV_Attribute_idx (Attribute), INDEX apiVA_Creator (apiAV_Owner_Id), PRIMARY KEY (AttributeValue_Id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE V2__Api_Attribute_Templates (ApiAT_Id INT AUTO_INCREMENT NOT NULL, Api_T_Id INT NOT NULL, Api_A_Id INT NOT NULL, INDEX Api_L_Id (Api_A_Id), INDEX Api_T_Id (Api_T_Id), PRIMARY KEY (ApiAT_Id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE V2__Api_Attributes (ApiA_id INT AUTO_INCREMENT NOT NULL, ApiA_Name VARCHAR(45) DEFAULT NULL, ApiA_description VARCHAR(255) DEFAULT NULL, ApiA_module VARCHAR(45) DEFAULT NULL, ApiA_repeatable TINYINT NOT NULL, apiA_Owner_Id INT NOT NULL, apiA_Created_at DATETIME NOT NULL, apiA_Last_modifier INT NOT NULL, apiA_Last_modified_at DATETIME NOT NULL, INDEX apia_Last_modifier (apiA_Last_modifier), INDEX apiA_Creator (apiA_Owner_Id), PRIMARY KEY (ApiA_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE V2__Api_Template_Languages (ApiTL_Id INT AUTO_INCREMENT NOT NULL, Api_L_Id INT DEFAULT NULL, Api_T_Id INT DEFAULT NULL, INDEX Api_T_Id (Api_T_Id), INDEX Api_L_Id (Api_L_Id), PRIMARY KEY (ApiTL_Id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE V2__Api_Templates (Template_Id INT AUTO_INCREMENT NOT NULL, Template_Content LONGTEXT NOT NULL, apiT_Language_Id INT NOT NULL, apiT_Created_at DATETIME NOT NULL, apit_Last_modified_at DATETIME NOT NULL, apiT_Owner_Id INT DEFAULT NULL, apiT_Last_modifier INT DEFAULT NULL, INDEX apiA_Creator (apiT_Owner_Id), INDEX apia_Last_modifier (apiT_Last_modifier), PRIMARY KEY (Template_Id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE V2__Api_Type_Attributes (Type_Id INT NOT NULL, ApiTA_Order DOUBLE PRECISION DEFAULT NULL, apiTA_Owner_Id INT NOT NULL, apiTA_Created_at DATETIME NOT NULL, apiTA_Last_modifier INT NOT NULL, apiTA_Last_modified_at DATETIME NOT NULL, Attribute_Id INT NOT NULL, INDEX apiTA_Creator (apiTA_Owner_Id), INDEX apiTa_Last_modifier (apiTA_Last_modifier), INDEX Type_idx (Type_Id), INDEX IDX_F5EA81283B53D1A0 (Attribute_Id), PRIMARY KEY (Type_Id, Attribute_Id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE V2__Api__Items (apiI_id INT AUTO_INCREMENT NOT NULL, apiI_Name VARCHAR(45) DEFAULT NULL, apiI_Image VARCHAR(45) NOT NULL, apiI_Created_at DATETIME NOT NULL, apiI_Last_modified_at DATETIME NOT NULL, apiI_Owner_Id INT DEFAULT NULL, apiI_Last_modifier INT DEFAULT NULL, apiI_Page INT DEFAULT NULL, apiI_Type INT DEFAULT NULL, INDEX Last_modifier_idx (apiI_Last_modifier), INDEX type_idx (apiI_Type), INDEX page_idx (apiI_Page), INDEX Creator_idx (apiI_Owner_Id), PRIMARY KEY (apiI_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE V2__Api__Types (apiT_Id INT AUTO_INCREMENT NOT NULL, apiT_Name VARCHAR(45) DEFAULT NULL, apiT_Created_at DATETIME NOT NULL, apiT_Last_modified_at DATETIME NOT NULL, apiT_Owner_Id INT DEFAULT NULL, apiT_Last_modifier INT DEFAULT NULL, INDEX apiT_Creator (apiT_Owner_Id), INDEX apiT_Last_modifier (apiT_Last_modifier), PRIMARY KEY (apiT_Id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE V2__API__AttributeValues ADD CONSTRAINT FK_6519DA4E8F632D44 FOREIGN KEY (apiAV_Last_modifier) REFERENCES management__users (user_Id)');
        $this->addSql('ALTER TABLE V2__API__AttributeValues ADD CONSTRAINT FK_6519DA4E788B6D58 FOREIGN KEY (Attribute) REFERENCES V2__Api_Attributes (ApiA_id)');
        $this->addSql('ALTER TABLE V2__API__AttributeValues ADD CONSTRAINT FK_6519DA4EB6AD7B66 FOREIGN KEY (apiAV_Owner_Id) REFERENCES management__users (user_Id)');
        $this->addSql('ALTER TABLE V2__API__AttributeValues ADD CONSTRAINT FK_6519DA4EBF298A20 FOREIGN KEY (Item) REFERENCES V2__Api__Items (apiI_id)');
        $this->addSql('ALTER TABLE V2__Api_Template_Languages ADD CONSTRAINT FK_A0176B54248314BC FOREIGN KEY (Api_L_Id) REFERENCES management__languages (language_Id)');
        $this->addSql('ALTER TABLE V2__Api_Template_Languages ADD CONSTRAINT FK_A0176B54B12E6BCC FOREIGN KEY (Api_T_Id) REFERENCES V2__Api_Templates (Template_Id)');
        $this->addSql('ALTER TABLE V2__Api_Templates ADD CONSTRAINT FK_D5FDD559B93A3CE1 FOREIGN KEY (apiT_Owner_Id) REFERENCES management__users (user_Id)');
        $this->addSql('ALTER TABLE V2__Api_Templates ADD CONSTRAINT FK_D5FDD559F5A293F8 FOREIGN KEY (apiT_Last_modifier) REFERENCES management__users (user_Id)');
        $this->addSql('ALTER TABLE V2__Api_Type_Attributes ADD CONSTRAINT FK_F5EA81283B53D1A0 FOREIGN KEY (Attribute_Id) REFERENCES V2__Api_Attributes (ApiA_id)');
        $this->addSql('ALTER TABLE V2__Api__Items ADD CONSTRAINT FK_4C394BE46B6A36B9 FOREIGN KEY (apiI_Owner_Id) REFERENCES management__users (user_Id)');
        $this->addSql('ALTER TABLE V2__Api__Items ADD CONSTRAINT FK_4C394BE44DCE7EBB FOREIGN KEY (apiI_Last_modifier) REFERENCES management__users (user_Id)');
        $this->addSql('ALTER TABLE V2__Api__Items ADD CONSTRAINT FK_4C394BE41807DC53 FOREIGN KEY (apiI_Page) REFERENCES management__pages (page_Id)');
        $this->addSql('ALTER TABLE V2__Api__Items ADD CONSTRAINT FK_4C394BE480D33D5A FOREIGN KEY (apiI_Type) REFERENCES V2__Api__Types (apiT_Id)');
        $this->addSql('ALTER TABLE V2__Api__Types ADD CONSTRAINT FK_F4172B99B93A3CE1 FOREIGN KEY (apiT_Owner_Id) REFERENCES management__users (user_Id)');
        $this->addSql('ALTER TABLE V2__Api__Types ADD CONSTRAINT FK_F4172B99F5A293F8 FOREIGN KEY (apiT_Last_modifier) REFERENCES management__users (user_Id)');
        $this->addSql('ALTER TABLE api__nicknames DROP INDEX nickname, ADD INDEX nickname (nickname_nickname)');
        $this->addSql('ALTER TABLE management__pages DROP FOREIGN KEY `FK_9C550D23AB28E4CD`');
        $this->addSql('DROP INDEX IDX_9C550D23AB28E4CD ON management__pages');
        $this->addSql('ALTER TABLE management__pages ADD page_Blocks JSON DEFAULT NULL, ADD page_Settings JSON DEFAULT NULL, ADD page_Valid_From DATETIME DEFAULT NULL, ADD page_Valid_To DATETIME DEFAULT NULL, DROP page_Status, CHANGE page_Type page_Status_Code_Id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE management__pages ADD CONSTRAINT FK_9C550D239CDDF20 FOREIGN KEY (page_Status_Code_Id) REFERENCES codes (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_9C550D239CDDF20 ON management__pages (page_Status_Code_Id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE V2__API__AttributeValues DROP FOREIGN KEY FK_6519DA4E8F632D44');
        $this->addSql('ALTER TABLE V2__API__AttributeValues DROP FOREIGN KEY FK_6519DA4E788B6D58');
        $this->addSql('ALTER TABLE V2__API__AttributeValues DROP FOREIGN KEY FK_6519DA4EB6AD7B66');
        $this->addSql('ALTER TABLE V2__API__AttributeValues DROP FOREIGN KEY FK_6519DA4EBF298A20');
        $this->addSql('ALTER TABLE V2__Api_Template_Languages DROP FOREIGN KEY FK_A0176B54248314BC');
        $this->addSql('ALTER TABLE V2__Api_Template_Languages DROP FOREIGN KEY FK_A0176B54B12E6BCC');
        $this->addSql('ALTER TABLE V2__Api_Templates DROP FOREIGN KEY FK_D5FDD559B93A3CE1');
        $this->addSql('ALTER TABLE V2__Api_Templates DROP FOREIGN KEY FK_D5FDD559F5A293F8');
        $this->addSql('ALTER TABLE V2__Api_Type_Attributes DROP FOREIGN KEY FK_F5EA81283B53D1A0');
        $this->addSql('ALTER TABLE V2__Api__Items DROP FOREIGN KEY FK_4C394BE46B6A36B9');
        $this->addSql('ALTER TABLE V2__Api__Items DROP FOREIGN KEY FK_4C394BE44DCE7EBB');
        $this->addSql('ALTER TABLE V2__Api__Items DROP FOREIGN KEY FK_4C394BE41807DC53');
        $this->addSql('ALTER TABLE V2__Api__Items DROP FOREIGN KEY FK_4C394BE480D33D5A');
        $this->addSql('ALTER TABLE V2__Api__Types DROP FOREIGN KEY FK_F4172B99B93A3CE1');
        $this->addSql('ALTER TABLE V2__Api__Types DROP FOREIGN KEY FK_F4172B99F5A293F8');
        $this->addSql('DROP TABLE V2__API__AttributeValues');
        $this->addSql('DROP TABLE V2__Api_Attribute_Templates');
        $this->addSql('DROP TABLE V2__Api_Attributes');
        $this->addSql('DROP TABLE V2__Api_Template_Languages');
        $this->addSql('DROP TABLE V2__Api_Templates');
        $this->addSql('DROP TABLE V2__Api_Type_Attributes');
        $this->addSql('DROP TABLE V2__Api__Items');
        $this->addSql('DROP TABLE V2__Api__Types');
        $this->addSql('ALTER TABLE api__nicknames DROP INDEX nickname, ADD INDEX nickname (nickname_nickname(768))');
        $this->addSql('ALTER TABLE management__pages DROP FOREIGN KEY FK_9C550D239CDDF20');
        $this->addSql('DROP INDEX IDX_9C550D239CDDF20 ON management__pages');
        $this->addSql('ALTER TABLE management__pages ADD page_Status VARCHAR(20) DEFAULT \'published\' NOT NULL, DROP page_Blocks, DROP page_Settings, DROP page_Valid_From, DROP page_Valid_To, CHANGE page_Status_Code_Id page_Type INT DEFAULT NULL');
        $this->addSql('ALTER TABLE management__pages ADD CONSTRAINT `FK_9C550D23AB28E4CD` FOREIGN KEY (page_Type) REFERENCES management__pagetypes (pagetype_Id)');
        $this->addSql('CREATE INDEX IDX_9C550D23AB28E4CD ON management__pages (page_Type)');
    }
}
