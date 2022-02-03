<?php
session_start();

$db_host = '127.0.0.1';
$db_user = 'root';
$db_password = 'root';
$db_db = 'moduleconnexion';
$db_port = 8889;

$mysqli = new mysqli(
    $db_host, 
    $db_user, 
    $db_password, 
    $db_db, 
    $db_port);

function console_log($output, $with_script_tags = true)
{
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . ');';
    if ($with_script_tags)
    {
        $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
}

function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}

if ($mysqli->connect_error) {
    console_log($mysqli->connect_errno);
    console_log($mysqli->connect_error);
    exit();
} else {
    $success = 'Hello from the database - database connected';
    console_log($success);
}

$create_users_tab = "CREATE TABLE users(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    login VARCHAR(32) NOT NULL,
    firstname VARCHAR(32) NOT NULL,
    lastname VARCHAR(32) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(32) NOT NULL,
    created_at DATETIME,
    last_connexion_at DATETIME,
    modified_at DATETIME
    )";

if ($mysqli->query($create_users_tab) === TRUE) {
    $success = "Table 'users' created successfully";
    console_log($success);
} else {
    console_log($mysqli->error);
}

$find_a_login = "SELECT `login` FROM `users`";
$query_login = mysqli_query($mysqli, $find_a_login);
$result_find_a_login = mysqli_fetch_all($query_login);

console_log('Result find a login is "'.json_encode($result_find_a_login).'".');

if (!$result_find_a_login) {

    $login = [];

    $first_name = [];
    $last_name = [];

    $readjson = file_get_contents('https://jsonplaceholder.typicode.com/users');
    $data = json_decode($readjson, true);
    $data_length = count($data);

    $log_data = 'data is "'.json_encode($data).'".';

    $num5 = substr($data[5]["name"], 5);
    $num7 = substr($data[7]["name"], 0, -2);
    $data[5]["name"] = $num5;
    $data[7]["name"] = $num7;

    for ($i = 0; $i < $data_length; $i++) {

        $login[] = $data[$i]["username"];

        $log_data_name = 'data name number '.$i.' is "'.json_encode($data[$i]['name']).'".';
        
        $name_split = explode(' ', $data[$i]["name"]);
        $name_split_length = count($name_split);
    
        for ($y = 0; $y < $name_split_length; $y++) {
            if ($y%2 == 0) {
                array_push($first_name, $name_split[$y]);
            } else {
                array_push($last_name, $name_split[$y]);
            }
        }
        
        $comb = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $shfl = str_shuffle($comb);
        $password = substr($shfl,0,8);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $role = 'users';
        $date = date('Y-m-d h:i:s', time());

        $filled_data_base = "INSERT INTO `users`(
            `login`,
            `firstname`, 
            `lastname`, 
            `password`,
            `role`, 
            `created_at`,
            `last_connexion_at`, 
            `modified_at`
            ) VALUES (
                '$login[$i]',
                '$first_name[$i]',
                '$last_name[$i]',
                '$hashed_password',
                '$role',
                '$date',
                '$date',
                '$date'
                )";

        if (mysqli_query($mysqli, $filled_data_base)) {
            console_log('Records from "json fake users" added successfully in database.');
        } else {
            console_log('ERROR: Could not able to execute'.$filled_data_base.'""'.mysqli_error($mysqli));
        }
    }
}