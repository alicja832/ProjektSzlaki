<?php
 
namespace baza ;
use PDO ;
 
class Model 
{  
   protected static $db ;
   private $sth ;
   private $id_trasa;
  
   function __construct()
   {
    try{
    $db = new PDO("pgsql:host='flora.db.elephantsql.com' port='5432'
        user='*****' password='*****' dbname='****'"); 
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        self::$db = $db; 
    }
    catch(PDOException $e)
    {
      echo "An error occurred.\n";
      exit;
    }
  }
  
   /**
    * @return [type] zwraca wszystkie schroniska zawarte w bazie danych
    */
   public function listaSchr()
   {
      $sql = "SELECT * FROM ap_szlaki.schronisko";
      $this->sth = self::$db->query($sql);
      $result = $this->sth->fetchAll() ;
      return $result ;
   }
   /**
    * @return [type] zwraca wszystkie szlaki zawarte w bazie danych
    */
   public function listaSzlak()
   {
    $sql = "SELECT szlak_id,s.nazwa as szczyt, schr.nazwa as schronisko, kolor, czas_w_gore,czas_w_dol, dlugosc 
    FROM (ap_szlaki.szlak join ap_szlaki.szczyt s using(szczyt_id))
    join ap_szlaki.schronisko schr using(schronisko_id)";
     
    $this->sth = self::$db->query($sql);
    $result = $this->sth->fetchAll() ;
    return $result ;
   }
    /**
    * @return [type] zwraca wszystkie szczyty zawarte w bazie danych
    */
   public function listaSzczyt()
   {
     $sql = "SELECT * FROM ap_szlaki.szczyt";
     $this->sth = self::$db->query($sql);
     $result = $this->sth->fetchAll() ;
     return $result ;
   }
   /**
    * @return [type] zwraca wszystkie polaczenia zawarte miedzy roznymi punktami w bazie danych
    */
   public function zobaczPolaczenie()
   {
      $sql = "SELECT * FROM ap_szlaki.wypisz_polaczenia()";
      $this->sth = self::$db->query($sql);
      $result = $this->sth->fetchAll() ;
      return $result ;
   }
  
   /**
    * @return [type] usuwanie schroniska z bazy danych
    */
   public function usunSchr($obj)
   {
    $this->sth = self::$db->prepare('DELETE FROM ap_szlaki.schronisko WHERE schronisko_id=:id') ;
    $this->sth->bindValue(':id',$obj, PDO::PARAM_INT); 
   
    $resp = ( $this->sth->execute() ? 'true' : 'false' ) ;
    return $resp ; 
   }
   /**
    * @return [type] usuwanie szczytu z bazy danych
    */
   public function usunSzczyt($obj)
   {
    $this->sth = self::$db->prepare('DELETE FROM ap_szlaki.szczyt WHERE szczyt_id=:id') ;
    $this->sth->bindValue(':id',$obj, PDO::PARAM_INT); 
 
    $resp = ($this->sth->execute() ? 'true' : 'false');
    return $resp ; 
   }
   /**
    * @return [type] usuwanie polaczenia z bazy danych
    */
   public function usunPolaczenie($obj)
   {
    $this->sth = self::$db->prepare('DELETE FROM ap_szlaki.punkt_punkt WHERE punkt1_id=:punkt1_id and punkt2_id=:punkt2_id') ;
    $this->sth->bindValue(':punkt1_id',$obj[0], PDO::PARAM_INT); 
    $this->sth->bindValue(':punkt2_id',$obj[1], PDO::PARAM_INT);
    $resp = ($this->sth->execute() ? 'true' : 'false');
    return $resp ; 
   }
   /**
    * @return [type] usuwanie szlalku z bazy danych
    */
   public function usunSzlak($obj)
   {
    $this->sth = self::$db->prepare('DELETE FROM ap_szlaki.szlak WHERE szlak_id=:id') ;
    $this->sth->bindValue(':id',$obj, PDO::PARAM_INT); 
   
    $resp = ($this->sth->execute() ? 'true' : 'false');
    return $resp ; 
   }
   /**
    * @param mixed $obj
    * usuwanie trasy przez zalogowanego uzytkownika
    * @return [type]
    */
   public function usunTrase($obj)
   {
    $this->sth = self::$db->prepare('DELETE FROM ap_szlaki.trasa WHERE trasa_id=:id') ;
    $this->sth->bindValue(':id',$obj, PDO::PARAM_INT); 
   
    $resp = ( $this->sth->execute() ? 'true' : 'false' ) ;
    return $resp ; 

   }
    /**
    * @return [type] dodawanie schroniska do bazy danych
    */
    public function dodajSchr($obj)
    {
     $this->sth = self::$db->prepare('INSERT INTO ap_szlaki.schronisko (nazwa,wysokosc) VALUES ( :nazwa, :wysokosc)') ;
     $this->sth->bindValue(':nazwa',$obj->nazwa,PDO::PARAM_STR); 
     $this->sth->bindValue(':wysokosc',$obj->wysokosc, PDO::PARAM_INT); 
 
     $resp = ( $this->sth->execute() ? 'true' : 'false' ) ;
     return $resp ; 
    }
    /**
    * @return [type] dodawanie szczytu do bazy danych
    */
   public function dodajSzczyt($obj)
   {
    $this->sth = self::$db->prepare('INSERT INTO ap_szlaki.szczyt (nazwa,wysokosc) VALUES ( :nazwa, :wysokosc)') ;
    $this->sth->bindValue(':nazwa',$obj->nazwa,PDO::PARAM_STR); 
    $this->sth->bindValue(':wysokosc',$obj->wysokosc, PDO::PARAM_INT); 
   
    $resp = ( $this->sth->execute() ? 'true' : 'false' ) ;
    return $resp ; 
   }
   /**
    * @return [type] dodawanie szlaku do bazy danych
    */
   public function dodajSzlak($obj)
   {
    $this->sth = self::$db->prepare('SELECT szczyt_id FROM ap_szlaki.szczyt where nazwa=:szczyt');
    $this->sth->bindValue(':szczyt',$obj->szczyt,PDO::PARAM_STR); 
    $this->sth->execute();
    $id_szczyt=$this->sth->fetch();
    if(!$id_szczyt)
      return "Podany szczyt nie jest dostępny";

    $this->sth = self::$db->prepare('SELECT schronisko_id FROM ap_szlaki.schronisko where nazwa=:schronisko');
    $this->sth->bindValue(':schronisko',$obj->schronisko,PDO::PARAM_STR); 
    $this->sth->execute();
    $id_schronisko=$this->sth->fetch();
    if(!$id_schronisko)
      return "Podane schronisko nie jest dostępne";

    $this->sth = self::$db->prepare('INSERT INTO ap_szlaki.szlak (szczyt_id, schronisko_id, kolor, czas_w_gore,czas_w_dol, dlugosc) 
      VALUES(:szczyt,:schronisko,:kolor,:czas_w_gore,:czas_w_dol,:dlugosc)') ;

    $this->sth->bindValue(':szczyt',$id_szczyt['szczyt_id'],PDO::PARAM_INT); 
    $this->sth->bindValue(':schronisko',$id_schronisko['schronisko_id'], PDO::PARAM_INT); 
    $this->sth->bindValue(':kolor',$obj->kolor, PDO::PARAM_STR); 
    $this->sth->bindValue(':czas_w_gore',$obj->czas_do, PDO::PARAM_STR); 
    $this->sth->bindValue(':czas_w_dol',$obj->czas_z, PDO::PARAM_STR); 
    $this->sth->bindValue(':dlugosc',$obj->dlugosc, PDO::PARAM_INT); 
   
    $resp = ( $this->sth->execute() ? "Dodano szlak" : "Błąd" ) ;
    return $resp ; 
   }
   /**
    * @return [type] dodawanie polaczenia do bazy danych
    */
   public function dodajPolaczenie($obj)
   {
    $this->sth = self::$db->prepare('INSERT INTO ap_szlaki.punkt_punkt (punkt1_id, punkt2_id,szlak_id,czas_w_gore, czas_w_dol,odleglosc) 
    VALUES(:punkt1_id,:punkt2_id,:szlak_id,:czas_w_gore,:czas_w_dol,:odleglosc)') ;

    $this->sth->bindValue(':punkt1_id',$obj->punkt1_id,PDO::PARAM_INT); 
    $this->sth->bindValue(':punkt2_id',$obj->punkt2_id, PDO::PARAM_INT); 
    $this->sth->bindValue(':szlak_id',$obj->szlak_id, PDO::PARAM_INT); 
    $this->sth->bindValue(':czas_w_gore',$obj->czas_w_gore, PDO::PARAM_STR); 
    $this->sth->bindValue(':czas_w_dol',$obj->czas_w_dol, PDO::PARAM_STR); 
    $this->sth->bindValue(':odleglosc',$obj->odleglosc, PDO::PARAM_INT); 
   
    $resp = ( $this->sth->execute() ? 'true' : 'false' ) ;
    return $resp ; 
   }
   
  
   /**
    * @param mixed $obj
    * funkcja pobierająca wszyskie szlaki prowadządze na dany wybrany szczyt
    * @return [type]
    */
   public function listaSzlakWyb($obj)
   {
      $sql = "SELECT * FROM ap_szlaki.szlak(:id)";
      $this->sth = self::$db->prepare($sql);
      $this->sth->bindValue(':id',$obj,PDO::PARAM_INT); 
      $this->sth->execute();
      $result = $this->sth->fetchAll() ;
      return $result ;
   }
   /**
    * @return [type] funkcja wypisujaca informacje na temat stworzonej trasy
    */
   public function zakoncztrase()
   {
    $sql = "SELECT trasa_id FROM ap_szlaki.trasa order by trasa_id desc limit 1";
    $this->sth = self::$db->query($sql);
    $result = $this->sth->fetch() ;
    
    $sql = "SELECT odleglosc,czas FROM ap_szlaki.trasa where trasa_id=:trasa_id";
    $this->sth = self::$db->prepare($sql);
    $this->sth->bindValue(':trasa_id',$result['trasa_id'],PDO::PARAM_INT); 
    $this->sth->execute();
    $result = $this->sth->fetchAll() ;
    return $result ;

   }
   
   /**
    * @param mixed $obj - id aktualnie zalogowanego uzytkownika- zapisany w sesji
    * pobieranie informacji o wszystkich utworzonych przez zalogowanego uzytkownika tras
    * @return [type]
    */
   public function mojeTrasy($obj)
   {
    $sql = "SELECT * FROM ap_szlaki.trasa where uzytkownik_id=:uzytkownik_id";
    $this->sth = self::$db->prepare($sql);
    $this->sth->bindValue(':uzytkownik_id',$obj,PDO::PARAM_INT); 
    $this->sth->execute();
    $result = $this->sth->fetchAll() ;
    return $result ;

   }
   
   /**
    * @param mixed $obj
    * pobieranie z bazy danych informacji o danej trasie uzytkownika
    * @return [type]
    */
   public function zobaczTrase($obj)
   {
    $sql = "SELECT * FROM ap_szlaki.punkty_trasa(:trasa_id)";
    $this->sth = self::$db->prepare($sql);
    $this->sth->bindValue(':trasa_id',$obj->trasa_id,PDO::PARAM_INT); 
    $this->sth->execute();
    $result = $this->sth->fetchAll() ;
    return $result ;
   }
   
   /**
    * @return [type] wypisuje wszystkie kolejne punkty w danej trasie
    */
   public function punktywtrasie()
   {
    $sql = "SELECT trasa_id FROM ap_szlaki.trasa order by trasa_id desc limit 1";
    $this->sth = self::$db->prepare($sql);
    $this->sth->execute();
    $result = $this->sth->fetch() ;
    
    $sql = "SELECT * FROM ap_szlaki.punkty_trasa(:trasa_id)";
    $this->sth = self::$db->prepare($sql);
    $this->sth->bindValue(':trasa_id',$result['trasa_id'],PDO::PARAM_INT); 
    $this->sth->execute();
    $result = $this->sth->fetchAll() ;
    return $result ;

   }
   /**
    * @param mixed $id
    * dodawanie punktu do trasy
    * @return [type]
    */
   public function dodaj_pkt($id)
   {
      $sql = "SELECT trasa_id FROM ap_szlaki.trasa order by trasa_id desc limit 1";
      $this->sth = self::$db->query($sql);
      $result = $this->sth->fetch() ;
      error_log($result['trasa_id']);
      $sql = "INSERT INTO ap_szlaki.punkt_trasa(punkt_id,trasa_id)values(:id,:trasa_id)";
      $this->sth = self::$db->prepare($sql);
      $this->sth->bindValue(':id',$id,PDO::PARAM_INT); 
      $this->sth->bindValue(':trasa_id',$result['trasa_id'],PDO::PARAM_INT); 
      error_log('CO JESTTTT');
      $resp = $this->sth->execute() ? 'true' : 'false';
      $errorInfo = $this->sth->errorInfo();
      if ($errorInfo[0] !== '00000') {
      error_log("SQL error: {$errorInfo[2]}");
      error_log('CO JESTTTT');
      return  $resp; 
   }
  }
   /**
    * klient chce stworzyc trase- od razu taka trase dodajemy do bazy danych, aby moc dalej ja modyfikowac
    */
   public function stworz_trase($obj)
   {
      if($obj)
      {
        $sql = "INSERT INTO ap_szlaki.trasa(uzytkownik_id)values(:uzytkownik)";
        $this->sth = self::$db->prepare($sql);
        $this->sth->bindValue(':uzytkownik',$obj,PDO::PARAM_INT); 
        $resp = ( $this->sth->execute() ? 'true' : 'false' );

        return $resp ; 
      }
    
        $sql = "INSERT INTO ap_szlaki.trasa(czas,odleglosc)values('00:00:00',0)";
        error_log("Dodano trasie");
        $this->sth = self::$db->prepare($sql);
        $resp = ( $this->sth->execute() ? 'true' : 'false' ) ;
        return $resp ; 
      
    }
    
   /**
    * @return [type] lista punktow ktore uzytkownik moze dodac do trasy znajdujacych sie wyzej poprzedniego punktu
    */
   public function listapkt_gora()
   {
    $sql = "SELECT trasa_id FROM ap_szlaki.trasa order by trasa_id desc limit 1";
    $this->sth = self::$db->query($sql);
    $result = $this->sth->fetch() ;
    
    $sql = "SELECT * FROM ap_szlaki.kolejny_pkt_gora(:id)";
    $this->sth = self::$db->prepare($sql);
    $this->sth->bindValue(':id',$result['trasa_id'],PDO::PARAM_INT); 
    $this->sth->execute();
    $result = $this->sth->fetchAll();
    return $result ;
   }
    /**
    * @return [type] lista punktow ktore uzytkownik moze dodac do trasy znajdujacych sie nizej poprzedniego punktu
    */
   public function listapkt_dol()
   {
    $sql = "SELECT trasa_id FROM ap_szlaki.trasa order by trasa_id desc limit 1";
    $this->sth = self::$db->prepare($sql);
    $this->sth->execute();
    $result = $this->sth->fetch() ;
    
    $sql = "SELECT * FROM ap_szlaki.kolejny_pkt_dol(:id)";
    $this->sth = self::$db->prepare($sql);
    $this->sth->bindValue(':id',$result['trasa_id'],PDO::PARAM_INT); 
    $this->sth->execute();
    $result = $this->sth->fetchAll() ;
    return $result;
   }
   
   /**
    * @param mixed $obj
    * wypisuje polaczenia w danej trasie- ktorymi uzytkownik ma sie poruszac miedzy wybranymi punktami
    * @return [type]
    */
   public function polaczeniaWTrasie($obj)
   {
    if($obj!='false')
     {
      $sql = "SELECT * FROM ap_szlaki.trasa_polaczenia(:trasa_id)";
      $this->sth = self::$db->prepare($sql);
      $this->sth->bindValue(':trasa_id',$obj->trasa_id,PDO::PARAM_STR) ; 
     }
     else{
      $sql = "SELECT trasa_id FROM ap_szlaki.trasa order by trasa_id desc limit 1";
      $this->sth = self::$db->prepare($sql);
      $this->sth->execute();
      $result = $this->sth->fetch() ;

      $sql = "SELECT * FROM ap_szlaki.trasa_polaczenia(:trasa_id)";
      $this->sth = self::$db->prepare($sql);
      $this->sth->bindValue(':trasa_id',$result['trasa_id'],PDO::PARAM_STR) ; 
     }
     $this->sth->execute();
     $result = $this->sth->fetchAll() ;
     return $result ;
   }
   
   public function usunTrase_aktualna()
   {
    $sql = "SELECT trasa_id FROM ap_szlaki.trasa order by trasa_id desc limit 1";
    $this->sth = self::$db->prepare($sql);
    $this->sth->execute();
    $result = $this->sth->fetch() ;

    $this->sth = self::$db->prepare('DELETE FROM ap_szlaki.trasa WHERE trasa_id=:id') ;
    $this->sth->bindValue(':id',$result['trasa_id'], PDO::PARAM_INT); 
   
    $resp = ( $this->sth->execute() ? 'true' : 'false' ) ;
    return $resp ; 
   }
   
   /**
    * @return [type] funkcja zwracajaca dane z widoku ktory zawiera kolejno uzytkownikow o najdluzszych lacznie dlugosci tras
    */
   public function ranking1()
   {
    $sql = "SELECT * FROM ap_szlaki.trasa_odleglosci";
    $this->sth = self::$db->prepare($sql);
    $this->sth->execute();
    $result = $this->sth->fetchAll() ;
    return $result;
   }
   
   /**
    * @return [type] funkcja zwracajaca dane z widoku ktory zawiera kolejno uzytkownikow o najwiekszej ilosci tras
    */
   public function ranking2()
   {
    $sql = "SELECT * FROM ap_szlaki.trasa_ilosci";
    $this->sth = self::$db->prepare($sql);
    $this->sth->execute();
    $result = $this->sth->fetchAll() ;
    return $result;
   }
   
   /**
    * @return [type] funkcja zwracajaca liste punktow , ktore sa najczesciej dodawane przez uzytkownikow do tras- tych zalogowanych jak i nie zalogowanych
    */
   public function ranking3()
   {
    $sql = "SELECT ilosc, (SELECT nazwa_punktu from ap_szlaki.nazwa_punktu(punkt_id)) as nazwa_punktu FROM ap_szlaki.punkty_ilosci";
    $this->sth = self::$db->prepare($sql);
    $this->sth->execute();
    $result = $this->sth->fetchAll() ;
    return $result;
   }
  
   /**
    * @return [type] lista wszystkich punktow- potrzebne przy wyborze punktu startowego
    */
   public function listaPunktyAll()
   {
     $sql = "SELECT * FROM ap_szlaki.wypisz_punkty()";
     $this->sth = self::$db->prepare($sql);
     $this->sth->execute();
     $result = $this->sth->fetchAll() ;
     return $result ;
   }

   /**
    * @param mixed $obj-dane do rejestracji pobrane z formularza
    * rejestrowanie uzytkownika
    * @return [type]
    */
   public function rejestruj($obj)
   {
      $resp='istnieje';
      $this->sth = self::$db->prepare('SELECT * FROM ap_szlaki.Uzytkownik WHERE login= :nazwa and haslo=:haslo') ;
      $this->sth->bindValue(':nazwa',$obj->login,PDO::PARAM_STR); 
      $this->sth->bindValue(':haslo',md5($obj->haslo),PDO::PARAM_STR);
      $this->sth->execute(); 
      if(empty($this->sth->fetchAll()))
      {
        $this->sth = self::$db->prepare('INSERT INTO ap_szlaki.Uzytkownik (login,haslo) VALUES ( :nazwa, :haslo)') ;
        $this->sth->bindValue(':nazwa',$obj->login,PDO::PARAM_STR); 
        $this->sth->bindValue(':haslo',md5($obj->haslo),PDO::PARAM_STR); 
        $resp = ( $this->sth->execute() ? 'true' : 'false' ) ;
      }
      return $resp ; 
   }
   
   /**
    * @param mixed $obj-dane do logowania pobrane z formularza
    * logowanie uzytkownika
    * @return [type]
    */
   public function loguj($obj)
   {
      $this->sth=self::$db->prepare('SELECT * FROM ap_szlaki.Uzytkownik WHERE login= :nazwa and haslo= :haslo' );
      $this->sth->bindValue(':nazwa',$obj->login,PDO::PARAM_STR) ; 
      $this->sth->bindValue(':haslo',md5($obj->haslo),PDO::PARAM_STR) ;
      if($this->sth->execute())
      {
        $this->sth = self::$db->prepare('SELECT * FROM ap_szlaki.Uzytkownik WHERE login= :nazwa and haslo= :haslo' ) ;
        $this->sth->bindValue(':nazwa',$obj->login,PDO::PARAM_STR) ; 
        $this->sth->bindValue(':haslo',md5($obj->haslo),PDO::PARAM_STR) ;
        $this->sth->execute();
        $result = $this->sth->fetch();
      }
      return $result; 
   
  }
  
}
 
?>
