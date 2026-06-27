<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db.php';

if (isset($_POST['delete_tout'])) {

    $sql = "DELETE FROM t_produit_panier 
            WHERE panier_fk IN (
                SELECT panier_id FROM t_panier WHERE client_fk = (
                    SELECT client_id FROM t_client WHERE login = ?
                ) AND statut = 1
            )";
    $stmt = $db->prepare($sql);
    $stmt->execute([$_SESSION['login']]);

    unset($_SESSION['cart']);

    header('Location: panier.php');
    exit();
}
?>


