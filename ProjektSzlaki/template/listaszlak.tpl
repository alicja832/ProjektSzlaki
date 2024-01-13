<table border="1">
 <?php
    echo '<tr class="pierwszy"><td>szczyt</td><td>schronisko</td>
       <td>kolor</td><td>czas wejścia</td><td>czas zejścia</td><td>długość(km)</td><td>Usuń</td></tr>' ;
    if ($data) { 
       foreach ( $data as $row ) { 
       echo '<tr><td>'.$row['szczyt'].'</td><td>'.$row['schronisko'].'</td>
       <td>'.$row['kolor'].'</td><td>'.$row['czas_w_gore'].'</td><td>'.$row['czas_w_dol'].'</td><td>'.$row['dlugosc'].'</td><td><input type="checkbox" id="id" value='.$row['szlak_id'].'/>&nbsp;</td></tr>' ;
    }}
 ?> 
  <tr><td></td><td></td><td></td><td></td><td></td><td></td><td> <span id="data"><input type="button" value="Usuń" onclick="usunszlak()"/></span> </td></tr>
 
</table> 
<section>

<section class="response"> <span id="response"></span></section>

<section>
<table>
         <p>Dodaj szlak:</p>
          <tr><td><label for="szczyt">Szczyt:</label></td>
          <td><input value="<?php if(isset($formData)) echo $formData['szczyt']; ?>" type="text" id="szczyt" name="szczyt" /></td></tr>
          <tr><td><label for="schronisko">Schronisko:</label></td>
          <td><input value="<?php if(isset($formData)) echo $formData['schronisko']; ?>" type="text" id="schronisko" name="schronisko" /></td></tr>
          <tr><td><label for="kolor">kolor:</label></td>
          <td><input value="<?php if(isset($formData)) echo $formData['kolor']; ?>" type="text" id="kolor" name="kolor" /></td></tr>
          <tr><td><label for="czas_do">czas_wejscia:</label></td>
          <td><input value="<?php if(isset($formData)) echo $formData['czas_do']; ?>" type="text" id="czas_do" name="czas_do" /></td></tr>
          <tr><td><label for="czas_do">czas_zejscia:</label></td>
          <td><input value="<?php if(isset($formData)) echo $formData['czas_z']; ?>" type="text" id="czas_z" name="czas_z" /></td></tr>
          <tr><td><label for="czas_do">dlugosc:</label></td>
          <td><input value="<?php if(isset($formData)) echo $formData['dlugosc']; ?>" type="text" id="dlugosc" name="dlugosc" /></td></tr>
          
          <tr><td><span id="data"><input type="button" value="Dodaj" onclick="dodajszlak()" /></span></td>
          <td><span id="response2"></span></td></tr>
</table>

