<?php

$file = fopen('viewed_pages.txt', 'r+');
 
$viewedPages = fgets($file); // On lit la première ligne (nombre de pages vues)
$viewedPages += 1; // On augmente de 1 ce nombre de pages vues
fseek($file, 0); // On remet le curseur au début du fichier
fputs($file, $viewedPages); // On écrit le nouveau nombre de pages vues
 
fclose($file);
 
echo('<p class="d-flex justify-content-center">Cette page a été vue ' . $viewedPages . ' fois !</p>');
?>