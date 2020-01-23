<?php
/**
 * 此函数库用于数据库中与借书卡相关信息的管理与查询
 *
 * @author v25bh145
 * @version 1.00
 *
 * @function QueryDelay
 * @function CreateLeadingCards
 * @function DeleteLeadingBooks
 */
include_once "OpOnStudents.php";
include_once "OpOnBooks.php";
include_once "../connect.php";

function QueryDelay($id)
    /**
     * 查询此学生是否有过期情况
     * 有：true，无：false
     */
{
    $res = false;
    $conn = Connect();
    UseDatabase($conn);

    $sql = "select returnTime from leadingCards where studentID = '{$id}'";
    $try = $conn->query($sql);

    date_default_timezone_set("Asia/Shanghai");
    $nowTime = date("y-m-d H:i:s");

    while($row2 = mysqli_fetch_array($try,MYSQLI_ASSOC))
    {
        $thisTime = $row2["returnTime"];
        if(strtotime($nowTime) > strtotime($thisTime))
            $res = true;
    }
    return $res;
}

function CreateLeadingCards($studentID, $bookID, $dateTime)
    /**
     * 借书
     * @param $studentID
     * @param $bookID
     * @param $dateTime
     */
{
    $conn = Connect();
    UseDatabase($conn);

    if(QueryDelay($studentID) == true)
        die("INFO: 有书逾期未还，处于封禁状态");

    if(QueryMaxBorrow($studentID) == true)
    {
        die("借出书目达到上限");
    }

    AddOutLibrary($bookID);

    date_default_timezone_set("Asia/Shanghai");
    $nowTime = date("y-m-d H:i:s");

    $returnTime = strtotime($nowTime) + $dateTime * 24 * 3600;
    $returnTime = date("y-m-d H:i:s", $returnTime);

    $sql = "insert into leadingCards (bookID, studentID, returnTime) values (?, ?, ?);";
    $statement = $conn->prepare($sql);
    $statement->bind_param("sss", $bookID, $studentID, $returnTime);
    $statement->execute();

    if( QueryBookIsPro($bookID, $studentID) )
        AddBorrowPro($studentID, 1);
    else
        AddBorrowNotPro($studentID, 1);

    return true;
}

function DeleteLeadingBooks($studentID, $bookID)
    /**
     * 还书
     * @param $studentID
     * @param $bookID
     */
{
    $conn = Connect();
    UseDatabase($conn);

    $sql = "select id, returnTime from leadingCards where studentID = ? and bookID = ?;";
    $statement = $conn->prepare($sql);
    $statement->bind_param("ss", $studentID, $bookID);
    $statement->execute();

    $res = $statement->get_result()->fetch_array(MYSQLI_ASSOC);
    $id = $res['id'];
    if($id == null)
        die("没有查询到借书卡");

    date_default_timezone_set("Asia/Shanghai");
    $nowTime = date("y-m-d H:i:s");
    if(strtotime($res["returnTime"]) < strtotime($nowTime))
    {
        echo "逾期归还，增加封禁一周<br>";
        SetForbidden($studentID, 7);
    }

    $sql = "delete from leadingCards where id = ?;";
    $statement = $conn->prepare($sql);
    $statement->bind_param("s", $id);
    $statement->execute();

    if( QueryBookIsPro($bookID, $studentID) )
        AddBorrowPro($studentID, -1);
    else
        AddBorrowNotPro($studentID, -1);

    AddInLibrary($bookID);
}