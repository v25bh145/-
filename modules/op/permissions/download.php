<?php
ob_clean();
$filename = "学生信息.xlsx";
$filepath = '../../../bin/'.$filename;

if(!file_exists($filepath)){
    echo "不存在";
    exit;
}

$fp=fopen($filepath,"r");
$fileSize=filesize($filepath);

header("Content-Disposition: attachment; filename=$filename");
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Length: '.filesize("$filename"));
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');
readfile("$filename");

header("Accept-Ranges:bytes");
header("Accept-Length:".$fileSize);
header("Content-Disposition: attachment; filename=".$filename);

$buffer=1024;
$buffer_count=0;
while(!feof($fp)&&$fileSize-$buffer_count>0){
    $data=fread($fp,$buffer);
    $buffer_count+=$buffer;
    echo $data;
}
fclose($fp);

?>