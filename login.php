<!DOCTYPE html>
<html>
<head>
<title>Log in</title>
<link rel="stylesheet" href="login.css">
</head>
<body>
  <form id="form">
    <h2 id="title">Log in</h2>
    <input type="email" id="email" placeholder="Email" required>
    <input type="password" id="password" placeholder="Password" required>
    <button type="submit" id="submit">Log in</button>
    <p id="msg"></p>
  </form>

  <script>
    var signup = false;
    var $ = function (id) { return document.getElementById(id); };

    $('form').onsubmit = function (e) {
      e.preventDefault();
      $('msg').textContent = 'Account created.';
    };
  </script>
</body>
</html>
