<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Header: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With ");

$host = "localhost:3307";
$user = "root";
$password = "";
$dbname = "essay_helpers";

$con = mysqli_connect($host, $user, $password, $dbname);

session_start();



// if (isset($_POST['submit'])) {
//     //extract($_POST);
//     $email = mysqli_real_escape_string($con, $_POST['email']);
//     $password = mysqli_real_escape_string($con, $_POST['user_pass']);
//     $check = mysqli_query($con, "select * from tbl_users where email='$email' and pwd='$password'");
//     $check_fetch = mysqli_fetch_array($check);

//     if ($check_fetch['id'] != '') {
//         $_SESSION['id'] = $check_fetch['id'];

//         $_SESSION['email'] = $email;
//         $_SESSION['id'] = $id;

//         header('location:../order.php');
//     }
// }


$data = json_decode(file_get_contents("php://input"));
// print_r($data);

$email = $data->email;
$password = $data->password;

$result = mysqli_query($con, "SELECT * from admin WHERE email='" . $email . "' AND password='" . $password . "'");


$nums = mysqli_num_rows($result);
$rs = mysqli_fetch_array($result);
$check_fetch = mysqli_fetch_array($result);

if ($nums >= 1) {
    http_response_code(200);
    $outp = "";

    $outp .= '{"email":"' . $rs["email"] . '", ';
    $outp .= '{"username":"' . $rs["username"] . '", ';
    $outp .= '{"id":"' . $rs["id"] . '", ';
    $outp .= '{"Status":"200"}';

    echo $outp;

    $ad_id = $rs["id"];
    // print_r($outp);
    // print_r($data);
    // print_r(http_response_code());

    $rslt = mysqli_query($con, "SELECT * from admin WHERE id = $ad_id");



    // $_SESSION['id'] = $rs["id"];
    // $ad_id = $rs["id"];
    // print_r($ad_id);

} else {
    http_response_code(202);
    print_r(http_response_code());
    print_r($data);
}

// =================================================================


// if ($check_fetch['id'] != '') {

//     $_SESSION['id'] = $check_fetch['id'];

//     $_SESSION['email'] = $email;

//     print_r($email);
// };

if (mysqli_num_rows($rslt) > 0) {
    // while (
    $row = mysqli_fetch_array($rslt);
    // ) {
    $viewjson["id"] = $row['id'];
    $viewjson["first_name"] = $row['first_name'];
    // $viewjson["name"] = $row['first_name'] + $row['last_name'];
    $viewjson["email"] = $row['email'];
    $viewjson["created_at"] = $row['created_at'];
    $json_array["admindata"][] = $viewjson;
    // }
    echo json_encode(["success" => true, "adminlist" => $json_array]);
    return;
}

echo "This is the login page test page";
