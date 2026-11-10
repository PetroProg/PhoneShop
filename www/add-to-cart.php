<?php
session_start();
require_once 'db.php';
require_once 'cookie.php';

if (isset($_GET['id'])) {
    $id_cart = $_GET['id'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $sql = "SELECT client_id FROM t_client WHERE login = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$_SESSION['login']]);
    $client_id = $stmt->fetchColumn();

    if ($client_id) {
        $sql = "SELECT COUNT(*) FROM t_panier WHERE client_fk = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$client_id]);
        $panier_count = $stmt->fetchColumn();

        if ($panier_count == 0) {
            $sql = "INSERT INTO t_panier (client_fk) VALUES (?)";
            $stmt = $db->prepare($sql);
            $stmt->execute([$client_id]);
        }

        $sql = "SELECT panier_id FROM t_panier WHERE client_fk = ? ORDER BY panier_id DESC LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->execute([$client_id]);
        $panier_id = $stmt->fetchColumn();

        $sql = "SELECT COUNT(*) FROM t_produit_panier WHERE produit_fk = ? AND panier_fk = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id_cart, $panier_id]);
        $product_exists = $stmt->fetchColumn();

        if ($product_exists == 0) {
            $sql = "INSERT INTO t_produit_panier (produit_fk, panier_fk) VALUES (?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->execute([$id_cart, $panier_id]);
        }

        if (!in_array($id_cart, $_SESSION['cart'])) {
            $_SESSION['cart'][] = $id_cart;
        }
    }
}

header('Location: index.php');
exit;
?>