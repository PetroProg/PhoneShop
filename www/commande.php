<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db.php';

$items_in_cart = [];
$panier_id = null;
$client_id = null;
$total = 0;
$login = $_SESSION['login'] ?? null;


if ($login) {
    if (empty($_SESSION['client_id'])) {
        $get_client = $db->prepare("SELECT client_id FROM t_client WHERE login = ?");
        $get_client->execute([$login]);
        $_SESSION['client_id'] = $get_client->fetchColumn();
    }
    $client_id = $_SESSION['client_id'];
}

if (isset($_POST['buttonfinish']) && $client_id) {
    $sql = "UPDATE t_client SET nom = ?, prenom = ?, telephone = ?, rue = ?, localite = ?, npa = ? WHERE client_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$_POST['surname'], $_POST['name'], $_POST['tel'], $_POST['adresse'], $_POST['city'], $_POST['npa'], $client_id]);

    $sql = "SELECT panier_id FROM t_panier WHERE client_fk = ? AND statut = 1";
    $stmt = $db->prepare($sql);
    $stmt->execute([$client_id]);
    $panier_id = $stmt->fetchColumn();

    if ($panier_id) {
        if (isset($_POST['quantity']) && is_array($_POST['quantity'])) {
            $sql_panier = "UPDATE t_produit_panier SET quantite = ? WHERE panier_fk = ? AND produit_fk = ?";
            $stmt_panier = $db->prepare($sql_panier);

            $sql_stock = "UPDATE t_produit SET quantite = quantite - ? WHERE produit_id = ?";
            $stmt_stock = $db->prepare($sql_stock);

            foreach ($_POST['quantity'] as $product_id => $quantity) {
                $qty = (int)$quantity;
                $stmt_panier->execute([$qty, $panier_id, $product_id]);
                $stmt_stock->execute([$qty, $product_id]);
            }
        }

        $sql = "UPDATE t_produit_panier pp
                INNER JOIN t_produit p ON pp.produit_fk = p.produit_id
                SET pp.prix_unitaire = p.prix
                WHERE pp.panier_fk = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$panier_id]);

        $sql = "UPDATE t_panier SET statut = 2 WHERE panier_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$panier_id]);

        header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
    }
}

if (!$panier_id && $client_id) {
    $sql_last_panier = "SELECT panier_id FROM t_panier WHERE client_fk = ? AND statut = 2 ORDER BY date_ajout DESC LIMIT 1";
    $stmt_last = $db->prepare($sql_last_panier);
    $stmt_last->execute([$client_id]);
    $panier_id = $stmt_last->fetchColumn();
}

if ($panier_id) {
    $sql_products = "SELECT p.produit_id, p.nom, pp.prix_unitaire AS prix, pp.quantite, img.url,
                            MAX(v_storage.valeur) AS storage_value 
                     FROM t_produit_panier pp
                     INNER JOIN t_produit p ON pp.produit_fk = p.produit_id
                     LEFT JOIN t_image img ON p.produit_id = img.produit_fk
                     LEFT JOIN t_produit_attribut pa_storage ON p.produit_id = pa_storage.produit_fk
                     LEFT JOIN t_valeur v_storage ON pa_storage.valeur_fk = v_storage.valeur_id AND v_storage.attribut_fk = 2
                     WHERE pp.panier_fk = ?
                     GROUP BY p.produit_id";
                     
    $stmt_products = $db->prepare($sql_products);
    $stmt_products->execute([$panier_id]);
    $items_in_cart = $stmt_products->fetchAll(PDO::FETCH_ASSOC);

    foreach ($items_in_cart as $product) {
        $total += ($product['prix'] * $product['quantite']);
    }
}

