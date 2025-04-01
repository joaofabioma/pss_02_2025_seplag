--CREATE DATABASE app_pss_seplag

DROP TABLE IF EXISTS foto_pessoa ;
DROP TABLE IF EXISTS pessoa_endereco ;
DROP TABLE IF EXISTS servidor_temporario ;
DROP TABLE IF EXISTS servidor_efetivo ;
DROP TABLE IF EXISTS lotacao ;
DROP TABLE IF EXISTS pessoa ;

DROP TABLE IF EXISTS unidade_endereco ;
DROP TABLE IF EXISTS unidade ;
DROP TABLE IF EXISTS endereco ;
DROP TABLE IF EXISTS cidade ;



CREATE TABLE IF NOT EXISTS pessoa(
    pes_id SERIAL PRIMARY KEY,
    pes_nome VARCHAR(100),
    pes_data_nascimento DATE,
    pes_sexo VARCHAR(9),
	pes_mae VARCHAR(200),
	pes_pai  VARCHAR(200)
);

CREATE TABLE IF NOT EXISTS unidade(
	unid_id SERIAL PRIMARY KEY,
	unid_nome VARCHAR(200),
	unid_sigla VARCHAR(20)
);

CREATE TABLE IF NOT EXISTS cidade(
	cid_id SERIAL PRIMARY KEY,
	cid_nome VARCHAR(200),
	cid_uf CHAR(2)
);

CREATE TABLE IF NOT EXISTS endereco(
	end_id SERIAL PRIMARY KEY,
	end_tipo_logradouro VARCHAR(50),
	end_logradouro VARCHAR(200),
	end_numero INTEGER,
	end_bairro VARCHAR(100),
	cid_id INTEGER REFERENCES cidade(cid_id)
);

CREATE TABLE IF NOT EXISTS foto_pessoa(
	fp_id SERIAL PRIMARY KEY,
	pes_id INTEGER REFERENCES pessoa(pes_id),
	fp_data DATE,
	fp_bucket VARCHAR(50),
	fp_hash VARCHAR(50)
);

CREATE TABLE IF NOT EXISTS pessoa_endereco(
	pes_id INTEGER REFERENCES pessoa(pes_id),
	end_id INTEGER REFERENCES endereco(end_id)
);

CREATE TABLE IF NOT EXISTS servidor_temporario(
	pes_id INTEGER REFERENCES pessoa(pes_id),
	st_data_admissao DATE,
	st_data_demissao DATE
);

CREATE TABLE IF NOT EXISTS servidor_efetivo(
	pes_id INTEGER REFERENCES pessoa(pes_id),
	se_matricula VARCHAR(20)
);

CREATE TABLE IF NOT EXISTS lotacao(
	lot_id SERIAL PRIMARY KEY,
	pes_id INTEGER REFERENCES pessoa(pes_id),
	unid_id INTEGER REFERENCES unidade(unid_id),
	lot_data_lotacao DATE,
	lot_data_remocao DATE,
	lot_portaria VARCHAR(100)
);

CREATE TABLE IF NOT EXISTS unidade_endereco(
	unid_id INTEGER REFERENCES unidade(unid_id),
	end_id INTEGER REFERENCES endereco(end_id)
);
