<?php
 
namespace baza   ;
 
use appl\ { View, Controller } ;

/**
 * klasa, w ktorej sformułowane są funkcje wysylajace zadanie poszczegolnych
 * danych do Modelu
 * lub bezposrednio do VIEW o wyslanie do klienta odpowiedniego szablonu
 */
class Baza extends Controller 
{
 
    protected $layout ;
    protected $model ;
 
    function __construct() {
	    
       session_start();   
       parent::__construct();
       $this->layout = new View('main') ;
       $this->layout->css = $this->css ;
       $this->layout->color=$this->color;
       $this->layout->title="";

     	   if(isset($_SESSION['user_id']))
         {
	         $this->layout->menu = file_get_contents ('template/menulog.tpl') ;
	      }
         else
         {
	         $this->layout->menu = file_get_contents ('template/menu.tpl') ;
         }
       
         $this->model  = new Model() ;
    }
    /**
     * view - formularz
     */
    function add_dane() {

       $this->layout->title = 'Podaj login i hasło' ;
       $this->view = new View('form') ;
       $this->layout->content = $this->view ;
        return $this->layout ;
   }
   /**
    * rejestracja
    */
   function rejestruj() 
   {
      $data = $_POST['data'] ;
      $obj  = json_decode($data) ;
      if (isset($obj->login) and isset($obj->haslo)) {      
         $response = $this->model->rejestruj($obj);
      }
      if($response!='false')
      {
         if($response=='istnieje')
            return "Użytkownik o podanym loginie i haśle już istnieje.";
         $resp = $this->model->loguj($obj) ;
         if(!empty($response))
            {       
               $id=$resp['uzytkownik_id'];
               $_SESSION['user_id'] = $id;
            }
         }
      return ( $response ? "Zarejestrowano użytkownika" : "Blad " ) ;

   }
   /**
    * logwonie uzytkownika
    * @return [type]
    */
   public function loguj() {
         
      $data = $_POST['data'];
      $obj  = json_decode($data);
      if ( isset($obj->login) and isset($obj->haslo)) {
         $response = $this->model->loguj($obj) ;
         if(!empty($response))
         {       
            $id=$response['uzytkownik_id'];
            $_SESSION['user_id'] = $id;
           
            return "Zalogowano użytkownika";
         }
      }
      return "Blad logowania";
   }
     /**
      * @return [type] dodawanie schroniska do bazy
      */
     public function dodajSchr()
      {
         $data = $_POST['data'];
         $obj  = json_decode($data);
         if (isset($obj->nazwa) and isset($obj->wysokosc)) {      
             $response = $this->model->dodajSchr($obj);
         }
         return  ( $response ? "Dodano schronisko" : "Blad" ) ; 
      }
      /**
       * @return [type] dodawanie szlaku do bazy
       */
      public function dodajSzlak()
      {
         $data = $_POST['data'];
         $obj  = json_decode($data);
         
         if (isset($obj->szczyt) and isset($obj->schronisko) and isset($obj->kolor) and isset($obj->czas_do) and isset($obj->czas_z) and isset($obj->dlugosc)) 
         {     
             $response = $this->model->dodajSzlak($obj);
         }
         return ( $response ) ;
      }
       /**
       * dodawanie szczytu
       * @return [type]
       */
      public function dodajSzczyt()
      {
         $data = $_POST['data'];
         $obj  = json_decode($data);
         if (isset($obj->nazwa) and isset($obj->wysokosc)) {      
            $response = $this->model->dodajSzczyt($obj);
         }

         return ( $response ? "Dodano Szczyt" : "Blad " ) ;
      }
      /**
       * @return [type] wysylanie formularza z dostepnymi punktami i szlakami ktore moga je polaczyc
       */
      public function dodajPolaczenie()
      {
         $this->view = new View('polaczenie');
         $this->view->data = $this->model->listaPunktyAll();
         $this->layout->content = $this->view; 
         $this->view = new View('szlakpolaczenie');
         $this->view->data = $this->model->listaSzlak();
         $this->layout->content2 = $this->view; 
         return $this->layout;
      }
      