if ($client_id) {
    $sql_orders = "SELECT * FROM t_panier 
                   WHERE client_fk = ? AND statut = 2 
                   ORDER BY date_ajout DESC";
    $stmt_orders = $db->prepare($sql_orders);
    $stmt_orders->execute([$client_id]);
    $orders = $stmt_orders->fetchAll();
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
    <title>Commande - DemoMot</title>
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
    <main class="max-w-4xl w-full mx-auto p-6 md:py-12 flex-grow">
        <div class="mb-10 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 mb-4">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900">Commande validée !</h1>
            <p class="text-slate-500 mt-2">Merci pour votre confiance. Voici le récapitulatif de vos achats.</p>
        </div>

        <div id="cart-items-container" class="space-y-4">
            <?php foreach ($items_in_cart as $product): ?>
                <div id="product-card-<?= $product['produit_id'] ?>" data-price="<?= $product['prix'] ?>" class="cart-item flex flex-col sm:flex-row items-center gap-6 bg-white border border-slate-200/80 hover:border-slate-300 rounded-2xl p-5 transition-all duration-200 hover:shadow-md group">
                    <div class="w-24 h-24 bg-slate-50 rounded-xl p-2 flex-shrink-0 flex items-center justify-center">
                        <?php
                        if (!empty($product['url'])) {
                            $fileName = basename($product['url']);
                            $imagePath = '/media/images/' . trim($fileName);
                        } else {
                            $imagePath = '/media/images/no-photo.png';
                        }
                        ?>
                        <img class="max-w-full max-h-full object-contain mix-blend-multiply"
                            src="<?= htmlspecialchars($imagePath) ?>"
                            alt="Photo de <?= htmlspecialchars($product['nom']) ?>">
                    </div>

                    <div class="flex-grow">
                        <h4 class="font-semibold text-lg text-slate-900 group-hover:text-indigo-600 transition duration-150"><?= htmlspecialchars($product['nom']) ?></h4>
                        <p class="text-sm text-slate-500">Quantité : <?= $product['quantite'] ?></p>
                    </div>

                    <div class="text-right">
                        <p class="font-black text-slate-900 text-lg">
                            <?= number_format($product['prix'] * $product['quantite'], 2) ?> CHF
                        </p>
                    </div>
                    <button type="button" command="show-modal" commandfor="dialog-<?= $product['produit_id'] ?>" class="bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold py-2.5 px-4 rounded-xl border border-slate-200 transition text-xs cursor-pointer">
                        Détails d'un produit
                    </button>
                </div>
            <?php endforeach; ?>
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
                        <div class="flex justify-between"><span class="text-slate-400">Mémoire:</span> <span class="font-medium text-slate-800"><?= htmlspecialchars($product['storage_value'] ?? 'N/A') ?></span></div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="button" command="close" commandfor="dialog-<?= $product['produit_id'] ?>" class="w-full sm:w-auto bg-slate-900 hover:bg-slate-800 text-white font-semibold py-2.5 px-5 rounded-xl transition cursor-pointer text-sm text-center">Fermer</button>
                    </div>
                </div>
            </dialog>
        <?php endforeach; ?>
        <div class="relative my-8">
            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                <div class="w-full border-t border-slate-200"></div>
            </div>
            <div class="relative flex justify-center text-xs uppercase">
                <span class="bg-slate-50 px-3 text-slate-400 font-medium tracking-wider">Liens utiles</span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 text-left mb-8">
            <a href="index.php" class="p-4 bg-white border border-slate-200/60 rounded-xl shadow-xs hover:border-indigo-500/50 hover:shadow-md transition duration-200 group">
                <p class="text-sm font-semibold text-slate-800 group-hover:text-indigo-600 transition">Boutique</p>
                <p class="text-xs text-slate-400 mt-1">Retourner au catalogue</p>
            </a>
            <a href="panier.php" class="p-4 bg-white border border-slate-200/60 rounded-xl shadow-xs hover:border-indigo-500/50 hover:shadow-md transition duration-200 group">
                <p class="text-sm font-semibold text-slate-800 group-hover:text-indigo-600 transition">Mon panier</p>
                <p class="text-xs text-slate-400 mt-1">Voir vos articles sélectionnés</p>
            </a>
        </div>
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
</body>

</html>