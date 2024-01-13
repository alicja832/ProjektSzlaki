<table border="1">
 <?php
  echo '<p>Wybierz jaki szlak je łączy:</p>';
    echo '<tr class="pierwszy"><td>szczyt</td><td>schronisko</td>
       <td>kolor</td><td>Wybierz</td></tr>' ;
    if ($data) { 
       foreach ( $data as $row ) { 
       echo '<tr><td>'.$row['szczyt'].'</td><td>'.$row['schronisko'].'</td>
       <td>'.$row['kolor'].'</td><td><input type="checkbox" id="id" value='.$row['szlak_id'].'/>&nbsp;</td></tr>' ;
    }}
 ?> 
</table>
<table>
          <p>Podaj potrzebne informacje:</p>
          <tr><td><label for="nazwa">czas w górę:</label></td>
          <td><input value="<?php if(isset($formData)) echo $formData['czas_w_gore']; ?>" type="text" id="czas_w_gore" name="czas_w_gore" /></td></tr>
          <tr><td><label for="wysokosc">czas w dół:</label></td>
          <td><input value="<?php if(isset($formData)) echo $formData['czas_w_dol']; ?>" type="text" id="czas_w_dol" name="czas_w_dol"/></td></tr>
          <tr><td><label for="wysokosc">odleglosc:</label></td>
          <td><input value="<?php if(isset($formData)) echo $formData['odleglosc']; ?>" type="text" id="odleglosc" name="odleglosc"/></td></tr>


</table> 
<br>
<section style="background-color: green;">
<span id="data"><input type="button" value="Dodaj" onclick="dodaj_polaczenie()" /></span></td>
         <td><span id="response"></span>
</section>