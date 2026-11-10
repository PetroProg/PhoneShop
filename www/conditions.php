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
</head>

<body class="bg-slate-50 text-slate-900">
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
    <main class="w-full min-h-screen bg-slate-50 flex justify-center py-5">
        <section class="max-w-4xl w-full bg-white border border-slate-200/70 rounded-2xl p-8 md:p-12 shadow-sm">

            <div class="border-b border-slate-100 pb-8 mb-10">
                <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 text-center tracking-tight">
                    Mentions légales
                </h1>
            </div>

            <div class="mb-12">
                <h2 class="text-2xl font-bold text-slate-800 mb-6 text-center">Éditeur et hébergeur du site</h2>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-slate-50 border border-slate-100 rounded-2xl p-6 shadow-inner">
                        <p class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-3">Hébergeur:</p>
                        <p class="text-slate-700 leading-relaxed">
                            Maksym Tsybulevskyi <br> Rue de la Gare 20 <br> 1348 Le Brassus <br> +41 76 542 80 89 <br> Apprenti ETML 1er anée
                        </p>
                    </div>
                    <div class="bg-slate-50 border border-slate-100 rounded-2xl p-6 shadow-inner">
                        <p class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-3">Hébergeur:</p>
                        <p class="text-slate-700 leading-relaxed">
                            Mon Premier Site Sàrl <br> Route de Lausanne 170 <br> 1400 Yverdon-Les-Bains <br> +41 22 333 44 55 <br> IDE: CHE-203.574.xxx
                        </p>
                    </div>
                </div>
            </div>

            <div class="space-y-10 text-slate-700 leading-relaxed">

                <section>
                    <h2 class="text-2xl font-bold text-slate-800 mb-4 border-l-4 border-indigo-500 pl-4">Conditions d’utilisation</h2>
                    <p>Ce site est proposé en différents langages web (HTML, PHP, Javascript, CSS, etc…) pour un meilleur confort d’utilisation et un graphisme plus agréable, nous vous recommandons de recourir à des navigateurs modernes comme Edge, Safari, Firefox, Google Chrome, etc….</p>
                    <p class="mt-4">DemoMot met en œuvre tous les moyens dont elle dispose, pour assurer une information fiable et une mise à jour fiable de ses sites internet. Toutefois, des erreurs ou omissions peuvent survenir. L’internaute devra donc s’assurer de l’exactitude des informations auprès de DemoMot, et signaler toutes modifications du site qu’il jugerait utile. DemoMot n’est en aucun cas responsable de l’utilisation faite de ces informations, et de tout préjudice direct ou indirect pouvant en découler.</p>
                </section>

                <section>
                    <h2 class="text-2xl font-bold text-slate-800 mb-4 border-l-4 border-indigo-500 pl-4">Cookies</h2>
                    <p>Ce site peut être amené à vous demander l’acceptation des cookies pour des besoins de statistiques et d’affichage. Un cookie est une information déposée sur votre disque dur par le serveur du site que vous visitez. Il contient plusieurs données qui sont stockées sur votre ordinateur dans un simple fichier texte auquel un serveur accède pour lire et enregistrer des informations. Certaines parties de ce site ne peuvent être fonctionnelles sans l’acceptation de cookies.</p>
                </section>

                <section>
                    <h2 class="text-2xl font-bold text-slate-800 mb-4 border-l-4 border-indigo-500 pl-4">Propriété intellectuelle</h2>
                    <p>Le site DemoMot est propriétaire exclusif de tous les droits de propriété intellectuelle ou détient les droits d’usage sur tous les éléments de contenus accessibles sur le site, tant sur la structure que sur les textes, images, graphismes, logo, icônes, sons … Toute reproduction totale ou partielle de ce site, représentation, modification, publication, adaptation totale ou partielle de l'un quelconque de ces éléments, quel que soit le moyen ou le procédé utilisé, est interdite, sauf autorisation écrite préalable de DemoMot.</p>
                </section>

                <section class="bg-indigo-50/50 p-8 rounded-2xl border border-indigo-100">
                    <h2 class="text-2xl font-bold text-indigo-900 mb-6">Politique de confidentialité</h2>
                    <div class="space-y-6">
                        <div>
                            <h3 class="font-bold text-slate-900 mb-2">Consentement</h3>
                            <p class="text-sm">En vous inscrivant à nos services ou en remplissant un formulaire de contact sur notre site, vous acceptez que le site DemoMot puisse recolleter, traiter, stocker et/ou utiliser vos données personnelles.</p>
                        </div>

                        <div>
                            <h3 class="font-bold text-slate-900 mb-2">Quelles sont les données personnelles traitées ?</h3>
                            <p class="text-sm">Il s’agit des données que vous nous communiquez au travers de notre site Internet lorsque vous utilisez un des formulaires de contact. Nous utilisons ces données (noms, prénoms, emails, téléphones) pour pouvoir vous recontacter et vous envoyer des newsletters.</p>
                        </div>

                        <div>
                            <h3 class="font-bold text-slate-900 mb-2">Quels sont mes droits sur ces données ?</h3>
                            <p class="text-sm">Vous disposez pendant toute la durée du traitement du droit de demander au responsable du traitement l'accès aux données, la rectification ou l'effacement de celles-ci, ou une limitation du traitement.</p>
                        </div>
                    </div>
                </section>

                <section>
                    <h2 class="text-2xl font-bold text-slate-800 mb-4 border-l-4 border-indigo-500 pl-4">Litiges</h2>
                    <p>Les présentes conditions sont régies par le droit Suisse dont dépend le siège social de la société DemoMot.</p>
                </section>
            </div>
        </section>
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
    <script src="./media/js/script.js"></script>
</body>

</html>