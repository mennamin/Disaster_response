<!DOCTYPE html>
<html>
<head>
<title>Log in</title>
<style>
  html { height: 100%; margin: 0; }
  body { height: 100%; margin: 0; }
  body { display: flex; align-items: center; justify-content: center;}
  form { display: flex; flex-direction: column; gap: 8px; width: 260px; }
  input { padding: 8px; font-size: 16px; }
  select { padding: 8px; font-size: 16px; }
  button { padding: 8px; font-size: 16px; }
</style>
</head>
<body>
  <form id="form">
    <h2 id="title">Log in</h2>
     <select id="dashboard" required>
     <option value="" disabled selected>Select dashboard</option>
     <option value="firefighter">Firefighter</option>
     <option value="reporter">Reporter</option>
     <option value="hospital">Hospital</option>
     <option value="police">Police</option>
     <option value= "incident managment">Incident managment</option>
     <option value="admin">Admin</option>
       </select>
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
      if (signup && $('password').value !== $('confirm').value) {
        $('msg').textContent = 'Passwords do not match.';
        return;
      }
      $('msg').textContent = signup ? 'Account created.' : 'Logged in.';
    };
  </script>
</body>
</html>
