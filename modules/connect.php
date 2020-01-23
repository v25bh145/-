<?php
/**
 * 此函数库用于连接数据库以及选择数据库
 *
 * Connect()
 * UseDatabase()
 *
 * @author v25bh145
 * @version 1.00
 */

function Connect()
    /**
     * 用于连接上数据库
     */
{
    $password = "123456";
    $host = "182.92.213.78";
    $user = "root";
    $conn = new mysqli($host,$user,$password);
    if($conn->connect_error)
    {
        die("Connect failed");
    }
    else {
        return $conn;
    }
}
function UseDatabase($conn)
    /**
     * 用于选择数据库
     * @param $conn 连接对象
     */
{
    $sql = "use library;";
    $conn->query($sql);
}