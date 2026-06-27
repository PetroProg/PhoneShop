<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db.php'; 

if (isset($_GET['produit_id']) && isset($_SESSION['login'])) {
    $produit_id = $_GET['produit_id'];
    $login = $_SESSION['login'];

    $sql_panier = "SELECT p.panier_id 
                   FROM t_panier p
                   INNER JOIN t_client c ON p.client_fk = c.client_id
                   WHERE c.login = ? 
                   ORDER BY p.panier_id DESC LIMIT 1";
                   
    $stmt_panier = $db->prepare($sql_panier);
    $stmt_panier->execute([$login]);
    $panier_id = $stmt_panier->fetchColumn();

    if ($panier_id) {
        $sql_delete = "DELETE FROM t_produit_panier 
                       WHERE produit_fk = ? AND panier_fk = ?";
        $stmt_delete = $db->prepare($sql_delete);
        $stmt_delete->execute([$produit_id, $panier_id]);
    }

    if (isset($_SESSION['cart'])) {
        $key = array_search($produit_id, $_SESSION['cart']);
        if ($key !== false) {
            unset($_SESSION['cart'][$key]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); 
        }
    }
    header('Location: panier.php');
    exit();
} else {
    header('Location: index.php');
    exit();
}
