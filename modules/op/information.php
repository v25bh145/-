<?php
/**
 * 此函数库用于学生登录，注册以及是否登录的验证
 *
 * @author v25bh145
 * @version 1.00
 *
 * Login()
 * Logout()
 * Register()
 * Examine()
 */
include_once "../connect.php";//only index
include_once "OpOnStudents.php";

function Login()
{
    $SNO = $_POST['SNO'];
    $password = md5($_POST['password']);
    if ($SNO == null || $password == null) die("Cannot be empty");

    $id = QuerySNO($SNO);
    if ($id == false) die("Cannot find the student!");
    if (isset($_COOKIE["user"]))
    {
        echo "You have login!<br>";
        echo '<a href="index.php">' .
            '<button>点我前往主页</button>';
        die("");
    }
    if (Qualification($id, $password)) {
        echo "登陆成功!<br>";
        setcookie("user", $SNO, time()+3600, "");
        echo '<a href="index.php">' .
            '<button>点我前往主页</button>';
    } else
        die("Wrong SNO or password");
}
function Logout()
{
    if (!isset($_COOKIE["user"])) die("You haven't login!");
    setcookie("user", $_COOKIE['user'], time()-3600);
    echo
        '注销成功'.
        '<br>'.
        '<a href="../../login.html">' .
        '<button>点我前往登录页面</button>';
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
    if ($studentID == false && $studentID != QueryName($name)) die("Cannot find this student!");

    if ($password != $rePassword) die("the first password is not the same as the second password!");

    if (QueryOnUsers($studentID)) die("Have been signed");

    $conn = Connect();
    UseDatabase($conn);

    $sql = "insert into users(studentID, password) values (?, ?);";
    $statement = $conn->prepare($sql);
    $statement->bind_param("ss", $studentID, $password);
    $statement->execute();

    echo
        '注册成功！<br>'.
        '<a href="../../login.html">' .
        '<button>点我前往登录页面</button>';
}
function Examine()
{
    if (!isset($_COOKIE["user"]) || isset($_COOKIE["op"])) {
        die("请先登录学生账号！");
    }
}
