<?php
$db_host = 'ec2-50-17-203-84.compute-1.amazonaws.com';
$db_name = 'd7g8dnm8c63iun';
$db_user_name = 'iuzqekdvmljdic';
$db_password = '74a1a9a790154922b9d770bd29fef5c8634797e15581c065db1dc2dfa237f0d7';

try {

    $db_connection = new PDO("pgsql:dbname=$db_name;host=$db_host", $db_user_name, $db_password);
    $db_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    echo $e->getMessage();
}

