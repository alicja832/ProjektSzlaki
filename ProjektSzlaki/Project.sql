create schema ap_szlaki;

SET search_path to ap_szlaki;

CREATE TABLE schronisko (
              schronisko_id SERIAL,
              nazwa VARCHAR NOT NULL,
              wysokosc INTEGER NOT NULL,
              CONSTRAINT schroniska_pk PRIMARY KEY (schronisko_id)
);

CREATE TABLE szczyt (
              szczyt_id SERIAL,
              nazwa VARCHAR NOT NULL,
              wysokosc INTEGER NOT NULL,
              CONSTRAINT szczyt_pk PRIMARY KEY (szczyt_id)
);

CREATE TABLE szlak (
              szlak_id SERIAL,
              szczyt_id INTEGER NOT NULL,
              schronisko_id INTEGER NOT NULL,
              kolor VARCHAR NOT NULL,
              czas_w_gore TIME NOT NULL,
              czas_w_dol TIME NOT NULL,
              dlugosc FLOAT NOT NULL,
              CONSTRAINT szlaki_pk PRIMARY KEY (szlak_id),
              CONSTRAINT Szczyty_Szlaki_fk FOREIGN KEY(szczyt_id) REFERENCES szczyt(szczyt_id) ON DELETE CASCADE,
              CONSTRAINT Schroniska_Szlaki_fk FOREIGN KEY(schronisko_id) REFERENCES schronisko(schronisko_id) ON DELETE CASCADE
);

CREATE TABLE punkt(
        punkt_id SERIAL,
        szczyt_id INTEGER,
        schronisko_id INTEGER,
        CONSTRAINT punkt__pk PRIMARY KEY (punkt_id),
        CONSTRAINT punkt_szczyt_fk FOREIGN KEY(szczyt_id) REFERENCES szczyt(szczyt_id) ON DELETE CASCADE,
        CONSTRAINT punkt_schronisko_fk FOREIGN KEY(schronisko_id) REFERENCES schronisko(schronisko_id) ON DELETE CASCADE
);


CREATE TABLE  Uzytkownik(
              uzytkownik_id SERIAL,
              haslo VARCHAR not NULL,
              CONSTRAINT uzytkownik_pk PRIMARY KEY (uzytkownik_id)
);



--moze byc dla zalogowanego lub nie
CREATE TABLE trasa(
              trasa_id SERIAL,
              uzytkownik_id INT DEFAULT NULL,
              odleglosc FLOAT DEFAULT 0,
              czas TIME,
              liczba_punktow INT DEFAULT 0,
              CONSTRAINT trasa_pk PRIMARY KEY (trasa_id),
              CONSTRAINT trasa_uzytkownik_fk  FOREIGN KEY(uzytkownik_id) REFERENCES Uzytkownik(uzytkownik_id) ON DELETE CASCADE
);

CREATE TABLE punkt_punkt (
              punkt1_id INTEGER NOT NULL,
              punkt2_id INTEGER NOT NULL,
              szlak_id INTEGER NOT NULL,
              czas_w_gore TIME NOT NULL,
              czas_w_dol TIME NOT NULL,
              odleglosc FLOAT NOT NULL,
              CONSTRAINT punkt_punkt_pk PRIMARY KEY (punkt1_id, punkt2_id),
              CONSTRAINT punkt1_punkt_fk  FOREIGN KEY(punkt1_id) REFERENCES punkt(punkt_id) ON DELETE CASCADE,
              CONSTRAINT punkt2_punkt_fk FOREIGN KEY(punkt2_id) REFERENCES punkt(punkt_id) ON DELETE CASCADE,
              CONSTRAINT punkt_szlaki_fk FOREIGN KEY(szlak_id) REFERENCES szlak(szlak_id) ON DELETE CASCADE
);

