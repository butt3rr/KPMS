
<html>
<head>
<meta charset="utf-8">
<title>Staff Login</title>
<link rel="stylesheet" type="text/css" href="login_css.css">
<link rel="icon" type="image/png" href="../favicon.ico"/>
</head>

<body>
	<div class="form">
<div class="title">
  <div class="logo">
    <img src="uptmLogo.png">
    <img src="komasa.png">
  </div>
	
    <h1 style="color: #e71810">KOMASA PARCEL MANAGEMENT SYSTEM</h1>
	
  
</div>
	</div>

<form action="loginAction.php" method="post"> 
    <h2>STAFF LOGIN</h2>
    <?php if (isset($_GET['error'])) {?>
    <p class="error"><?php echo $_GET['error']; ?></p>
    <?php } ?>
    <label>Staff ID:</label>
    <input type="text" name="staffID" placeholder="Staff ID"> <br>
           
    <label>Password:</label>
    <input type="password" name="password" placeholder="Password"> <br>
    
    <button type="submit">Login</button>
	
	<a href="forgotpassword.php" style="text-decoration: none;">
    <h6 style="color: #e71810">Forgot Password?</h6>
</a>
</form>
</body>
</html>
