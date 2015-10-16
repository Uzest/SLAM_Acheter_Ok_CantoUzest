delete from proposerA;
delete from pointRelais;
delete from produit;
delete from typeProd;
delete from rayon;
delete from choisir;
delete from question;
delete from utilisateur;
delete from communes;

load data local infile 'communes.csv' into table communes fields terminated by ';' lines terminated by '\n';
load data local infile 'rayon.csv' into table rayon fields terminated by ';' lines terminated by '\n';
load data local infile 'question.csv' into table question fields terminated by ';' lines terminated by '\n';
load data local infile 'choisir.csv' into table choisir fields terminated by ';' lines terminated by '\n';
load data local infile 'typeProd.csv' into table typeProd fields terminated by ';' lines terminated by '\n';
load data local infile 'produit.csv' into table produit fields terminated by ';' lines terminated by '\n';
load data local infile 'pointRelais.csv' into table pointRelais fields terminated by ';' lines terminated by '\n';
load data local infile 'proposerA.csv' into table proposerA fields terminated by ';' lines terminated by '\n';
