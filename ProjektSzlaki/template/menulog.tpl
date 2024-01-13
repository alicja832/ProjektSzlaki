<!DOCTYPE html>
 
<html>
   <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title>Simple MVC</title>
    <?php echo $css ; ?>   
    <script  src="js/baza.js"></script> 
    </head>
    <body>   
    <section>
     
       
        <nav>
            <ul>
              <button class="menus"><a href="index.php">Główna</a></li>
              <button class="menus"><a href="index.php?sub=Baza&action=listaSchr">Schroniska</a></li>
              <button class="menus"><a href="index.php?sub=Baza&action=listaSzlak">Szlaki</a></li>
              <button class="menus"><a href="index.php?sub=Baza&action=listaSzczyt">Szczyty</a></li>
              <button class="menus"><a href="index.php?sub=Baza&action=trasa">Trasy</a></li>
              <button class="menus"><a href="index.php?sub=Baza&action=ranking">Rankingi</a></li>
              <button class="menus"><a href="index.php?sub=Baza&action=mojeTrasy">Moje trasy</a></li>
              <button class="menus"><a href="index.php?sub=Baza&action=logoutRec" >Wyloguj się</a></button><br /> 
            </ul>
        </nav>

    </section>
      <section class="main">
    
            <div class="logo"><img src="mountains.png" alt="Logo Strony" /></div>
            <h1>Szlaki Górskie</h1>
      </section>
    </body>
</html>