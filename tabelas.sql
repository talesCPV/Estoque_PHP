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

ALTER TABLE tb_produto
ADD column tipo varchar(7) DEFAULT 'VENDA'; 
