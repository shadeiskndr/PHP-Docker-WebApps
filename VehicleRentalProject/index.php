<html>
<head>
  <style>
    .big-title {
      text-align: center;
      font-size: 48px;
      font-weight: bold;
    }
    
    .menu {
      display: flex;
      justify-content: center;
      align-items: center;
    }
    
    .menu-item {
      margin: 0 10px;
    }
  </style>
</head>
<body>
  <div class="big-title">
    <?php echo "Online Vehicle Rental<br> Management System"; ?>
  </div>
  <br></br>
  <br></br>
  <div class="menu">
    <a href="login_manager.php" class="menu-item">Manager Log in </a>
    <a href="login_customer.php" class="menu-item">Customer Log in </a>
    <a href="register_customer.php" class="menu-item">Customer Register </a>
  </div>
</body>
</html>