      /**
       * Wypisywanie danych z utworzonych widokow dotyczacych rankingow
       * @return [type]
       */
      public function ranking()
      {
         $this->view = new View('ranking1');
         $this->view->data = $this->model->Ranking1();
         $this->view->info = 'Lista użytkownikow trasami o łącznie największej długości:';
         $this->layout->content = $this->view; 
         $this->view = new View('ranking2');
         $this->view->data = $this->model->Ranking2();
         $this->view->info = 'Lista użytkownikow z największą liczbą zaplanowanych tras:';
         $this->layout->content2 = $this->view; 
         $this->view = new View('ranking3');
         $this->view->data = $this->model->Ranking3();
         $this->view->info = 'Lista miejsc najczęściej uczęszczanych:';
         $this->layout->content3 = $this->view; 
         return $this->layout;
      }

      /**
       * 
       * @return [type] 
       */
      public function zobaczPolaczenie()
      {
         $this->layout->title = 'Lista połączeń:';
         $this->view = new View('listapolaczen');
         $this->view->data = $this->model->zobaczPolaczenie();
         $this->layout->content = $this->view; 
         return $this->layout;
      }
      

      /**
       * dodawanie polaczenia miedzy wybranymi punktami
       * @return [type]
       */
      public function dodajPolaczenie_wyk()
      {
         $data = $_POST['data'];
         $obj  =  json_decode($data);
         $response='false';
       
        
            if (isset($obj->punkt1_id))
            {
               $response = $this->model->dodajPolaczenie($obj);     
            } 
       
         
         return ( $response ? "Dodano Polaczenie" : "Blad" ) ;
      }

      /**
       * usuwanie wybranego polaczenia 
       * @return [type]
       */
      public function usunPolaczenie()
      {
         $data = $_POST['data'];
         $obj  =  json_decode($data,true);
         $elements=array();
         foreach($obj as $id)
         {
            $parts = explode("/",$id);
            foreach($parts as $el){
               $el=intval($el);
               if($el)
                 array_push($elements,$el);
            }
         }
         $response=$this->model->usunPolaczenie($elements);    
         return ( $response ? "Usunięto" : "Blad " ) ;
      }

      /**
       * usuwanie schroniska
       * @return [type]
       */
      public function usunSchr()
      {
         $data = $_POST['data'];
         $obj  =  json_decode($data,true);
         foreach($obj as $id)
         {
            $parts = explode("/",$id);
            foreach($parts as $el){
               $el=intval($el);
               $response = $this->model->usunSchr($el);
            }
         }
         return ( $response ? "Usunieto" : "Blad " ) ;
      }

      /**
       * usuwanie szczytu
       * @return [type]
       */
      public function usunSzczyt()
      {
         $data = $_POST['data'];
         $obj  =  json_decode($data,true);
         foreach($obj as $id)
         {
            $parts = explode("/",$id);
            foreach($parts as $el){
               $el=intval($el);
               $response = $this->model->usunSzczyt($el);
            }
         }
         return ( $response ? "Usunieto" : "Blad " ) ;
      }
      /**
       * usuwanie wybranego szlaku
       * @return [type]
       */
      public function usunSzlak()
      {
         $data = $_POST['data'];
         $obj  =  json_decode($data,true);
         foreach($obj as $id)
         {
            $parts = explode("/",$id);
            foreach($parts as $el){
               $el=intval($el);
               $response = $this->model->usunSzlak($el);
            }
         }
         return ( $response ? "Usunieto" : "Blad " );
      }
      /**
       * usuwanie wybranej trasy
       * @return [type]
       */
      public function usunTrase()
      {
         $data = $_POST['data'];
         $obj  =  json_decode($data,true);
         foreach($obj as $id)
         {
            $parts = explode("/",$id);
            foreach($parts as $el){
               $el=intval($el);
               $response = $this->model->usunTrase($el);
            }
         }
         return ( $response ? "Usunieto" : "Blad " );
      }

