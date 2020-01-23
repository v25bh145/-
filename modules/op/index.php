<?php
    require_once "Connect.php";
    require_once "classes/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php";
    require_once "op/Information.php";
    require_once "op/TestFor.php";

    if(Examine()) echo "YES";
    else echo "NO";

    if(isset($_COOKIE["user"])) echo "YES";
    else echo "NO";

print_r($_COOKIE);
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
<form class="form-inline" method="post" action="Search.php">
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
