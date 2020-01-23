<?php
include_once "../connect.php";
include_once "OpOnTagsAndProfessions.php";
include_once "..\classes\PHPExcel-1.8\Classes\PHPExcel\IOFactory.php";
/**
 * 此函数库用于数据库中与图书相关信息的管理与查询
 *
 * @author v25bh145
 * @version 1.00
 *
 * @function AddBookTag()
 * @function AddBook()
 * @function InitBooks()
 * @function QueryBookID()
 * @function QueryBookName()
 * @function QueryBookAuthor()
 * @function QueryBookDesc()
 * @function QueryBookProf()
 * @function QueryBookInLibrary()
 * @function QueryBookOutLibrary()
 * @function QueryBookIsPro()
 */
function AddBookTag($tagName, $bookID)
    /**
     * 添加一个标签
     * @param $tagName
     * @param $bookID
     */
{
    $conn = Connect();
    UseDatabase($conn);

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
function AddBook($name, $professionID, $amount, $description, $author)
    /**
     * 添加单一书籍
     * @param $name
     * @param $professionID
     * @param $amount
     * @param $description
     * @param author
     */
{
    $conn = Connect();
    UseDatabase($conn);
    $sql = "insert into books(name, author, description, inLibrary, professionID) values (?, ?, ?, ?, ?);";
    $statement = $conn->prepare($sql);
    $statement->bind_param("sssss", $name, $author, $description, $amount, $professionID);
    $statement->execute();
}
function InitBooks()
    /**
     * 初始化所有书籍
     * 书籍信息位于excel中，'图书馆管理系统\bin'，按照以下格式：
     * 名称 专业名 现存几本 标签1 标签2 标签3 ……
     * 相对路径路径设置为从permissions文件夹开始
     */
{
    $conn = Connect();
    UseDatabase($conn);

    $filePath = "../../../bin/图书信息.xlsx";

    $inputFileType = PHPExcel_IOFactory::identify($filePath);
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load($filePath);

    $sheet = $objPHPExcel->getSheet(0);

    $highestRow = $sheet->getHighestRow();

    for($row = 2; $row <= $highestRow; $row++) {
        $name = (string)$sheet->getCell("A" . $row)->getValue();
        $professionName = (string)$sheet->getCell("B" . $row)->getValue();
        $amount = (int)$sheet->getCell("C" . $row)->getValue();
        $description = (string)$sheet->getCell("D" . $row)->getValue();
        $author = (string)$sheet->getCell("E" . $row)->getValue();

        $professionID = QueryProfessionsID($professionName);
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
        AddBook($name, $professionID, $amount, $description, $author);
        $bookID = QueryBookID($name);
        $letterTable = "0ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $head = 6;
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
    /**
     * 输入图书名字查询图书信息，存在返回id，不存在返回false
     * @param $name
     */
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
function QueryBookName($id)
    /**
     * 输入图书id查询图书信息，存在返回书名，不存在返回false
     * @param $id
     */
{
    $conn = Connect();
    UseDatabase($conn);

    $sql = "select name from books where id = ?;";
    $statement = $conn->prepare($sql);
    $statement->bind_param("s", $id);
    $statement->execute();
    $result = $statement->get_result()->fetch_array();
    return $result['name'];
}
function QueryBookAuthor($id)
    /**
     * 输入图书id查询图书信息，存在返回作者，不存在返回false
     * @param $id
     */
{
    $conn = Connect();
    UseDatabase($conn);

    $sql = "select author from books where id = ?;";
    $statement = $conn->prepare($sql);
    $statement->bind_param("s", $id);
    $statement->execute();
    $result = $statement->get_result()->fetch_array();
    return $result['author'];
}
function QueryBookDesc($id)
    /**
     * 输入图书id查询图书信息，存在返回图书描述，不存在返回false
     * @param $id
     */
{
    $conn = Connect();
    UseDatabase($conn);

    $sql = "select description from books where id = ?;";
    $statement = $conn->prepare($sql);
    $statement->bind_param("s", $id);
    $statement->execute();
    $result = $statement->get_result()->fetch_array();
    return $result['description'];
}
function QueryBookProf($bookID)
    /**
     * 输入图书id查询图书信息，存在返回图书专业，不存在返回false
     * @param $id
     */
{
    $conn = Connect();
    UseDatabase($conn);

    $sql = "select professionID from books where id = ?;";
    $statement = $conn->prepare($sql);
    $statement->bind_param("s", $id);
    $statement->execute();
    $result = $statement->get_result()->fetch_array();
    return $result['professionID'];
}

function AddInLibrary($bookID)
    /**
     * 输入图书id查询图书信息，存在返回图书在馆数目，不存在返回false
     * @param $id
     */
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
    /**
     * 输入图书id查询图书信息，存在返回图书出馆书目，不存在返回false
     * @param $id
     */
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
    /**
     * 输入图书id与学生id查询两者专业是否匹配，匹配返回true，反之返回false
     * @param $bookID
     * @param $studentID
     */
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