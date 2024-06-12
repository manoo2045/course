create database utr;

\c utr

create table course(
    id serial primary key,
    nom varchar(50),
    "date" date,
    duree int
);

create table etape(
    id serial primary key,
    nom varchar(100),
    longueur float,
    nb_coureur int,
    rang int,
    "date" date,
    depart TIMESTAMP,
    id_course int default 1
);
ALTER TABLE etape ADD COLUMN depart TIMESTAMP;

create table equipe(
    id serial primary key,
    nom varchar(100),
    pwd text,
    "login" varchar(20)
);

create table coureur(
    id serial primary key,
    nom varchar(250),
    numero int unique,
    genre varchar(20),
    id_equipe int,
    FOREIGN KEY (id_equipe) REFERENCES equipe(id)
);
ALTER TABLE coureur ADD COLUMN date_naissance date;
-- Import
-- ALTER TABLE coureur alter column numero type varchar(10);

create table categorie_coureur(
    id_coureur int,
    categorie varchar(50),
    FOREIGN KEY (id_coureur) REFERENCES coureur(id)
);

create table etape_coureur(
    id_etape int,
    id_coureur int,
    FOREIGN KEY (id_etape) REFERENCES etape(id),
    FOREIGN KEY (id_coureur) REFERENCES coureur(id),
    unique(id_etape,id_coureur)
);

create or replace view v_etape_coureur as
    SELECT
        ec.id_etape,
        ec.id_coureur,
        c.nom AS nom_coureur,
        c.numero AS numero_coureur,
        c.genre AS genre_coureur,
        c.id_equipe
    FROM
        etape_coureur ec
    JOIN
        coureur c ON ec.id_coureur = c.id;



select * from coureur where id not in (select id_coureur FROM etape_coureur );

SELECT 
    etape.nom AS nom_etape,
    coureur.nom AS nom_coureur,
    categorie.nom AS nom_categorie
    FROM 
        etape_coureur
    JOIN 
        etape ON etape_coureur.id_etape = etape.id
    JOIN 
        coureur ON etape_coureur.id_coureur = coureur.id
    JOIN 
        categorie ON etape_coureur.id_categorie = categorie.id;

create table temps_coureur(
    id serial primary key,
    id_etape int not null,
    heure_depart TIMESTAMP,
    heure_arrive TIMESTAMP,
    id_coureur int,
    -- penalite Time default '00:00:00',
    FOREIGN KEY (id_etape) REFERENCES etape(id),
    FOREIGN KEY (id_coureur) REFERENCES coureur(id),
    unique(id_etape,id_coureur)
);

ALTER table temps_coureur drop column penalite;
-- DROP cascade sur vue v_temps_coureur_etape_rank
-- DROP cascade sur vue v_temps_coureur_etape_point
-- DROP cascade sur vue v_classement_equipe

create table penalite(
    id serial primary key,
    id_etape int,
    id_equipe int,
    heure_penalite INTERVAL,
    FOREIGN KEY (id_etape) REFERENCES etape(id),
    FOREIGN KEY (id_equipe) REFERENCES equipe(id)
);
-- DROP cascade sur vue v_somme_penalite
-- DROP cascade sur vue v_temps_coureur_etape_rank
-- DROP cascade sur vue v_temps_coureur_etape_point

create or replace view v_penalite as
    select 
        p.id,e.id id_etape,e.nom etape_nom,eq.id id_equipe,eq.nom equipe_nom,p.heure_penalite
    from penalite p
        join etape e on e.id = p.id_etape
        join equipe eq on eq.id = p.id_equipe;  

create or replace view v_somme_penalite as 
    select id_etape,id_equipe,sum(heure_penalite) as total_penalite from penalite group by id_equipe,id_etape; 
-- DROP cascade sur vue v_temps_coureur_etape_point
-- DROP cascade sur vue v_classement_equipe

create table parametre_point(
    id serial primary key,
    rang int,
    "point" int,
    id_course int default 1
);


-- -- JOUR 1
-- CREATE or replace VIEW v_temps_coureur_etape AS
--     SELECT 
--         te.id_etape,
--         e.nom AS etape_nom,
--         e.depart,
--         te.heure_arrive,
--         te.id_coureur,
--         te.penalite,
--         e.longueur,
--         e.nb_coureur,
--         e.rang,
--         e.date,
--         e.id_course,
--         c.nom AS coureur_nom,
--         c.numero AS coureur_numero,
--         c.genre AS coureur_genre,
--         c.id_equipe
--     FROM 
--         coureur c
--     JOIN 
--         temps_coureur te on te.id_coureur = c.id
--     JOIN
--         etape e ON te.id_etape = e.id;

-- JOUR 2 mise a jour
CREATE or REPLACE VIEW v_temps_coureur_etape AS
    SELECT 
        te.id_etape,
        e.nom AS etape_nom,
        e.depart,
        te.heure_arrive,
        c.id id_coureur,
        e.longueur,
        e.nb_coureur,
        e.rang,
        e.date,
        e.id_course,
        c.nom AS coureur_nom,
        c.numero AS coureur_numero,
        c.genre AS coureur_genre,
        c.id_equipe
    FROM 
        coureur c
    Le
    LEFT JOIN 
        temps_coureur te on te.id_coureur = c.id
    left JOIN
        etape e ON te.id_etape = e.id; 

