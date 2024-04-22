<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>WAPH-Login page</title>
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
  <script type="text/javascript">
    function displayTime() {
      document.getElementById('digit-clock').innerHTML = "Current time: " + new Date().toLocaleTimeString();
    }
    setInterval(displayTime, 500);
  </script>
</head>
<body>
  <div class="container">
    <h1>A MINIFACEBOOK</h1>
    <h2>BY TEAM21</h2>
    <div id="digit-clock"></div>
    <?php
      echo "Visited time: " . date("Y-m-d h:i:sa");
    ?>
    <form action="index.php" method="POST" class="form login">
      <input type="text" class="text_field" name="username" placeholder="Username" required><br>
      <input type="password" class="text_field" name="password" placeholder="Password" required><br>
      <a href="registrationform.php" class="button">Register Here</a>
      <button type="submit" class="button">Login Here</button>
    </form>
  </div>
</body>
</html>

