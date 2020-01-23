<?php

include_once "OpOnStudents.php";
include_once "OpOnLeadingCards.php";
$bookID = $_POST['bookID'];
$studentSNO = $_COOKIE['user'];
$studentID = QuerySNO($studentSNO);
$day = $_POST['day'];
if($day > 30) die("不能多于30天");
CreateLeadingCards($studentID, $bookID, $day);
echo "借书成功";
