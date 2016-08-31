-- ========================================================
-- |                    BANCO DE DADOS                    |
-- |                       IC Rafael                      |
-- ========================================================


-- ========================================================
--                       DATABASE
-- ========================================================
DROP DATABASE IF EXISTS `icrafael`;

CREATE DATABASE IF NOT EXISTS `icrafael`
  DEFAULT CHARACTER SET utf8;

USE `icrafael`;

-- ========================================================
--                        TABELAS
-- ========================================================


-- Estrutura para tabela `usuarios`
CREATE TABLE `usuarios` (
  `Uid`     INT(11)      NOT NULL AUTO_INCREMENT,
  `RA`      INT(8)       NOT NULL DEFAULT '0',
  `nome`    VARCHAR(255) NOT NULL,
  `email`   VARCHAR(80)  NOT NULL,
  `senha`   VARCHAR(60)  NOT NULL,
  `admin`   TINYINT(1)   NOT NULL DEFAULT '0',
  `token`   VARCHAR(60)  NOT NULL DEFAULT '-',
  PRIMARY KEY (`Uid`),
  UNIQUE KEY `email` (`email`)
) ENGINE = INNODB;

-- --------------------------------------------------------
-- Estrutura para tabela `turmas`
CREATE TABLE `turmas` (
  `Tid`        INT(11)     NOT NULL AUTO_INCREMENT,
  `Tnome`      VARCHAR(30) NOT NULL,
  `quad`       VARCHAR(15) NOT NULL,
  `UidCriador` INT(11)     NOT NULL,
  PRIMARY KEY (`Tid`),
  FOREIGN KEY (`UidCriador`) REFERENCES `usuarios` (`Uid`) ON DELETE CASCADE
) ENGINE = INNODB;

-- --------------------------------------------------------
-- Estrutura para tabela `userturma`
CREATE TABLE `userturma` (
  `UTid` INT(11) NOT NULL AUTO_INCREMENT,
  `Uid`  INT(11) NOT NULL,
  `Tid`  INT(11) NOT NULL,
  PRIMARY KEY (`UTid`),
  FOREIGN KEY (`Uid`) REFERENCES `usuarios` (`Uid`) ON DELETE CASCADE,
  FOREIGN KEY (`Tid`) REFERENCES `turmas` (`Tid`) ON DELETE CASCADE
) ENGINE = INNODB;

-- --------------------------------------------------------
-- Estrutura para tabela `lista`
CREATE TABLE `listas` (
  `Lid`        INT(11)     NOT NULL AUTO_INCREMENT,
  `Ltitulo`    VARCHAR(100) NOT NULL,
  `UidCriador` INT(11)     NOT NULL,
  PRIMARY KEY (`Lid`),
  FOREIGN KEY (`UidCriador`) REFERENCES `usuarios` (`Uid`) ON DELETE CASCADE
) ENGINE = INNODB;

-- --------------------------------------------------------
-- Estrutura para tabela `turmalista`
CREATE TABLE `turmalista` (
  `TLid`     INT(11) NOT NULL AUTO_INCREMENT,
  `Tid`      INT(11) NOT NULL,
  `Lid`      INT(11) NOT NULL,
  `dia`      DATE    NULL,
  `hora`     TIME    NULL,
  PRIMARY KEY (`TLid`),
  FOREIGN KEY (`Lid`) REFERENCES `listas` (`Lid`) ON DELETE CASCADE,
  FOREIGN KEY (`Tid`) REFERENCES `turmas` (`Tid`) ON DELETE CASCADE
) ENGINE = INNODB;

-- --------------------------------------------------------
-- Estrutura para tabela `questoes`
CREATE TABLE `questoes` (
  `Qid`              INT(11)     NOT NULL AUTO_INCREMENT,
  `Qtitulo`          VARCHAR(200) NOT NULL,
  `corpo`            TEXT        NOT NULL,
  `alfabeto`         VARCHAR(200) NOT NULL,
  `gabaritoDados`    TEXT        NOT NULL,
  `gabaritoDesigner` TEXT        NOT NULL,
  `UidCriador`       INT(11)     NOT NULL,
  PRIMARY KEY (`Qid`),
  FOREIGN KEY (`UidCriador`) REFERENCES `usuarios` (`Uid`) ON DELETE CASCADE
) ENGINE = INNODB;

