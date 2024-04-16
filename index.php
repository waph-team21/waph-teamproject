<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Welcome</title>
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      background-color: #f0f0f0;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 500px;
      margin: 50px auto;
      padding: 30px;
      background-color: #ffffff;
      border-radius: 8px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
      text-align: center;
    }
    h1 {
      color: #2196f3;
    }
    h2 {
      color: #333;
      margin-bottom: 20px;
    }
    #digit-clock {
      font-size: 16px;
      color: #888;
      margin-bottom: 20px;
    }
    .form {
      margin-top: 20px;
    }
    .text_field {
      width: calc(100% - 24px);
      padding: 12px;
      margin-top: 10px;
      border: 1px solid #ccc;
      border-radius: 25px;
      box-shadow: none;
      transition: border-color 0.3s ease;
    }
    .text_field:focus {
      border-color: #2196f3;
    }
    .button {
      display: inline-block;
      padding: 15px 30px;
      margin-top: 20px;
      background-color: #2196f3;
      color: white;
      border: none;
      border-radius: 25px;
      cursor: pointer;
      transition: background-color 0.3s ease, transform 0.2s ease;
    }
    .button:hover {
      background-color: #0c7cd5;
      transform: translateY(-3px);
    }
  </style>
</head>
<body>
<?php
    //session_set_cookie_params(15*60,"/","waph-team21.minifacebook.com",TRUE,TRUE);
	session_start(); 
	if (isset($_POST["username"]) and isset($_POST["password"])) {

	    if (checklogin_mysql($_POST["username"],$_POST["password"])) {

		    $_SESSION['authenticated'] = TRUE;
		    $_SESSION['username']= $_POST["username"];
		//$sanitized_username = htmlspecialchars($_POST['username'])
	    }else{
		    session_destroy();
		    echo "<script>alert('Invalid username/password');window.location='form.php';</script>";
		    die();
	}
}
	if (!isset($_SESSION['authenticated']) or $_SESSION['authenticated']!= TRUE){
	 session_destroy();
	 echo "<script>alert('you have not login.please login')</script>";
	 header("Refresh: 0; url=form.php");
	 die();
	}
	if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    echo "<script>alert('Not authorized. Please login first.'); window.location='form.php';</script>";
    exit;
}

$userProfile = getUserProfile($_SESSION['username']);

function getUserProfile($username) {
    $mysqli = new mysqli('localhost', 'team21', 'Pa$$w0rd', 'waph_team21');
    if ($mysqli->connect_errno) {
        echo "Database connection failed: " . $mysqli->connect_error;
        exit();
    }

    $sql = "SELECT * FROM users WHERE username=?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}
	/*if($_SESSION['browser'] != $_SERVER['HTTP_USER_AGENT']){
		session_destroy();
		echo "<script>alert('session hijacking attack is detected!');</script>";
		header("Refresh:0; url=form.php");
		die();
	}*/

	/*function checklogin($username, $password) {
		$account = array("admin","1234");
		if (($username== $account[0]) and ($password == $account[1])) 
		  return TRUE;
		else 
		  return FALSE;
  	}*/

  	function checklogin_mysql($username,$password){
  		$mysqli = new mysqli('localhost','team21','Pa$$w0rd','waph_team21');
  		if($mysqli->connect_errno){
  			printf("Database connection failed : %s\n", $mysqli->connect-error);
  			exit();

  		}
  	$sql = "SELECT * FROM users WHERE username= ? AND password = md5(?)";
  	$stmt = $mysqli->prepare($sql);
  	$stmt->bind_param("ss",$username,$password);
  	$stmt->execute();
  	$result=$stmt->get_result();
  	if($result->num_rows == 1)
  		return TRUE;
  	return FALSE;
  	}
?>
  <div class="container">
    <h2>Welcome <?php echo htmlentities($_SESSION['username']); ?>!</h2>
    <p>Email: <?php echo htmlentities($userProfile['email']); ?></p>
    <p>Name: <?php echo htmlentities($userProfile['name']); ?></p>
    <p>Phone: <?php echo htmlentities($userProfile['phone']); ?></p>

    <div class="button-container">
      <a class="button" href="changepasswordform.php">Change Password</a>
      <a class="button" href="updatedetails.php">Change Details</a>
      <a class="button" href="logout.php">Logout</a>
    </div>
  </div>
</body>
</html>