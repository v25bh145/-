<?php
include_once "../Connect.php";
include_once "../classes/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php";
include_once "Information.php";
Examine();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <base href="" target="_blank">
    <meta charset="gbk">
    <title>index</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>

<?php
include_once "OpOnStudents.php";
include_once "OpOnBooks.php";
$studentSNO = $_COOKIE['user'];
$studentID = QuerySNO($studentSNO);

$conn = Connect();
UseDatabase($conn);

$sql = "select bookID, leadingTime, returnTime from leadingcards where studentID = ?;";
$statement = $conn->prepare($sql);
$statement->bind_param("s", $studentID);
$statement->execute();
$res = $statement->get_result();
while($row = mysqli_fetch_array($res, MYSQLI_ASSOC))
{
    $tmpBookID = $row['bookID'];
    $tmpLeadingTime = $row['leadingTime'];
    $tmpReturnTime = $row['returnTime'];
    $tmpBookName = QueryBookName($tmpBookID);
    echo
    "书名：$tmpBookName<br>".
    "借阅时间：$tmpLeadingTime<br>".
    "归还时间：$tmpReturnTime<br>".
    "<br><hr>";
}
