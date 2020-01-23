<?php
/**
 * 这个文件用于初始化数据，仅作范例使用
 *
 * @author v25bh145
 * @version 1.00
 */
include_once "../op/OpOnTagsAndProfessions.php";
include_once "../op/OpOnStudents.php";
include_once "../op/OpOnBooks.php";
include_once "..\classes\PHPExcel-1.8\Classes\PHPExcel\IOFactory.php";
AddProfession("咕咕咕");
AddProfession("数学");
AddProfession("智能与计算");
InitStudents();
InitBooks();
