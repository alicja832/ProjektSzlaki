/**
 * wysylanie danych pobranych z formalrza rejestracji
 */
function rejestruj()
{
    var login = document.getElementById("login").value ;
    var haslo = document.getElementById("haslo").value ;
    document.getElementById("data").style.display = "none" ;
    json_data = "{\"login\":\"" + login + "\",\"haslo\":\"" + haslo +"\"}" ;
    var msg = "data=" + encodeURIComponent(json_data) ;                                    
    // alert ( '['+msg+']' ) ; 
    url = "index.php?sub=Baza&action=rejestruj" ;
    resp = function(response) {
       document.getElementById("response").innerHTML = response ; 
     }
     xmlhttpPost (url, msg, resp) ;                          
}  
/**
 * wysylanie danych pobranych z formalrza logowania
 */
function log()
{
   var login = document.getElementById("login").value ;
   var haslo = document.getElementById("haslo").value ;
   document.getElementById("data").style.display = "none" ;
   json_data = "{\"login\":\"" + login + "\",\"haslo\":\"" + haslo +"\"}" ;
   var msg = "data=" + encodeURIComponent(json_data) ;                                      
   
   url="index.php?sub=Baza&action=loguj";
   resp = function(response) {
       document.getElementById("response").innerHTML = response ; 
     }
   xmlhttpPost (url, msg, resp) ; 
}
/**
 * wysylanie danych pobranych z formalrza usuwania schroniska
 */
function usunschr()
{
   var selected=[];

   var id = document.querySelectorAll('input[type="checkbox"]:checked');
      
      id.forEach(function(checkbox){
         selected.push(checkbox.value);
   });

   var json_data=JSON.stringify(selected);
   document.getElementById("data").style.display = "none";
   var msg = "data=" + encodeURIComponent(json_data) ;                                     
   
   url = "index.php?sub=Baza&action=usunSchr";
   resp = function(response) {
       document.getElementById("response").innerHTML = response ; 
     }
   xmlhttpPost (url, msg, resp) ;                          
} 
/**
 * wysylanie danych pobranych z formalrza usuwania szlaku
 */
function usunszlak()
{
   var selected=[];
   var id = document.querySelectorAll('input[type="checkbox"]:checked');
      
      id.forEach(function(checkbox){
         selected.push(checkbox.value);
   });
   var json_data=JSON.stringify(selected);
   console.log(json_data);
   document.getElementById("data").style.display = "none";
   var msg = "data=" + encodeURIComponent(json_data) ;                                    
    
   url = "index.php?sub=Baza&action=usunSzlak" ;
   resp = function(response) {
       document.getElementById("response").innerHTML = response ; 
     }
   xmlhttpPost (url, msg, resp) ;                          
}
/**
 * wysylanie danych pobranych z formalrza wyboru szczytu do wylistowania szlakow
 */
function wyborszczyt()
{
   var selected=[];
   var id = document.querySelectorAll('input[type="checkbox"]:checked');
      
      id.forEach(function(checkbox){
         selected.push(checkbox.value);
   });
   var json_data=JSON.stringify(selected);
   console.log(json_data);
   document.getElementById("data").style.display = "none";
   var msg = "data=" + encodeURIComponent(json_data) ;                                    
    // alert ( '['+msg+']' ) ; 
   url = "index.php?sub=Baza&action=wyborszczyt" ;
   resp = function(response) {
       document.getElementById("response").innerHTML = response ; 
     }
   xmlhttpPost (url, msg, resp) ;                          
} 
/**
 * wysylanie danych pobranych z formalrza wyboru punktow do trasy
 *  */
