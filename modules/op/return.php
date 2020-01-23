<?php
/**
 * 此文件用于学生还书的操作
 *
 * @author v25bh145
 * @version 1.00
 */
include_once "OpOnStudents.php";
include_once "OpOnLeadingCards.php";
$bookID = $_POST['bookID'];
$studentSNO = $_COOKIE['user'];
$studentID = QuerySNO($studentSNO);

DeleteLeadingBooks($studentID, $bookID);

echo "还书成功";