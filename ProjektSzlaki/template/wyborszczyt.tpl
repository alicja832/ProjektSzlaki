<table border="1">
 <?php
   echo '<p>Wybierz jaki szczyt chesz zdobyć:</p>';
   echo '<tr class="pierwszy"><td>nazwa szczytu</td><td>wysokość</td><td>wybierz</td></tr>' ;
    if ($data) { 
       foreach ( $data as $row ) { 
       echo '<tr><td>'.$row['nazwa'].'</td><td>'.$row['wysokosc'].'</td><td><input type="checkbox" id="id" value='.$row['szczyt_id'].'/>&nbsp;</td></tr>' ;
    }}
 ?> 
</table> 
<br>
<span id="data"><input type="button" value="Zobacz dostępne szlaki prowadzące do celu" onclick="wyborszczyt()" /></span>
         <span id="response"></span>
<div class="image2">
 <img src="mapa.png" width="800" height="300"></img>
 </div>