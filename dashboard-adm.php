<?php
require_once 'db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$login = $_SESSION['login'] ?? null;

$checkPrivilege = $db->prepare("SELECT privilege_fk FROM t_client WHERE login = ?");
$checkPrivilege->execute([$login]);
$privilege = $checkPrivilege->fetchColumn();

if ($privilege != 3) {
    header("Location: error.php");
    exit;
}

$checkPanier = $db->prepare("SELECT COUNT(*) FROM t_panier WHERE statut = 'valide'");
$checkPanier->execute();
$panierCount = $checkPanier->fetchColumn();

$selectProducts = $db->prepare("
    SELECT 
        panier.panier_id, p.produit_id, p.nom, p.prix,pp.quantite AS quantite_commandee, (p.prix * pp.quantite) AS total_position, 
        p.quantite AS quantite_stock, IF(p.quantite > 0, 'En stock', 'Rupture de stock') AS disponibilite, MIN(img.url) AS url, 
        MAX(CASE WHEN a.nom = 'Color' THEN v.valeur END) AS color_valeur,
        MAX(CASE WHEN a_storage.nom = 'Storage' THEN v_storage.valeur END) AS memoire,      
        c.login AS client_login, panier.statut AS statut,
        CONCAT_WS(' ', c.prenom, c.nom) AS client_fullname,
        CONCAT(c.rue, ', ', c.npa, ' ', c.localite) AS client_fulladdress
    FROM t_produit p
    INNER JOIN t_produit_panier pp ON p.produit_id = pp.produit_fk
    INNER JOIN t_panier panier ON pp.panier_fk = panier.panier_id
    INNER JOIN t_client c ON panier.client_fk = c.client_id
    
    LEFT JOIN t_image img ON p.produit_id = img.produit_fk
    LEFT JOIN t_produit_attribut pa ON p.produit_id = pa.produit_fk
    LEFT JOIN t_valeur v ON pa.valeur_fk = v.valeur_id
    LEFT JOIN t_attribut a ON v.attribut_fk = a.attribut_id AND a.nom = 'Color'
    LEFT JOIN t_produit_attribut pa_storage ON p.produit_id = pa_storage.produit_fk
    LEFT JOIN t_valeur v_storage ON pa_storage.valeur_fk = v_storage.valeur_id
    LEFT JOIN t_attribut a_storage ON v_storage.attribut_fk = a_storage.attribut_id AND a_storage.nom = 'Storage'
    
    WHERE panier.statut = 'valide'
    GROUP BY p.produit_id, panier.panier_id
");

$selectProducts->execute();
$products = $selectProducts->fetchAll(PDO::FETCH_ASSOC);

$selectProducts->execute();
$products = $selectProducts->fetchAll(PDO::FETCH_ASSOC);

$panierCount = count($products);

$sql = "SELECT panier_id FROM t_panier WHERE client_fk = (SELECT client_id FROM t_client WHERE login = ?) AND statut = 'valide'";
$stmt = $db->prepare($sql);
$stmt->execute([$login]);
$panier_id = $stmt->fetchColumn();


$sql = "SELECT SUM(p.prix * pp.quantite) AS total 
        FROM t_produit_panier pp
        INNER JOIN t_produit p ON pp.produit_fk = p.produit_id
        WHERE pp.panier_fk = ?";

$stmt = $db->prepare($sql);
$stmt->execute([$panier_id]);
$total_commande = $stmt->fetchColumn() ?: 0;
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
    <title>Dashboard Admin - DemoMot</title>
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

                            <a href="dashboard-adm.php" class="block px-3 py-2 text-sm font-medium rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition">Produits commandés</a>
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
    <main id="main-admin-dashboard" class="max-w-7xl w-full mx-auto px-6 py-10 flex-grow">

        <div class="mb-8 bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                    <span class="w-2.5 h-6 bg-indigo-600 rounded-full inline-block"></span>
                    Dashboard Admin
                </h1>
                <p class="mt-1 text-sm text-slate-500 max-w-2xl">Bienvenue sur le dashboard admin de DemoMot. Ici, vous pouvez voir les produits commandés et l'information précise sur les commandes.</p>
            </div>
            <div class="bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-right shrink-0 self-stretch md:self-auto flex md:flex-col justify-between items-center md:items-end">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Commandes</span>
                <span class="text-xl font-extrabold text-slate-900"><?= $panierCount ?></span>
            </div>
        </div>
        <?php if ($panierCount === 0): ?>
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm text-center">
                <p class="text-sm text-slate-500">Aucune commande disponible.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php foreach ($products as $product): ?>
                    <div class="bg-white border border-slate-200/70 hover:border-slate-300 rounded-2xl p-4 flex gap-4 shadow-xs hover:shadow-md transition-all duration-200 group">

                        <div class="w-28 h-28 bg-slate-50 rounded-xl p-2 flex-shrink-0 flex items-center justify-center border border-slate-100 overflow-hidden relative">
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

                        <div class="flex-grow flex flex-col justify-between min-w-0 py-1">
                            <div>
                                <h3 class="font-bold text-slate-900 text-sm line-clamp-1 group-hover:text-indigo-600 transition duration-150" title="<?= htmlspecialchars($product['nom']) ?>">
                                    <?= htmlspecialchars($product['nom']) ?>
                                </h3>
                                <p class="text-base font-black text-emerald-600 mt-0.5"><?= htmlspecialchars($product['prix']) ?> CHF</p>
                            </div>

                            <div class="flex items-center gap-2">
                                <?php $isStock = ($product['disponibilite'] ?? 'En stock') === 'En stock'; ?>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs font-bold <?= $isStock ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/50' : 'bg-amber-50 text-amber-700 border border-amber-200/50' ?>">
                                    <span class="w-1.5 h-1.5 rounded-full <?= $isStock ? 'bg-emerald-500' : 'bg-amber-500' ?>"></span>
                                    <?= htmlspecialchars($product['disponibilite'] ?? 'En stock') ?>
                                </span>
                            </div>
                        </div>

                        <div class="flex flex-col gap-1.5 justify-center shrink-0 border-l border-slate-100 pl-4">
                            <button command="show-modal" commandfor="dialog-details-<?= $product['produit_id'] ?>" class="inline-flex items-center justify-center bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold py-2 px-3 rounded-xl text-center transition text-xs border border-indigo-100/50 whitespace-nowrap cursor-pointer">
                                Détails d'achat
                            </button>

                            <button command="show-modal" commandfor="dialog-device-<?= $product['produit_id'] ?>" class="inline-flex items-center justify-center bg-slate-50 hover:bg-slate-100 text-slate-600 font-semibold py-2 px-3 rounded-xl border border-slate-200 transition text-xs whitespace-nowrap cursor-pointer">
                                Appareil
                            </button>
                        </div>

                        <el-dialog>
                            <dialog id="dialog-details-<?= $product['produit_id'] ?>" aria-labelledby="title-details-<?= $product['produit_id'] ?>" class="fixed inset-0 size-auto max-h-none max-w-none overflow-y-auto bg-transparent backdrop:bg-transparent">
                                <el-dialog-backdrop class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in"></el-dialog-backdrop>

                                <div class="flex min-h-full items-center justify-center p-4 text-center">
                                    <el-dialog-panel class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all data-closed:scale-95 data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in sm:my-8 sm:w-full sm:max-w-md">
                                        <div class="bg-white p-6">
                                            <div class="flex items-center gap-3 mb-4">
                                                <div class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-indigo-50">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5 text-indigo-600">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 111.084 1.084l-.041.02a.75.75 0 11-1.084-1.084zM12 21a9 9 0 100-18 9 9 0 000 18z" />
                                                    </svg>
                                                </div>
                                                <h3 id="title-details-<?= $product['produit_id'] ?>" class="text-base font-black text-slate-900 leading-tight">Détails d'achat: <?= htmlspecialchars($product['nom']) ?></h3>
                                            </div>

                                            <div class="space-y-2.5 border-t border-b border-slate-100 py-3 text-xs text-slate-600">
                                                <div class="space-y-2.5 border-t border-b border-slate-100 py-3 text-xs text-slate-600">
                                                    <div class="flex justify-between">
                                                        <span class="text-slate-400">Client:</span>
                                                        <span class="font-bold text-indigo-600"><?= htmlspecialchars($product['client_login']) ?></span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-slate-400">Nom:</span>
                                                        <span class="font-bold text-indigo-600"><?= htmlspecialchars($product['client_fullname']) ?></span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-slate-400">Adresse:</span>
                                                        <span class="font-bold text-indigo-600"><?= htmlspecialchars($product['client_fulladdress']) ?></span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-slate-400">Prix total:</span>
                                                        <span class="font-extrabold text-emerald-600"><?= htmlspecialchars($product['total_position']) ?> CHF</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-slate-400">Quantité commanée:</span>
                                                        <span class="font-extrabold text-emerald-600"><?= htmlspecialchars($product['quantite_commandee']) ?></span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-slate-400">Statut de la commande:</span>
                                                        <span class="font-bold text-slate-800">
                                                            <?php
                                                            if ($product['statut'] === 'valide') {
                                                                echo '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/50">Validée</span>';
                                                            }


                                                            ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="bg-slate-50 px-6 py-3 flex sm:flex-row-reverse">
                                            <button type="button" command="close" commandfor="dialog-details-<?= $product['produit_id'] ?>" class="w-full sm:w-auto bg-slate-900 hover:bg-slate-800 text-white font-bold py-2 px-4 rounded-xl shadow-sm transition text-xs cursor-pointer">Fermer</button>
                                        </div>
                                    </el-dialog-panel>
                                </div>
                            </dialog>
                        </el-dialog>

                        <el-dialog>
                            <dialog id="dialog-device-<?= $product['produit_id'] ?>" aria-labelledby="title-device-<?= $product['produit_id'] ?>" class="fixed inset-0 size-auto max-h-none max-w-none overflow-y-auto bg-transparent backdrop:bg-transparent">
                                <el-dialog-backdrop class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in"></el-dialog-backdrop>

                                <div class="flex min-h-full items-center justify-center p-4 text-center">
                                    <el-dialog-panel class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all data-closed:scale-95 data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in sm:my-8 sm:w-full sm:max-w-md">
                                        <div class="bg-white p-6">
                                            <div class="flex items-center gap-3 mb-4">
                                                <div class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-slate-100">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5 text-slate-600">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H13.5M10.5 22.5H13.5M19.5 4.5V19.5C19.5 20.6046 18.6046 21.5 17.5 21.5H6.5C5.39543 21.5 4.5 20.6046 4.5 19.5V4.5C4.5 3.39543 5.39543 2.5 6.5 2.5H17.5C18.6046 2.5 19.5 3.39543 19.5 4.5Z" />
                                                    </svg>
                                                </div>
                                                <h3 id="title-device-<?= $product['produit_id'] ?>" class="text-base font-black text-slate-900 leading-tight"><?= htmlspecialchars($product['nom']) ?></h3>
                                            </div>

                                            <div class="space-y-2.5 border-t border-b border-slate-100 py-3 text-xs text-slate-600">
                                                <div class="flex justify-between"><span class="text-slate-400">Couleurs:</span> <span class="font-bold text-slate-800"><?= htmlspecialchars($product['color_valeur'] ?? 'N/A') ?></span></div>
                                                <div class="flex justify-between"><span class="text-slate-400">Stockage:</span> <span class="font-bold text-slate-800"><?= htmlspecialchars($product['memoire'] ?? 'N/A') ?></span></div>
                                                <div class="flex justify-between"><span class="text-slate-400">Disponibilité:</span> <span class="font-bold text-indigo-600"><?= htmlspecialchars($product['disponibilite'] ?? 'En stock') ?></span></div>
                                            </div>
                                        </div>
                                        <div class="bg-slate-50 px-6 py-3 flex sm:flex-row-reverse">
                                            <button type="button" command="close" commandfor="dialog-device-<?= $product['produit_id'] ?>" class="w-full sm:w-auto bg-slate-900 hover:bg-slate-800 text-white font-bold py-2 px-4 rounded-xl shadow-sm transition text-xs cursor-pointer">Fermer</button>
                                        </div>
                                    </el-dialog-panel>
                                </div>
                            </dialog>
                        </el-dialog>

                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <script>
            document.querySelectorAll('.js-add-to-cart').forEach(button => {
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
    <script src="/media/js/script.js"></script>
</body>

</html>