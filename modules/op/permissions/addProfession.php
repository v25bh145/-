<?php
/**
 * 此文件用于管理员进行添加专业操作
 *
 * @author v25bh145
 * @version 1.00
 */
include_once "../OpOnTagsAndProfessions.php";
include_once "information.php";
Examine();
$name = $_POST['name'];
AddProfession($name);
echo
    "添加成功！<br>".
    '<a href="index.php"> <button class=\"btn btn-default\">点击返回</button>';