<table border="1">
<header><?php echo $info;?></header>
 <?php
   echo '<tr class="pierwszy"><td>nazwa punktu</td><td>Ile razy wykorzystany</td></tr>' ;
    if ($data) { 
       foreach ( $data as $row ) { 
       echo '<tr></td><td>'.$row['nazwa_punktu'].'</td><td>'.$row['ilosc'].'</td></tr>' ;
    }}
 ?> 
</table>
<div class="image2">
 <img src="mapa.png" width="800" height="300"></img>
 </div>