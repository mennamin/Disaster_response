<?php
require 'includes/auth.php';
require_role(['admin']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    header('Content-Type: application/json');

    
    $error = add_user($_POST['name'], $_POST['email'], 'reporter', date('Y-m'), $_POST['password']);

    if ($error == '') {
        
        $last = count($_SESSION['users']) - 1;
        $_SESSION['users'][$last]['phone'] = $_POST['phone'];
        $_SESSION['users'][$last]['address'] = $_POST['address'];
        $_SESSION['users'][$last]['shift_time'] = $_POST['shifttime'];
        $_SESSION['users'][$last]['shift_days'] = $_POST['shiftdays'];
        $_SESSION['users'][$last]['reporter_id'] = $_POST['reporterid'];
        $_SESSION['users'][$last]['birthday'] = $_POST['birthday'];
        $_SESSION['users'][$last]['gender'] = $_POST['gender'];
        set_message('User added.', 'ok');
        echo json_encode(['ok' => true]);
    } else {
        echo json_encode(['ok' => false, 'error' => $error]);
    }
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>create user</title>
<link rel="stylesheet" href="create-user.css">
</head>
<body>
  <form id="form">
    <h2 id="title">Create user</h2>
    <input type="text" id="name" placeholder="Name" required>
    <input type="email" id="email" placeholder="Email" required>
    <input type="password" id="password" placeholder="Password" required>
    <input type="password" id="confirm" placeholder="Confirm password" required>
    <input type="tel" id="phone" placeholder="Phone number" required pattern="(\+20|0)1[0125][0-9]{8}" title="Enter a valid Egyptian mobile number, e.g. 01012345678">
    <input type="text" id="address" placeholder="Address" required>
    <input type="text" id="shifttime" placeholder="Shift time" required>
    <input type="text" id="shiftdays" placeholder="Shift days" required>
    <input type="text" id="reporterid" placeholder="Reporter id" required>
    <input type="date" id="birthday" placeholder="Date of birth" required>
    <div class="gender-group">
    <span>Gender:</span>
    <label for="male">
    <input type="radio" id="male" name="gender" value="male" required> Male
    </label>
    <label for="female">
    <input type="radio" id="female" name="gender" value="female" required> Female
    </label>
    </div>
    <label for="photo">Reporter id:</label>
    <input type="file" id="photo" accept="image/*" required>
    <label for="photo">Profile Picture:</label>
    <input type="file" id="pfp" accept="image/*" required>
    <button type="submit" id="submit">Create user</button>
    <p id="msg"></p>
  </form>

  <script>
    var $ = function (id) { return document.getElementById(id); };
    $('form').onsubmit = function (e) {
      e.preventDefault();
      if (/\d/.test($('name').value)) {
       $('msg').textContent = 'Name cannot contain numbers.';
      return;
      }
      if ($('password').value !== $('confirm').value) {
        $('msg').textContent = 'Passwords do not match.';
        return;
      }
      var data = new FormData();
      data.append('name', $('name').value.trim());
      data.append('email', $('email').value.trim());
      data.append('password', $('password').value);
      data.append('phone', $('phone').value);
      data.append('address', $('address').value);
      data.append('shifttime', $('shifttime').value);
      data.append('shiftdays', $('shiftdays').value);
      data.append('reporterid', $('reporterid').value);
      data.append('birthday', $('birthday').value);
      data.append('gender', document.querySelector('input[name="gender"]:checked').value);

      fetch('create-user.php', { method: 'POST', body: data })
        .then(function (r) { return r.json(); })
        .then(function (res) {
          if (res.ok) {
            $('msg').textContent = 'Account created.';
            window.location.href = 'users.php';
          } else {
            $('msg').textContent = res.error;
          }
        })
        .catch(function () {
          $('msg').textContent = 'Something went wrong. Try again.';
        });
    };
  </script>
</body>
</html>