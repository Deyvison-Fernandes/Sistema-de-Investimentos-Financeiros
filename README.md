Sistem de Investimento Financeiro
=======================

Introdução
------------
Sistema onde pode ser feito simulações financeiras

Installation
------------
Executar o script para criar as tabelas no banco de dados:

    CREATE TABLE `tipo_investimento` (
	    `id` INT(11) NOT NULL AUTO_INCREMENT,
	    `descricao` VARCHAR(100) NOT NULL,
	    `rentabilidade` DECIMAL(5,2) NOT NULL,
	    `taxa` DECIMAL(5,2) NOT NULL,
	    `periodo_aplicacao` INT(11) NOT NULL,
	    `data_cadastro` DATETIME NOT NULL,
	    PRIMARY KEY (`id`)
    )
    COLLATE='utf8_general_ci'
    ENGINE=InnoDB;

    CREATE TABLE `investimento` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `aplicacao` DECIMAL(10,2) NOT NULL,
        `data` DATE NOT NULL,
        `tipo_investimento_id` INT(11) NOT NULL,
        PRIMARY KEY (`id`),
        INDEX `FK_investimento_tipo_investimento` (`tipo_investimento_id`),
        CONSTRAINT `FK_investimento_tipo_investimento` FOREIGN KEY (`tipo_investimento_id`) REFERENCES `tipo_investimento` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION
    )
    COLLATE='utf8_general_ci'
    ENGINE=InnoDB;

Configurar o acesso ao banco de dados no arquivo `config/autoload/local.php`.
