<?php
/**
 * 此文件为图书馆信息管理系统管理员面板
 *
 * @author v25bh145
 * @version 1.00
 */
include_once "../../connect.php";
include_once "information.php";
include_once "..\..\classes\PHPExcel-1.8\Classes\PHPExcel\IOFactory.php";
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
<h1>管理员操作面板<h1>
        <hr>

<h2>注册新的管理员<h2>
    <form class="form-horizontal" method="post" action="register.php">
        <div class="form-group">
            <label class="col-sm-2 control-label">Name</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="name">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">SNO</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="SNO">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">IDCard</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="IDCard">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Password</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" name="password">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Rewrite Password</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" name="rePassword">
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">name a new op</button>
            </div>
        </div>
    </form>
    <hr>

<h2> 显示所有专业 <h2>
    <form class="form-horizontal" method="post" action="showProfessions.php">
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">show all professions</button>
            </div>
        </div>
    </form>
    <hr>

<h2> 添加一个专业 <hr>
    <form class="form-horizontal" method="post" action="addProfession.php">
        <div class="form-group">
            <label class="col-sm-2 control-label">Name</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="name">
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">name a new profession</button>
            </div>
        </div>
    </form>
    <hr>

<h2>解禁学生</h2>
    <form class="form-horizontal" method="post" action="cancelBanOfStudent.php">
        <div class="form-group">
            <label class="col-sm-2 control-label">SNO</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="SNO">
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">modify this student</button>
            </div>
        </div>
    </form>
    <hr>

<h2>下载学生与图书表格</h2>
    <form class="form-horizontal" method="post" action="download.php">
        <div class="radio">
            <label>
                <input type="radio" name="optionsRadios" value="option1" checked>
                下载学生信息
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="optionsRadios" value="option2">
                下载图书信息
            </label>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">下载</button>
            </div>
        </div>
    </form>
    <hr>
<h2>上传表格文件(修改)</h2>
    <form class="form-horizontal" action="upload.php" enctype="multipart/form-data" method="post">
        上传文件：<input type="file" name="upfile" /><br>
        <input type="submit" value="upload" />
    </form>
    <hr>
<h2>更新数据信息</h2>
    <form class="form-horizontal" method="post" action="refresh.php">
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">refresh</button>
            </div>
        </div>
    </form>