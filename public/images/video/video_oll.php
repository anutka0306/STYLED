<?php //http://ru.vehiclevisuals.com/animation_link.php?num=jysvkn3v73zb
 $file = fopen ($_POST["url"], "r");//
if (!$file) {
    echo "<p>Невозможно открыть удаленный файл.\n";
    exit;
}
$x=0;
while (!feof ($file)) {
    $line = fgets ($file, 1024);
    if (strstr ($line,'Ссылка, по которой вы перешли более неактивна.'))
							{$x=1;
							//print  $line;
						}
}
fclose($file); 
print $x;
if ($x==1){
	

 }


 ?>