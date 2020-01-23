<?php
    require_once ("../Connect.php");
    require_once ("..\classes\PHPExcel-1.8\Classes\PHPExcel\IOFactory.php");
    require_once ("OpOnTagsAndProfessions.php");

    function InitStudents()
        //对学生进行初始化操作，批量添加
        //表格位于 "/bin/学生信息.xlsx"
        //表格切记设计为文本模式以看到实际参数
    {
        $conn = Connect();
        UseDatabase($conn);

        $filePath = "../../bin/学生信息.xlsx";

        $inputFileType = PHPExcel_IOFactory::identify($filePath);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($filePath);

        $sheet = $objPHPExcel->getSheet(0);

        $highestRow = $sheet->getHighestRow();

        for($row = 2; $row <= $highestRow; $row++)
        {
            $name = (string)$sheet->getCell("A".$row)->getValue();
            $IDCard = (string)$sheet->getCell("B".$row)->getValue();
            $SNO = (string)$sheet->getCell("C".$row)->getValue();
            $graduatingTime = $sheet->getCell("D".$row)->getValue();
            $professionName = (string)$sheet->getCell("E".$row)->getValue();

            $professionID = QueryProfessionsName($professionName);
            if ($professionID == 0)
                echo "无此专业<br>";
            else if ( QuerySNO($SNO) == true)
                echo "已经存在<br>";
            else
            {
                AddStudent($name, $SNO, $IDCard, $professionID, $graduatingTime);
            }
        }
    }
    function AddStudent($name, $SNO, $IDCard, $professionID, $graduatingTime)
        //添加单个学生 AddStudent("同学","159357", "1593571593571593577", 1, "2023-01-25");
    {
        $conn = Connect();
        UseDatabase($conn);

        if($_POST["SNO"] != null)
        {
            $name = $_POST["name"];
            $SNO = $_POST["SNO"];
            $IDCard = $_POST["IDCard"];
            $professionID = $_POST["professionID"];
            $graduatingTime = $_POST["graduatingTime"];
        }

        date_default_timezone_set("Asia/Shanghai");

        $time = strtotime($graduatingTime);
        $time = date("y-m-d H:i:s", $time);

        $sql = "insert into students (name, SNO, IDCard, professionID, graduatingTime) values (?, ?, ?, ?, ?)";
        $statement = $conn->prepare($sql);
        $statement->bind_param("sssss",$name,$SNO,$IDCard,$professionID,$time);
        $statement->execute();
        echo $statement->error;
    }

    function DeleteStudent($id)
        //删除单个学生
    {
        $conn = Connect();
        UseDatabase($conn);

        if($_POST["id"] != null)
            $id = $_POST;

        $sql = "delete from students where id = ?;";
        $statement = $conn->prepare("$sql");
        $statement->bind_param("s",$id);
        $statement->execute();
    }

    function QuerySNO($SNO)
        //在数据库中查找是否有此SNO的学生
        //有则返回id,没有则返回false
    {
        $conn = Connect();
        UseDatabase($conn);

        $sql = "select id from students where SNO = ?;";
        $statement = $conn->prepare($sql);
        $statement->bind_param("s",$SNO);
        $statement->execute();
        $result = $statement->get_result()->fetch_assoc();
        if($result["id"] == null)
            return false;
        else
            return $result["id"];
    }

    function SetForbidden($id, $day)
        //设置封禁时间，时间以天计数

        //设置起始时间为现在时间
        //如果已经被封禁
            //如果已经封禁的时间没有过去，则设置起始时间为此封禁时间
        //封禁时间 = 起始时间 + 封禁时间段
    {
        $conn = Connect();
        UseDatabase($conn);

        if($_POST["id"] != null)
        {
            $id = $_POST["id"];
            $day = $_POST["day"];
        }
        date_default_timezone_set("Asia/Shanghai");
        $nextTime = date("y-m-d H:i:s");

        $sql = "select forbidden from students where id = ?;";
        $statement = $conn->prepare($sql);
        $statement->bind_param("s", $id);
        $statement->execute();
        $res = $statement->get_result()->fetch_assoc();
        $hasBeenForbidden = $res["forbidden"];
        if($hasBeenForbidden != null)
            if (strtotime($nextTime) < strtotime($hasBeenForbidden))
                $nextTime = $hasBeenForbidden;

        $nextTime = strtotime($nextTime);
        $nextTime = date("y-m-d H:i:s", $nextTime + $day * 3600 * 24);

        $sql = "update students set forbidden = ? where id = ?;";
        $statement = $conn->prepare($sql);
        $statement->bind_param("ss",$nextTime, $id);
        $statement->execute();
    }

    function UpdateStudentsData($id)
        //非常重要的一个函数
        //如果学生已经毕业，就删掉学生数据
        //如果学生已经解除封禁，就解封

        //如果没有传入id，则默认对所有学生均执行一遍 UpdateStudentsData("");
    {
        $conn = Connect();
        UseDatabase($conn);

        date_default_timezone_set("Asia/Shanghai");
        $nowTime = date("y-m-d H:i:s");

        if($_POST["id"] != null)
            $id = $_POST["id"];

        if($id == null)
            //对所有人执行
        {
            $sql = "select id, graduatingTime, forbidden from students;";
            $try = $conn->query($sql);
            while($row = mysqli_fetch_array($try,MYSQLI_ASSOC))
            {
                if(strtotime($row["graduatingTime"]) < strtotime($nowTime))
                    DeleteStudent($row["id"]);
                else if(strtotime($row["forbidden"]) < strtotime($nowTime))
                {
                    $sql = "update students set forbidden = null where id = ?;";
                    $statement = $conn->prepare($sql);
                    $statement->bind_param("s",$row["id"]);
                    $statement->execute();
                }
            }
        }
        else
            //对单个学生执行
        {
            $sql = "select graduatingTime,forbidden from students where id = ?;";
            $statement = $conn->prepare($sql);
            $statement -> bind_param("s", $id);
            $statement->execute();
            $res = $statement->get_result()->fetch_assoc();
            if(strtotime($res["graduatingTime"]) < strtotime($nowTime))
                DeleteStudent($id);
            else if(strtotime($res["forbidden"]) < strtotime($nowTime))
            {
                $sql = "update students set forbidden = null where id = ?;";
                $statement = $conn->prepare($sql);
                $statement->bind_param("s",$id);
                $statement->execute();
            }
        }
    }

    function AddBorrowPro($id, $num)
        //借专业书/还专业书，还书就传入负值 AddBorrowPro(QuerySNO("3019244253"), 2);
    {
        $conn = Connect();
        UseDatabase($conn);

        $sql = "select borrowProfession from students where id = ?;";
        $statement = $conn->prepare($sql);
        $statement->bind_param("s",$id);
        $statement->execute();
        $result = $statement->get_result()->fetch_array();

        if($result["borrowProfession"] == null)
            $result["borrowProfession"] = 0;

        $result["borrowProfession"] = $result["borrowProfession"] + $num;
        if($result["borrowProfession"] < 0)
            die("ERROR: <0");
        /*
        try{
            $result["borrowProfession"] = $result["borrowProfession"] + $num;
            if($result["borrowProfession"] < 0)
                throw ...
        }*/

        $sql = "update students set borrowProfession = ? where id = ?;";
        $statement = $conn->prepare($sql);
        $statement->bind_param("ss", $result["borrowProfession"], $id);
        $statement->execute();
    }

    function AddBorrowNotPro($id, $num)
        //借专业书/还专业书，还书就传入负值 AddBorrowNotPro(QuerySNO("3019244253"), -8);
    {
        $conn = Connect();
        UseDatabase($conn);

        $sql = "select borrowNotProfession from students where id = ?;";
        $statement = $conn->prepare($sql);
        $statement->bind_param("s",$id);
        $statement->execute();
        $result = $statement->get_result()->fetch_array();

        if($result["borrowNotProfession"] == null)
            $result["borrowNotProfession"] = 0;

        $result["borrowNotProfession"] = $result["borrowNotProfession"] + $num;
        if($result["borrowNotProfession"] < 0)
            die("ERROR: <0");
        /*
        try{
            $result["borrowNotProfession"] = $result["borrowNotProfession"] + $num;
            if($result["borrowNotProfession"] < 0)
                throw ...
        }*/

        $sql = "update students set borrowNotProfession = ? where id = ?;";
        $statement = $conn->prepare($sql);
        $statement->bind_param("ss", $result["borrowNotProfession"], $id);
        $statement->execute();
    }

    function QueryMaxBorrow($id)
    {
        $conn = Connect();
        UseDatabase($conn);

        $sql = "select borrowNotProfession from students where id = ?;";
        $statement = $conn->prepare($sql);
        $statement->bind_param("s",$id);
        $statement->execute();
        $resultNotPro = $statement->get_result()->fetch_array();

        $sql = "select borrowProfession from students where id = ?;";
        $statement = $conn->prepare($sql);
        $statement->bind_param("s",$id);
        $statement->execute();
        $resultPro = $statement->get_result()->fetch_array();

        if($resultNotPro["borrowNotProfession"] > 10 || $resultPro["borrowProfession"] > 20)
            return true;

        return false;
    }
//echo QuerySNO("3019244253");
//AddBorrowNotPro(QuerySNO("3019244253"), -8);
InitStudents();
