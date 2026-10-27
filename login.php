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
    var testUsers = [
      { email: 'admin@gmail.com',      password: 'admin123',      page: 'admin_dashboard.php' },
      { email: 'department@gmail.com', password: 'department123', page: 'department_dashboard.php' },
      { email: 'manager@gmail.com',    password: 'manager123',    page: 'manager_dashboard.php' },
      { email: 'reporter@gmail.com',   password: 'reporter123',   page: 'reporter_dashboard.php' }
    ];
 
    $('form').onsubmit = function (e) {
      e.preventDefault();
 
      var email = $('email').value.trim().toLowerCase();
      var password = $('password').value;
 
      var user = testUsers.find(function (u) {
        return u.email === email && u.password === password;
      });
 
      if (user) {
        $('msg').textContent = 'Login successful. Redirecting...';
        window.location.href = user.page;
      } else {
        $('msg').textContent = 'Invalid email or password.';
      }
    };
  </script>
</body>
</html>