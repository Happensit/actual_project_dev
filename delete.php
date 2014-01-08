<?php
header("Content-Type: text/html; charset=utf-8");
$time = 2;
$filename = 'settings.php';
//rename("sites/default/settings.php", "sites/default/old-settings.php");
chmod('sites/default/'.$file, 0777);
unlink('sites/default/'.$filename);
sleep($time);
if (file_exists('sites/default/'.$filename)) {
    echo "Файл $filename существует";
} else {
    echo "Файл $filename удалён";
}
?>
