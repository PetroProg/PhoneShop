<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
    <link rel="stylesheet" href="./media/css/style.css" type="text/css">
    <title>Settings</title>
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
        <div class="max-w-7xl mx-auto flex justify-between items-center px-6 py-4 text-white">

            <nav class="flex items-center">
                <a href="index.php" class=" inline-block transition-all duration-200 hover:scale-105 active:scale-95">
                    <img class="w-10 h-10 rounded-full border-2 border-indigo-500/40 object-cover shadow-md shadow-indigo-500/10" src="./media/images/icone-site.png" alt="Le photo de site">
                </a>
            </nav>

            <h1 class="text-xl font-black tracking-tight bg-gradient-to-r from-indigo-400 via-cyan-400 to-emerald-400 bg-clip-text text-transparent select-none">
                DemoMot
            </h1>

            <nav class="flex items-center gap-x-2">
                <form action="index.php" method="GET" class="w-full">
                    <div class=" barre-rech relative">
                        <input
                            type="text"
                            name="search"
                            placeholder="Rechercher un produit..."
                            class="px-4 py-2 text-sm font-medium bg-slate-950/40 border border-slate-800 text-slate-300 placeholder-slate-500 rounded-xl transition-all duration-200 focus:outline-none focus:border-indigo-500/50 focus:bg-slate-800/60 focus:text-white w-48">
                    </div>
                </form>
                <a href="/panier.php" class="head-p px-4 py-2 text-sm font-semibold hover:bg-slate-800/80 hover:text-indigo-400 rounded-xl transition-all duration-200 flex items-center justify-center border border-slate-800 bg-slate-950/40 group">
                    Panier
                </a>

                <el-dropdown class="inline-block relative group">
                    <button class=" but-p px-4 py-2 text-sm font-semibold hover:bg-slate-800/80 text-slate-300 hover:text-white rounded-xl transition-all duration-200 flex items-center justify-center border border-slate-800 bg-slate-950/40 focus:outline-none cursor-pointer group-hover:border-slate-700">
                        Compte
                        <svg class="w-4 h-4 ml-1.5 opacity-60 group-hover:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <el-menu anchor="bottom end" popover class="w-56 origin-top-right rounded-xl bg-white p-1.5 text-slate-800 shadow-2xl border border-slate-200/80 transition transition-discrete [--anchor-gap:--spacing(2)] data-closed:scale-95 data-closed:transform data-closed:opacity-0 data-enter:duration-150 data-enter:ease-out data-leave:duration-100 data-leave:ease-in">
                        <div class="space-y-0.5">

                            <?php if (!isset($_COOKIE['utilisateur'])): ?>
                                <a href="login.php" class="flex items-center gap-2 px-3 py-2 text-sm font-semibold rounded-lg text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition duration-150">
                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-600"></span>
                                    Login
                                </a>
                            <?php else: ?>
                                <a href="homepage.php" class="flex items-center gap-2 px-3 py-2 text-sm font-semibold rounded-lg text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition duration-150">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Account
                                </a>
                            <?php endif; ?>

                            <a href="account-settings.php" class="block px-3 py-2 text-sm font-medium rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition duration-150">Account settings</a>
                            <a href="license.php" class="block px-3 py-2 text-sm font-medium rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition duration-150">License</a>

                            <div class="my-1.5 border-t border-slate-100"></div>

                            <form action="signout.php" method="POST">
                                <button type="submit" class="cursor-pointer flex w-full items-center gap-2 px-3 py-2 text-left text-sm font-semibold rounded-lg text-red-600 hover:bg-red-50 transition duration-150">
                                    <svg class="w-4 h-4 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Sign out
                                </button>
                            </form>
                        </div>
                    </el-menu>
                </el-dropdown>

            </nav>
        </div>
    </header>
    <main class="max-w-4xl mx-auto py-10 px-6">
        <h1 class="text-3xl font-extrabold text-slate-900 mb-8">Paramètres du compte</h1>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <nav class="md:col-span-1 space-y-2">
                <a href="#" class="block px-4 py-2 bg-indigo-50 text-indigo-700 font-semibold rounded-lg">Profil</a>
                <a href="#" class="block px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-lg">Sécurité</a>
                <a href="#" class="block px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-lg">Notifications</a>
            </nav>

            <div class="md:col-span-3 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <form action="update_profile.php" method="POST" class="space-y-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-slate-200 rounded-full flex items-center justify-center text-slate-500">Photo</div>
                        <button type="button" class="text-sm font-medium text-indigo-600 hover:underline">Modifier</button>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Nom</label>
                            <input type="text" name="nom" class="mt-1 w-full p-2 border border-slate-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Prénom</label>
                            <input type="text" name="prenom" class="mt-1 w-full p-2 border border-slate-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <div class="pt-4 border-t">
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-xl font-semibold hover:bg-indigo-700 transition">
                            Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
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