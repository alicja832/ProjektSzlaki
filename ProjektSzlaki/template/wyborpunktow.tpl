<table border="1">
   <header><?php echo $info;?></header>
 <?php
   if ($data) { 
   echo '<tr class="pierwszy"><td>nazwa punktu</td><td>wysokość</td><td>wybierz</td></tr>' ;
   
      foreach ( $data as $row ) { 
      echo '<tr><td>'.$row['nazwa_pkt'].'</td><td>'.$row['wysokosc_pkt'].'</td><td><input type="checkbox" id="id" value='.$row['id'].'/>&nbsp;</td></tr>' ;
   }}
   else
     echo ' <p> Brak </p> '
      
 ?> 
 </table> 
 <?php
    if($data)
    echo '<section id="response"><tr><td></td><td><span id="data"><input type="button" value="Dodaj do trasy" onclick="wyborpunktu()" /></span></td>
          <td><span id="response"></span></td></tr></section>';
  if($dol)
    echo ' <button><a href="index.php?sub=Baza&action=zakoncztrase" >Zakończ</a></button><br />';
?>