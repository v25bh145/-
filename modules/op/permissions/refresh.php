<?php
/**
 * 此文件用于管理员进行刷新数据库信息操作
 *
 * @author v25bh145
 * @version 1.00
 */
include_once "../OpOnStudents.php";
include_once "../OpOnBooks.php";
include_once "information.php";
include_once "..\..\classes\PHPExcel-1.8\Classes\PHPExcel\IOFactory.php";
Examine();
InitStudents();
InitBooks();
UpdateStudentsData(0);
echo "更新完毕<br>";
