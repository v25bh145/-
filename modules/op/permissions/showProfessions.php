<?php
/**
 * 此文件用于管理员查看所有专业操作
 *
 * @author v25bh145
 * @version 1.00
 */
include_once "information.php";
Examine();
include_once "../../connect.php";

$conn = Connect();
UseDatabase($conn);

$sql = 'select id, name from professions;';
$statement = $conn->prepare($sql);
$statement->execute();

$results = $statement->get_result();
$flag = false;
while($result = mysqli_fetch_array($results, MYSQLI_ASSOC))
{
    $flag = true;

    $professionID = $result['id'];
    $professionName = $result['name'];
    echo "id: ".$professionID." professionName: ".$professionName."<hr>";
}
if($flag == false)
    echo "竟然还没有专业??宁在使用空城计嘛?";

echo
    '<a href="index.php"> <button class=\"btn btn-default\">点击返回</button>';