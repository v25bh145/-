<?php
/**
 * 这个文件用于初始化数据，仅作范例使用
 *
 * @author v25bh145
 * @version 1.00
 */
include_once "../../connect.php";
include_once "../OpOnTagsAndProfessions.php";
include_once "../OpOnStudents.php";
include_once "../OpOnBooks.php";
include_once "..\..\classes\PHPExcel-1.8\Classes\PHPExcel\IOFactory.php";
AddProfession("咕咕咕");
AddProfession("数学");
AddProfession("智能与计算");
InitStudents();
InitBooks();

$rePassword = md5(123456);
$password = md5(123456);
$name = "修改人";
$SNO = "3256666656";
$IDCard = "258888999999999989";

$studentID = QuerySNO($SNO);

if ($studentID != QueryIDCard($IDCard)) die("identity is not consistent!");
if ($studentID == false && $studentID != QueryName($name)) die("Cannot find this student!");

if ($password != $rePassword) die("the first password is not the same as the second password!");

if (QueryOnOps($studentID)) die("Have been signed");

$conn = Connect();
UseDatabase($conn);

$sql = "insert into ops(studentID, password) values (?, ?);";
$statement = $conn->prepare($sql);
$statement->bind_param("ss", $studentID, $password);
$statement->execute();

echo
'激活成功！<br>';