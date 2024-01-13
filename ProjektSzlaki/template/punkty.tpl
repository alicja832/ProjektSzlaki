<table border="1">
 <?php
   echo '<p class="opis">Lista kolejnych punktów w twojej trasie:</p>';
 
   if ($data) { 
      if($info)
        echo '<tr class="pierwszy"><td>nazwa punktu</td><td>wysokość</td></tr>';
      else
        echo '<tr class="pierwszy"><td>nazwa punktu</td><td>wysokość</td><td>wybierz</td></tr>' ;
      foreach ( $data as $row ) { 
        if($info)
        {
                echo '<tr><td>'.$row['nazwa_pkt'].'</td><td>'.$row['wysokosc_pkt'].'</td></tr>' ;

        }
        else
        {
                echo '<tr><td>'.$row['nazwa_pkt'].'</td><td>'.$row['wysokosc_pkt'].'</td><td><input type="checkbox" id="id" value='.$row['id'].'/>&nbsp;</td></tr>' ;
        }
   }} 
 ?> 
</table>