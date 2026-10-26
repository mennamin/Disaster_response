<?php

session_start();

$roles = ['reporter', 'incident_manager', 'department', 'admin'];
$severities = ['critical', 'high', 'medium', 'low'];
$statuses = ['open', 'assigned', 'resolved', 'closed'];
$incident_types = ['Fire', 'Car accident', 'Burglary', 'Medical emergency', 'Flood', 'Building collapse', 'Other'];

function e($text) {
    return htmlspecialchars($text);
}

function role_label($role) {
    return ucwords(str_replace('_', ' ', $role));
}


function started_text($started) {
    if ($started == '') {
        return '-';
    }
    return date('F Y', strtotime($started . '-01'));
}


function get_initials($name) {
    $words = explode(' ', trim($name));
    $letters = strtoupper(substr($words[0], 0, 1));
    if (count($words) > 1) {
        $letters = $letters . strtoupper(substr($words[count($words) - 1], 0, 1));
    }
    return $letters;
}

function seed_data() {
    
    $_SESSION['users'] = [
        ['id' => 1, 'name' => 'Peter Ben Parker', 'email' => 'peterparker@gmail.com', 'role' => 'reporter', 'started' => '2018-03'],
        ['id' => 2, 'name' => 'Mary Jane', 'email' => 'maryjane@gmail.com', 'role' => 'incident_manager', 'started' => '2019-06'],
        ['id' => 3, 'name' => 'Department Staff', 'email' => 'staff@gmail.com', 'role' => 'department', 'started' => '2020-01'],
        ['id' => 4, 'name' => 'Admin', 'email' => 'admin@city.gov', 'role' => 'admin', 'started' => '2017-09'],
    ];
    $_SESSION['next_user_id'] = 5;

    $_SESSION['incidents'] = [
        ['id' => 101, 'type' => 'Fire', 'location' => 'Nasr City', 'reporter_id' => 1, 'severity' => 'critical', 'status' => 'assigned', 'note' => 'Fire trucks on the way', 'time' => '02:10', 'injured' => 3, 'dead' => 0, 'description' => 'A fire started in a shop on the ground floor of a building.'],
        ['id' => 100, 'type' => 'Car accident', 'location' => 'Maadi', 'reporter_id' => 1, 'severity' => 'high', 'status' => 'open', 'note' => '', 'time' => '01:45', 'injured' => 2, 'dead' => 0, 'description' => 'Two cars collided on the main road.'],
        ['id' => 99, 'type' => 'Burglary', 'location' => 'Zamalek', 'reporter_id' => 1, 'severity' => 'medium', 'status' => 'open', 'note' => '', 'time' => '01:10', 'injured' => 0, 'dead' => 0, 'description' => 'A shop was broken into while the owner was away.'],
        ['id' => 98, 'type' => 'Burglary', 'location' => 'Dokki', 'reporter_id' => 1, 'severity' => 'medium', 'status' => 'resolved', 'note' => 'Suspect arrested', 'time' => '00:30', 'injured' => 0, 'dead' => 0, 'description' => 'An apartment was robbed during the night.'],
        ['id' => 97, 'type' => 'Medical', 'location' => 'Heliopolis', 'reporter_id' => 1, 'severity' => 'low', 'status' => 'closed', 'note' => '', 'time' => '23:50', 'injured' => 1, 'dead' => 0, 'description' => 'A man fainted in the street.'],
        ['id' => 96, 'type' => 'Car accident', 'location' => 'Dokki', 'reporter_id' => 1, 'severity' => 'high', 'status' => 'assigned', 'note' => 'Ambulance sent', 'time' => '00:50', 'injured' => 4, 'dead' => 1, 'description' => 'A bus and a car collided at an intersection.'],
    ];

    $_SESSION['backup_pending'] = 1;
}

if (!isset($_SESSION['users']) || isset($_GET['reset'])) {
    seed_data();
    if (isset($_GET['reset'])) {
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}


function set_message($text, $type) {
    $_SESSION['message'] = ['text' => $text, 'type' => $type];
}

function get_message() {
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        unset($_SESSION['message']);
        return $message;
    }
    return false;
}



function get_user($id) {
    foreach ($_SESSION['users'] as $u) {
        if ($u['id'] == $id) {
            return $u;
        }
    }
    return false;
}

function user_name($id) {
    $u = get_user($id);
    if ($u) {
        return $u['name'];
    }
    return 'Unknown';
}


function check_user($name, $email, $role, $started, $skip_id) {
    global $roles;

    if ($name == '' || $email == '') {
        return 'Name and email are required.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Invalid email address.';
    }
    if (!in_array($role, $roles)) {
        return 'Invalid role.';
    }
    if ($started != '' && (strlen($started) != 7 || substr($started, 4, 1) != '-')) {
        return 'Started on must be a month and a year.';
    }
    foreach ($_SESSION['users'] as $u) {
        if ($u['id'] != $skip_id && strtolower($u['email']) == strtolower($email)) {
            return 'That email is already used by another user.';
        }
    }
    return '';
}

