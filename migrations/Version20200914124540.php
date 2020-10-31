<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200914124540 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE agendamento (id INT AUTO_INCREMENT NOT NULL, condominio_morador_id INT NOT NULL, area_comum_id INT NOT NULL, total_pessoas INT NOT NULL, data DATETIME NOT NULL, duracao INT NOT NULL, criado_em DATETIME NOT NULL, atualizado_em DATETIME NOT NULL, no_show TINYINT(1) DEFAULT NULL, ativo TINYINT(1) NOT NULL, INDEX IDX_1F6FB7AA63FCDC60 (condominio_morador_id), INDEX IDX_1F6FB7AA50A35A32 (area_comum_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE area_comum (id INT AUTO_INCREMENT NOT NULL, condominio_id INT NOT NULL, nome VARCHAR(255) NOT NULL, capacidade INT NOT NULL, limitacao INT NOT NULL, criado_em DATETIME NOT NULL, atualizado_em DATETIME NOT NULL, ativo TINYINT(1) NOT NULL, INDEX IDX_D77B9D2AB6A66817 (condominio_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE condominio (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, qtd_unidades_habitacionais INT NOT NULL, ativo TINYINT(1) NOT NULL, criado_em DATETIME NOT NULL, atualizado_em DATETIME NOT NULL, UNIQUE INDEX UNIQ_154DBFF854BD530C (nome), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE condominio_morador (id INT AUTO_INCREMENT NOT NULL, condominio_id INT NOT NULL, morador_id INT NOT NULL, unidade_habitacional INT NOT NULL, criado_em DATETIME NOT NULL, atualizado_em DATETIME NOT NULL, ativo TINYINT(1) NOT NULL, admin TINYINT(1) DEFAULT 0, INDEX IDX_8AAB812B6A66817 (condominio_id), INDEX IDX_8AAB81257CCA19A (morador_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE morador (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, telefone_primario VARCHAR(30) NOT NULL, telefone_secundario VARCHAR(30) DEFAULT NULL, email VARCHAR(255) NOT NULL, senha VARCHAR(255) NOT NULL, token VARCHAR(255) DEFAULT NULL, criado_em DATETIME NOT NULL, atualizado_em DATETIME NOT NULL, ativo TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_9D976899E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE agendamento ADD CONSTRAINT FK_1F6FB7AA63FCDC60 FOREIGN KEY (condominio_morador_id) REFERENCES condominio_morador (id)');
        $this->addSql('ALTER TABLE agendamento ADD CONSTRAINT FK_1F6FB7AA50A35A32 FOREIGN KEY (area_comum_id) REFERENCES area_comum (id)');
        $this->addSql('ALTER TABLE area_comum ADD CONSTRAINT FK_D77B9D2AB6A66817 FOREIGN KEY (condominio_id) REFERENCES condominio (id)');
        $this->addSql('ALTER TABLE condominio_morador ADD CONSTRAINT FK_8AAB812B6A66817 FOREIGN KEY (condominio_id) REFERENCES condominio (id)');
        $this->addSql('ALTER TABLE condominio_morador ADD CONSTRAINT FK_8AAB81257CCA19A FOREIGN KEY (morador_id) REFERENCES morador (id)');

        $condID = 1;
        $this->addSql("INSERT INTO condominio (id, nome, qtd_unidades_habitacionais, ativo, criado_em, atualizado_em) VALUES ({$condID}, 'Edf. Beira Canal', 60, 1, NOW(), NOW())");

        $this->addSql("INSERT INTO area_comum (condominio_id, nome, capacidade, limitacao, criado_em, atualizado_em, ativo) VALUES ({$condID}, 'Piscina', 30, 15, NOW(), NOW(), 1)");
        $this->addSql("INSERT INTO area_comum (condominio_id, nome, capacidade, limitacao, criado_em, atualizado_em, ativo) VALUES ({$condID}, 'Churrasqueira', 40, 20, NOW(), NOW(), 1)");
        $this->addSql("INSERT INTO area_comum (condominio_id, nome, capacidade, limitacao, criado_em, atualizado_em, ativo) VALUES ({$condID}, 'Academia', 10, 5, NOW(), NOW(), 1)");
        $this->addSql("INSERT INTO area_comum (condominio_id, nome, capacidade, limitacao, criado_em, atualizado_em, ativo) VALUES ({$condID}, 'Sauna', 5, 2, NOW(), NOW(), 1)");
        $this->addSql("INSERT INTO area_comum (condominio_id, nome, capacidade, limitacao, criado_em, atualizado_em, ativo) VALUES ({$condID}, 'Parquinho', 15, 8, NOW(), NOW(), 1)");
        $this->addSql("INSERT INTO area_comum (condominio_id, nome, capacidade, limitacao, criado_em, atualizado_em, ativo) VALUES ({$condID}, 'Salão de Festas', 50, 25, NOW(), NOW(), 1)");

        $amidnId = 1;
        $senha = password_hash('admin', PASSWORD_BCRYPT);
        $this->addSql("INSERT INTO morador (id, nome, telefone_primario, telefone_secundario, email, senha, token, criado_em, atualizado_em, ativo) VALUES ({$amidnId}, 'Admin', '8199998888', NULL, 'admin@admin.com', '{$senha}', NULL, NOW(), NOW(), 1)");

        $this->addSql("INSERT INTO condominio_morador (condominio_id, morador_id, unidade_habitacional, criado_em, atualizado_em, ativo, admin) VALUES ({$condID}, {$amidnId}, 101, NOW(), NOW(), 1, 1)");
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE agendamento DROP FOREIGN KEY FK_1F6FB7AA50A35A32');
        $this->addSql('ALTER TABLE area_comum DROP FOREIGN KEY FK_D77B9D2AB6A66817');
        $this->addSql('ALTER TABLE condominio_morador DROP FOREIGN KEY FK_8AAB812B6A66817');
        $this->addSql('ALTER TABLE agendamento DROP FOREIGN KEY FK_1F6FB7AA63FCDC60');
        $this->addSql('ALTER TABLE condominio_morador DROP FOREIGN KEY FK_8AAB81257CCA19A');
        $this->addSql('DROP TABLE agendamento');
        $this->addSql('DROP TABLE area_comum');
        $this->addSql('DROP TABLE condominio');
        $this->addSql('DROP TABLE condominio_morador');
        $this->addSql('DROP TABLE morador');
    }
}
