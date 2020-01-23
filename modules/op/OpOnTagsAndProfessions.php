<?php
/**
 * 此函数库用于数据库中与标签或专业相关信息的管理与查询
 *
 * @author v25bh145
 * @version 1.00
 *
 * @function QueryTags()
 * @function AddProfession()
 * @function QueryProfessionsName()
 * @function QQueryProfessionsName()
 * @function QueryProfessionsID()
 */
include_once "../connect.php";
    function QueryTags($id)
        /**
         * 根据输入的id返回标签名称
         * @param $id
         */
    {
        $conn = Connect();
        UseDatabase($conn);

        $statement = $conn->prepare("select name from tags where id = ?;");
        $statement->bind_param("s",$id);
        $statement->execute();

        $result = $statement->get_result()->fetch_assoc();
        return $result["name"];
    }




    function AddProfession($professionName)
        /**
         * 添加专业，修改失败返回false，否则返回true
         * @param $professionName
         */
    {
        $conn = Connect();
        UseDatabase($conn);

        $statement = $conn->prepare("insert into professions(name) values (?);");
        $statement->bind_param("s",$professionName);
        $statement->execute();
        if($statement->error)
            return false;
        return true;
    }

    function QueryProfessionsName($id)
        /**
         * 根据输入的id返回专业名称
         * @param $id
         */
    {
        $conn = Connect();
        UseDatabase($conn);

        $statement = $conn->prepare("select name from professions where id = ?;");
        $statement->bind_param("s",$id);
        $statement->execute();

        $result = $statement->get_result()->fetch_assoc();
        return $result["name"];
    }
    function QueryProfessionsID($name)
        /**
         * 根据输入的专业名称返回id
         * @param $name
         */
    {
        $conn = Connect();
        UseDatabase($conn);

        $statement = $conn->prepare("select id from professions where name = ?;");
        $statement->bind_param("s",$name);
        $statement->execute();
        if($statement->error)
            return 0;
        $result = $statement->get_result()->fetch_assoc();
        return $result["id"];
    }

