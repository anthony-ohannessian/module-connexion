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
$result_find_a_login = mysqli_fetch_all($query_login, MYSQLI_ASSOC);

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
};

$admin_login = 'admin13003';
$find_an_admin = "SELECT `login` FROM `users` WHERE login = '$admin_login'";
$query_find_an_admin = mysqli_query($mysqli, $find_an_admin);
$result_find_an_admin = mysqli_fetch_all($query_find_an_admin, MYSQLI_ASSOC);

if (!$result_find_an_admin) {

    $admin_first_name = 'Admin';
    $admin_last_name = 'Admin';

    $admin_password = '@Admin13003';
    $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);

    $role = 'admin';
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
            '$admin_login',
            '$admin_first_name',
            '$admin_last_name',
            '$hashed_password',
            '$role',
            '$date',
            '$date',
            '$date'
            )";

    if (mysqli_query($mysqli, $filled_data_base)) {
        console_log('Records from "admin profile" added successfully in database.');
    } else {
        console_log('ERROR: Could not able to execute'.$filled_data_base.'""'.mysqli_error($mysqli));
    }
};

$_SESSION = null;
$login = null;
$first_name = null;
$last_name = null;
$role = null;

$error = '';
$error_signup = false;
$error_login = false;

console_log('$_SESSION is "'.$_SESSION.'".');
console_log('$login is "'.$login.'".');
console_log('$first_name is "'.$first_name.'".');
console_log('$last_name is "'.$last_name.'".');
console_log('$role is "'.$role.'".');

if ($_POST['login'] && $_POST['firstname'] && $_POST['lastname'] && $_POST['password'] && $_POST['passwordconfirm']) {

    $login = $_POST['login'];
    $first_name = ucfirst($_POST['firstname']);
    $last_name = ucfirst($_POST['lastname']);
    $pwd = $_POST['password'];
    $hashed_password = password_hash($pwd, PASSWORD_DEFAULT);

    $role = 'users';

    $date = date('Y-m-d h:i:s', time());

    console_log("the login is ".$login."'.");
    console_log("the firstname is ".$first_name."'.");
    console_log("the lastname is ".$last_name."'.");
    console_log("the password is ".$hashed_password."'.");

    $find_a_login = "SELECT `login` FROM `users` WHERE login = '$login'";
    $query_login = mysqli_query($mysqli, $find_a_login);
    $result_find_a_login = mysqli_fetch_all($query_login, MYSQLI_ASSOC);

    console_log('Result find a login is "'.json_encode($result_find_a_login).'".');

    $login_from_database = $result_find_a_login[0][0];

    if (!isset($login_from_database)) {

        console_log("user was not found in database");

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
                '$login',
                '$first_name',
                '$last_name',
                '$hashed_password',
                '$role',
                '$date',
                '$date',
                '$date'
                )";

        if (mysqli_query($mysqli, $filled_data_base)) {

            $error_signup = true;
            $error = 'your account was created with success, now login :)';
            console_log('Records from "'.$login.' profile" added successfully in database.');
        } else {

            $error_login = true;
            console_log('ERROR: Could not able to execute'.$filled_data_base.'""'.mysqli_error($mysqli));
        }
    } else {

        $error_signup = true;
        $error = 'your account already exist, please login :)';
        console_log('Records from "'.$login.' profile" found in database.');
    }
};

if ($_POST['login_login'] && $_POST['login_password']) {

    $login = $_POST['login_login'];
    $password = $_POST['login_password'];

    console_log("the login is ".$login."'.");
    console_log("the password is ".$password."'.");

    $find_a_login = "SELECT * FROM `users` WHERE login = '$login'";
    $query_login = mysqli_query($mysqli, $find_a_login);
    $result_find_a_login = mysqli_fetch_all($query_login, MYSQLI_ASSOC);
    
    console_log('Result find a login is "'.json_encode($result_find_a_login).'".');

    if (!$result_find_a_login) {

        $error_signup = true;
        $error = 'this login wasn\'t found, please try again or signup :)';

        console_log('Records from "'.$login.' profile" not found in database.');
    } else {

        if (password_verify($password, $result_find_a_login[0]['password'])) {

            $error_login = false;
            $error = 'the login matches the password !';

            $date = date('Y-m-d h:i:s', time());
            $login = $_POST['login_login'];
            
            $find_current_user = "SELECT
                    `login`,
                    `firstname`,
                    `lastname`,
                    `role`,
                    `last_connexion_at`,
                    `modified_at`
                FROM
                    `users`
                WHERE
                    login = '$login'";

            $query_current_user = mysqli_query($mysqli, $find_current_user);
            $result_find_current_user = mysqli_fetch_all($query_current_user, MYSQLI_ASSOC);
            console_log('Result find current_user is "'.json_encode($result_find_current_user).'".');

            $updating_last_connexion_at = "UPDATE
                    `users`
                SET
                    `last_connexion_at` = '$date'
                WHERE
                    login = '$login'";

            if (mysqli_query($mysqli, $updating_last_connexion_at)) {

                console_log('Updated last connexion from "'.$login.' profile" added successfully in database.');
            } else {

                console_log('ERROR: Could not able to execute'.$updating_last_connexion_at.'""'.mysqli_error($mysqli));
            }

            $_SESSION['login'] = $result_find_current_user[0]['login'];
            $_SESSION['first_name'] = $result_find_current_user[0]['firstname'];
            $_SESSION['last_name'] = $result_find_current_user[0]['lastname'];
            $_SESSION['role'] = $result_find_current_user[0]['role'];

            console_log('$_SESSION current user is "'.json_encode($_SESSION).'".');
            console_log('$_SESSION[`login`] current user is "'.json_encode($_SESSION['login']).'".');
            console_log('$_SESSION[`first_name`] current user is "'.json_encode($_SESSION['first_name']).'".');
            console_log('$_SESSION[`last_name`] current user is "'.json_encode($_SESSION['last_name']).'".');

            $login = $_SESSION['login'];

            $avatar = $_SESSION['avatar'];

            $first_name = $_SESSION['first_name'];
            $last_name = $_SESSION['last_name'];

            $role = $_SESSION['role'];

            console_log('$login is "'.$login.'".');
            console_log('$first_name is "'.$first_name.'".');
            console_log('$last_name is "'.$last_name.'".');
            console_log('$role is "'.$role.'".');

        } else {

            $error_signup = true;
            $error = 'the login doesn\'t match the password, please try again!';

            console_log('the current login must match the password of the database...');
        }
        
    }
};

if ($_GET['logout'] === true) {

    console_log('$_GET is "'.json_encode($_GET).'".');
    console_log('$_SESSION current user is "'.json_encode($_SESSION).'".');

    session_unset();
    session_destroy();
};


