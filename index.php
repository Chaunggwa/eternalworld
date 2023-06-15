<?php
include("./config.php");
$errorMessage = '';

if(!empty($_POST["login"]) && $_POST["email"]!=''&& $_POST["password"]!='') {	
    $email = $_POST['email'];
    $password = $_POST['password'];

  
   // Get the visited users
   try {
    $stmp = $conn->query("SELECT * FROM users_visit_count");
    $result = $stmp->fetchAll(PDO::FETCH_ASSOC);
   } catch (PDOException $e) {
      echo "Something went wrong!";
   }
  
    // State flags
    $is_in_users_visit_count = false;
    $is_in_users = false;
    $is_in_admin = false;

      for($i = 0; $i < count($result); $i++) {
        if($email == $result[$i]["email"] && $password == $result[$i]["password"]) {
          $new_visits_count = intval($result[$i]["visits_count"]) + 1;
          $sql = "UPDATE users_visit_count
                 SET visits_count = $new_visits_count
                 WHERE id = " . $result[$i]["id"];
                 try {
                  $conn->exec($sql);
                 } catch(PDOException $e) {
                   echo "Something went wrong!";
                 }
         
          $is_in_users_visit_count = true;
          // header("Location: https://www.google.com");
          break;
        }
      }

      if(!$is_in_users_visit_count) {
        // Check in users database
        try {
          $stmp = $conn->query("SELECT * FROM users");
          $result = $stmp->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
          echo "Something went wrong!";
        }
       
       
        echo "<br>";
        for ($i = 0; $i < count($result); $i++) {
          if($email == $result[$i]["email"] && $password == $result[$i]["password"]) {
            
            $sql = "INSERT INTO users_visit_count(email, password, visits_count)
                    VALUES('$email', '$password', 1)";
                    try {
                      $conn->exec($sql);
                    } catch (PDOException $th) {
                      echo "Something went wrong!";
                    }
          
            // header("Location: https:://www.google.com");
            $is_in_users = true;
            break;
          }
        }
      }

      try {
        $stmp = $conn->query("SELECT * FROM admin");
      $result = $stmp->fetchAll(PDO::FETCH_ASSOC);
      } catch(PDOException $e) {
        echo "Something went wrong!";
      }
      
      if(!$is_in_users_visit_count && !$is_in_users) {
        for ($i = 0; $i < count($result); $i++) {
          if($email == $result[$i]["email"] && $password == $result[$i]["password"]) {
            
           
            $is_in_admin = true;
            setcookie("admin", $result[$i]["username"], time() + (30 * 86400), "/");
            header("Location: /eternalworld/admin/index.php");
            break;
          }
        }
      }
      

      

      if(!$is_in_users_visit_count && !$is_in_users && !$is_in_admin) {
        echo "You haven't signed up! Sign up please";
      }
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="./assets/index.css" /> -->
    <title>Eternal World</title>
    <style>
        body {
    background-color: rgba(250, 250, 250, 0.7);
    font-family: "Asap", sans-serif;
  }

  .container {
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .description {
    text-align: center;
    background-color: #fff;
    padding: 12px;
    border-radius: 5px;
  }
  
  .login {
    overflow: hidden;
    background-color: white;
    padding: 40px 30px 30px 30px;
    border-radius: 10px;
    position: absolute;
    top: 50%;
    left: 50%;
    width: 100%;
    max-width: 300px;
    -webkit-transform: translate(-50%, -50%);
    -moz-transform: translate(-50%, -50%);
    -ms-transform: translate(-50%, -50%);
    -o-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
    -webkit-transition: -webkit-transform 300ms, box-shadow 300ms;
    -moz-transition: -moz-transform 300ms, box-shadow 300ms;
    transition: transform 300ms, box-shadow 300ms;
    box-shadow: 5px 10px 10px rgba(2, 128, 144, 0.2);
  }
  .login::before, .login::after {
    content: "";
    position: absolute;
    width: 600px;
    height: 600px;
    border-top-left-radius: 40%;
    border-top-right-radius: 45%;
    border-bottom-left-radius: 35%;
    border-bottom-right-radius: 40%;
    z-index: -1;
  }
  .login::before {
    left: 40%;
    bottom: -130%;
    background-color: rgba(69, 105, 144, 0.15);
    -webkit-animation: wawes 6s infinite linear;
    -moz-animation: wawes 6s infinite linear;
    animation: wawes 6s infinite linear;
  }
  .login::after {
    left: 35%;
    bottom: -125%;
    background-color: rgba(2, 128, 144, 0.2);
    -webkit-animation: wawes 7s infinite;
    -moz-animation: wawes 7s infinite;
    animation: wawes 7s infinite;
  }
  .login > input {
    font-family: "Asap", sans-serif;
    display: block;
    border-radius: 5px;
    font-size: 16px;
    background: white;
    width: 100%;
    border: 0;
    padding: 10px 10px;
    margin: 15px -10px;
  }
  .login > input[type=submit] {
    font-family: "Asap", sans-serif;
    cursor: pointer;
    color: #fff;
    font-size: 16px;
    text-transform: uppercase;
    width: 80px;
    border: 0;
    padding: 10px 0;
    margin-top: 10px;
    margin-left: -5px;
    border-radius: 5px;
    background-color: #f45b69;
    -webkit-transition: background-color 300ms;
    -moz-transition: background-color 300ms;
    transition: background-color 300ms;
  }
  .login > button:hover {
    background-color: #f24353;
  }
  
  @-webkit-keyframes wawes {
    from {
      -webkit-transform: rotate(0);
    }
    to {
      -webkit-transform: rotate(360deg);
    }
  }
  @-moz-keyframes wawes {
    from {
      -moz-transform: rotate(0);
    }
    to {
      -moz-transform: rotate(360deg);
    }
  }
  @keyframes wawes {
    from {
      -webkit-transform: rotate(0);
      -moz-transform: rotate(0);
      -ms-transform: rotate(0);
      -o-transform: rotate(0);
      transform: rotate(0);
    }
    to {
      -webkit-transform: rotate(360deg);
      -moz-transform: rotate(360deg);
      -ms-transform: rotate(360deg);
      -o-transform: rotate(360deg);
      transform: rotate(360deg);
    }
  }
  
    </style>
</head>
<body>
    <div class="container"><p class="description">Coin ရယူရန် Login ပြုလုပ်ပါ</p></div>
    <div class="container"><p class="description" style="color: red"><?php echo $errorMessage; ?></p></div>
<form class="login" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  <input type="email" name="email" placeholder="Email">
  <input type="password" name="password" placeholder="Password">
  <input type="submit" value="Login" name="login">
  Don't have an account? <a href="">Sign in</a> here.
</form>

</body>
</html>