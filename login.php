<!DOCTYPE html>
<html>
<head>
<title>Log in</title>
<link rel="stylesheet" href="login.css">
</head>
<body>
  <form id="form">
    <h2 id="title">Log in</h2>
    <input type="text" id="name" placeholder="Name" hidden>
    <input type="email" id="email" placeholder="Email" required>
    <input type="password" id="password" placeholder="Password" required>
    <input type="password" id="confirm" placeholder="Confirm password" hidden>
    <button type="submit" id="submit">Log in</button>
    <button type="button" id="switch">Create an account</button>
    <p id="msg"></p>
  </form>

  <script>
    var signup = false;
    var $ = function (id) { return document.getElementById(id); };

    $('switch').onclick = function () {
      signup = !signup;
      $('title').textContent = signup ? 'Sign up' : 'Log in';
      $('submit').textContent = signup ? 'Sign up' : 'Log in';
      $('switch').textContent = signup ? 'I already have an account' : 'Create an account';
      $('name').hidden = !signup;
      $('confirm').hidden = !signup;
      $('msg').textContent = '';
    };

    $('form').onsubmit = function (e) {
      e.preventDefault();
      if (signup && /\d/.test($('name').value)) {
       $('msg').textContent = 'Name cannot contain numbers.';
        return;
      }
      else if (signup && $('password').value !== $('confirm').value) {
        $('msg').textContent = 'Passwords do not match.';
        return;
      }
      $('msg').textContent = signup ? 'Account created.' : 'Logged in.';
    };
  </script>
</body>
</html>