CREATE TABLE punkt_trasa (
				punkt_trasa_id serial,
                punkt_id INTEGER NOT NULL,
                trasa_id INTEGER NOT NULL,
                id_punktu_w_trasie INTEGER default 0,
                CONSTRAINT punkt_trasa_pk PRIMARY KEY (punkt_trasa_id),
                constraint punkt_punkt_trasa_fk FOREIGN KEY (punkt_id) REFERENCES punkt(punkt_id) on delete CASCADE,
                constraint trasa_punkt_trasa_fk FOREIGN KEY (trasa_id) REFERENCES trasa(trasa_id) on delete cascade

);
 
 
create or replace function do_wprowadzania_szlakow()
returns trigger as
$$
declare
var_1 record;
var_2 record;
begin
   	select punkt_id into var_1 from ap_szlaki.punkt where schronisko_id=new.schronisko_id;
   	select punkt_id into var_2 from ap_szlaki.punkt where szczyt_id=new.szczyt_id;
   if var_1 is null then
		raise exception 'Podane schronisko nie istnieje.';
   elsif var_2 is null then
   		raise exception 'Podany szczyt nie istnieje.';
   end if;
    --dodajemy glowny szlak rowniez jako polaczenie punktow
   	insert into ap_szlaki.punkt_punkt(punkt1_id,punkt2_id,szlak_id,czas_w_gore,czas_w_dol,odleglosc)
   			values(var_1.punkt_id,var_2.punkt_id,new.szlak_id,new.czas_w_gore,new.czas_w_dol,new.dlugosc);
    return new;
end;
$$
language 'plpgsql';

create trigger szlak_walidacja 
	after insert on szlak 
	for each ROW execute procedure do_wprowadzania_szlakow();


--funkcja, ktora wybiera mozliwe szlaki na wybrany szczyt
create or replace function szlak(id_szczytu integer)
returns table (id_szlaku INTEGER,kolor_szlaku VARCHAR, nazwa_schroniska VARCHAR, czas_wejscia TIME,czas_zejscia TIME,odleglosc FLOAT) as
$$
begin 
  return query
  select szlak_id,kolor,nazwa,czas_w_gore,czas_w_dol, dlugosc
    from ap_szlaki.szlak join ap_szlaki.schronisko 
      using(schronisko_id) 
    where szczyt_id=id_szczytu 
    order by czas_w_gore;
end;
$$
language 'plpgsql';
select *from trasa;
--wprowadza dodawany szczyt do listy punktow 
create or replace function szczyt_wprowadz()
returns trigger as
$$
begin 
  insert into ap_szlaki.punkt(szczyt_id) values(new.szczyt_id);
  return null;
end;
$$
language 'plpgsql';

create trigger wprowadz_szczyt 
	AFTER insert or update on szczyt 
	for each ROW execute procedure szczyt_wprowadz();

--wprowadza dodawane schronisko do listy punktow 
create or replace function schronisko_wprowadz()
returns trigger as 
$$
begin 
  insert into ap_szlaki.punkt(schronisko_id) values (new.schronisko_id);
  return new;
end;
$$
language 'plpgsql';

create trigger wprowadz_schronisko 
	AFTER insert or update on schronisko 
	for each ROW execute procedure schronisko_wprowadz();


create or replace function nazwa_punktu(id INTEGER)
returns table( nazwa_punktu varchar,wysokosc_punktu FLOAT)
as $$
declare
var record;
var_1 record;
begin
	select * into var from ap_szlaki.punkt where punkt_id=id;
	if var.schronisko_id is not null then
		select nazwa,wysokosc 
			into var_1 
			from ap_szlaki.schronisko 
			where schronisko_id=var.schronisko_id; 
	else
		select nazwa,wysokosc 
			into var_1 
			from ap_szlaki.szczyt 
			where szczyt_id=var.szczyt_id; 
	end if;
	nazwa_punktu:=var_1.nazwa;
	wysokosc_punktu:=var_1.wysokosc;
	return next;
end;
$$
language 'plpgsql';

create or replace function wypisz_punkty()
	returns table(id integer, nazwa_pkt varchar, wysokosc_pkt float)
	as $$
	declare 
		var_1 record;
		var_2 record;
	begin
		for var_1 in (select punkt_id from ap_szlaki.punkt)
		loop 
			select * into var_2 from ap_szlaki.nazwa_punktu(var_1.punkt_id);
			id=var_1.punkt_id;
			nazwa_pkt:=var_2.nazwa_punktu;
			wysokosc_pkt:=var_2.wysokosc_punktu;
			return next;
		end loop;
	end;
	$$
	language 'plpgsql';

create or replace function punkty_trasa(trasa integer)
returns table(id integer, nazwa_pkt varchar, wysokosc_pkt float)
	as $$
	declare 
		var_1 record;
		var_2 record;
	begin
		for var_1 in (select punkt_id from ap_szlaki.punkt_trasa where trasa_id=trasa)
		loop 
			select * into var_2 from ap_szlaki.nazwa_punktu(var_1.punkt_id);
				id=var_1.punkt_id;
				nazwa_pkt:=var_2.nazwa_punktu;
				wysokosc_pkt:=var_2.wysokosc_punktu;
			return next;	
		end loop;
	end;
	$$
