<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>WAPH-Login page</title>
    <style>
  body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 600px;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2 {
    color: #333;
    text-align: center;
    margin-bottom: 20px;
}

a {
    display: block;
    width: 100%;
    padding: 12px;
    margin-bottom: 10px;
    background-color: #007bff;
    color: #fff;
    text-align: center;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

a:hover {
    background-color: #0056b3;
}

.btn-primary {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.btn-danger {
    background-color: #dc3545;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.btn-danger:hover {
    background-color: #c82333;
}
</style>
  <script type="text/javascript">
      function displayTime() {
        document.getElementById('digit-clock').innerHTML = "Current time: " + new Date();
      }
      setInterval(displayTime, 500);
  </script>
</head>
<body>
  <div class="container">
    <h1>A MINI FACEBOOK</h1>
    <h2>BY TEAM-21</h2>
    <div id="digit-clock"></div>  
    <?php
      //some code here
      echo "Visited time: " . date("Y-m-d h:i:sa")
    ?>
    <form action="index.php" method="POST" class="form login">
      <input type="text" class="form-control" name="username" placeholder="Username" /> 
      <input type="password" class="form-control" name="password" placeholder="Password" /> 
      <button class="button" type="submit">Login</button>
    </form>
</div>
</body>
</html>