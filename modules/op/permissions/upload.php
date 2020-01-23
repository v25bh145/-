<?php
/**
 * 此文件用于管理员上传修改后的数据文件操作
 *
 * @author v25bh145
 * @version 1.00
 */
include_once "information.php";
include_once "../../connect.php";
include_once "../OpOnStudents.php";
include_once "../OpOnBooks.php";
include_once ("..\..\classes\PHPExcel-1.8\Classes\PHPExcel\IOFactory.php");
Examine();
header("Content-type: text/html; charset=utf-8");

require("../../classes/PHPExcel-1.8/Classes/PHPExcel.php");

// var_dump($_FILES);
if(@is_uploaded_file($_FILES['upfile']['tmp_name'])) {
    $upfile = $_FILES["upfile"];  //获取数组里面的值
    $name = $upfile["name"];//上传文件的文件名
    $type = $upfile["type"];//上传文件的类型
    $size = $upfile["size"];//上传文件的大小
    $tmp_name = $upfile["tmp_name"];//上传文件的临时存放路径

    if ($name != "学生信息.xlsx" && $name != "图书信息.xlsx")
        die('格式不正确！请传输名为 "学生信息.xlsx" 或 "图书信息.xlsx" 的文件！');

    $destination = "../../../bin/" . $name;
    move_uploaded_file($tmp_name, $destination);//将上传到服务器临时文件夹的文件重新移动到新位置
    $file_name = dirname(__FILE__) . $name;
    $error = $upfile["error"];//上传后系统返回的值
    if ($error == 0) {
        echo "文件上传成功<br>";
    } else {
        echo "上传失败";
    }
    $objReader = \PHPExcel_IOFactory::createReader('Excel5');//创建读取实例
    /*
     * log()//方法参数
     * $file_name excal文件的保存路径
     */
    $objPHPExcel = $objReader->load($file_name,$encode='utf-8');//加载文件
    $sheet = $objPHPExcel->getSheet(0);//取得sheet(0)表
    $highestRow = $sheet->getHighestRow(); // 取得总行数
    $highestColumn = $sheet->getHighestColumn(); // 取得总列数

    if($name == "学生信息.xlsx")
    {
        InitStudents();
        echo "修改学生信息成功<br>";
    }
    else if($name == "图书信息.xlsx")
    {
        InitBooks();
        echo "修改图书信息成功<br>";
    }
}