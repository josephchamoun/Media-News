<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="CSS/register.css" />
  <title>Register</title>
</head>
<body>
  <h2>Register</h2>
  <form action="?action=register" method="POST">
    <input type="text" name="name" placeholder="Name" required /><br>
    <input type="email" name="email" placeholder="Email" required /><br>
    <input type="password" name="password" placeholder="Password" required /><br>
    <button type="submit">Register</button>
  </form>
  <a href="?page=login">Already have an account?</a>
</body>
</html>