language 'plpgsql';

create or replace function kolejny_pkt_gora(trasa_nr INTEGER)
returns table(id INTEGER,nazwa_pkt VARCHAR, wysokosc_pkt INTEGER) as
$$
declare
var record;
var_1 record;
var_2 record;

begin 
  --wybieramy punkt ktory zostal ostatnio dodany do trasy
 select punkt_id into var from ap_szlaki.punkt join ap_szlaki.punkt_trasa using (punkt_id) where punkt_trasa.trasa_id=trasa_nr order by 
 	id_punktu_w_trasie desc limit 1;
 
 for var_1 in (select punkt2_id from ap_szlaki.punkt_punkt where punkt1_id=var.punkt_id)
 loop
	select * into var_2 from ap_szlaki.nazwa_punktu(var_1.punkt2_id);
	
	id = var_1.punkt2_id;
	nazwa_pkt:=var_2.nazwa_punktu;
	wysokosc_pkt:=var_2.wysokosc_punktu;
	return next;
 
	end loop;
end;
$$
language 'plpgsql';

--kolejny punkt dol
create or replace function kolejny_pkt_dol(trasa_nr INTEGER)
returns table(id INTEGER,nazwa_pkt VARCHAR, wysokosc_pkt INTEGER) as
$$
declare
var record;
var_1 record;
var_2 record;

begin 
  --wybieramy punkt ktory zostal ostatnio dodany do trasy
 select punkt_id into var 
 	from ap_szlaki.punkt 
 	join ap_szlaki.punkt_trasa 
 	using (punkt_id) 
 	where punkt_trasa.trasa_id=trasa_nr 
 	order by id_punktu_w_trasie desc limit 1;
 
 for var_1 in (select punkt1_id from ap_szlaki.punkt_punkt where punkt2_id=var.punkt_id)
 loop
	
 	select * into var_2 from ap_szlaki.nazwa_punktu(var_1.punkt1_id);
	
	id = var_1.punkt1_id;
	nazwa_pkt:=var_2.nazwa_punktu;
	wysokosc_pkt:=var_2.wysokosc_punktu;
	return next;

	end loop;
end;
$$
language 'plpgsql';


create or replace function do_wprowadzania_punkt_trasa()
returns trigger as
$$
declare 
var record;
var_pkt record;
var_new record;
var_2 record;
var_info1 record;
var_info2 record;
var_czas TIME;
begin 
  --fakty o danej trasie do ktorej dodajemy punkty
  select liczba_punktow, odleglosc, czas 
  into var from ap_szlaki.trasa 
  where trasa_id=new.trasa_id;
 

  if var.liczba_punktow=0 then
    	update ap_szlaki.trasa 
    	set liczba_punktow=1,czas='00:00:00' 
    	where trasa_id=new.trasa_id;
    	new.id_punktu_w_trasie=1;
  		
    return new;
  end if;
 	--punkt aktualny
 	select punkt.punkt_id 
 	into var_new from ap_szlaki.punkt 
 	where punkt_id=new.punkt_id;
    --punkt poprzedni
 	select punkt_id into var_pkt 
 	from ap_szlaki.punkt_trasa 
 	join ap_szlaki.punkt using(punkt_id)
    		where 
    			punkt_trasa.trasa_id = new.trasa_id and 
      		  	punkt_trasa.id_punktu_w_trasie=var.liczba_punktow;
    --interesuje nas wysokosc punktu a i punktu b aby wyznaczyc czas
     	select wysokosc_punktu into var_info1 from ap_szlaki.nazwa_punktu(var_pkt.punkt_id);
     	select wysokosc_punktu into var_info2 from ap_szlaki.nazwa_punktu(var_new.punkt_id); 
	--na pewno wiemy, że istniej polaczenie    
   if exists (select * from ap_szlaki.punkt_punkt pp 
   			  where punkt1_id=var_pkt.punkt_id 
   			  and punkt2_id=new.punkt_id) then
   			  
			    select * into var_2 
			   	from ap_szlaki.punkt_punkt pp 
			   	where punkt1_id=var_pkt.punkt_id 
			   	and punkt2_id=new.punkt_id;
  
  elsif exists(select * from ap_szlaki.punkt_punkt pp 
   			  where punkt2_id=var_pkt.punkt_id 
   			  and punkt1_id=new.punkt_id) THEN
   			  
				select * into var_2 
				from ap_szlaki.punkt_punkt pp 
				where punkt2_id=var_pkt.punkt_id 
				and punkt1_id=new.punkt_id;
  else
  	   raise SQLSTATE '00200' ; 
  end if;
	
   if var_info1.wysokosc_punktu<var_info2.wysokosc_punktu then
		var_czas:=var_2.czas_w_gore;
   else 
		var_czas:=var_2.czas_w_dol;
   end if;
	
	   new.id_punktu_w_trasie = var.liczba_punktow+1;
	   update ap_szlaki.trasa 
	   set odleglosc= odleglosc+var_2.odleglosc,
	   		czas=czas::interval+var_czas::interval, 
	   		liczba_punktow = var.liczba_punktow+1
	   where trasa_id=new.trasa_id;
	  
   return new;