function add_user($name, $email, $role, $started) {
    $name = trim($name);
    $email = trim($email);

    $error = check_user($name, $email, $role, $started, 0);
    if ($error != '') {
        return $error;
    }

    $id = $_SESSION['next_user_id'];
    $_SESSION['next_user_id'] = $id + 1;
    $_SESSION['users'][] = ['id' => $id, 'name' => $name, 'email' => $email, 'role' => $role, 'started' => $started];
    return '';
}

function update_user($id, $name, $email, $role, $started, $my_id) {
    $name = trim($name);
    $email = trim($email);

    $old = get_user($id);
    if (!$old) {
        return 'User not found.';
    }
    $error = check_user($name, $email, $role, $started, $id);
    if ($error != '') {
        return $error;
    }
    if ($id == $my_id && $role != $old['role']) {
        return "You can't change your own role.";
    }

    foreach ($_SESSION['users'] as $i => $u) {
        if ($u['id'] == $id) {
            $_SESSION['users'][$i]['name'] = $name;
            $_SESSION['users'][$i]['email'] = $email;
            $_SESSION['users'][$i]['role'] = $role;
            $_SESSION['users'][$i]['started'] = $started;
        }
    }
    return '';
}

function delete_user($id, $my_id) {
    if ($id == $my_id) {
        return 'You cannot delete your own account.';
    }
    if (!get_user($id)) {
        return 'User not found.';
    }

    $left = [];
    foreach ($_SESSION['users'] as $u) {
        if ($u['id'] != $id) {
            $left[] = $u;
        }
    }
    $_SESSION['users'] = $left;
    return '';
}



function is_active($incident) {
    return $incident['status'] == 'open' || $incident['status'] == 'assigned';
}


function count_status($list, $wanted) {
    $count = 0;
    foreach ($list as $inc) {
        if (in_array($inc['status'], $wanted)) {
            $count = $count + 1;
        }
    }
    return $count;
}

function count_critical() {
    $count = 0;
    foreach ($_SESSION['incidents'] as $inc) {
        if ($inc['severity'] == 'critical' && is_active($inc)) {
            $count = $count + 1;
        }
    }
    return $count;
}

function incidents_of($reporter_id) {
    $list = [];
    foreach ($_SESSION['incidents'] as $inc) {
        if ($inc['reporter_id'] == $reporter_id) {
            $list[] = $inc;
        }
    }
    return $list;
}

function incidents_with_status($status) {
    $list = [];
    foreach ($_SESSION['incidents'] as $inc) {
        if ($inc['status'] == $status) {
            $list[] = $inc;
        }
    }
    return $list;
}


function sort_incidents($list) {
    global $severities;
    $sorted = [];

    foreach ($severities as $sev) {
        foreach ($list as $inc) {
            if ($inc['severity'] == $sev && is_active($inc)) {
                $sorted[] = $inc;
            }
        }
    }
    foreach ($severities as $sev) {
        foreach ($list as $inc) {
            if ($inc['severity'] == $sev && !is_active($inc)) {
                $sorted[] = $inc;
            }
        }
    }
    return $sorted;
}


function add_incident($type, $location, $severity, $injured, $dead, $description, $reporter_id) {
    global $incident_types, $severities;

    $location = trim($location);
    $description = trim($description);

    if (!in_array($type, $incident_types)) {
        return 'Please choose the type of disaster.';
    }
    if ($location == '') {
        return 'Please write the location.';
    }
    if (!in_array($severity, $severities)) {
        return 'Please choose how critical it is.';
    }
    
    if ($injured != 'unknown' && (!is_numeric($injured) || $injured < 0)) {
        return 'The number of injured people must be 0 or more, or unknown.';
    }
    if ($dead != 'unknown' && (!is_numeric($dead) || $dead < 0)) {
        return 'The number of dead people must be 0 or more, or unknown.';
    }
    if ($description == '') {
        return 'Please describe what happened.';
    }

    if ($injured != 'unknown') { $injured = (int)$injured; }
    if ($dead != 'unknown') { $dead = (int)$dead; }

    
    $new_id = 1;
    foreach ($_SESSION['incidents'] as $inc) {
        if ($inc['id'] >= $new_id) {
            $new_id = $inc['id'] + 1;
        }
    }

    $new = [
        'id' => $new_id,
        'type' => $type,
        'location' => $location,
        'reporter_id' => $reporter_id,
        'severity' => $severity,
        'status' => 'open',
        'note' => '',
        'time' => date('H:i'),
        'injured' => $injured,
        'dead' => $dead,
        'description' => substr($description, 0, 1000),
    ];
    // newest report goes first
    array_unshift($_SESSION['incidents'], $new);
    return '';
}


function update_incident($id, $severity, $status, $note) {
    global $severities, $statuses;

    if (!in_array($severity, $severities) || !in_array($status, $statuses)) {
        return 'Invalid severity or status.';
    }
    foreach ($_SESSION['incidents'] as $i => $inc) {
        if ($inc['id'] == $id) {
            $_SESSION['incidents'][$i]['severity'] = $severity;
            $_SESSION['incidents'][$i]['status'] = $status;
            $_SESSION['incidents'][$i]['note'] = substr(trim($note), 0, 200);
            return '';
        }
    }
    return 'Incident not found.';
}