<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db.php';
require_once 'cookie.php';

$total = 0;
$items_in_cart = [];

$login = $_SESSION['login'] ?? null;

if (isset($_SESSION['login'])) {
    $current_login = $_SESSION['login'];

    if (empty($_SESSION['client_id'])) {
        $get_client = $db->prepare("SELECT client_id FROM t_client WHERE login = ?");
        $get_client->execute([$current_login]);
        $_SESSION['client_id'] = $get_client->fetchColumn();
    }

    $client_id = $_SESSION['client_id'];

    $sql = "SELECT p.produit_id, p.nom, p.prix, p.quantite, img.url, pan.statut AS panier_statut,  MAX(v_storage.valeur) AS storage_value
            FROM t_produit p
            INNER JOIN t_produit_panier pp ON p.produit_id = pp.produit_fk
            INNER JOIN t_panier pan ON pp.panier_fk = pan.panier_id
            INNER JOIN t_client c ON pan.client_fk = c.client_id
            LEFT JOIN t_image img ON p.produit_id = img.produit_fk
            LEFT JOIN t_produit_attribut pa_storage ON p.produit_id = pa_storage.produit_fk
            LEFT JOIN t_valeur v_storage ON pa_storage.valeur_fk = v_storage.valeur_id AND v_storage.attribut_fk = 2
            WHERE c.login = ? AND pan.statut = 1
            GROUP BY p.produit_id";

    $stmt = $db->prepare($sql);
    $stmt->execute([$current_login]);
    $items_in_cart = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($items_in_cart)) {
        foreach ($items_in_cart as $product) {
            $total += $product['prix'];
        }
    }

    $sql_panier = "SELECT panier_id FROM t_panier 
                   WHERE client_fk = ? AND statut = 1 
                   LIMIT 1";
    $stmt = $db->prepare($sql_panier);
    $stmt->execute([$client_id]);
    $panier = $stmt->fetch();

    if ($panier) {
        $current_panier_id = $panier['panier_id'];
    } else {
        $sql_create = "INSERT INTO t_panier (client_fk, statut, date_ajout) 
                   VALUES (?, 1, NOW())";
        $stmt_create = $db->prepare($sql_create);
        $stmt_create->execute([$client_id]);

        $current_panier_id = $db->lastInsertId();
    }
}

$checkPrivilege = $db->prepare("SELECT privilege_fk FROM t_client WHERE login = ?");
$checkPrivilege->execute([$login]);
$privilege = $checkPrivilege->fetchColumn();

