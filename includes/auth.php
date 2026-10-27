<?php


define('DEMO_MODE', true);

require 'includes/data.php';


if (DEMO_MODE && isset($_GET['as']) && get_user((int)$_GET['as'])) {
    $_SESSION['demo_user_id'] = (int)$_GET['as'];
}

function current_user() {
    if (DEMO_MODE) {
        $user = false;
        if (isset($_SESSION['demo_user_id'])) {
            $user = get_user($_SESSION['demo_user_id']);
        }
        if (!$user) {
            foreach ($_SESSION['users'] as $u) {
                if ($u['role'] == 'admin' && !$user) {
                    $user = $u;
                }
            }
        }
        if ($user) {
            $_SESSION['demo_user_id'] = $user['id'];
        }
        return $user;
    }

    
    if (isset($_SESSION['user_id'])) {
        return get_user($_SESSION['user_id']);
    }
    return false;
}

function dashboard_for($role) {
    if ($role == 'admin') {
        return 'admin_dashboard.php';
    } elseif ($role == 'incident_manager') {
        return 'manager_dashboard.php';
    } elseif ($role == 'department') {
        return 'department_dashboard.php';
    }
    return 'reporter_dashboard.php';
}


function require_role($allowed) {
    $user = current_user();
    if ($user && in_array($user['role'], $allowed)) {
        return;
    }
    
    foreach ($_SESSION['users'] as $u) {
        if (in_array($u['role'], $allowed)) {
            $_SESSION['user_id'] = $u['id'];
            $_SESSION['demo_user_id'] = $u['id'];
            return;
        }
    }
}