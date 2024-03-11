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
                'message' => 'product added Suceessfully',
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

function getproduct($productParams){

    global $conn;

    if($productParams['id'] == null){

        return error422('Enter your product ID');
    }
  $productId = mysqli_real_escape_string($conn, $productParams['id']);
  $query = "SELECT * FROM products WHERE id='$productId' LIMIT 1";
  $result = mysqli_query($conn, $query);

  if($result){

    if(mysqli_num_rows($result) == 1)
    {
          $res = mysqli_fetch_assoc($result);

          $data = [
            'status' => 200,
            'message' => 'Product Fetched Successfully',
            'data'=> $res
        ];
        header("HTTP/1.1 200 OK");
        return json_encode($data);
    }
    else
    {
        $data = [
            'status' => 404,
            'message' => 'No Product Found',
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

function productList()
{
    global $conn;
    if(!$conn){
        die("Connection failed: " . mysqli_connect_error());
    }
    $query = "SELECT * FROM products";

    $query_run = mysqli_query($conn, $query);

    if ($query_run) {

        if (mysqli_num_rows($query_run) > 0) {

            $systemProducts = mysqli_fetch_all($query_run, MYSQLI_ASSOC);

            $data = [
                'status' => 200,
                'message' => 'product List Fetched Successfully',
                'SystemProducts'=>$systemProducts
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


function updateProduct($productInput, $productParams){

    global $conn;

    if(!isset($productParams['id'])){

        return error422('productID not Found in URL');

    }elseif($productParams['id'] == null){
        return error422('Enter The productID');
    }

    $productId = mysqli_real_escape_string($conn, $productParams['id']);

    $id = mysqli_real_escape_string($conn, $productInput['id']);
    $description = mysqli_real_escape_string($conn, $productInput['description']);
    $image = mysqli_real_escape_string($conn, $productInput['image']);
    $pricing = mysqli_real_escape_string($conn, $productInput['pricing']);
    $shipping_cost	 = mysqli_real_escape_string($conn, $productInput['hipping_cost']);

    if(empty(trim($id))){

        return error422('Enter your ID');
            
    }elseif(empty(trim($description))){

        return error422('Enter description');

    }elseif(empty(trim($image))){

        return error422('Input your image');

    }elseif(empty(trim($pricing))){

        return error422('Enter pricing');

    }elseif(empty(trim($shipping_cost))){

        return error422('Enter shipping cost');

    }
    else
    {
          $query = "UPDATE products SET id='$id', description= '$description', image= '$image', pricing='$pricing', shipping_cost= '$shipping_cost' WHERE id ='$productId' LIMIT 1";
          $result = mysqli_query($conn, $query);
          
          if($result){

            $data = [
                'status' => 200,
                'message' => 'product Updated Suceessfully',
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

function deleteProduct($productParams){
    global $conn;
    
    if(!isset($productParams['id'])){

        return error422('ProductID not Found in URL');

    }elseif($productParams['id'] == null){
        return error422('Enter The ProductID');
    }

    $productId = mysqli_real_escape_string($conn, $productParams['id']);

    $query = "DELETE FROM  products WHERE id=$productId LIMIT 1";
    $result = mysqli_query($conn, $query);

    if($result){

        $data = [
            'status' => 204,
            'message' => 'Product Deleted Successfully',
        ];
        header("HTTP/1.1 204 Deleted");
        return json_encode($data);

    }
    else
    {
        $data = [
            'status' => 404,
            'message' => 'Product Not Found',
        ];
        header("HTTP/1.1 404 Not  Found");
        return json_encode($data);
    }

}

function storeOrder($orderInput){

    global $conn;

    $id = mysqli_real_escape_string($conn, $orderInput['id']);
    $cart_id = mysqli_real_escape_string($conn, $orderInput['cart_id']);
    $order_date = mysqli_real_escape_string($conn, $orderInput['order_date']);
    $total = mysqli_real_escape_string($conn, $orderInput['total']);
    

    if(empty(trim($id))){

        return error422('Enter your ID');
            
    }elseif(empty(trim($cart_id))){

        return error422('Enter cart_id');

    }elseif(empty(trim($order_date))){

        return error422('Enter order date');

    }elseif(empty(trim($total))){

        return error422('Enter total');
    }
    else
    {
          $query = "INSERT INTO orders (id,cart_id,order_date,total) VALUES ('$id','$cart_id','$order_date','$total')";
          $result = mysqli_query($conn, $query);
          
          if($result){

            $data = [
                'status' => 201,
                'message' => 'Order Created Suceessfully',
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

function storeComments($commentsInput){

    global $conn;

    $id = mysqli_real_escape_string($conn, $commentsInput['id']);
    $cart_id = mysqli_real_escape_string($conn, $commentsInput['cart_id']);
    $order_date = mysqli_real_escape_string($conn, $commentsInput['order_date']);
    $total = mysqli_real_escape_string($conn, $commentsInput['total']);

    if(empty(trim($id))){

        return error422('Enter your ID');
            
    }elseif(empty(trim($cart_id))){

        return error422('Enter your cart-id');

    }elseif(empty(trim($order_date))){

        return error422('Enter order_date');

    }elseif(empty(trim($total))){

        return error422('Enter total');
    }
    else
    {
          $query = "INSERT INTO comments (id,cart_id,order_date,total) VALUES ('$id','$cart_id','$order_date','$total')";
          $result = mysqli_query($conn, $query);
          
          if($result){

            $data = [
                'status' => 201,
                'message' => 'Comments Created Suceessfully',
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

function storeCart($cartInput){

    global $conn;

    $id = mysqli_real_escape_string($conn, $cartInput['id']);
    $product_ids = mysqli_real_escape_string($conn, $cartInput['product_ids']);
    $quantities = mysqli_real_escape_string($conn, $cartInput['quantities']);
    $user_id = mysqli_real_escape_string($conn, $cartInput['user_id']);

    if(empty(trim($id))){

        return error422('Enter your ID');
            
    }elseif(empty(trim($product_ids))){

        return error422('Enter product_id');

    }elseif(empty(trim($quantities))){

        return error422('Enter quantities');

    }elseif(empty(trim($user_id))){

        return error422('Enter user_id');
    }
    else
    {
          $query = "INSERT INTO cart (id,product_ids,quantities,user_id) VALUES ('$id','$product_ids','$quantities','$user_id')";
          $result = mysqli_query($conn, $query);
          
          if($result){

            $data = [
                'status' => 201,
                'message' => 'cart Created Suceessfully',
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

function cartList()
{
    global $conn;
    if(!$conn){
        die("Connection failed: " . mysqli_connect_error());
    }
    $query = "SELECT * FROM cart";

    $query_run = mysqli_query($conn, $query);

    if ($query_run) {

        if (mysqli_num_rows($query_run) > 0) {

            $systemUsers = mysqli_fetch_all($query_run, MYSQLI_ASSOC);

            $data = [
                'status' => 200,
                'message' => 'cart List Fetched Successfully',
                'SystemUsers'=>$systemUsers
            ];
            header("HTTP/1.1 200 OK");
            header('Content-Type: application/json');
            echo json_encode($data);
        } else {
            $data = [
                'status' => 404,
                'message' => 'No cart Found',
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

function getCart($cartParams){

    global $conn;

    if($cartParams['id'] == null){

        return error422('Enter your CART ID');
    }
  $cartId = mysqli_real_escape_string($conn, $cartParams['id']);
  $query = "SELECT * FROM cart WHERE id='$cartId' LIMIT 1";
  $result = mysqli_query($conn, $query);

  if($result){

    if(mysqli_num_rows($result) == 1)
    {
          $res = mysqli_fetch_assoc($result);

          $data = [
            'status' => 200,
            'message' => 'Cart Fetched Successfully',
            'data'=> $res
        ];
        header("HTTP/1.1 200 OK");
        return json_encode($data);
    }
    else
    {
        $data = [
            'status' => 404,
            'message' => 'No cart Found',
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

function commentsList()
{
    global $conn;
    if(!$conn){
        die("Connection failed: " . mysqli_connect_error());
    }
    $query = "SELECT * FROM comments";

    $query_run = mysqli_query($conn, $query);

    if ($query_run) {

        if (mysqli_num_rows($query_run) > 0) {

            $systemUsers = mysqli_fetch_all($query_run, MYSQLI_ASSOC);

            $data = [
                'status' => 200,
                'message' => 'comments Fetched Successfully',
                'SystemUsers'=>$systemUsers
            ];
            header("HTTP/1.1 200 OK");
            header('Content-Type: application/json');
            echo json_encode($data);
        } else {
            $data = [
                'status' => 404,
                'message' => 'No comments Found',
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

function orderList()
{
    global $conn;
    if(!$conn){
        die("Connection failed: " . mysqli_connect_error());
    }
    $query = "SELECT * FROM orders";

    $query_run = mysqli_query($conn, $query);

    if ($query_run) {

        if (mysqli_num_rows($query_run) > 0) {

            $systemUsers = mysqli_fetch_all($query_run, MYSQLI_ASSOC);

            $data = [
                'status' => 200,
                'message' => 'order Fetched Successfully',
                'SystemUsers'=>$systemUsers
            ];
            header("HTTP/1.1 200 OK");
            header('Content-Type: application/json');
            echo json_encode($data);
        } else {
            $data = [
                'status' => 404,
                'message' => 'No order Found',
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

function getComments($commentsParams){

    global $conn;

    if($commentsParams['id'] == null){

        return error422('Enter your CART ID');
    }
  $commentsId = mysqli_real_escape_string($conn, $commentsParams['id']);
  $query = "SELECT * FROM comments WHERE id='$commentsId' LIMIT 1";
  $result = mysqli_query($conn, $query);

  if($result){

    if(mysqli_num_rows($result) == 1)
    {
          $res = mysqli_fetch_assoc($result);

          $data = [
            'status' => 200,
            'message' => 'Comments Fetched Successfully',
            'data'=> $res
        ];
        header("HTTP/1.1 200 OK");
        return json_encode($data);
    }
    else
    {
        $data = [
            'status' => 404,
            'message' => 'No comments Found',
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


function getOrder($orderParams){

    global $conn;

    if($orderParams['id'] == null){

        return error422('Enter your order ID');
    }
  $orderId = mysqli_real_escape_string($conn, $orderParams['id']);
  $query = "SELECT * FROM orders WHERE id='$orderId' LIMIT 1";
  $result = mysqli_query($conn, $query);

  if($result){

    if(mysqli_num_rows($result) == 1)
    {
          $res = mysqli_fetch_assoc($result);

          $data = [
            'status' => 200,
            'message' => 'Order Fetched Successfully',
            'data'=> $res
        ];
        header("HTTP/1.1 200 OK");
        return json_encode($data);
    }
    else
    {
        $data = [
            'status' => 404,
            'message' => 'No Order Found',
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

function updateComments($commentsInput, $commentsParams){

    global $conn;

    if(!isset($commentsParams['id'])){

        return error422('commentsID not Found in URL');

    }elseif($commentsParams['id'] == null){
        return error422('Enter The commentsID');
    }

    $commentsId = mysqli_real_escape_string($conn, $commentsParams['id']);

    $id = mysqli_real_escape_string($conn, $commentsInput['id']);
    $product_id = mysqli_real_escape_string($conn, $commentsInput['product_id']);
    $user_id = mysqli_real_escape_string($conn, $commentsInput['user_id']);
    $rating = mysqli_real_escape_string($conn, $commentsInput['rating']);
    $image = mysqli_real_escape_string($conn, $commentsInput['image']);
    $text = mysqli_real_escape_string($conn, $commentsInput['text']);

    if(empty(trim($id))){

        return error422('Enter your ID');
            
    }elseif(empty(trim($product_id))){

        return error422('Enter product_id');

    }elseif(empty(trim($user_id))){

        return error422('Input user_id');

    }elseif(empty(trim($rating))){

        return error422('Enter rating');

    }elseif(empty(trim($image))){

        return error422('input your image');

    }elseif(empty(trim($text))){

        return error422('Enter text');

    }
    else
    {
          $query = "UPDATE comments SET id='$id', product_id= '$product_id', user_id= '$user_id', rating='$rating', image= '$image',text='$text' WHERE id ='$commentsId' LIMIT 1";
          $result = mysqli_query($conn, $query);
          
          if($result){

            $data = [
                'status' => 200,
                'message' => 'comments Updated Suceessfully',
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


function updateCart($cartInput, $cartParams){

    global $conn;

    if(!isset($cartParams['id'])){

        return error422('productID not Found in URL');

    }elseif($cartParams['id'] == null){
        return error422('Enter The productID');
    }

    $cartId = mysqli_real_escape_string($conn, $cartParams['id']);

    $id = mysqli_real_escape_string($conn, $cartInput['id']);
    $product_ids = mysqli_real_escape_string($conn, $$cartInput['product_ids']);
    $quantities = mysqli_real_escape_string($conn, $cartInput['quantities']);
    $user_id = mysqli_real_escape_string($conn, $cartInput['user_id']);

    if(empty(trim($id))){

        return error422('Enter your ID');
            
    }elseif(empty(trim($product_ids))){

        return error422('Enter description');

    }elseif(empty(trim($quantities))){

        return error422('Input your image');

    }elseif(empty(trim($user_id))){

        return error422('Enter pricing');
    }
    else
    {
          $query = "UPDATE cart SET id='$id', product_ids= '$product_ids', quantities= '$quantities', user_id='$user_id' WHERE id ='$cartId' LIMIT 1";
          $result = mysqli_query($conn, $query);
          
          if($result){

            $data = [
                'status' => 200,
                'message' => 'Cart Updated Suceessfully',
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


function updateOrder($orderInput, $orderParams){

    global $conn;

    if(!isset($orderParams['id'])){

        return error422('orderID not Found in URL');

    }elseif($orderParams['id'] == null){
        return error422('Enter The orderID');
    }

    $orderId = mysqli_real_escape_string($conn, $orderParams['id']);

    $id = mysqli_real_escape_string($conn, $orderInput['id']);
    $cart_id = mysqli_real_escape_string($conn, $orderInput['cart_id']);
    $order_date = mysqli_real_escape_string($conn, $orderInput['order_date']);
    $total= mysqli_real_escape_string($conn, $orderInput['total']);

    if(empty(trim($id))){

        return error422('Enter your ID');
            
    }elseif(empty(trim($cart_id))){

        return error422('Enter description');

    }elseif(empty(trim($order_date))){

        return error422('Input your image');

    }elseif(empty(trim($total))){

        return error422('Enter pricing');
    }
    else
    {
          $query = "UPDATE orders SET id='$id', cart_id= '$cart_id', order_date='$order_date', total= '$total' WHERE id ='$orderId' LIMIT 1";
          $result = mysqli_query($conn, $query);
          
          if($result){

            $data = [
                'status' => 200,
                'message' => 'order Updated Suceessfully',
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

function deleteCart($cartParams){
    global $conn;
    
    if(!isset($cartParams['id'])){

        return error422('CartID not Found in URL');

    }elseif($cartParams['id'] == null){
        return error422('Enter The CartID');
    }

    $cartId = mysqli_real_escape_string($conn, $cartParams['id']);

    $query = "DELETE FROM  cart WHERE id=$cartId LIMIT 1";
    $result = mysqli_query($conn, $query);

    if($result){

        $data = [
            'status' => 204,
            'message' => 'cart Deleted Successfully',
        ];
        header("HTTP/1.1 204 Deleted");
        return json_encode($data);

    }
    else
    {
        $data = [
            'status' => 404,
            'message' => 'cart Not Found',
        ];
        header("HTTP/1.1 404 Not  Found");
        return json_encode($data);
    }

}


function deleteOrder($orderParams){
    global $conn;
    
    if(!isset($orderParams['id'])){

        return error422('orderID not Found in URL');

    }elseif($orderParams['id'] == null){
        return error422('Enter The orderID');
    }

    $orderId = mysqli_real_escape_string($conn, $orderParams['id']);

    $query = "DELETE FROM  orders WHERE id=$orderId LIMIT 1";
    $result = mysqli_query($conn, $query);

    if($result){

        $data = [
            'status' => 204,
            'message' => 'order Deleted Successfully',
        ];
        header("HTTP/1.1 204 Deleted");
        return json_encode($data);

    }
    else
    {
        $data = [
            'status' => 404,
            'message' => 'order Not Found',
        ];
        header("HTTP/1.1 404 Not  Found");
        return json_encode($data);
    }

}


function deleteComments($commentsParams){
    global $conn;
    
    if(!isset($commentsParams['id'])){

        return error422('commentID not Found in URL');

    }elseif($commentsParams['id'] == null){
        return error422('Enter The commentID');
    }

    $commentsId = mysqli_real_escape_string($conn, $commentsParams['id']);

    $query = "DELETE FROM  comments WHERE id=$commentsId LIMIT 1";
    $result = mysqli_query($conn, $query);

    if($result){

        $data = [
            'status' => 204,
            'message' => 'comments Deleted Successfully',
        ];
        header("HTTP/1.1 204 Deleted");
        return json_encode($data);

    }
    else
    {
        $data = [
            'status' => 404,
            'message' => 'comments Not Found',
        ];
        header("HTTP/1.1 404 Not  Found");
        return json_encode($data);
    }

}


?>
