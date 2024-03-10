<?php

require 'database_connection.php';

function error422($message){
    $data = [
        'status' => 422,
        'message' => $message,
   ];
   header("HTTP/1.0 422 Unprocessable entity");
   echo json_encode($data);
   exit();
}


function storeUser($userInput){

    global $conn;

    $id = mysqli_real_escape_string($conn, $userInput['id']);
    $email = mysqli_real_escape_string($conn, $userInput['email']);
    $password = mysqli_real_escape_string($conn, $userInput['password']);
    $username = mysqli_real_escape_string($conn, $userInput['username']);
    $purchase_history = mysqli_real_escape_string($conn, $userInput['purchase_history']);
    $shipping_address = mysqli_real_escape_string($conn, $userInput['shipping_address']);

    if(empty(trim($id))){

        return error422('Enter your ID');
            
    }elseif(empty(trim($email))){

        return error422('Enter your email');

    }elseif(empty(trim($password))){

        return error422('Enter your password');

    }elseif(empty(trim($username))){

        return error422('Enter your username');

    }elseif(empty(trim($purchase_history))){

        return error422('Enter your purchase history');

    }elseif(empty(trim($shipping_address))){

        return error422('Enter your shipping address');
    }
    else
    {
          $query = "INSERT INTO users (id,email,password,username,purchase_history,shipping_address) VALUES ('$id','$email','$password','$username','$purchase_history','$shipping_address')";
          $result = mysqli_query($conn, $query);
          
          if($result){

            $data = [
                'status' => 201,
                'message' => 'User Created Suceessfully',
            ];
            header("HTTP/1.1 201 Created");
            header('Content-Type: application/json');
            echo json_encode($data);
          }

          else{
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
            ];
            header("HTTP/1.1 500 Internal Server Error");
            header('Content-Type: application/json');
            echo json_encode($data);
          }
    }
}

function userList()
{
    global $conn;
    if(!$conn){
        die("Connection failed: " . mysqli_connect_error());
    }
    $query = "SELECT * FROM users";

    $query_run = mysqli_query($conn, $query);

    if ($query_run) {

        if (mysqli_num_rows($query_run) > 0) {

            $systemUsers = mysqli_fetch_all($query_run, MYSQLI_ASSOC);

            $data = [
                'status' => 200,
                'message' => 'User List Fetched Successfully',
                'SystemUsers'=>$systemUsers
            ];
            header("HTTP/1.1 200 OK");
            header('Content-Type: application/json');
            echo json_encode($data);
        } else {
            $data = [
                'status' => 404,
                'message' => 'No User Found',
            ];
            header("HTTP/1.1 404 Not Found");
            header('Content-Type: application/json');
            echo json_encode($data);
        }
    } else {

        $data = [
            'status' => 500,
            'message' => 'Internal Server Error',
        ];
        header("HTTP/1.1 500 Internal Server Error");
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}

function getUser($userParams){

    global $conn;

    if($userParams['id'] == null){

        return error422('Enter your USer ID');
    }
  $userId = mysqli_real_escape_string($conn, $userParams['id']);
  $query = "SELECT * FROM users WHERE id='$userId' LIMIT 1";
  $result = mysqli_query($conn, $query);

  if($result){

    if(mysqli_num_rows($result) == 1)
    {
          $res = mysqli_fetch_assoc($result);

          $data = [
            'status' => 200,
            'message' => 'Customer Fetched Successfully',
            'data'=> $res
        ];
        header("HTTP/1.1 200 OK");
        return json_encode($data);
    }
    else
    {
        $data = [
            'status' => 404,
            'message' => 'No user Found',
        ];
        header("HTTP/1.1 404 NoT Found");
        return json_encode($data);
    }

  }else{
    $data = [
        'status' => 500,
        'message' => 'Internal Server Error',
    ];
    header("HTTP/1.1 500 Internal Server Error");
    return json_encode($data);
  }

}

function updateUser($userInput, $userParams){

    global $conn;

    if(!isset($userParams['id'])){

        return error422('UserID not Found in URL');

    }elseif($userParams['id'] == null){
        return error422('Enter The CustomerID');
    }

    $userId = mysqli_real_escape_string($conn, $userParams['id']);

    $id = mysqli_real_escape_string($conn, $userInput['id']);
    $email = mysqli_real_escape_string($conn, $userInput['email']);
    $password = mysqli_real_escape_string($conn, $userInput['password']);
    $username = mysqli_real_escape_string($conn, $userInput['username']);
    $purchase_history = mysqli_real_escape_string($conn, $userInput['purchase_history']);
    $shipping_address = mysqli_real_escape_string($conn, $userInput['shipping_address']);

    if(empty(trim($id))){

        return error422('Enter your ID');
            
    }elseif(empty(trim($email))){

        return error422('Enter your email');

    }elseif(empty(trim($password))){

        return error422('Enter your password');

    }elseif(empty(trim($username))){

        return error422('Enter your username');

    }elseif(empty(trim($purchase_history))){

        return error422('Enter your purchase history');

    }elseif(empty(trim($shipping_address))){

        return error422('Enter your shipping address');
    }
    else
    {
          $query = "UPDATE users SET id='$id', email= '$email', password= '$password', username='$username', purchase_history= '$purchase_history',shipping_address='$shipping_address' WHERE id ='$userId' LIMIT 1";
          $result = mysqli_query($conn, $query);
          
          if($result){

            $data = [
                'status' => 200,
                'message' => 'User Updated Suceessfully',
            ];
            header("HTTP/1.1 200 Success");
            header('Content-Type: application/json');
            echo json_encode($data);
          }

          else{
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
            ];
            header("HTTP/1.1 500 Internal Server Error");
            header('Content-Type: application/json');
            echo json_encode($data);
          }
    }
}