if ($privilege >= 3) {
    header("Location: dashboard-adm.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
    <style>
        @media only screen and (max-width: 770px) {
            h1 {
                font-size: 14px;
            }

            .barre-rech {
                display: none;
            }

            .but-p,
            .head-p {
                width: 65px;
                font-size: 11px;
                padding: 5px;
            }
        }
    </style>
    <title>Panier - DemoMot</title>
</head>

<body class="bg-slate-50 text-slate-900 font-sans min-h-screen flex flex-col">
    <header class="bg-slate-900/90 backdrop-blur-md shadow-lg border-b border-slate-800/60 sticky top-0 z-40 transition-all duration-300">
        <div class="max-w-7xl mx-auto grid grid-cols-3 items-center px-4 py-3 text-white">

            <nav class="flex justify-start">
                <a href="index.php" class="p-1 rounded-2xl bg-white/5 border border-white/10 hover:border-indigo-500/50 transition-all">
                    <svg class="w-8 h-8 text-indigo-400" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M4 21V12H2L12 3L22 12H20V21H4Z" opacity="0.5" />
                        <rect x="9" y="14" width="6" height="7" />
                    </svg>
                </a>
            </nav>

            <div class="flex justify-center">
                <h1 class="text-2xl font-black tracking-tight bg-gradient-to-r from-slate-400 via-slate-200 to-slate-500 bg-clip-text text-transparent select-none">
                    DemoMot
                </h1>
            </div>

            <nav class="flex justify-end items-center gap-x-2">
                <form action="index.php" method="GET">
                    <input type="text" name="search" placeholder="Rechercher..." class="w-32 md:w-48 bg-slate-900 border border-white/10 text-white text-xs px-3 py-2 rounded-xl focus:outline-none focus:border-indigo-500 transition-all">
                </form>

                <a href="/panier.php" class="px-4 py-2 text-sm font-semibold hover:bg-slate-800/80 text-slate-300 hover:text-white rounded-xl transition-all duration-200 flex items-center justify-center border border-slate-800 bg-slate-950/40">
                    Panier
                </a>

                <el-dropdown class="relative">
                    <button class="px-4 py-2 text-sm font-semibold hover:bg-slate-800/80 text-slate-300 hover:text-white rounded-xl transition-all duration-200 flex items-center justify-center border border-slate-800 bg-slate-950/40">
                        Compte
                        <svg class="w-4 h-4 ml-1.5 opacity-60 group-hover:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <el-menu anchor="bottom end" popover class="w-56 mt-2 rounded-xl bg-white p-1.5 text-slate-800 shadow-2xl border border-slate-200/80">
                        <div class="space-y-0.5">
                            <?php if (!isset($_COOKIE['utilisateur'])): ?>
                                <a href="login.php" class="flex items-center gap-2 px-3 py-2 text-sm font-semibold rounded-lg text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition">
                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-600"></span> Login
                                </a>
                            <?php else: ?>
                                <a href="homepage.php" class="flex items-center gap-2 px-3 py-2 text-sm font-semibold rounded-lg text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Account
                                </a>
                            <?php endif; ?>

                            <a href="commande.php" class="block px-3 py-2 text-sm font-medium rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition">Produits commandés</a>
                            <a href="conditions.php" class="block px-3 py-2 text-sm font-medium rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition">Conditions</a>

                            <div class="my-1.5 border-t border-slate-100"></div>

                            <form action="signout.php" method="POST">
                                <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-left text-sm font-semibold rounded-lg text-red-600 hover:bg-red-50 transition">
                                    Sign out
                                </button>
                            </form>
                        </div>
                    </el-menu>
                </el-dropdown>
            </nav>
        </div>
    </header>
    <main id="main-panier" class="max-w-7xl w-full mx-auto px-6 py-10 flex-grow items-center justify-center">
        <section id="empty-cart-view" class="flex flex-col items-center justify-center bg-white border border-slate-200 rounded-2xl p-12 text-center shadow-xs <?= !empty($items_in_cart) ? 'hidden' : '' ?>">
            <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                <img class="w-16 h-16 object-contain opacity-70" src="./media/images/panier.png" alt="Panier vide">
            </div>
            <p class="text-xl font-medium text-slate-950 mb-2">Votre panier est encore vide</p>
            <p class="text-sm text-slate-500 mb-6 max-w-sm">Découvrez nos products et ajoutez-les à votre panier pour passer commande.</p>
            <a href="index.php" class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-xl transition text-sm shadow-sm shadow-indigo-100">
                Continuer mes achats
            </a>
        </section>

        <?php if (!empty($items_in_cart)): ?>

            <form action="commande.php" method="POST" id="main-order-form">

                <div id="cart-content-view" class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                    <div class="lg:col-span-2 space-y-6">
                        <div>
                            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                                Votre panier
                                <span class="text-indigo-600">
                                    <?php
                                    if (!empty($login)) {
                                        echo htmlspecialchars($login) . " !";
                                    } else {
                                        echo "invité !";
                                    }
                                    ?>
                                </span>
                            </h2>
                        </div>

                        <div id="cart-items-container" class="space-y-4">
                            <?php foreach ($items_in_cart as $product): ?>
                                <div id="product-card-<?= $product['produit_id'] ?>" data-price="<?= $product['prix'] ?>" class="cart-item flex flex-col sm:flex-row items-center gap-6 bg-white border border-slate-200/80 hover:border-slate-300 rounded-2xl p-5 transition-all duration-200 hover:shadow-md group">

                                    <div class="w-24 h-24 bg-slate-50 rounded-xl p-2 flex-shrink-0 flex items-center justify-center">
                                        <?php
                                        if (!empty($product['url'])) {
                                            $fileName = basename($product['url']);
                                            $imagePath = 'media/images/' . trim($fileName);
                                        } else {
                                            $imagePath = 'media/images/no-photo.png';
                                        }
                                        ?>
                                        <img class="max-w-full max-h-full object-contain mix-blend-multiply"
                                            src="<?= htmlspecialchars($imagePath) ?>"
                                            alt="Photo de <?= htmlspecialchars($product['nom']) ?>">
                                    </div>

                                    <div class="flex-1 text-center sm:text-left">
                                        <h4 class="font-semibold text-lg text-slate-900 group-hover:text-indigo-600 transition duration-150"><?= htmlspecialchars($product['nom']) ?></h4>
                                        <p class="text-emerald-600 font-bold text-md mt-1"><?= htmlspecialchars($product['prix']) ?> CHF</p>
                                    </div>

                                    <div class="flex items-center gap-x-3 w-full sm:w-auto justify-center sm:justify-end border-t sm:border-t-0 pt-4 sm:pt-0 border-slate-100">
                                        <?php
                                        if ($product['quantite'] === 0) {
                                            $produitcommande = 0;
                                            echo '<span class="text-rose-600 font-semibold text-sm">Produit pour l\'instant indisponible</span>';
                                        } else if ($product['quantite'] < 5) {
                                            $produitcommande = (int)$product['quantite'];
                                            echo '<span class="text-rose-600 font-semibold text-sm">Quantité disponible: ' . $product['quantite'] . '</span>';
                                        } else {
                                            $produitcommande = 5;
                                            echo '<span class="text-emerald-600 font-semibold text-sm">Quantité disponible: ' . $product['quantite'] . '</span>';
                                        }
                                        ?>

                                        <select name="quantity[<?= $product['produit_id'] ?>]" id="nmbr-appar-<?= $product['produit_id'] ?>"
                                            <?= $produitcommande === 0 ? 'disabled' : '' ?>
                                            class="js-quantity-select bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold py-2.5 px-3 rounded-xl border border-slate-200 transition text-xs cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                            <?php if ($produitcommande === 0): ?>
                                                <option value="0">0</option>
                                            <?php else: ?>
                                                <?php for ($i = 1; $i <= $produitcommande; $i++): ?>
                                                    <option value="<?= $i ?>"><?= $i ?></option>
                                                <?php endfor; ?>
                                            <?php endif; ?>
                                        </select>

                                        <button type="button" command="show-modal" commandfor="dialog-<?= $product['produit_id'] ?>" class="bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold py-2.5 px-4 rounded-xl border border-slate-200 transition text-xs cursor-pointer">
                                            Détails du produit
                                        </button>

                                        <a href="remove_from_cart.php?produit_id=<?= $product['produit_id'] ?>"
                                            data-id="<?= $product['produit_id'] ?>"
                                            title="Supprimer du panier"
                                            class="js-delete-btn text-slate-400 hover:text-red-600 p-2.5 rounded-xl hover:bg-red-50 transition flex items-center justify-center shrink-0 border border-transparent hover:border-red-100 cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 6h18"></path>
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                                <line x1="14" y1="11" x2="14" y2="17"></line>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
            </form>
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-6 lg:sticky lg:top-32 mt-15">
                <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-3">Résumé de la commande</h3>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between text-slate-500">
                        <span>Articles (<span><?= count($items_in_cart) ?></span>)</span>
                        <span><span id="js-subtotal-price"><?= $total ?></span> CHF</span>
                    </div>
                    <div class="flex justify-between text-slate-500">
                        <span>Livraison</span>
                        <span class="text-emerald-600 font-medium">Gratuit</span>
                    </div>
                    <div class="border-t border-slate-100 pt-3 flex justify-between font-extrabold text-slate-900 text-lg">
                        <span>Total</span>
                        <span><span id="js-total-price"><?= $total ?></span> CHF</span>
                    </div>
                </div>

                <div class="flex flex-col items-center gap-y-4 mt-6">
                    <button type="button" command="show-modal" commandfor="dialogCasse" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-xl transition text-sm shadow-sm shadow-indigo-100 cursor-pointer">
                        Passer à la caisse
                    </button>

                    <div class="max-w-7xl mx-auto flex justify-center mt-4">
                        <form action="clear_cart.php" method="POST" class="pt-2 text-center border-t border-slate-100">
                            <button name="delete_tout" type="submit" class="inline-flex items-center gap-x-2 text-xs text-slate-400 hover:text-red-500 transition font-semibold cursor-pointer bg-transparent border-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 6h18M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                </svg>
                                Vider tout le panier
                            </button>
                        </form>
                    </div>
                </div>

                <el-dialog>
                    <dialog id="dialogCasse" class="fixed inset-0 size-auto max-h-none max-w-none overflow-y-auto bg-transparent backdrop:bg-transparent">
                        <el-dialog-backdrop class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in"></el-dialog-backdrop>

                        <div class="flex min-h-full items-center justify-center p-4 text-center">
                            <el-dialog-panel class="relative p-8 transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg">

                                <div class="flex justify-between align-center mb-6">
                                    <h2 class="text-xl font-black text-slate-900 tracking-tight">Finaliser la commande</h2>
                                    <button type="button" command="close" commandfor="dialogCasse" class="cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                    </button>
                                </div>

                                <div class="space-y-4">
                                    <div class="flex flex-col gap-1.5">
                                        <label for="surname" class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Nom</label>
                                        <input type="text" name="surname" id="surname" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition">
                                    </div>

                                    <div class="flex flex-col gap-1.5">
                                        <label for="name" class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Prénom</label>
                                        <input type="text" name="name" id="name" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition">
                                    </div>

                                    <div class="flex flex-col gap-1.5">
                                        <label for="tel" class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Téléphone</label>
                                        <input type="tel" name="tel" id="tel" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition">
                                    </div>

                                    <div class="flex flex-col gap-1.5">
                                        <label for="adresse" class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Adresse</label>
                                        <input type="text" name="adresse" id="adresse" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition">
                                    </div>

                                    <div class="flex flex-col gap-1.5">
                                        <label for="city" class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Ville</label>
                                        <input type="text" name="city" id="city" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition">
                                    </div>

                                    <div class="flex flex-col gap-1.5">
                                        <label for="npa" class="text-xs font-semibold text-slate-500 uppercase tracking-wider">NPA</label>
                                        <input type="text" name="npa" id="npa" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition">
                                    </div>

                                    <button type="submit" id="buttonfinish" name="buttonfinish" class="w-full mt-6 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl transition shadow-lg shadow-indigo-200 active:scale-[0.98]">
                                        Confirmer la commande
                                    </button>
                                </div>
                            </el-dialog-panel>
                        </div>
                    </dialog>
                </el-dialog>
            </div>
            </div>

            <?php foreach ($items_in_cart as $product): ?>
                <dialog id="dialog-<?= $product['produit_id'] ?>" aria-labelledby="title-<?= $product['produit_id'] ?>" class="fixed inset-0 m-auto rounded-2xl bg-white p-0 shadow-2xl backdrop:bg-slate-900/60 backdrop:backdrop-blur-xs max-w-md w-full border border-slate-100 open:animate-fade-in">
                    <div class="p-6">
                        <div class="flex items-center gap-x-3 mb-4">
                            <div class="w-10 h-10 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-600 shrink-0">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 111.084 1.084l-.041.02a.75.75 0 11-1.084-1.084zM12 21a9 9 0 100-18 9 9 0 000 18z" />
                                </svg>
                            </div>
                            <h3 id="title-<?= $product['produit_id'] ?>" class="text-xl font-bold text-slate-900 leading-tight"><?= htmlspecialchars($product['nom']) ?></h3>
                        </div>

                        <div class="space-y-3 border-t border-b border-slate-100 py-4 text-sm text-slate-600">
                            <div class="flex justify-between"><span class="text-slate-400">Prix:</span> <span class="font-bold text-emerald-600 text-base"><?= htmlspecialchars($product['prix']) ?> CHF</span></div>
                            <?php
                            $sql_colors = "SELECT t_valeur.valeur 
                                           FROM t_produit_attribut
                                           INNER JOIN t_valeur ON t_produit_attribut.valeur_fk = t_valeur.valeur_id
                                           INNER JOIN t_attribut ON t_valeur.attribut_fk = t_attribut.attribut_id
                                           WHERE t_attribut.nom = 'Color' AND t_produit_attribut.produit_fk = ?";
                            $stmt_colors = $db->prepare($sql_colors);
                            $stmt_colors->execute([$product['produit_id']]);
                            $product_colors = $stmt_colors->fetchAll(PDO::FETCH_COLUMN);
                            ?>

                            <p class="flex justify-between">
                                <span class="text-slate-400">Couleurs:</span>
                                <span class="font-medium text-slate-800">
                                    <?= !empty($product_colors) ? htmlspecialchars(implode(', ', $product_colors)) : 'N/A' ?>
                                </span>
                            </p>
                            <div class="flex justify-between"><span class="text-slate-400">Stockage:</span> <span class="font-medium text-slate-800"><?= htmlspecialchars($product['storage_value'] ?? 'N/A') ?></span></div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="button" command="close" commandfor="dialog-<?= $product['produit_id'] ?>" class="w-full sm:w-auto bg-slate-900 hover:bg-slate-800 text-white font-semibold py-2.5 px-5 rounded-xl transition cursor-pointer text-sm text-center">Fermer</button>
                        </div>
                    </div>
                </dialog>
            <?php endforeach; ?>
        <?php endif; ?>

        <script>
            document.querySelectorAll('.js-delete-btn').forEach(button => {
                button.addEventListener('click', () => {
                    sessionStorage.setItem('sidebar_scroll', window.scrollY);
                });
            });

            window.addEventListener('DOMContentLoaded', () => {
                const scrollPosition = sessionStorage.getItem('sidebar_scroll');
                if (scrollPosition) {
                    window.scrollTo({
                        top: parseInt(scrollPosition, 10),
                        behavior: 'auto'
                    });
                    sessionStorage.removeItem('sidebar_scroll');
                }
            });
            (function() {
                const updateCartTotal = () => {
                    let total = 0;
                    const selects = document.querySelectorAll('.js-quantity-select');

                    selects.forEach(select => {
                        const card = select.closest('.cart-item');
                        if (card) {
                            const price = parseFloat(card.getAttribute('data-price'));
                            const quantity = parseInt(select.value);
                            total += price * quantity;
                        }
                    });

                    const subtotalEl = document.getElementById('js-subtotal-price');
                    const totalEl = document.getElementById('js-total-price');

                    if (subtotalEl) subtotalEl.innerText = total.toFixed(2);
                    if (totalEl) totalEl.innerText = total.toFixed(2);
                };

                document.addEventListener('DOMContentLoaded', () => {

                    const scrollPosition = sessionStorage.getItem('sidebar_scroll');
                    if (scrollPosition) {
                        window.scrollTo({
                            top: parseInt(scrollPosition, 10),
                            behavior: 'auto'
                        });
                        sessionStorage.removeItem('sidebar_scroll');
                    }

                    document.querySelectorAll('.js-delete-btn').forEach(button => {
                        button.addEventListener('click', () => {
                            sessionStorage.setItem('sidebar_scroll', window.scrollY);
                        });
                    });

                    document.querySelectorAll('.js-quantity-select').forEach(select => {
                        select.addEventListener('change', updateCartTotal);
                    });
                });
            })();
        </script>
    </main>

    <footer class="relative z-10 bg-slate-950 text-slate-500 text-xs border-t border-slate-900/80 mt-auto">
        <div class="max-w-7xl mx-auto px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p>&copy; 2026 Projet P_Appro. Fait par Tsybulevskyi Maksym et Maltsev Petro. Tous droits réservés.</p>
            <div class="flex items-center space-x-4">
                <a href="https://www.facebook.com/" class="object-contain filter invert hover:text-white transition"><img src="./media/images/icons8-facebook.svg" alt="Facebook" class="w-5 h-5 opacity-40 hover:opacity-100 transition invert"></a>
                <a href="https://www.instagram.com/" class="object-contain filter invert hover:text-white transition"><img src="./media/images/icons8-instagram.svg" alt="Instagram" class="w-5 h-5 opacity-40 hover:opacity-100 transition invert"></a>
                <a href="https://www.youtube.com/" class="object-contain filter invert hover:text-white transition"><img src="./media/images/icons8-youtube.svg" alt="YouTube" class="w-5 h-5 opacity-40 hover:opacity-100 transition invert"></a>
            </div>
        </div>
    </footer>
</body>

</html>