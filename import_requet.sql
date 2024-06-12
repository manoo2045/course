-- Coureur
insert into coureur(nom,numero,genre,date_naissance,id_equipe)
    select ir.nom,
        ir.numero_dossard,
        ir.genre,
        ir.date_naissance,
        e.id 
    from import_resultat ir
        join equipe e on e.nom = ir.equipe group by ir.nom,ir.numero_dossard,ir.genre,ir.date_naissance,e.id;

-- Coureur etape
insert into etape_coureur(id_coureur,id_etape)
    select 
        c.id,
        e.id
    from import_resultat ir
        join etape e on e.rang = ir.etape_rang
        join coureur c on c.nom = ir.nom group by c.id,e.id; 

-- Coureur temps
insert into temps_coureur (id_etape,heure_depart,heure_arrive,id_coureur)
    select 
        e.id,
        e.depart,
        ir.arrivee,
        c.id
    from import_resultat ir
        join etape e on e.rang = ir.etape_rang
        join coureur c on c.nom = ir.nom group by e.id,e.depart,ir.arrivee,c.id;


select * from import_resultat;
select * from etape;
select * from equipe;
select * from coureur;
select * from temps_coureur;



etape_rang	numero dossard	nom	genre	date naissance	equipe	