end;
$$
language 'plpgsql';

create trigger polaczenie_walidacja BEFORE insert or update on punkt_trasa 
	for each ROW execute procedure do_wprowadzania_punkt_trasa();


create or replace function wypisz_polaczenia()
returns table(id1 INTEGER, id2 INTEGER,nazwa_1 varchar,nazwa_2 varchar,szlak varchar, czas_do time, czas_z time, dlugosc float) as
$$
declare 
var record;
var_1 record;
var_2 record;
var_3 record;
begin
	
	for var in (select * from ap_szlaki.punkt_punkt)
	loop
		select * into var_1 from ap_szlaki.nazwa_punktu(var.punkt1_id);
		select * into var_3 from ap_szlaki.nazwa_punktu(var.punkt2_id);
		
	select kolor into var_2 from ap_szlaki.szlak where szlak_id=var.szlak_id;
		nazwa_1:=var_1.nazwa_punktu;
		nazwa_2:=var_3.nazwa_punktu;
		szlak:=var_2.kolor;
		czas_do:=var.czas_w_gore;
		czas_z:=var.czas_w_dol;
		dlugosc:=var.odleglosc;
		id1:=var.punkt1_id;
		id2:=var.punkt2_id;
		return next;
	
	end loop;
end;
$$
language 'plpgsql';

create or replace function trasa_polaczenia(trasa INTEGER)
returns table(szlak varchar, czas_do time, czas_z time, dlugosc float) as
$$
declare 
var record;
var_1 record;
var_2 record;
var_3 record;
begin
	for var in (select * from ap_szlaki.punkt_trasa 
				where trasa_id=trasa 
				order by id_punktu_w_trasie)
	loop
		if exists (select * from ap_szlaki.punkt_trasa 
				   where trasa_id=trasa 
				   and id_punktu_w_trasie=(var.id_punktu_w_trasie+1)) then
		
			select * into var_1 
		    from ap_szlaki.punkt_trasa 
		    where trasa_id=trasa and id_punktu_w_trasie=(var.id_punktu_w_trasie+1);
			
			select s.kolor,pp.czas_w_gore,pp.czas_w_dol,pp.odleglosc into var_2 
				from ap_szlaki.punkt_punkt pp join 
					ap_szlaki.szlak s using(szlak_id) where 
					(pp.punkt1_id=var.punkt_id and pp.punkt2_id=var_1.punkt_id) or 
				 	(pp.punkt2_id=var.punkt_id and pp.punkt1_id=var_1.punkt_id);
			szlak:=var_2.kolor;
			czas_do:=var_2.czas_w_gore;
			czas_z:=var_2.czas_w_dol;
			dlugosc:=var_2.odleglosc;
		else
			szlak:='koniec';
			czas_do:='00:00:00';
			czas_z:='00:00:00';
			dlugosc:=0;
		end if;
			return next;
	end loop;
end;
$$
language 'plpgsql';


create view trasa_uzytkownik as 
	SELECT * FROM ap_szlaki.uzytkownik 
	join ap_szlaki.trasa using(uzytkownik_id);

create view trasa_odleglosci as
	SELECT login,SUM(odleglosc) as dlugosc 
	FROM ap_szlaki.trasa_uzytkownik 
		group by uzytkownik_id,login order by dlugosc desc limit 7;
	
create view trasa_ilosci as 
	SELECT login,COUNT(*) as ilosc 
	FROM ap_szlaki.trasa_uzytkownik 
		group by uzytkownik_id,login order by ilosc desc limit 7;
	
