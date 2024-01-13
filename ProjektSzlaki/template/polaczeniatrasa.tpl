<p class="opis"><?php echo $info;?></p>
<table border="1">
 <?php
    if ($data) { 
         echo '<tr class="pierwszy"><td>kolor szlaku</td><td>czas w górę</td><td>czas w dół</td><td>odległość</td></tr>' ;
       foreach ( $data as $row ) { 
      if($row['szlak']!='koniec')
       echo '<tr><td>'.$row['szlak'].'</td><td>'.$row['czas_do'].'</td><td>'.$row['czas_z'].'</td><td>'.$row['dlugosc'].'</td></tr>' ;
    }}
 ?> 
</table> 
<br>