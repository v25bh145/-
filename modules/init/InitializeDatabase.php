<?php
    //连接到数据库
    require_once "../Connect.php";
    $conn = Connect();

    //如果没有数据库，则创建一个数据库
    $sql = "create database if not exists library;";
    $conn->query($sql);

    //选择这个数据库
    $sql = "use library;";
    $conn->query($sql);

    //如果没有数据表，创建这些数据表

    $sql = "create table if not exists account".
        "(".
        "id int unsigned auto_increment,".
        "SNO char(10) not null,".
        "IDCard char(18) not null,".
        "professionID int not null,".
        "name varchar(100) not null,".
        "password varchar(200) not null,".
        "graduatingTime datetime not null,".
        "forbidden datetime,".
        "borrowNotProfession int,".
        "borrowProfession int,".
        "primary key(id)".
        ");";
    $conn->query($sql);

    $sql = "create table if not exists users".
        "(".
        "id int unsigned auto_increment,".
        "studentID int not null,".
        "primary key(id)".
        ");";
    $conn->query($sql);

    $sql = "create table if not exists ops".
        "(".
        "id int unsigned auto_increment,".
        "studentID int not null,".
        "primary key(id)".
        ");";
    $conn->query($sql);

    $sql = "create table if not exists book".
        "(".
        "id int unsigned auto_increment,".
        "name varchar(100) not null,".
        "inLibrary int,".
        "outLibrary int,".
        "professionID int not null,".
        "tags varchar(200),".//每四位为一个标签id，缺省0
        "primary key(id)".
        ");";
    $conn->query($sql);

    $sql = "create table if not exists tag".
        "(".
        "id int unsigned auto_increment,".
        "name varchar(100) not null,".
        "primary key(id)".
        ");";
    $conn->query($sql);

    $sql = "create table if not exists profession".
        "(".
        "id int unsigned auto_increment,".
        "name varchar(100) not null,".
        "primary key(id)".
        ");";
    $conn->query($sql);

    $sql = "create table if not exists leadingCard".
        "(".
        "id int unsigned auto_increment,".
        "bookID int not null,".
        "studentID int not null,".
        "leadingTime timestamp default current_timestamp(),".
        "returnTime datetime not null,".
        "primary key(id)".
        ");";
    $conn->query($sql);