create view punkty_ilosci as 
	SELECT punkt_id,COUNT(*) as ilosc 
	FROM ap_szlaki.punkt_trasa 
		group by punkt_id order by ilosc desc limit 7;


	
	
--wprowadzenie danych
insert into szczyt(nazwa,wysokosc) values ('Kozi Wierch',2291);
insert into szczyt(nazwa,wysokosc) values ('Kościelec',2155);
insert into szczyt(nazwa,wysokosc) values ('Rysy',2499);
insert into szczyt(nazwa,wysokosc) values ('Kasprowy Wierch',1987);
insert into szczyt(nazwa,wysokosc) values ('Giewont',1894);
insert into szczyt(nazwa,wysokosc) values ('Szpiglasowy Wierch',2172);
insert into szczyt(nazwa,wysokosc) values ('Trzydniowiański Wierch',1754);
insert into szczyt(nazwa,wysokosc) values ('Granaty',2240);
insert into szczyt(nazwa,wysokosc) values ('Wołowiec',2064);
insert into szczyt(nazwa,wysokosc) values ('Kończyczty Wierch',2002);

insert into schronisko (nazwa,wysokosc) values ('W Dolinie Pięciu Stawów Polskich',1671);
insert into schronisko (nazwa,wysokosc) values ('Murowaniec',1500);
insert into schronisko (nazwa,wysokosc) values ('Kasprowy Wierch',19);
insert into schronisko (nazwa,wysokosc) values ('Na Polanie Chochołowskiej',1146);
insert into schronisko (nazwa,wysokosc) values ('Morskie Oko',1410);
insert into schronisko (nazwa,wysokosc) values ('Na Hali Ornak',1100);


insert into szlak (szczyt_id,schronisko_id,kolor,czas_w_gore,czas_w_dol,dlugosc)
	values (11,7,'czarny','01:50:00','01:20:00',3);
insert into szlak (szczyt_id,schronisko_id,kolor,czas_w_gore,czas_w_dol,dlugosc) 
	values (11,8,'niebieski','03:15:00','02:45:00',2.8);
insert into szlak (szczyt_id,schronisko_id,kolor,czas_w_gore,czas_w_dol,dlugosc) 
	values (14,8,'żółty','01:40:00','01:10:00',4.2);
insert into szlak (szczyt_id,schronisko_id,kolor,czas_w_gore,czas_w_dol,dlugosc) 
	values (12,8,'niebieski','01:50:00','01:20:00',4.0);
insert into szlak (szczyt_id,schronisko_id,kolor,czas_w_gore,czas_w_dol,dlugosc) 
	values (15,12,'zielony','04:30:00','03:43:00',9.6);


insert into szlak (szczyt_id,schronisko_id,kolor,czas_w_gore,czas_w_dol,dlugosc) 
	values (18,8,'zielony','02:25:00','02:00:00',4.8);
insert into szlak (szczyt_id,schronisko_id,kolor,czas_w_gore,czas_w_dol,dlugosc) 
	values (19,10,'zielony','02:40:00','02:00:00',6.2);
insert into szlak (szczyt_id,schronisko_id,kolor,czas_w_gore,czas_w_dol,dlugosc) 
	values (17,10,'czerwony','02:00:00','01:30:00',5.5);
insert into szlak (szczyt_id,schronisko_id,kolor,czas_w_gore,czas_w_dol,dlugosc) 
	values (20,10,'zielony','02:45:00','02:10:00',6.8);
insert into szlak (szczyt_id,schronisko_id,kolor,czas_w_gore,czas_w_dol,dlugosc) 
	values (20,12,'żółty','05:33:00','04:30:00',6.8);


insert into szlak (szczyt_id,schronisko_id,kolor,czas_w_gore,czas_w_dol,dlugosc) 
	values (13,11,'czerwony','03:50:00','03:10:00',5);
insert into szlak (szczyt_id,schronisko_id,kolor,czas_w_gore,czas_w_dol,dlugosc) 
	values (16,11,'żółty','02:30:00','02:00:00',4.2);

insert into punkt_punkt(punkt1_id,punkt2_id,szlak_id,czas_w_gore,czas_w_dol,odleglosc) 
	values(31,27,33,'01:45:00','01:00:00',4);
insert into punkt_punkt(punkt1_id,punkt2_id,szlak_id,czas_w_gore,czas_w_dol,odleglosc) 
	values(23,33,35,'00:45:00','00:40:00',4);

