<?php
$products = [];
require_once 'db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request = trim($request, '/');
 
$fullPath = $_SERVER['DOCUMENT_ROOT'] . '/' . $request;
 
if ($request !== '' && $request !== 'index.php' && !file_exists($fullPath)) {
    http_response_code(404);
    require __DIR__ . '/error.php';
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
    <link rel="stylesheet" href="./media/css/style.css" type="text/css">
    <title>DemoMot</title>
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
    <main class="max-w-7xl w-full mx-auto flex flex-col md:flex-row p-6 gap-6 flex-grow">
        <button id="menu-btn">

        </button>
        <aside id="sidebar" class="w-full md:w-1/4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm self-start">
            <form id="form" class="space-y-6">
                <div>
                    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-3">Marque</h3>
                    <div class="space-y-2.5">
                        <label class="flex items-center gap-3 text-sm font-medium text-slate-700 cursor-pointer hover:text-slate-900"><input class="w-4 h-4 rounded text-indigo-600 border-slate-300 focus:ring-indigo-500" type="checkbox" name="iPhone" id="iPhone"> iPhone</label>
                        <label class="flex items-center gap-3 text-sm font-medium text-slate-700 cursor-pointer hover:text-slate-900"><input class="w-4 h-4 rounded text-indigo-600 border-slate-300 focus:ring-indigo-500" type="checkbox" name="Samsung" id="Samsung"> Samsung</label>
                        <label class="flex items-center gap-3 text-sm font-medium text-slate-700 cursor-pointer hover:text-slate-900"><input class="w-4 h-4 rounded text-indigo-600 border-slate-300 focus:ring-indigo-500" type="checkbox" name="Xiaomi" id="Xiaomi"> Xiaomi</label>
                        <label class="flex items-center gap-3 text-sm font-medium text-slate-700 cursor-pointer hover:text-slate-900"><input class="w-4 h-4 rounded text-indigo-600 border-slate-300 focus:ring-indigo-500" type="checkbox" name="Google" id="Google"> Google</label>
                        <label class="flex items-center gap-3 text-sm font-medium text-slate-700 cursor-pointer hover:text-slate-900"><input class="w-4 h-4 rounded text-indigo-600 border-slate-300 focus:ring-indigo-500" type="checkbox" name="OnePlus" id="OnePlus"> OnePlus</label>
                        <label class="flex items-center gap-3 text-sm font-medium text-slate-700 cursor-pointer hover:text-slate-900"><input class="w-4 h-4 rounded text-indigo-600 border-slate-300 focus:ring-indigo-500" type="checkbox" name="Nothing" id="Nothing"> Nothing</label>
                    </div>
                </div>
                <div class="border-t border-slate-100 pt-4">
                    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-3">Couleur</h3>
                    <div class="space-y-2.5">
                        <label class="flex items-center gap-3 text-sm font-medium text-slate-700 cursor-pointer hover:text-slate-900"><input class="w-4 h-4 rounded text-indigo-600 border-slate-300 focus:ring-indigo-500" type="checkbox" name="couleurs[]" id="Sombre" value="Sombre"> Sombre</label>
                        <label class="flex items-center gap-3 text-sm font-medium text-slate-700 cursor-pointer hover:text-slate-900"><input class="w-4 h-4 rounded text-indigo-600 border-slate-300 focus:ring-indigo-500" type="checkbox" name="couleurs[]" id="Clair" value="Clair"> Clair</label>
                        <label class="flex items-center gap-3 text-sm font-medium text-slate-700 cursor-pointer hover:text-slate-900"><input class="w-4 h-4 rounded text-indigo-600 border-slate-300 focus:ring-indigo-500" type="checkbox" name="couleurs[]" id="Personnalise" value="Personnalise"> Personnalisé</label>
                        <div class="border-t border-slate-100 pt-6 mt-6">
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Filtrer par coût</h3>

                            <div class="flex items-center gap-4">
                                <div class="flex-1 flex gap-2">
                                    <div class="relative">
                                        <span class="absolute left-2 top-2 text-[10px] font-bold text-slate-400 uppercase">Min</span>
                                        <input name="price_min" type="number" min="0" max="2000" step="50" placeholder="0" class="w-full pl-8 pr-2 py-2 rounded-lg border border-slate-200 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition">
                                    </div>
                                    <div class="relative">
                                        <span class="absolute left-2 top-2 text-[10px] font-bold text-slate-400 uppercase">Max</span>
                                        <input name="price_max" type="number" min="0" max="2000" step="50" placeholder="2000" class="w-full pl-8 pr-2 py-2 rounded-lg border border-slate-200 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition">
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="border-t border-slate-100 pt-4">
                            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-3">Mémoire</h3>
                            <div class="space-y-2.5">
                                <label class="flex items-center gap-3 text-sm font-medium text-slate-700 cursor-pointer hover:text-slate-900"><input class="w-4 h-4 rounded text-indigo-600 border-slate-300 focus:ring-indigo-500" type="checkbox" name="storage[]" id="128 Go" value="128 Go"> 128 Go</label>
                                <label class="flex items-center gap-3 text-sm font-medium text-slate-700 cursor-pointer hover:text-slate-900"><input class="w-4 h-4 rounded text-indigo-600 border-slate-300 focus:ring-indigo-500" type="checkbox" name="storage[]" id="256 Go" value="256 Go"> 256 Go</label>
                                <label class="flex items-center gap-3 text-sm font-medium text-slate-700 cursor-pointer hover:text-slate-900"><input class="w-4 h-4 rounded text-indigo-600 border-slate-300 focus:ring-indigo-500" type="checkbox" name="storage[]" id="512 Go" value="512 Go"> 512 Go</label>
                                <label class="flex items-center gap-3 text-sm font-medium text-slate-700 cursor-pointer hover:text-slate-900"><input class="w-4 h-4 rounded text-indigo-600 border-slate-300 focus:ring-indigo-500" type="checkbox" name="storage[]" id="1 To" value="1 To"> 1 To</label>
                            </div>
                        </div>

                        <div class="border-t border-slate-100 pt-4">
                            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-3">Disponibilité</h3>
                            <div class="space-y-2.5">
                                <label class="flex items-center gap-3 text-sm font-medium text-slate-700 cursor-pointer hover:text-slate-900"><input class="w-4 h-4 text-indigo-600 border-slate-300 focus:ring-indigo-500" type="radio" name="dispo" id="EnStock" value="en_stock"> En Stock</label>
                                <label class="flex items-center gap-3 text-sm font-medium text-slate-700 cursor-pointer hover:text-slate-900"><input class="w-4 h-4 text-indigo-600 border-slate-300 focus:ring-indigo-500" type="radio" name="dispo" id="BienTot" value="bientot"> Bientôt disponible</label>
                                <label class="flex items-center gap-3 text-sm font-medium text-slate-700 cursor-pointer hover:text-slate-900"><input class="w-4 h-4 text-indigo-600 border-slate-300 focus:ring-indigo-500" type="radio" name="dispo" id="BienTotFini" value="epuise"> Bientôt épuisé</label>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3 pt-2">
                            <button type="submit" name="buttonfinish" id="envoyer" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-4 rounded-xl shadow-sm transition cursor-pointer text-center text-sm">Appliquer</button>
                            <a href="index.php" class="w-full bg-slate-100 text-slate-700 font-semibold py-2.5 px-4 rounded-xl transition text-center text-sm hover:bg-red-100 hover:text-red-600 transition">Reset</a>
                        </div>
            </form>
        </aside>

        <section class="w-full md:w-3/4">
            <div class="flex items-center justify-between mb-6 pl-2">
                <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Nos produits</h2>
                <span class="text-sm text-slate-500 font-medium"><?= count($products) ?> articles trouvés</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php if (!empty($products)): ?>

                    <?php foreach ($products as $product): ?>
                        <div class="bg-white border border-slate-200/70 rounded-2xl p-5 flex flex-col shadow-sm hover:shadow-md hover:-translate-y-0.5 transition duration-200">
                            <div class="w-full h-48 bg-slate-50 rounded-xl mb-4 p-4 flex items-center justify-center overflow-hidden border border-slate-100">
                                <?php
                                if (!empty($product['url'])) {
                                    $fileName = basename($product['url']);
                                    $imagePath = '/media/images/' . trim($fileName);
                                } else {
                                    $imagePath = '/media/images/no-photo.png';
                                }
                                ?>
                                <img class="w-full h-full object-contain mix-blend-multiply"
                                    src="<?= htmlspecialchars($imagePath) ?>"
                                    alt="Photo de <?= htmlspecialchars($product['nom']) ?>">
                            </div>
                            <div class="flex-grow flex flex-col justify-between mb-5">
                                <h3 class="font-bold text-slate-800 text-base line-clamp-2 mb-2"><?= htmlspecialchars($product['nom']) ?></h3>
                                <p class="text-lg font-extrabold text-emerald-600"><?= htmlspecialchars($product['prix']) ?> CHF</p>
                            </div>
                            <div class="flex flex-col gap-2">
                                <?php if (isset($_SESSION['login'])): ?>
                                    <a class="js-add-to-cart w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 px-4 rounded-xl text-center shadow-sm transition text-sm" href="add-to-cart.php?id=<?= $product['produit_id'] ?>">Ajouter au panier</a>
                                <?php elseif (!isset($_SESSION['login'])): ?>
                                    <a class="js-add-to-cart w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 px-4 rounded-xl text-center shadow-sm transition text-sm" href="login.php">Ajouter au panier</a>
                                <?php endif; ?>
                                <button command="show-modal" commandfor="dialog-<?= $product['produit_id'] ?>" class="w-full bg-slate-50 hover:bg-slate-100 text-slate-700 font-medium py-2 px-4 rounded-xl border border-slate-200/60 transition text-xs cursor-pointer">Cliquer pour regarder</button>
                            </div>

                            <el-dialog>
                                <dialog id="dialog-<?= $product['produit_id'] ?>" aria-labelledby="title-<?= $product['produit_id'] ?>" class="fixed inset-0 size-auto max-h-none max-w-none overflow-y-auto bg-transparent backdrop:bg-transparent">
                                    <el-dialog-backdrop class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in"></el-dialog-backdrop>

                                    <div class="flex min-h-full items-center justify-center p-4 text-center">
                                        <el-dialog-panel class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all data-closed:scale-95 data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in sm:my-8 sm:w-full sm:max-w-lg">
                                            <div class="bg-white p-6">
                                                <div class="sm:flex sm:items-start">
                                                    <div class="mx-auto flex size-10 shrink-0 items-center justify-center rounded-full bg-indigo-50 sm:mx-0">
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5 text-indigo-600">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 111.084 1.084l-.041.02a.75.75 0 11-1.084-1.084zM12 21a9 9 0 100-18 9 9 0 000 18z" />
                                                        </svg>
                                                    </div>
                                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                                        <h3 id="title-<?= $product['produit_id'] ?>" class="text-lg font-bold leading-6 text-slate-900"><?= htmlspecialchars($product['nom']) ?></h3>
                                                        <div class="mt-4 space-y-2 border-t border-slate-100 pt-3 text-sm text-slate-600">
                                                            <p class="flex justify-between"><span class="text-slate-400">Prix:</span> <span class=" font-bold text-emerald-600"><?= htmlspecialchars($product['prix']) ?> CHF</span></p>

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
                                                                    <?php if (!empty($product_colors)): ?>
                                                                        <?= htmlspecialchars(implode(', ', $product_colors)) ?>
                                                                    <?php else: ?>
                                                                        N/A
                                                                    <?php endif; ?>
                                                                </span>
                                                            </p>
                                                            <p class="flex justify-between"><span class="text-slate-400">Stockage:</span> <span class="font-medium text-slate-800"><?= htmlspecialchars($product['storage_value'] ?? 'N/A') ?></span></p>
                                                            <p class="flex justify-between">
                                                                <span class="text-slate-400">Disponibilité:</span>
                                                                <span class="font-semibold <?php
                                                                                            $qty = isset($product['quantite']) ? (int)$product['quantite'] : 0;
                                                                                            if ($qty > 5) {
                                                                                                echo 'text-emerald-600';
                                                                                            } elseif ($qty === 0) {
                                                                                                echo 'text-amber-600';
                                                                                            } else {
                                                                                                echo 'text-rose-600';
                                                                                            }
                                                                                            ?>">
                                                                    <?php
                                                                    if ($qty > 5) {
                                                                        echo "En stock";
                                                                    } elseif ($qty === 0) {
                                                                        echo "Bientôt disponible";
                                                                    } else {
                                                                        echo "Bientôt épuisé";
                                                                    }
                                                                    ?>
                                                                </span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bg-slate-50 px-6 py-4 flex sm:flex-row-reverse">
                                                <button type="button" command="close" commandfor="dialog-<?= $product['produit_id'] ?>" class="inline-flex w-full justify-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 sm:ml-3 sm:w-auto transition cursor-pointer">Fermer</button>
                                            </div>
                                        </el-dialog-panel>
                                    </div>
                                </dialog>
                            </el-dialog>
                        </div>
                    <?php endforeach; ?>

                <?php else: ?>
                    <div class="col-span-full text-center py-12 px-4 bg-white rounded-2xl border border-slate-200/80 shadow-sm self-start">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-10 h-10 text-slate-400 mx-auto mb-3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.008 1.24l.885 1.77a2.25 2.25 0 0 0 2.007 1.24h1.98a2.25 2.25 0 0 0 2.007-1.24l.885-1.77a2.25 2.25 0 0 1 2.007-1.24h3.86m-18 0h18a2.25 2.25 0 0 1 2.25 2.25v4.5A2.25 2.25 0 0 1 19.5 21h-15A2.25 2.25 0 0 1 2.25 18.75v-4.5A2.25 2.25 0 0 1 2.25 13.5Zm0-4.5h18A2.25 2.25 0 0 0 24 6.75v-3A2.25 2.25 0 0 0 21.75 1.5h-15A2.25 2.25 0 0 0 4.5 3.75v3A2.25 2.25 0 0 0 2.25 9Z" />
                        </svg>
                        <p class="text-base font-bold text-slate-700 mb-1">
                            Aucun produit trouvé
                        </p>
                        <p class="text-sm text-slate-400 max-w-sm mx-auto">
                            Désolé, nous n'avons trouvé aucun produit correspondant à "<?= htmlspecialchars($_GET['search'] ?? '') ?>". Vérifiez l'orthographe ou modifiez vos filtres.
                        </p>
                        <a href="index.php" class="inline-block mt-4 bg-slate-900 hover:bg-slate-800 text-white font-medium text-xs py-2 px-4 rounded-xl transition shadow-sm">
                            Réinitialiser les filtres
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <button id="scrollToTopBtn"
            onclick="scrollToTop()"
            class="fixed bottom-14 right-8 z-50 p-4 bg-indigo-600 text-white rounded-full shadow-lg hover:bg-indigo-700 transition-all duration-300 transform hover:scale-110 active:scale-100 cursor-pointer">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18" />
            </svg>
        </button>

        <script src="./media/js/script.js"></script>
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