<?php
include_once "../Connect.php";//only index
include_once "OpOnStudents.php";

function Login()
{
    $SNO = $_POST['SNO'];
    $password = md5($_POST['password']);
    if ($SNO == null || $password == null) die("Cannot be empty");

    $id = QuerySNO($SNO);
    if ($id == false) die("Cannot find the student!");
    if ($_COOKIE['SNO'] != null) die("You have login!");
    if (Qualification($id, $password)) {
        echo "登陆成功!<br>";
        setcookie("SNO", $id, time()+3600, "/modules/op/TestFor.php");
        echo '<a href="../../index.php">' .
            '<button>点我前往主页</button>';
    } else
        die("Wrong SNO or password");
}
function Register()
{
    $rePassword = md5($_POST['rePassword']);
    $password = md5($_POST['password']);
    $name = $_POST['name'];
    $SNO = $_POST['SNO'];
    $IDCard = $_POST['IDCard'];

    $studentID = QuerySNO($SNO);

    if ($studentID != QueryIDCard($IDCard)) die("identity is not consistent!");
    if ($studentID == false) die("Cannot find the student!");

    if ($password != $rePassword) die("the first password is not the same as the second password!");

    if (QueryOnUsers($studentID)) die("Have been signed");

    $conn = Connect();
    UseDatabase($conn);

    $sql = "insert into users(studentID, password) values (?, ?);";
    $statement = $conn->prepare($sql);
    $statement->bind_param("ss", $studentID, $password);
    $statement->execute();

    echo '<a href="../../Register.html">' .
        '<button>点我前往登录页面</button>';
}
function Examine()
{
    if ($_COOKIE['SNO'] == null)
        return false;
    else return true;
}
