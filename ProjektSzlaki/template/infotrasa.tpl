<table border="1">
 <?php
   echo '<tr class="pierwszy"><td>odległość</td><td>czas</td></tr>' ;
    if ($data) { 
       foreach ( $data as $row ) { 
       echo '<tr></td><td>'.$row['odleglosc'].'</td><td>'.$row['czas'].'</td></tr>';
    }}
 ?> 
</table> 