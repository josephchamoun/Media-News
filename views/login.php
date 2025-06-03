<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="/CSS/login.css" />
  <title>Login</title>
</head>
<body>
  <h2>Login</h2>
  <form action="?action=login" method="POST">
    <input type="email" name="email" placeholder="Email" required /><br>
    <input type="password" name="password" placeholder="Password" required /><br>
    <button type="submit">Login</button>
  </form>
  <a href="?page=register">Create an account</a>
</body>
</html>
