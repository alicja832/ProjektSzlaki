 <p>Wszystkie połączenia między schroniskami/szczytami</p>
<table border="1">
 <?php
   echo '<tr class="pierwszy"><td>punkt pierwszy</td><td>punkt drugi</td><td>szlak</td><td>czas w gore</td><td>czas w dół</td><td>odleglość(km)</td><td>Usuń</td></tr>' ;
    if ($data) { 
       foreach ( $data as $row ) { 
       echo '<tr></td><td>'.$row['nazwa_1'].'</td><td>'.$row['nazwa_2'].'</td>
       <td>'.$row['szlak'].'</td>
       <td>'.$row['czas_do'].'</td>
       <td>'.$row['czas_z'].'</td>
       <td>'.$row['dlugosc'].'</td>
       <td><input type="checkbox" id="id" value='.$row['id1'].'/'.$row['id2'].'/>&nbsp;</td></tr>';
    }}
 ?> 
   <tr><td></td><td></td><td></td><td></td><td></td><td></td><td> <span id="data"><input type="button" value="Usuń" onclick="usunpolaczenie()"/></span> </td></tr>
</table> 
<section class="response"> <span id="response3"></span></section>
<button class="menus"><a href="index.php?sub=Baza&action=dodajPolaczenie">Dodaj niestandardowe połączenie</a></button>
<br>
<div class="image2">
 <img src="mapa.png" width="800" height="300"></img>
 </div>