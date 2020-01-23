<?php
/**
 * 此文件用于搜索系统的实现
 * 用户可以在此面板借书
 *
 * @author v25bh145
 * @version 1.00
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="gbk">
    <title>index</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
<?php
include_once "connect.php";
include_once "op/OpOnTagsAndProfessions.php";

    $conn = Connect();
    UseDatabase($conn);

    $which = $_POST['optionsRadios'];
    $firstSentence = $_POST['firstSentence'];
    $secondSentence = $_POST['secondSentence'];
    $firstSentence = "%"."$firstSentence"."%";
    $secondSentence = "%"."$secondSentence"."%";

    $flag = false;

    if($which == "option1")
    {
        $sql = 'select id, description, author, name, inLibrary, outLibrary, professionID from books where (description like ? or author like ? or name like ?) and (description like ? or author like ? or name like ?);';
        $statement = $conn->prepare($sql);
        $statement->bind_param("ssssss", $firstSentence, $firstSentence,
            $firstSentence, $secondSentence, $secondSentence, $secondSentence);
        $statement->execute();
        $results = $statement->get_result();
    }
    else if($which == "option2")
    {
        $sql = 'select id, description, author, name, inLibrary, outLibrary, professionID from books where (description like ? or author like ? or name like ?) or (description like ? or author like ? or name like ?);';
        $statement = $conn->prepare($sql);
        $statement->bind_param("ssssss", $firstSentence, $firstSentence,
            $firstSentence, $secondSentence, $secondSentence, $secondSentence);
        $statement->execute();
        $results = $statement->get_result();
    }
    //Print the results:
    while($result = mysqli_fetch_array($results, MYSQLI_ASSOC))
    {

        $flag = true;

        $bookID = $result['id'];
        $bookName = $result['name'];
        $bookAuthor = $result['author'];
        $bookDescription = $result['description'];
        $bookInLibrary = $result['inLibrary'];
        $bookOutLibrary = $result['outLibrary'];

        $bookProfessionID = $result['professionID'];
        $bookProfessionName = QueryProfessionsName($bookProfessionID);

        echo '<table class="table table-striped">'.
            "<h2>$bookName</h2>".
            "<h3>$bookAuthor</h3>".
            "<br>".
            "<h3> Tags: </h3>";

        $sql = "select name from tags where bookID = ?;";
        $statementTag = $conn->prepare($sql);
        $statementTag->bind_param("s", $bookID);
        $statementTag->execute();
        $tags = $statementTag->get_result();
        while($row = mysqli_fetch_array($tags, MYSQLI_ASSOC))
        {
            $tmp = $row['name'];
            echo "<p5> $tmp </p5>";
        }
        echo
            "<br>".
            "<h3> Professions: $bookProfessionName</h3>";

        echo
            "<br>".
            "<h4>$bookDescription</h4>".
            "<br>".
            "<h3>in: $bookInLibrary<h3>".
            "<h3>out: $bookOutLibrary".
            "<br>";

        echo
            '<form class="form-horizontal" method="post" action="op/leading.php">' .

            "<input type=hidden name='bookID' value = $bookID>".

            '<div class="form-group">'.

            '<div class="form-group">'.
            '<label class="col-sm-2 control-label">借几天(最多30天)</label>'.
            '<div class="col-sm-10">'.
            '<input type="text" class="form-control" name="day">'.
            ' </div>'.
            '</div>'.

            '<div class="col-sm-offset-2 col-sm-10">'.
            '<button type="submit" class="btn btn-default">借一本</button>'.
            '</div>'.

            '</div>'.
            '</form>'.
            '<hr>';

    }
    if(!$flag)
        die("没有找到您想要看的书啊");