function deleteUser($userParams){
    global $conn;
    
    if(!isset($userParams['id'])){

        return error422('UserID not Found in URL');

    }elseif($userParams['id'] == null){
        return error422('Enter The CustomerID');
    }

    $userId = mysqli_real_escape_string($conn, $userParams['id']);

    $query = "DELETE FROM  users WHERE id=$userId LIMIT 1";
    $result = mysqli_query($conn, $query);

    if($result){

        $data = [
            'status' => 204,
            'message' => 'User Deleted Successfully',
        ];
        header("HTTP/1.1 204 Deleted");
        return json_encode($data);

    }
    else
    {
        $data = [
            'status' => 404,
            'message' => 'User Not Found',
        ];
        header("HTTP/1.1 404 Not  Found");
        return json_encode($data);
    }

}


function storeProduct($productInput){

    global $conn;

    $id = mysqli_real_escape_string($conn, $productInput['id']);
    $description = mysqli_real_escape_string($conn, $productInput['description']);
    $image = mysqli_real_escape_string($conn, $productInput['image']);
    $pricing = mysqli_real_escape_string($conn, $productInput['pricing']);
    $shipping_cost = mysqli_real_escape_string($conn, $productInput['shipping_cost']);

    if(empty(trim($id))){

        return error422('Enter your ID');
            
    }elseif(empty(trim($description))){

        return error422('Enter your description');

    }elseif(empty(trim($image))){

        return error422('input your image');

    }elseif(empty(trim($pricing))){

        return error422('Enter pricing');

    }elseif(empty(trim($shipping_cost))){

        return error422('Enter shipping_cost');

    }
    else
    {
          $query = "INSERT INTO products (id,description,image,pricing,shipping_cost) VALUES ('$id','$description','$image','$pricing','$shipping_cost')";
          $result = mysqli_query($conn, $query);
          
          if($result){

            $data = [
                'status' => 201,
                'message' => 'User Created Suceessfully',
            ];
            header("HTTP/1.1 201 Created");
            header('Content-Type: application/json');
            echo json_encode($data);
          }

          else{
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
            ];
            header("HTTP/1.1 500 Internal Server Error");
            header('Content-Type: application/json');
            echo json_encode($data);
          }
    }
}

?>
