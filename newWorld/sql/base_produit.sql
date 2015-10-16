-- Fait par Jessy Canto le 3 Fevrier 2015



drop table if exists proposerA;
drop table if exists pointRelais;
drop table if exists lot;
drop table if exists produit;
drop table if exists typeProd;
drop table if exists rayon;
drop table if exists choisir;
drop table if exists question;
drop table if exists utilisateur;
drop table if exists communes;


create table lot(
	lotNum int,
	lotModeDeProd varchar(15),
	lotUniteMesure varchar(15),
	lotDateRecolte date,
	lotNbJourDeConsommation smallint,
	lotPrixUnite decimal(5,2),
	lotQuantiteDispo smallint,
	lotQteMini tinyint,
	lotCommuneCP varchar(5),
	lotParcelle int,
	prodId int,
	utiNum int,
	PRIMARY KEY (lotNum)
)engine=innodb default charset=utf8;

create table communes(
	communeCodeInsee varchar (5),
	communeLibelle varchar(50),
	communeCP varchar(5),	
	communeLibelleAcheminement varchar(50),
	PRIMARY KEY (communeCP,communeCodeInsee,communeLibelleAcheminement)
)engine=innodb default charset=utf8; 

create table produit(
	prodId int,
	prodLibelle varchar(30),
	typeId int,
	rayonId int,
	PRIMARY KEY (prodId,typeId,rayonId)
)engine=innodb default charset=utf8;

create table typeProd(
	typeId int,
	typeLibelle varchar(30),
	rayonId int,
	PRIMARY KEY (typeId,rayonId)
)engine=innodb default charset=utf8;

create table rayon(
	rayonId int,
	rayonLibelle varchar(30),
	PRIMARY KEY (rayonId)
)engine=innodb default charset=utf8; 

create table pointRelais(
	relNum int,
	relLibelle varchar (45),
	relActivite varchar (40),
	relNomResponsable varchar(30),
	relPrenomResponsable varchar(30),
	relCodePostal varchar(5),
	relCommuneLibelle varchar(50),
	relRue1 varchar(30),
	relRue2 varchar(30),
	relTel varchar(14),
	relEmail varchar(30),
	PRIMARY KEY (relNum)
)engine=innodb default charset=utf8;

create table utilisateur(
	utiNum int,
	utiNom varchar(35),
	utiPrenom varchar(35),
	utiTel varchar(14),
	utiRue varchar(20),
	utiCP varchar(5),
	utiVille varchar(20),
	utiEmail varchar(30),
	utiPseudo varchar(30),
	utiMDP varchar(40),
	utiCle varchar(20),
	utiValidationEmail boolean,
	utiDateInscription date,
	PRIMARY KEY (utiNum)
)engine=innodb default charset=utf8;

create table question(
	quesNo tinyint,
	quesLibelle varchar(100),
	PRIMARY KEY (quesNo)
)engine=innodb default charset=utf8;

create table choisir(
	quesNo tinyint,
	utiNum int,
	choiRep varchar(30),
	PRIMARY KEY (quesNo,utiNum)
)engine=innodb default charset=utf8;

create table proposerA(
	lotNum int,
	relNum int,
	PRIMARY KEY (lotNum,relNum)
)engine=innodb default charset=utf8;



-- les contraintes


alter table lot
	add foreign key (prodId) references produit(prodId),
	add foreign key (utiNum) references utilisateur(utiNum);

alter table proposerA
	add foreign key (lotNum) references lot(lotNum),
	add foreign key (relNum) references pointRelais(relNum);

alter table choisir
	add foreign key (utiNum) references utilisateur(utiNum),
	add foreign key (quesNo) references question(quesNo);

alter table produit
	add foreign key (typeId,rayonId) references typeProd(typeId,rayonId);

alter table typeProd
	add foreign key (rayonId) references rayon(rayonId);

alter table utilisateur
	add foreign key (utiCP) references communes(communeCP);

alter table pointRelais
	add foreign key (relCodePostal) references communes(communeCP);


http://172.16.63.111/~jcanto/newWorld/verifEmail.php?num=1000&key=cb58490ed7fb606f06ef
