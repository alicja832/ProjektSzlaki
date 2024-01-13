<table border="1">
<header><?php echo $info;?></header>
 <?php
   echo '<tr class="pierwszy"><td>użytkownik</td><td>długość trasy</td></tr>' ;
    if ($data) { 
       foreach ( $data as $row ) { 
       echo '<tr></td><td>'.$row['login'].'</td><td>'.$row['dlugosc'].'</td></tr>' ;
    }}
 ?> 
</table>
