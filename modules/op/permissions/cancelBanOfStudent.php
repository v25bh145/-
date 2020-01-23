<?php
/**
 * 此文件用于管理员进行取消封禁操作
 *
 * @author v25bh145
 * @version 1.00
 */
include_once "../OpOnStudents.php";
include_once "../OpOnLeadingCards.php";
include_once "information.php";
Examine();
$SNO = $_POST['SNO'];
CancelForbidden($SNO);