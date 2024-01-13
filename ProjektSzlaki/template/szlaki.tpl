<table border="1">
 <?php
    if ($data) {
       echo '<header>Szlaki, którymi możesz dojść na wybrany szczyt:</header>';
       echo '<tr class="pierwszy"><td>kolor szlaku</td><td>schronisko-punkt startu</td>
       <td>czas wejścia</td><td>czas zejścia</td><td>dlugość</td></tr>' ; 
       foreach ( $data as $row ) { 
       echo '<tr><td>'.$row['kolor_szlaku'].'</td><td>'.$row['nazwa_schroniska'].'</td>
       <td>'.$row['czas_wejscia'].'</td><td>'.$row['czas_zejscia'].'</td><td>'.$row['odleglosc'].'</td></tr>';
    }}
    else
    {
      echo '<p> Na podany szczyt nie prowadzą żadne bezpieczne dla turystów szlaki </p>';
    }
 ?> 
</table> 
