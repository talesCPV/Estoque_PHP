use flexib52_db_estoque;

CREATE TABLE tb_agenda(
    id INT NOT NULL AUTO_INCREMENT,
    id_emp INT NOT NULL,
    nome VARCHAR(40) NOT NULL,
    email VARCHAR(70) DEFAULT NULL,
    depart varchar(15) DEFAULT NULL,
    cel1 varchar(14) DEFAULT NULL,
    cel2 varchar(14) DEFAULT NULL,
    PRIMARY KEY(ID),
    FOREIGN KEY(id_emp) REFERENCES tb_empresa(id)
)DEFAULT CHARSET=UTF8;

CREATE TABLE tb_empresa(
    id int(11) NOT NULL AUTO_INCREMENT,
    nome varchar(50) NOT NULL,
    cnpj varchar(14) DEFAULT NULL,
    ie varchar(14) DEFAULT NULL,
    endereco varchar(60) DEFAULT NULL,
    cidade varchar(30) DEFAULT NULL,
    estado varchar(2) DEFAULT NULL,
    tipo varchar(3) DEFAULT NULL,
    tel varchar(14) DEFAULT NULL,
    cep varchar(10) DEFAULT NULL,
    class int(11) DEFAULT NULL,
    fantasia varchar(40) default null,
    PRIMARY KEY (id)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

CREATE TABLE tb_entrada (
    id int(11) NOT NULL AUTO_INCREMENT,
    nf varchar(10) DEFAULT NULL,
    id_emp int(11) NOT NULL,
    data_ent date DEFAULT NULL,
    resp varchar(15) DEFAULT NULL,
    status varchar(7) NOT NULL DEFAULT 'ABERTO',    
    PRIMARY KEY (id),
    FOREIGN KEY(id_emp) REFERENCES tb_empresa(id)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

CREATE TABLE tb_item_compra (
    id int(11) NOT NULL AUTO_INCREMENT,
    id_prod int(11) NOT NULL,
    id_ent int(11) DEFAULT NULL,
    qtd double NOT NULL DEFAULT 0,
    preco double NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    FOREIGN KEY (id_prod) REFERENCES tb_produto(id),
    FOREIGN KEY (id_ent) REFERENCES tb_entrada(id)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

CREATE TABLE tb_item_ped (
    id int(11) NOT NULL AUTO_INCREMENT,
    id_prod int(11) NOT NULL,
    id_ped int(11) DEFAULT NULL,
    qtd double NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    FOREIGN KEY (id_prod) REFERENCES tb_produto(id),
    FOREIGN KEY (id_ped) REFERENCES tb_pedido(id)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

CREATE TABLE tb_pedido (
    id int(11) NOT NULL AUTO_INCREMENT,
    id_emp int(11) NOT NULL,
    data_ped date DEFAULT NULL,
    data_ent date DEFAULT NULL,
    resp varchar(15) DEFAULT NULL,
    comp varchar(30) DEFAULT NULL,
    num_ped varchar(15) DEFAULT NULL,
    status varchar(7) NOT NULL DEFAULT 'ABERTO',
    nf varchar(10) DEFAULT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (id_emp) REFERENCES tb_empresa(id_emp)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

CREATE TABLE tb_produto (
    id int(11) NOT NULL AUTO_INCREMENT,
    id_emp int(11) NOT NULL,
    descricao varchar(80) NOT NULL,
    estoque double NOT NULL DEFAULT 0,
    etq_min double NOT NULL DEFAULT 0,
    unidade varchar(10) DEFAULT NULL,
    ncm varchar(8) DEFAULT NULL,
    cod varchar(15) NOT NULL,
    cod_bar varchar(15) DEFAULT NULL,
    reserva double NOT NULL DEFAULT 0,
    preco_comp double NOT NULL DEFAULT 0,
    margem double NOT NULL DEFAULT 40,
    PRIMARY KEY (id),
    UNIQUE KEY (cod),
    FOREIGN KEY (id_emp) REFERENCES tb_empresa(id)
) ENGINE=MyISAM AUTO_INCREMENT=91 DEFAULT CHARSET=utf8;

CREATE TABLE tb_usuario (
    id int(11) NOT NULL AUTO_INCREMENT,
    user varchar(12) NOT NULL,
    pass varchar(12) NOT NULL,
    class int(11) DEFAULT NULL,
    nome varchar(40) DEFAULT NULL,
    email varchar(70) DEFAULT NULL,
    cel varchar(14) DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

CREATE TABLE tb_etiqueta (
    id int(11) NOT NULL AUTO_INCREMENT,
    id_prod int(11) NOT NULL,
    descr varchar(60) NOT NULL,
    cp1 varchar(10) DEFAULT NULL,
    cp2 varchar(10) DEFAULT NULL,
    cp3 varchar(10) DEFAULT NULL,
    cp4 varchar(10) DEFAULT NULL,
    cp5 varchar(10) DEFAULT NULL,
    cp6 varchar(10) DEFAULT NULL,
    PRIMARY KEY (id)
)DEFAULT CHARSET=utf8;

CREATE TABLE tb_financeiro (
    id int(11) NOT NULL AUTO_INCREMENT,
    tipo varchar(7) NOT NULL DEFAULT 'ENTRADA',
    data_ini date DEFAULT NULL,
    data_pg date DEFAULT NULL,
    preco double NOT NULL DEFAULT 0,
    ref varchar(30) DEFAULT NULL,
    resp varchar(15) DEFAULT NULL,
    emp varchar(50) DEFAULT NULL,
    origem varchar(3) DEFAULT 'FUN',
    PRIMARY KEY (id)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

CREATE TABLE tb_subconj (
    id int(11) NOT NULL AUTO_INCREMENT,
    id_conj int(11) NOT NULL,
    id_peca int(11) NOT NULL,
    qtd double NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    FOREIGN KEY (id_conj) REFERENCES tb_produto(id),
	FOREIGN KEY (id_peca) REFERENCES tb_produto(id)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE tb_servico (
    id int(11) NOT NULL AUTO_INCREMENT,
    num_serv varchar(15) DEFAULT NULL,
    resp varchar(15) DEFAULT NULL,
    func varchar(30) DEFAULT NULL,
    tipo varchar(2) NOT NULL DEFAULT 'OF',
    data_serv datetime DEFAULT CURRENT_TIMESTAMP,
	status varchar(7) NOT NULL DEFAULT 'ABERTO',
    PRIMARY KEY (id)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE tb_item_serv (
    id int(11) NOT NULL AUTO_INCREMENT,
    id_serv int(11) NOT NULL,
    id_item int(11) NOT NULL,
    qtd double NOT NULL DEFAULT 0,   
    PRIMARY KEY (id),
    FOREIGN KEY (id_serv) REFERENCES tb_servico(id),
	FOREIGN KEY (id_item) REFERENCES tb_produto(id)    
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

#DROP TABLE tb_cargos;

CREATE TABLE tb_cargos (
    id int(11) NOT NULL AUTO_INCREMENT,
    cargo varchar(30) DEFAULT NULL,
    salario double NOT NULL DEFAULT 0,    
    tipo varchar(6) DEFAULT NULL,
    cbo varchar(8) DEFAULT NULL,
    PRIMARY KEY (id)  
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE tb_funcionario (
    id int(11) NOT NULL AUTO_INCREMENT,
    nome varchar(30) DEFAULT NULL,    
    rg varchar(15) DEFAULT NULL,
    cpf varchar(15) DEFAULT NULL,
    pis varchar(15) DEFAULT NULL,
    endereco varchar(60) DEFAULT NULL,
    cidade varchar(30) DEFAULT NULL,
    estado varchar(2) DEFAULT NULL,
    cep varchar(10) DEFAULT NULL,    
    data_adm datetime DEFAULT CURRENT_TIMESTAMP,
	data_dem datetime DEFAULT NULL,
    id_cargo int(11) NOT NULL,
    tel varchar(15) DEFAULT NULL,
    cel varchar(15) DEFAULT NULL,
    status varchar(5) DEFAULT NULL,
    vale double NOT NULL DEFAULT 0,
	obs varchar(200) DEFAULT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (id_cargo) REFERENCES tb_cargos(id)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


CREATE TABLE tb_hora_extra (
  id int(11) NOT NULL AUTO_INCREMENT,
  id_func int(11) NOT NULL,
  entrada datetime DEFAULT CURRENT_TIMESTAMP,
  saida datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (id_func) REFERENCES tb_funcionario(id)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE tb_serv_exec (
    id int(11) NOT NULL AUTO_INCREMENT,
    id_emp INT NOT NULL,
    data_exec datetime DEFAULT CURRENT_TIMESTAMP,
    num_carro varchar(15) DEFAULT NULL,
    nf varchar(10) DEFAULT NULL,
    pedido varchar(15) DEFAULT NULL,
    func varchar(150) DEFAULT NULL,
    obs varchar(500),
    PRIMARY KEY (id),
    FOREIGN KEY(id_emp) REFERENCES tb_empresa(id)    
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


CREATE TABLE tb_pcp (
    id int(11) NOT NULL AUTO_INCREMENT,
    data_serv datetime NOT NULL,
    frente varchar(300),
    suporte varchar(300),
    costura varchar(300),
    montagem varchar(300),  
    PRIMARY KEY (id),
    UNIQUE KEY (data_serv)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE tb_calendario (
    id_user int(11) NOT NULL,
    data_agd datetime NOT NULL,
    obs varchar(300),
    hint varchar(100),
    PRIMARY KEY (id_user, data_agd),
    FOREIGN KEY(id_user) REFERENCES tb_usuario(id)    	
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE tb_texto_nf (
    id int(11) NOT NULL AUTO_INCREMENT,
    nome varchar(50),
    texto varchar(500),
    PRIMARY KEY (id),
    UNIQUE KEY (nome)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE tb_inventario (
  id int(11) NOT NULL AUTO_INCREMENT,
  cod_prod int(11) NOT NULL,
  oper varchar(1) DEFAULT '1',
  qtd int(11) NOT NULL,
  user varchar(10) DEFAULT NULL,
  dia datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE tb_sanfonas (
    id int(11) NOT NULL AUTO_INCREMENT,
    fabricante varchar(25) NOT NULL,
    modelo varchar(40) NOT NULL,
    ano varchar(15) NOT NULL,
    barras int(11) NOT NULL,
    dob_teto int(11) DEFAULT 0,
    dob_chao int(11) DEFAULT 0,
    tipo_sanf varchar(15) DEFAULT "SANF.INTERNA",
    chao_larg int(11) DEFAULT 0,
    chao_comp int(11) DEFAULT 0,
    bainhas int(11) DEFAULT 0,
    alt_sanf int(11) DEFAULT 0,
    alt_prot_teto int(11) DEFAULT 0, 
    PRIMARY KEY (id)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE tb_ref_sanf (
    id int(11) NOT NULL AUTO_INCREMENT,
    entrada datetime DEFAULT CURRENT_TIMESTAMP,
    id_cliente int(11) NOT NULL,
    id_modelo int(11) NOT NULL,
    numero varchar(15) DEFAULT "0000/00/00",
    tipo varchar(11) DEFAULT "REFORMA",
    status varchar(15) DEFAULT "RECEBIMENTO",
    saida datetime DEFAULT CURRENT_TIMESTAMP,
    obs varchar(300),
    PRIMARY KEY (id),
	FOREIGN KEY(id_cliente) REFERENCES tb_empresa(id),
	FOREIGN KEY(id_modelo) REFERENCES tb_sanfonas(id)    
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


select * from tb_ref_sanf;

SELECT r.id, r.entrada, e.nome, s.fabricante, s.modelo, s.ano, r.numero, r.tipo, r.status, r.saida, r.obs
	                         FROM tb_ref_sanf AS r INNER JOIN tb_empresa AS e INNER JOIN tb_sanfonas as s 
	                         ON r.id_cliente = e.id
                             AND r.id_modelo = s.id;


INSERT INTO tb_ref_sanf ( entrada, id_cliente, id_modelo, numero, tipo, status, saida, obs) VALUES ('2021-01-07', '175', '1', '1234567890', 'REFORMA', 'RECEBIMENTO', 2021-01-19, 'teste 123' ) ;


ALTER table tb_cargos ADD cbo varchar(8) DEFAULT NULL;

ALTER TABLE tb_serv_exec ADD valor double NOT NULL DEFAULT 0;

ALTER table tb_funcionario ADD vale double NOT NULL DEFAULT 0;
ALTER table tb_funcionario ADD obs varchar(200) DEFAULT NULL;


SET SQL_SAFE_UPDATES = 0;
UPDATE tb_empresa SET fantasia = nome ;

SELECT * FROM tb_serv_exec;


d rop table tb_pcp;
//d rop table tb_serv_exec;
//D ROP TABLE IF EXISTS `tb_hora_extra`;
//d rop table tb_item_serv;
//d rop table tb_funcionario;
//d rop table tb_servico;
//d rop table tb_sanfonas;

ALTER TABLE tb_financeiro
ADD column resp varchar(15) DEFAULT NULL; 
ALTER TABLE tb_financeiro
ADD column emp varchar(50) DEFAULT NULL; 
ALTER TABLE tb_financeiro
ADD column origem varchar(3) DEFAULT 'FUN'; 

ALTER TABLE tb_item_ped
ADD column serv varchar(500) DEFAULT ''; 


ALTER TABLE tb_pedido
ADD column desconto double NOT NULL DEFAULT 0; 
ALTER TABLE tb_pedido
ADD column cond_pgto varchar(100) DEFAULT '28 d'; 
ALTER TABLE tb_pedido
ADD column obs varchar(100) DEFAULT NULL; 
ALTER TABLE tb_empresa
ADD column bairro varchar(60) DEFAULT NULL; 
ALTER TABLE tb_empresa
DROP class;
ALTER TABLE tb_empresa
ADD column num varchar(5) DEFAULT NULL; 

ALTER TABLE tb_pedido
ADD column path varchar(70) DEFAULT NULL; 

ALTER TABLE tb_produto
ADD column tipo varchar(7) DEFAULT 'VENDA'; 

drop table tb_etiqueta;


SELECT * FROM tb_empresa WHERE id=166;

select * from tb_agenda;

delete from tb_agenda where id= '13';

select * from tb_produto where tipo='TINTA' order by cod;

SELECT * FROM tb_produto WHERE id='108' order by cod;

select * from tb_empresa;

SELECT a.id, a.nome, a.depart, a.email, a.cel1, a.cel2, e.nome as emp FROM tb_agenda a, tb_empresa e WHERE a.nome LIKE '%%' AND a.id_emp = e.id ;

select * from tb_usuario;

update tb_usuario set class='4' where id = 10;

select * from tb_pedido ;

update tb_pedido set status='ABERTO' where id = 55;

INSERT INTO tb_produto (descricao, estoque, etq_min, unidade, cod, cod_bar, id_emp, ncm, preco_comp, margem, tipo) VALUES ('TESTE', '99', '0','900ml', '1', '', 16, '32081010', '12.65', '90', 'TINTA');
delete from tb_produto where cod='7046';

SELECT cod FROM tb_produto where tipo='TINTA' order by cod desc limit 1;

select * from tb_pedido where id = 76;

select * from tb_item_ped WHERE id_ped = 126;

select * from tb_item_ped WHERE id_prod = 213;

SELECT * FROM tb_produto order by cod;

SELECT cod, descricao FROM tb_produto order by cod;

SELECT cod FROM tb_produto WHERE tipo NOT LIKE '%TINTA%' order by cod desc limit 1;

SELECT * FROM tb_produto WHERE tipo NOT LIKE '%TINTA%' order by cod desc ;

update tb_produto set tipo='TINTA' where id = 302;

select * from tb_usuario;

update tb_usuario set class = 3 where id = 10;

show tables;

select * from tb_entrada;


update tb_entrada set status='ABERTO' where id = 43;

SELECT en.id, en.nf, e.nome, en.data_ent, en.status, en.resp
	                         FROM tb_entrada AS en INNER JOIN tb_empresa AS e 
	                         ON en.id_emp = e.id order by en.data_ent desc;
                             
SELECT cod FROM tb_produto WHERE tipo LIKE '%TINTA%' order by cod desc;      

select * from tb_financeiro;        

INSERT INTO tb_financeiro ( tipo, ref, emp, preco, data_pg, data_ini, resp) VALUES ('ENTRADA', '123', 'teste', '12.36','2020-03-11', '11-03-2020' ,'tales');   

delete from tb_financeiro where id <= 63;      

SELECT ref from tb_financeiro WHERE ref = 'NF1757';

SELECT p.id, p.num_ped, e.nome, p.comp, p.data_ped, p.data_ent, p.status
	                         FROM tb_pedido AS p INNER JOIN tb_empresa AS e 
	                         ON p.id_emp = e.id ORDER BY p.data_ped DESC ;
							
SELECT preco_comp,margem,(preco_comp * (1+ margem / 100)) AS PRECO FROM tb_produto;         

select qtd, preco, (qtd * preco) as preco_venda FROM tb_item_ped;  

select * from tb_pedido; 

select* from tb_item_ped;  

SELECT p.id, p.num_ped, p.comp, p.data_ped, p.data_ent, p.status, i.preco
	                         FROM tb_pedido AS p INNER JOIN tb_item_ped AS i 
	                         ON p.id = i.id_ped ;  
                             


SELECT p.id, p.num_ped, e.nome, p.comp, p.data_ped, p.data_ent, p.status, i.venda FROM tb_pedido AS p INNER JOIN (SELECT id_ped, ROUND(SUM(qtd * preco),2) AS venda FROM tb_item_ped GROUP BY id_ped) AS i ON p.id = i.id_ped INNER JOIN tb_empresa AS e ON p.id_emp = e.id AND p.data_ped >= '2020-03-01' AND p.data_ped <= '' ;                            


LOCK TABLES `tb_cargos` WRITE;
/*!40000 ALTER TABLE `tb_cargos` DISABLE KEYS */;
INSERT INTO `tb_cargos` VALUES (1,'COSTUREIRO 2',8.59,'HORA',''),(5,'COSTUREIRO 1',7.2,'HORA',''),(3,'MECANICO MANUT ONIBUS 2',7.8,'HORA',''),(7,'MECANICO MANUT ONIBUS 1',7.45,'HORA',''),(8,'MECANICO MANUT ONIBUS 3',8.26,'HORA',''),(9,'MECANICO MANUT ONIBUS 4',8.59,'HORA',''),(10,'MECANICO MANUT ONIBUS SENIOR',12.68,'HORA',''),(11,'SUPERVISOR',3726,'MENSAL',''),(12,'ENG. DE PROJETOS',7984,'MENSAL',''),(15,'LIDER DE PRODUÃ‡ÃƒO',1900,'MENSAL',''),(16,'COSTUREIRO (AUTÃ”NOMO)',9.2,'HORA',''),(17,'MONTADOR AUTONOMO',2000,'MENSAL',''),(18,'MENOR APRENDIZ',3.41,'HORA',''),(19,'AJUDANTE (AUTONOMO)',5.68,'HORA','');
/*!40000 ALTER TABLE `tb_cargos` ENABLE KEYS */;
UNLOCK TABLES;


INSERT INTO tb_hora_extra VALUES (DEFAULT, (SELECT id from tb_funcionario WHERE nome LIKE 'BRUNO%' ),'2021-01-30 10:00:00','2021-01-31 00:00:00');

SELECT id from tb_funcionario WHERE id = 19;