function wyborpunktu()
{
   var selected=[];
   var id = document.querySelectorAll('input[type="checkbox"]:checked');
      id.forEach(function(checkbox){
         selected.push(checkbox.value);
   });
   var json_data=JSON.stringify(selected);
   console.log(json_data);
   document.getElementById("data").style.display = "none";
   var msg = "data=" + encodeURIComponent(json_data) ;                                    
   
   url = "index.php?sub=Baza&action=wyborpunkt";
   resp = function(response) {
       document.getElementById("response").innerHTML = response ; 
     }
   xmlhttpPost (url, msg, resp) ;  
}
/**
 * wysylanie danych pobranych z formalrza usuwania szczytu
 *  */
function usunszczyt()
{
   var selected=[];
   var id = document.querySelectorAll('input[type="checkbox"]:checked');
      
      id.forEach(function(checkbox){
         selected.push(checkbox.value);
   });
   var json_data=JSON.stringify(selected);

   console.log(json_data);
   document.getElementById("data").style.display = "none";
   var msg = "data=" + encodeURIComponent(json_data) ;                                    
    // alert ( '['+msg+']' ) ; 
   url = "index.php?sub=Baza&action=usunSzczyt" ;
   resp = function(response) {
       document.getElementById("response").innerHTML = response ; 
     }
   xmlhttpPost (url, msg, resp) ;                          
} 


/**
 * wysylanie danych pobranych z formalrza usuwania trasy
 *  */
function usuntrase()
{
   var selected=[];
   var id = document.querySelectorAll('input[type="checkbox"]:checked');
      
      id.forEach(function(checkbox){
         selected.push(checkbox.value);
   });
   var json_data=JSON.stringify(selected);

   console.log(json_data);
   document.getElementById("data").style.display = "none";
   var msg = "data=" + encodeURIComponent(json_data) ;                                    
    // alert ( '['+msg+']' ) ; 
   url = "index.php?sub=Baza&action=usunTrase" ;
   resp = function(response) {
       document.getElementById("response").innerHTML = response ; 
     }
   xmlhttpPost (url, msg, resp) ;                          
}
// wysylanie danych z formularza wyboru trasy
function zobacztrase(event)
{
  
   var trasa_id=event.target.id;
   console.log(trasa_id);
   document.getElementById("data").style.display = "none";
   json_data = "{\"trasa_id\":\"" + trasa_id +"\"}" ;
   var msg = "data=" + encodeURIComponent(json_data) ;                                    
   // alert ( '['+msg+']' ) ;
   console.log(json_data); 
   url = "index.php?sub=Baza&action=zobaczTrase" ;
   resp = function(response) {
      document.getElementById("response").innerHTML = response ; 
    }
    xmlhttpPost (url, msg, resp) ;                     
}  
/**
* wysylanie danych pobranych z formalrza dodawania schroniska
*  */
function dodajschr()
{
    var nazwa = document.getElementById("nazwa").value ;
    var wysokosc = parseInt(document.getElementById("wysokosc").value) ;
    document.getElementById("data").style.display = "none";
    json_data = "{\"nazwa\":\"" + nazwa + "\",\"wysokosc\":\"" + wysokosc +"\"}" ;
    var msg = "data=" + encodeURIComponent(json_data) ;                                    
    // alert ( '['+msg+']' ) ;
    console.log(json_data); 
    url = "index.php?sub=Baza&action=dodajSchr" ;
    resp = function(response) {
       document.getElementById("response2").innerHTML = response ; 
     }
     xmlhttpPost (url, msg, resp) ;                          
} 
/**
 * wysylanie danych pobranych z formalrza usuwania polaczenia
 *  */
function usunpolaczenie()
{
   var selected=[];
   var id = document.querySelectorAll('input[type="checkbox"]:checked');
      
      id.forEach(function(checkbox){
         selected.push(checkbox.value);
   });
   var json_data=JSON.stringify(selected);

   console.log(json_data);
   document.getElementById("data").style.display = "none";
   var msg = "data=" + encodeURIComponent(json_data) ;                                   
   url = "index.php?sub=Baza&action=usunPolaczenie" ;
   resp = function(response) {
       document.getElementById("response3").innerHTML = response ; 
     }
   xmlhttpPost (url, msg, resp) ;
}
/**
 * wysylanie danych pobranych z formalrza dodawania polaczen
 *  */
