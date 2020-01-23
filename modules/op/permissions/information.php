<?php
/**
 * 此函数库用于管理登录，注册以及是否登录的验证
 *
 * @author v25bh145
 * @version 1.00
 *
 * Login()
 * Logout()
 * NameOp()
 * Examine()
 */
include_once "../../connect.php";
include_once "../OpOnStudents.php";
function Login()
    /**
     * 管理员登陆
     */
{
    $SNO = $_POST['SNO'];
    $password = md5($_POST['password']);
    if ($SNO == null || $password == null) die("Cannot be empty");

    $id = QuerySNO($SNO);
    if ($id == false) die("Cannot find the student!");
    if (isset($_COOKIE["op"]))
    {
        echo "You have login!<br>";
        echo '<a href="index.php">' .
            '<button>点我前往主页</button>';
        die("");
    }
    if (QualificationForOp($id, $password)) {
        echo "登陆成功!<br>";
        setcookie("op", $SNO, time()+3600, "");
        echo '<a href="index.php">' .
            '<button>点我前往主页</button>';
    } else
        die("Wrong SNO or password");
}
function Logout()
    /**
     * 管理员登出
     */
{
    if (!isset($_COOKIE["op"])) die("You haven't login!");
    setcookie("op", $_COOKIE['op'], time()-3600);
    echo
        '注销成功'.
        '<br>'.
        '<a href="login.html">' .
        '<button>点我前往登录页面</button>';
}
function NameOp()
    /**
     * 管理员任命新管理
     */
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

    if (QueryOnOps($studentID)) die("Have been signed");

    $conn = Connect();
    UseDatabase($conn);

    $sql = "insert into ops(studentID, password) values (?, ?);";
    $statement = $conn->prepare($sql);
    $statement->bind_param("ss", $studentID, $password);
    $statement->execute();

    echo
        '激活成功！<br>';
}
function Examine()
    /**
     * 检查是否登录Op账号
     */
{
    if (!isset($_COOKIE["op"]) || isset($_COOKIE["user"])) {
        die("请先登录op账号！");
    }
}