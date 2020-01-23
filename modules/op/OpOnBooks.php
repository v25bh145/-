<?php
require_once "../Connect.php";
require_once "OpOnTagsAndProfessions.php";
require_once ("..\classes\PHPExcel-1.8\Classes\PHPExcel\IOFactory.php");
//批量添加书目-->excel
//添加单个书
//为书添加标签

//书入库
//书出库--查新库内是否还有书
    //查询库内是否还有书
//↑查询剩余书书目↑
//查询借出书书目
//查询书目的专业id->专业名
//查询书目的标签， 查询书目的名字
//查询书目是否为学生的专业书
//修改借出书书目， 修改在库书书目
function AddBook($name, $professionID, $amount)
    //添加一本书
{
    //echo $name." ".$professionID." ".$amount;
    $conn = Connect();
    UseDatabase($conn);

    $sql = "insert into books (name, professionID, inLibrary, outLibrary, num_of_tags) values (?, ?, ?, 0, 0);";
    $statement = $conn->prepare($sql);
    $statement->bind_param("sss", $name, $professionID, $amount);
    $statement->execute();
}
function AddBookTag($tagName, $bookID)
    //添加一个标签
{
    $conn = Connect();
    UseDatabase($conn);

    if($_POST["tagName"] != null) {
        $tagName = $_POST["tagName"];
        $bookID = $_POST["bookID"];
    }

    $statement = $conn->prepare("insert into tags(name, bookID) values (?, ?);");
    $statement->bind_param("ss",$tagName, $bookID);
    $statement->execute();

    $sql = "select num_of_tags from books where id = ?;";
    $statement = $conn->prepare($sql);
    $statement->bind_param("s", $bookID);
    $statement->execute();
    $result = $statement->get_result()->fetch_array(MYSQLI_ASSOC);
    $amount = $result['num_of_tags'] + 1;

    $sql = "update books set num_of_tags = ? where id = ?;";
    $statement = $conn->prepare($sql);
    $statement->bind_param("ss", $amount, $bookID);
    $statement->execute();
}
function InitBooks()
    //初始化所有书籍
    //位于excel中，'图书馆管理系统\bin'，按照以下格式：
    //名称 专业名 现存几本 标签1 标签2 标签3 ……
{
    $conn = Connect();
    UseDatabase($conn);

    $filePath = "../../bin/图书信息.xlsx";

    $inputFileType = PHPExcel_IOFactory::identify($filePath);
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load($filePath);

    $sheet = $objPHPExcel->getSheet(0);

    $highestRow = $sheet->getHighestRow();

    for($row = 2; $row <= $highestRow; $row++) {
        $name = (string)$sheet->getCell("A" . $row)->getValue();
        $professionName = (string)$sheet->getCell("B" . $row)->getValue();
        $amount = (int)$sheet->getCell("C" . $row)->getValue();

        $professionID = QueryProfessionsName($professionName);
        if ($professionID == 0)
        {
            echo "无此专业<br>";
            continue;
        }
        else if ( QueryBookID($name) == true)
        {
            echo "已经存在<br>";
            continue;
        }
        AddBook($name, $professionID, $amount);
        $bookID = QueryBookID($name);
        $letterTable = "0ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $head = 4;
        $ch = $letterTable[$head];
        $tag = (string)$sheet->getCell($ch.$row)->getValue();
        while($tag != null)
        {
            AddBookTag($tag, $bookID);
            $head = $head + 1;
            $ch = $letterTable[$head];
            $tag = (string)$sheet->getCell($ch.$row)->getValue();
        }

    }
}
function QueryBookID($name)
{
    $conn = Connect();
    UseDatabase($conn);

    $sql = "select id from books where name = ?;";
    $statement = $conn->prepare($sql);
    $statement->bind_param("s", $name);
    $statement->execute();
    $result = $statement->get_result()->fetch_array();
    return $result['id'];
}
function AddInLibrary($bookID)
{
    $conn = Connect();
    UseDatabase($conn);

    $sql = "select inLibrary, outLibrary from books where id = ?;";
    $statement = $conn->prepare($sql);
    $statement->bind_param("s", $bookID);
    $statement->execute();

    $result = $statement->get_result()->fetch_array(MYSQLI_ASSOC);
    $inLibrary = $result["inLibrary"];
    $outLibrary = $result["outLibrary"];

    if($outLibrary == 0)
        die("ERROR: outLibrary = 0");
    $inLibrary++;
    $outLibrary--;

    $sql = "Update books set inLibrary = ?, outLibrary = ? where id = ?;";
    $statement = $conn->prepare($sql);
    $statement->bind_param("sss", $inLibrary, $outLibrary, $bookID);
    $statement->execute();
}
function AddOutLibrary($bookID)
{
    $conn = Connect();
    UseDatabase($conn);

    $sql = "select inLibrary, outLibrary from books where id = ?;";
    $statement = $conn->prepare($sql);
    $statement->bind_param("s", $bookID);
    $statement->execute();

    $result = $statement->get_result()->fetch_array(MYSQLI_ASSOC);
    $inLibrary = $result["inLibrary"];
    $outLibrary = $result["outLibrary"];

    if($inLibrary == 0)
        die("ERROR: inLibrary = 0");
    $inLibrary--;
    $outLibrary++;

    $sql = "Update books set inLibrary = ?, outLibrary = ? where id = ?;";
    $statement = $conn->prepare($sql);
    $statement->bind_param("sss", $inLibrary, $outLibrary, $bookID);
    $statement->execute();
}

function QueryBookIsPro($bookID, $studentID)
{
    $conn = Connect();
    UseDatabase($conn);

    $sql = "select professionID from books where id = ?;";
    $statement = $conn->prepare($sql);
    $statement->bind_param("s", $bookID);
    $statement->execute();
    $result = $statement->get_result()->fetch_array(MYSQLI_ASSOC);

    $professionID = $result["professionID"];

    $sql = "select professionID from students where id = ?;";
    $statement = $conn->prepare($sql);
    $statement->bind_param("s", $studentID);
    $statement->execute();
    $result = $statement->get_result()->fetch_array(MYSQLI_ASSOC);

    $studentProfessionID = $result["professionID"];
    if($studentProfessionID == $professionID)
        return true;
    else
        return false;
}
//InitBooks();