function dodaj_polaczenie()
{
   var czas_w_gore = document.getElementById("czas_w_gore").value ;
   var czas_w_dol = document.getElementById("czas_w_dol").value;
   var odleglosc = document.getElementById("odleglosc").value;

   var selected=[];
   var id = document.querySelectorAll('input[type="checkbox"]:checked');
      
      id.forEach(function(checkbox){
         selected.push(checkbox.value);
   });

   var punkt1_id=selected[0].split('/')[0];
   var punkt2_id=selected[1].split('/')[0];
   var szlak_id=selected[2].split('/')[0];

    document.getElementById("data").style.display = "none";
    json_data = "{\"punkt1_id\":\"" + punkt1_id+ "\",\"punkt2_id\":\"" + punkt2_id 
      +"\",\"szlak_id\":\"" + szlak_id +"\",\"czas_w_gore\":\"" 
      +czas_w_gore+"\",\"czas_w_dol\":\"" + czas_w_dol+"\",\"odleglosc\":\"" +odleglosc+"\"}" ;
    var msg = "data=" + encodeURIComponent(json_data);                                    
    console.log(json_data); 
    url = "index.php?sub=Baza&action=dodajPolaczenie_wyk" ;
    resp = function(response) {
       document.getElementById("response").innerHTML = response ; 
     }
     xmlhttpPost (url, msg, resp) ; 

}
/**
 * wysylanie danych pobranych z formalrza dodawania szlaku
 *  */
function dodajszlak()
{
    var szczyt = document.getElementById("szczyt").value ;
    var schronisko = document.getElementById("schronisko").value ;
    var kolor = document.getElementById("kolor").value ;
    var czas_do = document.getElementById("czas_do").value ;
    var czas_z = document.getElementById("czas_z").value ;
    var dlugosc = document.getElementById("dlugosc").value ;

    document.getElementById("data").style.display = "none";
    json_data = "{\"szczyt\":\"" + szczyt + "\",\"schronisko\":\"" + schronisko + "\",\"kolor\":\""+ kolor+ "\",\"czas_do\":\""
      +czas_do+"\",\"czas_z\":\""+czas_z+"\",\"dlugosc\":\"" +dlugosc+ "\"}" ;
   
    var msg = "data=" + encodeURIComponent(json_data) ;                                    
    url = "index.php?sub=Baza&action=dodajSzlak" ;
    resp = function(response) { 
       document.getElementById("response2").innerHTML = response ; 
     }
     xmlhttpPost (url, msg, resp) ;                          
} 
/**
 * wysylanie danych pobranych z formalrza dodawania szczytu
 *  */
function dodajszczyt()
{
    var nazwa = document.getElementById("nazwa").value ;
    var wysokosc = parseInt(document.getElementById("wysokosc").value) ;
    document.getElementById("data").style.display = "none";
    json_data = "{\"nazwa\":\"" + nazwa + "\",\"wysokosc\":\"" + wysokosc +"\"}" ;
    var msg = "data=" + encodeURIComponent(json_data) ;                                    
    url = "index.php?sub=Baza&action=dodajSzczyt" ;
    resp = function(response) {
         document.getElementById("response2").innerHTML = response ; 
     }
     xmlhttpPost (url, msg, resp) ;                          
}   

/**
 * @param mixed strURL
 * @param mixed mess
 * @param mixed respFunc
 * procedura wysylania danych XHR
 * @return [type]
 */
function xmlhttpPost(strURL, mess, respFunc) {
   var xhr = new XMLHttpRequest();;
   xhr.open('POST', strURL);
   xhr.addEventListener("load", e => {
     if ( xhr.status == 200 )  {
        respFunc ( xhr.response ) ;
     }
   })
   xhr.setRequestHeader("X-Requested-With","XMLHttpRequest");
   xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded; ");
   xhr.send(mess);        
}

