<table border="1">
 <?php
     if ($data) { 
   echo '<tr class="pierwszy"><td>id_trasy</td><td>odległość</td><td>czas</td><td>Usuń</td><td></td></tr>' ;
   
       foreach ( $data as $row ) { 
       echo '<tr><td>'.$row['trasa_id'].'</td><td>'.$row['odleglosc'].'</td><td>'.$row['czas'].'</td><td><input type="checkbox" id="id" value='.$row['trasa_id'].'/>&nbsp;</td>
       <td><span id="data"><input type="button" id='.$row['trasa_id'].' value="Zobacz" onclick="zobacztrase(event)"/></span> </td></tr>';
    }
    echo '<tr><td></td><td></td><td><span id="data"><input type="button" value="Usuń" onclick="usuntrase()"/></span></td>';
  
    }
    else
    {
        echo '<p> Nie masz jeszcze zapisanych żadnych tras</p>';
    }
 ?> 
</table> 
<section id "response">
   <td><span id="response"></span></td></tr>
</section>