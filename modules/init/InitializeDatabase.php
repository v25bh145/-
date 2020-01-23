<?php
/**
 * 此文件用于创建数据库
 *
 * @author v25bh145
 * @version 1.00
 */
    //连接到数据库
    include_once "../connect.php";
    $conn = Connect();

    //如果没有数据库，则创建一个数据库
    $sql = "create database if not exists library;";
    $conn->query($sql);

    //选择这个数据库
    $sql = "use library;";
    $conn->query($sql);

    //如果没有数据表，创建这些数据表

//对于学生，密码为空就是未注册
    $sql = "create table if not exists students".
        "(".
        "id int unsigned auto_increment,".
        "SNO char(10) not null,".
        "IDCard char(18) not null,".
        "professionID int not null,".
        "name varchar(100) not null,".
        "graduatingTime date not null,".
        "forbidden datetime,".
        "borrowNotProfession int,".
        "borrowProfession int,".
        "primary key(id)".
        ")ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    $conn->query($sql);

    $sql = "create table if not exists users".
        "(".
        "id int unsigned auto_increment,".
        "studentID int not null,".
        "password varchar(200),".
        "primary key(id)".
        ")ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    $conn->query($sql);

    $sql = "create table if not exists ops".
        "(".
        "id int unsigned auto_increment,".
        "studentID int not null,".
        "password varchar(200),".
        "primary key(id)".
        ")ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    $conn->query($sql);

    $sql = "create table if not exists books".
        "(".
        "id int unsigned auto_increment,".
        "name varchar(100) not null,".
        "author varchar(100) not null,".
        "description varchar(500),".
        "inLibrary int,".
        "outLibrary int,".
        "professionID int not null,".
        "num_of_tags int,".//应该是不需要的，不过我怕其他地方用了这个...没敢删
        "primary key(id)".
        ")ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    $conn->query($sql);

    $sql = "create table if not exists tags".
        "(".
        "id int unsigned auto_increment,".
        "name varchar(100) not null,".
        "bookID int not null,".
        "primary key(id)".
        ")ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    $conn->query($sql);

    $sql = "create table if not exists professions".
        "(".
        "id int unsigned auto_increment,".
        "name varchar(100) not null,".
        "primary key(id)".
        ")ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    $conn->query($sql);

    $sql = "create table if not exists leadingCards".
        "(".
        "id int unsigned auto_increment,".
        "bookID int not null,".
        "studentID int not null,".
        "leadingTime timestamp default current_timestamp(),".
        "returnTime datetime not null,".
        "primary key(id)".
        ")ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    $conn->query($sql);

