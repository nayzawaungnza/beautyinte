<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Beauty Salon Sign In</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      background-color: #f5e4d7; /* Nude background */
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .login-form {
      background-color: #003366; /* Dark blue form */
      padding: 50px;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
      width: 400px;
    }

    .login-form h2 {
      text-align: center;
      color: white;
      margin-bottom: 30px;
      font-size: 28px;
    }

    .login-form input[type="email"],
    .login-form input[type="password"] {
      width: 100%;
      padding: 15px;
      margin: 15px 0;
      border: none;
      border-radius: 8px;
      font-size: 16px;
    }

    .login-form button.signin {
      width: 100%;
      padding: 15px;
      margin-top: 15px;
      background-color: #ffffff;
      color: #003366;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s ease;
    }

    .login-form button.signin:hover {
      background-color: #dce6f2;
    }
  </style>
</head>
<body>

  <div class="login-form">
    <h2>Salon Sign In</h2>
    <form>
      <input type="email" placeholder="Email" required>
      <input type="password" placeholder="Password" required>
      <button type="submit" class="signin">Sign In</button>
    </form>
  </div>

</body>
</html>