   /**
    * wysylanie tabeli z punktami w wybranej trasie i polaczeniami w niej
    * @return [type]
    */
   public function zobaczTrase()
      {
         $layout = new View('mainplain');
         $this->view=new View('punkty');
         $data = $_POST['data'];
         $obj  =  json_decode($data);
         if(isset($obj->trasa_id))
         {
            $this->view->data= $this->model->zobaczTrase($obj);
            $layout->header="Trasa nr: ".$obj->trasa_id;
         }
         $layout->content = $this->view; 
         if(isset($obj->trasa_id))
         {
            $this->view=new View('polaczeniatrasa');
            $this->view->data = $this->model->polaczeniaWTrasie($obj);
            $this->view->info = 'Połączenia między kolejnymi punktami';
            $layout->content2 = $this->view; 
         }
        
         return $layout;
      }   
     
    

 
    /**
     * lista schronisk
     * @return [type]
     */
    function listaSchr() {   
       
       $this->layout->title = 'Lista schronisk:';
       $this->view = new View('listaschr');
       $this->view->data = $this->model->listaSchr();
       $this->layout->content = $this->view; 
       return $this->layout;
   }
   /**
    * lista szlakow
    * @return [type]
    */
   function listaSzlak() {   
       
      $this->layout->title = 'Lista szlaków:';
      $this->view = new View('listaszlak');
      $this->view->data = $this->model->listaSzlak();
      $this->layout->content = $this->view; 
      $this->view = new View('listapolaczen');
      $this->view->data = $this->model->zobaczPolaczenie();
      $this->layout->content2 = $this->view; 
      return $this->layout;
  }
  /**
   * lista szczytow
   * @return [type]
   */
  function listaSzczyt() {   
       
   $this->layout->title = 'Lista szczytów';
   $this->view = new View('listaszczyt');
   $this->view->data = $this->model->listaSzczyt();
   $this->layout->content = $this->view; 
   return $this->layout;
}

/**
 * funkcja wywolywana zaraz po wybraniu opcji stworz trase - wstawianie do bazy nowej trasy
 * ustawianie id tworzonej aktualnie trasy 
 * @return [type]
 */
function trasa() {   
       
   $this->layout->title = '';
   $this->view = new View('trasa');
   $this->layout->content = $this->view; 
   return $this->layout;
}

/**
 * funkcja wywolywana gdy uzytkownik chce zobaczyc mozliwe szlaki prowadzace na dany szczyt
 * @return [type]
 */
function szlaki() {   
       
   $this->layout->title = '';
   $this->view = new View('wyborszczyt');
   $this->view->data = $this->model->listaSzczyt();
   $this->layout->content = $this->view; 
   return $this->layout;
}

/**
 * wybieranie punktow w trasie
 * @return [type]
 */
function trasa_punkty() {   
   
   if(isset($_SESSION['user_id']))
   {
      $this->model->stworz_trase($_SESSION['user_id']);
   }
   else
   {
      $this->model->stworz_trase(NULL);
   }
  
   $this->layout->title = 'Wybierz punkt startowy';
   $this->view = new View('wyborpunktow');
   $this->view->data = $this->model->listaPunktyAll();
   $this->layout->content = $this->view; 
   return $this->layout;
}

/**
 * funkcja odbierajaca dane z formularza wyboru szczytu 
 * uzytkownik ma zobaczyc wszyskie szlaki prowadzace na ten szczyt
  * @return [type]
 */
function wyborszczyt(){

   $this->view=new View('szlaki');
   $data = $_POST['data'];
   
   $layout = new View('mainplain');
  
         $obj  =  json_decode($data,true);
         foreach($obj as $id)
         {
            $parts = explode("/",$id);
            foreach($parts as $el){
               $el=intval($el);
              
               if($el)
               {
                 
                  $this->view->data = $this->model->listaSzlakWyb($el);
               }
            }
         }
   $layout->content2= $this->view;
   //$this->layout->content= $this->view;
   return $layout;
}	
/**
 * funkcja odbierajaca z formularza kolejne punkty do dodania do trasy
 * @return [type]
 */
function wyborpunkt(){

   $this->layout->title='Kolejne punkty, do których możesz dojść:';
   $this->view=new View('wyborpunktow');
   $data = $_POST['data'];
         $obj  =  json_decode($data,true);
        
         foreach($obj as $id)
         {
            $parts = explode("/",$id);
          
            foreach($parts as $el){
               $el=intval($el);
            
               if($el)
               {
                  
                  $response = $this->model->dodaj_pkt($el);
                 
               }
            }
         }
         if($response != 'false')
            return "<button><a href='index.php?sub=Baza&action=punkt_dol'>Dodaj kolejne punkty</a></button><br />"; 
         
         return ( "<button>Blad</button> " ) ;
}	
/**
 * kolejne punkty ktore uzytkownik moze dodac do tworzonej aktualnie trasy
 * @return [type]
 */
function punkt_dol(){

   $this->layout->title='Nie ma żadnych punktów, do których możesz dojść z poprzedniego punktu:';
  
   
   if(!empty($this->model->listapkt_gora()))
   {
      $this->layout->title='Kolejne punkty, które możesz dodać:';
      $this->view=new View('wyborpunktow');
      $this->view->dol=0;
      $this->view->data = $this->model->listapkt_gora();
      $this->view->info = 'Punkty, znajdujace się wyżej:';
      $this->layout->content= $this->view;
   }
   if(!empty( $this->model->listapkt_dol()))
   {
      $this->layout->title='Kolejne punkty, które możesz dodać:';
      $this->view=new View('wyborpunktow');
      $this->view->data = $this->model->listapkt_dol();
      $this->view->dol=1;
      $this->view->info = 'Punkty, znajdujące się niżej:';
      $this->view->data = $this->model->listapkt_dol();
      $this->layout->content2= $this->view;
   }
   else{
      $this->view->dol=1;
   }
  
   return $this->layout;
}
   /**
    * funkcja ktora ma role podsumowania trasy
    * @return [type]
    */
   function zakoncztrase()
   {
      $this->layout->title='Twoja trasa:';
      $this->view=new View('infotrasa');
      $this->view->data = $this->model->zakoncztrase();
      $this->layout->content= $this->view;
   
      $this->view=new View('punkty');
      $this->view->info=1;
      $this->view->data = $this->model->punktywtrasie();
      $this->layout->content2= $this->view;

      $this->view=new View('polaczeniatrasa');
      $arg='false';
      $this->view->data = $this->model->polaczeniaWTrasie($arg);
      $this->view->info = 'Połączenia między kolejnymi punktami';
      $this->layout->content3 = $this->view; 
      
      If(!isset($_SESSION['user_id']))
         $this->model->usunTrase_aktualna($arg);
      return $this->layout;
   }
 
   /**
    * wypisywanie tras uzytkownika ktory jest aktualnie zalogowany
    * @return [type]
    */
   function mojeTrasy()
   {
      $this->layout->title='Twoje trasy:';
      $this->view=new View('mojetrasy');
      $this->view->data = $this->model->mojeTrasy($_SESSION['user_id']);
      $this->layout->content= $this->view;
      return $this->layout;
   }
   
 
   /**
    * funkcja wysylajaca widok strony glownej: index.php
    * @return [type]
    */
   function index ()  {
	     $this->layout->title='Strona Główna';
        $this->view=new View('glowna');
        $this->layout->content=$this->view;
       		return $this->layout; 
   }
	/**
    * wylogowywanie 
	 * @return [type]
	 */
	function logoutRec()
	{
		unset($_SESSION);
		session_destroy();
      $this->layout->title='Wylogowano';
		return $this->layout;
	}	
 
	
}   
?>
