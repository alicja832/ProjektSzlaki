<table border="1">
 <?php
   echo '<p>Wybierz dwa punkty, które chesz połączyć :</p>';
   echo '<tr class="pierwszy"><td>nazwa punktu</td><td>wysokość</td><td>wybierz</td></tr>' ;
   if ($data) { 
       foreach ( $data as $row ) { 
            echo '<tr><td>'.$row['nazwa_pkt'].'</td><td>'.$row['wysokosc_pkt'].'</td><td><input type="checkbox" id="id" value='.$row['id'].'/>&nbsp;</td></tr>' ;  
   }}
 ?> 
</table> 





