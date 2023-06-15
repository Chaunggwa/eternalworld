<?php
include("../config.php");
if(!empty($_POST['delete'])) {
  $sql = "TRUNCATE TABLE users_visit_count";
  try {
    $conn->exec($sql);
  } catch(PDOException $e) {
    echo "Something went wrong!";
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

  #customers {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #04AA6D;
  color: white;
}

.info {
  text-align: center;
  font-weight: bold;
  color: rgba(10,10, 200, 0.9);
  font-size: 1.2em;
}

.danger {
  text-align: center;
  font-weight: bold;
  font-size: 1.2em;
  color: rgba(200, 10, 10, 0.9);
}

caption {
  color: #04AA6D;
  font-size: 1.5em;
}

#container {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  align-items: center;
}

#container a {
  outline: none;
  color: #fff;
  background-color: #33e;
  border: none;
  padding: 10px;
  text-decoration: none;
}

#container a:hover {
  background-color: #38e;
}

form input {
  outline: none;
  color: #fff;
  background-color: #e33;
  border: none;
  padding: 10px;
}

form input:hover {
  background-color: #e66;
}
    </style>
</head>
<body>
  <?php
  ?>
  <?php
    if(isset($_COOKIE["admin"])) {
      $stmp = $conn->query("SELECT * FROM users_visit_count");
      $result = $stmp->fetchAll(PDO::FETCH_ASSOC);
      
      if(!isset($_COOKIE['users'])) {
        setcookie("users", count($result), time() + (86400), "/");
      }
      try {
        $diff = count($result) - intval($_COOKIE['users']);
      } catch(\Throwable $th) {
        
      }
    

      $noti =  ($diff > 0) ? "<p class='info'>$diff new users" : "<p class='danger'>No new user!</p>";

      setcookie("users", count($result), time() + (86400), "/");

      echo $noti;
      echo "<div id='container'><form action='" . $_SERVER['PHP_SELF'] . "' method='POST'>"
      . "
      <input type='submit' value='Delete All Users' name='delete' />
      </form>
      <a href='/eternalworld/admin/adduser.php'>Add user</a>
      </div>
      ";

     
      echo "<table border='1' id='customers'>
            <caption>Logged in users</caption>
              <tr>
                <th>No</th>
                <th>email</th>
                <th>Visits count</th>
              </tr>
      ";
      

      for($i = 0; $i < count($result); $i++) {
        $no = $i + 1;
        echo "<tr><td>$no</td><td>" . $result[$i]['email'] . "</td><td>". $result[$i]['visits_count'] . "</td></tr>";
      }

      echo "</table>";
    } else {
      echo "You are not authorized to view this page.";
    }
  ?>
</body>
</html>