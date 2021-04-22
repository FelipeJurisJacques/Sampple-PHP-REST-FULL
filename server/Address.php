<?php
abstract class Address {
    public static bool $isDebug = true;
}

$con = new Connection();
$con->host = 'localhost';
$con->port = 3606;
$con->name = 'crud';
$con->user = 'root';
$con->password = '';
Repository::add($con);