-- --------------------------------------------------------
-- Estrutura para tabela `listaquestao`
CREATE TABLE `listaquestao` (
  `LQid`INT(11) NOT NULL AUTO_INCREMENT,
  `Lid` INT(11) NOT NULL,
  `Qid` INT(11) NOT NULL,
  `pos` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`LQid`),
  FOREIGN KEY (`Lid`) REFERENCES `listas` (`Lid`) ON DELETE CASCADE,
  FOREIGN KEY (`Qid`) REFERENCES `questoes` (`Qid`) ON DELETE CASCADE
) ENGINE = INNODB;

-- --------------------------------------------------------
-- Estrutura para tabela `submissoes`
CREATE TABLE `submissoes` (
  `Sid`              INT(11)     NOT NULL      AUTO_INCREMENT,
  `Uid`              INT(11)     NOT NULL,
  `TLid`             INT(11)     NOT NULL,
  `Qid`              INT(11)     NOT NULL,
  `respostaDados`    TEXT        NOT NULL,
  `respostaDesigner` TEXT        NOT NULL,
  `status`           VARCHAR(1)  NOT NULL,
  `palavra`          VARCHAR(50) NULL,
  `horaJuiz`         DATETIME    NULL,
  PRIMARY KEY (`Sid`),
  UNIQUE KEY `unicaSubmissao` (`Uid`, `TLid`, `Qid`),
  FOREIGN KEY (`Uid`) REFERENCES `usuarios` (`Uid`) ON DELETE CASCADE,
  FOREIGN KEY (`TLid`) REFERENCES `turmalista` (`TLid`) ON DELETE CASCADE,
  FOREIGN KEY (`Qid`) REFERENCES `questoes` (`Qid`) ON DELETE CASCADE
) ENGINE = INNODB;

-- ========================================================
--                        DADOS
-- ========================================================

-- Usuario admin@DFAjudge.com.br : 12345
-- Usuario rafael.cardoso@aluno.ufabc.edu.br : 12345678
INSERT INTO `usuarios` (`Uid`, `RA`, `nome`, `email`, `senha`, `admin`, `token`)
VALUES ( 1, '0', 'ADMINISTRADOR', 'admin@DFAjudge.com.br', '$2y$10$RB8ujObTBIbEEI.F1oti3esxn8FkExEbRlQbM0vDvWjmaJtzdq2wW', 1 , '-'),
(2, 21048012, 'Rafael Cardoso da Silva', 'rafael.cardoso@aluno.ufabc.edu.br', '$2y$10$MeWSPFqHzu78ztE/IWWXzuWq1MQ7q1kxUjgpl7p.GDVxBsk02xqG6', 0, '-');

-- ========================================================
--                CHAVES ESTRANGEIRAS
-- ========================================================

-- RELACIONAMENTOS PARA TABELAS `turmas`:
--   `UidCriador` -> `usuarios`.`Uid`

-- RELACIONAMENTOS PARA TABELAS `userturma`:
--   `Tid` -> `turmas`.`Tid`
--   `Uid` -> `usuarios`.`Uid`

-- RELACIONAMENTOS PARA TABELAS `listas`:
--   `UidCriador` -> `usuarios`.`Uid`

-- RELACIONAMENTOS PARA TABELAS `listaquestao`:
--   `Lid` -> `lista` -> `Lid`
--   `Qid` -> `quetoes` -> `Qid`

-- RELACIONAMENTOS PARA TABELAS `turmalista`:
--   `Lid` -> `lista` -> `Lid`
--   `Tid` -> `turmas` -> `Tid`

-- RELACIONAMENTOS PARA TABELAS `submissoes`:
--   `Uid`  -> `usuarios` -> `Uid`
--   `TLid` -> `turmaslista` -> `TLid`
--   `Qid`  -> `quetoes` -> `Qid`


