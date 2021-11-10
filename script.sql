DROP DATABASE IF EXISTS site_faq;

CREATE DATABASE site_faq;

USE site_faq;

CREATE TABLE utilisateur (
	id_uti int primary key not null auto_increment,
	pseudo varchar(32),
	mail varchar(32),
	password varchar(32),
	droit varchar(10)
);


CREATE TABLE faq (
	id_faq int primary key not null auto_increment,
	id_sous_faq int,
	nom varchar(64),
	ordre_faq int,
	date_faq datetime not null,
	visible varchar(16),
	constraint fk_idsousfaq_idfaq
		foreign key (id_sous_faq)
		references faq(id_faq)
);

CREATE TABLE droituti (
	id_uti int not null,
	id_faq int not null
);
ALTER TABLE droituti ADD PRIMARY KEY (id_uti,id_faq);
ALTER TABLE droituti ADD FOREIGN KEY (id_uti) references utilisateur(id_uti);
ALTER TABLE droituti ADD FOREIGN KEY (id_faq) references faq(id_faq);

CREATE TABLE contenuefaq (
	id_cont int primary key not null auto_increment,
	id_faq int,
	question varchar(255),
	reponse varchar(300),
	ordre_cont int,
	date_cont datetime not null,
	visible varchar(16),
	constraint fk_idfaqcont_idfaq
		foreign key (id_faq)
		references faq(id_faq)
);

CREATE TABLE ContactFAQ (
	Id_Formulaire int primary key not null auto_increment,
	Nom varchar(32),
	Prenom varchar(32),
	Email varchar(32),
	Classe varchar(20),
	Texte varchar(300),
	Date_Envoi datetime not null,
	Traitement varchar(20)
);

INSERT INTO ContactFAQ(Nom,Prenom,Email,Classe,Texte,Date_Envoi,Traitement) VALUES('Admin', 'Admin', 'Admin.admin@admin.com', 'SuperUser', 'Je suis l''administrateur', '1970-01-01 23:59:59','Traité');

INSERT INTO utilisateur (pseudo,password,droit,mail) VALUES ('admin','admin','0','romainliot23@gmail.com'),('test','test',NULL,'');

INSERT INTO faq(nom,date_faq,ordre_faq,visible) VALUES('wifi','2017-14-06 10:10:50',1,'public');


INSERT INTO contenuefaq(question,reponse,id_faq,ordre_cont,visible) 
VALUES('Qui peut bénéficier de ce réseau ?','La connexion à ce réseau est réservé aux professeurs et aux étudiants de BTS',1,1,'public'),
('Comment bénéficier de ce réseau ?','Une demande doit être faite auprès de Mr Debroise ou de Mr Bogaert (Admin réseau) salle 106 ou par GLPI',1,2,'public'),
('Quels renseignements doit-on fournir ?','Votre identité, le nom de votre PC et son adresse physique.',1,3,'public'),
('Quelle incidence pour mon portable ?','Si vous souhaitez travailler facilement avec les fichiers de votre espace perso vous devrez créer un nouvel utilisateur sur votre portable. Ce nouvel utilisateur aura le nom de votre login de session du réseau NDLP. Vous devrez également utiliser le même mot de passe.',1,4,'public'),
('Le paramétrage est-il compliqué ?','Non, il suffit que votre interface wifi utilise l''adressage automatique (DHCP), ce qui est le cas le plus fréquent et le plus simple.',1,5,'public'),
('Quel est le nom du réseau ?','Le nom du réseau ou SSID est wifindlp.',1,6,'public');

INSERT INTO droituti(id_uti,id_faq) VALUES (1,1);
