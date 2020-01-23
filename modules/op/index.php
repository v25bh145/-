<?php
/**
 * 此文件为图书馆信息管理系统学生面板
 * 用户可以在此面板搜索图书相关信息，也可以查询借书的数目
 *
 * @author v25bh145
 * @version 1.00
 */
include_once "../connect.php";
include_once "../classes/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php";
include_once "information.php";
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

<form class="form-inline" method="post" action="../search.php">
    <div class="form-group">
        <label for="firstSentence">第一项</label>
        <input type="text" class="form-control" id="firstSentence" name="firstSentence" placeholder="快填鸭">
    </div>
    <div class="radio">
        <label>
            <input type="radio" name="optionsRadios" value="option1" checked>
            且
        </label>
    </div>
    <div class="radio">
        <label>
            <input type="radio" name="optionsRadios" value="option2">
            或
        </label>
    </div>
    <div class="form-group">
        <label for="secondSentence">第二项</label>
        <input type="text" class="form-control" id="secondSentence" name="secondSentence" placeholder="快填鸭">
    </div>
    <button type="submit" class="btn btn-default">Submit</button>
</form>

<a href="returnIndex.php">
<button type="submit" class="btn btn-default">点我前往还书页面</button>
<br>

<a href="logout.php"> <button class="btn btn-default">Log out</button>


