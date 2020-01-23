<?php
/**
 * 此文件用于管理员进行数据文件下载操作
 *
 * @author v25bh145
 * @version 1.00
 */
include_once "information.php";
Examine();

ob_clean();
$which = $_POST['optionsRadios'];
if($which == "option1")
    $filename = "学生信息.xlsx";
else if($which == "option2")
    $filename = "图书信息.xlsx";
else
    die("未知错误？？");

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
