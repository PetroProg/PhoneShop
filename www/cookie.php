<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$cookie_options = [
    'expires' => time() + (86400 * 30), 
    'path' => '/',
    'domain' => '', 
    'secure' => false, 
    'httponly' => true, 
    'samesite' => 'Lax'
];

if (isset($_SESSION['login']) && !isset($_COOKIE['utilisateur'])) {
    $cookie_value = $_SESSION['login']; 

    setcookie('utilisateur', $cookie_value, $cookie_options);

    $_COOKIE['utilisateur'] = $cookie_value;
}

if (!isset($_SESSION['login']) && isset($_COOKIE['utilisateur']) && !empty($_COOKIE['utilisateur'])) {
    
    $_SESSION['login'] = $_COOKIE['utilisateur'];
}
?>
