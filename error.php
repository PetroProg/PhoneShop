<?php
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="en">

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
    <title>Erreur</title>
</head>

<body class="min-h-screen flex flex-col bg-slate-50 text-slate-900 font-sans selection:bg-indigo-500/30 overflow-x-hidden">
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
    <main class="relative z-0bg-slate-50 flex items-center justify-center px-4 flex-grow bg-slate-50 backdrop-blur-md">
        <div class="max-w-xl w-full text-center">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 mb-6 border border-indigo-100">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                Erreur 404
            </span>

            <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight mb-3">
                Page introuvable
            </h1>

            <p class="text-base text-slate-500 mb-8 max-w-md mx-auto leading-relaxed">
                Désolé, la page que vous recherchez n'existe pas.
            </p>

            <div class="mb-8 bg-white p-2 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-2 focus-within:ring-2 focus-within:ring-indigo-600/20 focus-within:border-indigo-600 transition duration-200">
                <div class="pl-2 text-slate-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <form action="index.php" method="GET" class="w-full">
                    <div class="relative">
                        <input
                            type="text"
                            name="search"
                            placeholder="Rechercher un produit..."
                            class="w-full bg-transparent border-none text-sm text-slate-800 placeholder-slate-400 focus:outline-none py-1.5">
                    </div>
                </form>
            </div>

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

            <a href="index.php" class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white font-medium text-sm py-2.5 px-5 rounded-xl shadow-sm transition duration-200">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour à l'accueil
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
</body>

</html>