-- JOUR 4 modifier
CREATE or REPLACE VIEW v_temps_coureur_etape AS
    SELECT 
        et.id_etape,
        e.nom AS etape_nom,
        e.depart,
        te.heure_arrive,
        c.id id_coureur,
        e.longueur,
        e.nb_coureur,
        e.rang,
        e.date,
        e.id_course,
        c.nom AS coureur_nom,
        c.numero AS coureur_numero,
        c.genre AS coureur_genre,
        c.id_equipe
    FROM 
        coureur c
    left join 
        etape_coureur et on et.id_coureur = c.id
    LEFT JOIN 
        temps_coureur te on te.id_coureur = c.id and et.id_etape = te.id_etape
    left JOIN
        etape e ON et.id_etape = e.id; 



CREATE OR REPLACE VIEW v_coureur as
    select *, EXTRACT(year from CURRENT_DATE) - EXTRACT(year from date_naissance) as age from coureur;

select min(date) from etape

-- classement général et les points pour chaque étape
CREATE or REPLACE VIEW v_temps_coureur_etape_rank AS
    SELECT
        vr.id_etape,
        vr.etape_nom,
        vr.depart,
        vr.heure_arrive,
        vr.id_coureur,
        vr.longueur,
        vr.nb_coureur,
        vr.rang,
        vr.date,
        vr.id_course,
        vr.coureur_nom,
        vr.coureur_numero,
        vr.coureur_genre,
        vr.id_equipe,
        COALESCE(pe.total_penalite,'00:00:00') as penalite,
        TO_CHAR((heure_arrive - depart + COALESCE(pe.total_penalite,'00:00:00')), 'DD HH24:MI:SS') AS temps_effectue_hh,
        (EXTRACT(EPOCH FROM (heure_arrive - depart))+EXTRACT(EPOCH FROM COALESCE(pe.total_penalite,'00:00:00'))) / 60 AS temps_effectue_mm,
        DENSE_RANK() OVER (PARTITION BY vr.id_etape ORDER BY(EXTRACT(EPOCH FROM (heure_arrive - depart)) + EXTRACT(EPOCH FROM COALESCE(pe.total_penalite,'00:00:00'))) / 60 ASC) AS place
    FROM
        v_temps_coureur_etape vr
    LEFT JOIN
        v_somme_penalite pe on vr.id_equipe = pe.id_equipe and vr.id_etape = pe.id_etape
    ORDER BY
       id_etape,
        place ASC;


CREATE or replace VIEW v_temps_coureur_etape_point AS
    SELECT
        r.*,
        COALESCE(p.point,0) point
    FROM
        v_temps_coureur_etape_rank r
    LEFT JOIN
        parametre_point p ON r.place = p.rang AND r.id_course = p.id_course;


-- Classement general
CREATE Or replace VIEW v_classement_equipe as
    select 
        id_equipe,
        e.nom equipe_nom,
        sum(temps_effectue_mm) total_temps,
        sum(point) total_point,
        DENSE_RANK() OVER (ORDER BY sum(point) DESC) AS place
    from 
        v_temps_coureur_etape_point t
    JOIN
        equipe e on e.id = t.id_equipe
    group by 
        id_equipe,e.nom,t.id_equipe;



SELECT
    v.id_etape,v.etape_nom,v.longueur,point_2,    
    DENSE_RANK() OVER (ORDER BY v.temps_effectue_mm ASC) AS place_2,
    COALESCE(p.point, 0) AS point_2
FROM
    (SELECT
    *,
    DENSE_RANK() OVER (PARTITION BY id_etape ORDER BY temps_effectue_mm ASC) AS place_2
    FROM
    v_temps_coureur_etape_rank vr
    JOIN categorie_coureur cc on cc.id_coureur = vr.id_coureur
    ) v
LEFT JOIN
parametre_point p
ON
v.place_2 = p.rang where id_equipe = 1 group by v.id_etape,v.etape_nom,v.longueur,point_2;

--Requete pour classement par genre tsy mety
select
    id_equipe,
    e.nom equipe_nom,
    sum(point) total_point,
    DENSE_RANK() OVER ( ORDER BY sum(point) DESC) AS place
from 
    v_temps_coureur_etape_point rp 
    join 
        categorie_coureur c on rp.id_coureur = c.id_coureur
    JOIN
        equipe e on e.id = rp.id_equipe
    where categorie like '%*%' or coureur_genre like '%*%' group by id_equipe,e.nom; 


-- CLASSEMENT JOUEUR DANS EQUIPE            
select 
    id_coureur,
    sum(temps_effectue_mm),
    coureur_nom,
    DENSE_RANK() OVER (ORDER BY sum(temps_effectue_mm) DESC) AS place 
from 
    v_temps_coureur_etape_point 
        where id_equipe=1 
        group by id_coureur,coureur_nom;


select 
    id_coureur,
    sum(temps_effectue_mm),
    coureur_nom,
    DENSE_RANK() OVER (ORDER BY sum(temps_effectue_mm) DESC) AS place 
from 
    v_temps_coureur_etape_point 
        where id_equipe=1 
        group by id_coureur,coureur_nom;

create table import_resultat(
    etape_rang int,
    numero_dossard int,
    nom varchar(50),	
    genre varchar(20),	
    date_naissance date,
    equipe varchar(50),
    arrivee TIMESTAMP
);

