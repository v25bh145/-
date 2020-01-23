<?php
    require_once ("../Connect.php");

    function QueryTags($id)
        //系统操作，根据输入的id返回标签名称
    {
        $conn = Connect();
        UseDatabase($conn);

        if($_POST["id"] != null)
            $id = $_POST["id"];

        $statement = $conn->prepare("select name from tags where id = ?;");
        $statement->bind_param("s",$id);
        $statement->execute();

        $result = $statement->get_result()->fetch_assoc();
        return $result["name"];
    }




    function AddProfessions($professionName)
        //添加专业，修改失败返回false，否则返回true
    {
        $conn = Connect();
        UseDatabase($conn);

        if($_POST["professionName"] != null)
            $professionName = $_POST["professionName"];

        $statement = $conn->prepare("insert into professions(name) values (?);");
        $statement->bind_param("s",$professionName);
        $statement->execute();
        if($statement->error)
            return false;
        return true;
    }

    function QueryProfessionsID($id)
        //系统操作，根据输入的id返回专业名称
    {
        $conn = Connect();
        UseDatabase($conn);

        if($_POST["id"] != null)
            $id = $_POST["id"];

        $statement = $conn->prepare("select name from professions where id = ?;");
        $statement->bind_param("s",$id);
        $statement->execute();

        $result = $statement->get_result()->fetch_assoc();
        return $result["name"];
    }
    function QueryProfessionsName($name)
        //系统操作，根据输入的专业名称返回id
    {
        $conn = Connect();
        UseDatabase($conn);

        if($_POST["name"] != null)
            $name = $_POST["name"];

        $statement = $conn->prepare("select id from professions where name = ?;");
        $statement->bind_param("s",$name);
        $statement->execute();
        if($statement->error)
            return 0;
        $result = $statement->get_result()->fetch_assoc();
        return $result["id"];
    }
AddProfessions("智能与计算");
AddProfessions("数学");
AddProfessions("临床医学");
AddProfessions("养鱼");
AddProfessions("咕咕咕");
AddProfessions("软件工程");
