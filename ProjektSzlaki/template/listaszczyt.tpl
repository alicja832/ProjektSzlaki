<table border="1">
 <?php
   echo '<tr class="pierwszy"><td>nazwa</td><td>wysokość(m.n.p.m)</td><td>Usuń</td></tr>' ;
    if ($data) { 
       foreach ( $data as $row ) { 
       echo '<tr></td><td>'.$row['nazwa'].'</td><td>'.$row['wysokosc'].'</td><td><input type="checkbox" id="id" value='.$row['szczyt_id'].'/>&nbsp;</td></tr>' ;
    }}
 ?> 
  <tr><td></td><td></td><td> <span id="data"><input type="button" value="Usuń" onclick="usunszczyt()"/></span> </td></tr>
</table> 
<section class="response"> <span id="response2"></span></section>
<table>
         <p>Dodaj szczyt:</p>
          <tr><td><label for="nazwa">Nazwa:</label></td>
          <td><input value="<?php if(isset($formData)) echo $formData['nazwa']; ?>" type="text" id="nazwa" name="nazwa" /></td></tr>
          <tr><td><label for="wysokosc">Wysokość:</label></td>
          <td><input value="<?php if(isset($formData)) echo $formData['wysokosc']; ?>" type="text" id="wysokosc" name="wysokosc" /></td></tr>
          <tr><td><span id="data"><input type="button" value="Dodaj" onclick="dodajszczyt()" /></span></td>
</table>
<section class="response"> <span id="response"></span></section>

<div class="image2">
 <img src="mapa.png" width="800" height="300"></img>
 </div>