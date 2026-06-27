<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db.php';


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['buttonfinish'])) {

    $login = filter_var($_POST['login'] ?? '', FILTER_SANITIZE_EMAIL);
    $passwordRaw = $_POST['password'] ?? '';

    if ($_POST['buttonfinish'] === "S'inscrire") {
        if (!filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $error_message = "Error: Invalid email format.";
        } elseif (strlen($passwordRaw) < 8) {
            $error_message = "Error: Password must be at least 8 characters long.";
        } else {
            $domain = substr(strrchr($login, "@"), 1);

            if (!checkdnsrr($domain, "MX")) {
                $error_message = "Error: This email domain does not exist.";
            } else {
                $checkStmt = $db->prepare("SELECT COUNT(*) FROM t_client WHERE login = ?");
                $checkStmt->execute([$login]);

                if ($checkStmt->fetchColumn() > 0) {
                    $error_message = "Error: This email is already registered.";
                } else {
                    $hashedPassword = password_hash($passwordRaw, PASSWORD_DEFAULT);
                    $insertStmt = $db->prepare("INSERT INTO t_client (login, password, privilege_fk) VALUES (?, ?, 2)");
                    $insertStmt->execute([$login, $hashedPassword]);
                    $succes_message = "Vous avez bien créer votre compte!";
                }
            }
        }
    }
    if ($_POST['buttonfinish'] === "Login") {
        $stmt = $db->prepare("SELECT * FROM t_client WHERE login = ?");
        $stmt->execute([$login]);
        $user = $stmt->fetch();

        if ($user && password_verify($passwordRaw, $user['password'])) {
            $_SESSION['login'] = $user['login'];
            $_SESSION['nom'] = $user['nom'];

            $checkPrivilege = $db->prepare("SELECT privilege_fk FROM t_client WHERE login = ?");
            $checkPrivilege->execute([$login]);
            $privilege = $checkPrivilege->fetchColumn();

            if ($privilege == 3) {
                header("Location: dashboard-adm.php");
            } else {
                header("Location: homepage.php");
            }


            exit;
        } else {
            $error_message = "Invalid email or password.";
        }
    }
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
    <title>Login - DemoMot</title>
</head>

<body class="min-h-screen flex flex-col">
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
    <main class="flex items-center justify-center flex-grow relative z-10 bg-slate-50 backdrop-blur-md">
        <section class="w-full max-w-7xl mx-auto px-4 flex items-center justify-center">

            <form id="form-login" class=" formsInscr w-full max-w-md bg-white/90 backdrop-blur-md border border-slate-200/80 p-8 sm:p-10 rounded-2xl shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-slate-200/60 transition duration-300" action="" method="post">
                <div class="w-full">
                    <div class="mb-8 text-center">
                        <h2 class="text-3xl font-black text-slate-900 tracking-tight mb-2">Login</h2>
                        <p class="text-sm text-slate-500 font-medium">Heureux de vous revoir !</p>
                    </div>

                    <?php if (!empty($error_message) && $_POST['buttonfinish'] === "Login"): ?>
                        <div class="mb-5 p-4 bg-red-50 border border-red-200 text-red-700 text-sm font-medium rounded-xl flex items-center gap-2 animate-shake">
                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full shrink-0"></span>
                            <p><?php echo htmlspecialchars($error_message); ?></p>
                        </div>
                    <?php endif; ?>

                    <div class="space-y-4 mb-6">
                        <div>
                            <label for="login" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5 ml-1">Email</label>
                            <input class="w-full border border-slate-200 bg-slate-50/50 rounded-xl py-3 px-4 text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200 text-sm font-medium" required type="email" name="login" id="login" placeholder="Entrez votre login">
                        </div>
                        <div>
                            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5 ml-1">Mot de passe</label>
                            <input class="w-full border border-slate-200 bg-slate-50/50 rounded-xl py-3 px-4 text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200 text-sm font-medium" required type="password" name="password" id="password" placeholder="Entrez votre mot de passe">
                        </div>
                    </div>

                    <div class="flex flex-col items-center gap-5">
                        <div>
                            <p class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition duration-200 cursor-pointer" id="change-to-registry">Créer un nouveau compte</p>
                        </div>

                        <div class="w-full relative group/btn">
                            <div class="absolute -inset-0.5 bg-gradient-to-r from-indigo-500 to-cyan-500 rounded-xl blur-md opacity-30 group-hover/btn:opacity-60 transition duration-300"></div>
                            <input type="submit" name="buttonfinish" id="envoyer" value="Login" class="relative w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 px-4 rounded-xl transition duration-200 text-sm tracking-wide shadow-md shadow-indigo-200 cursor-pointer active:scale-[0.99]">
                        </div>
                    </div>
                </div>
            </form>

            <form id="form-registry" class="formsInscr hidden w-full max-w-md bg-white/90 backdrop-blur-md border border-slate-200/80 p-8 sm:p-10 rounded-2xl shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-slate-200/60 transition duration-300" action="" method="post">
                <div class="w-full">
                    <div class="mb-8 text-center">
                        <h2 class="text-3xl font-black text-slate-900 tracking-tight mb-2">Inscription</h2>
                        <p class="text-sm text-slate-500 font-medium">Rejoignez-nous en quelques clics</p>
                    </div>

                    <?php if (!empty($error_message) && $_POST['buttonfinish'] === "S'inscrire"): ?>
                        <div class="error-registry mb-5 p-4 bg-red-50 border border-red-200 text-red-700 text-sm font-medium rounded-xl flex items-center gap-2 animate-shake">
                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full shrink-0"></span>
                            <p><?php echo htmlspecialchars($error_message); ?></p>
                        </div>
                    <?php endif; ?>

                    <div class="space-y-4 mb-6">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5 ml-1">Email</label>
                            <input class="w-full border border-slate-200 bg-slate-50/50 rounded-xl py-3 px-4 text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200 text-sm font-medium" required type="email" name="login" id="login" placeholder="Entrez votre login">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5 ml-1">Mot de passe</label>
                            <input class="w-full border border-slate-200 bg-slate-50/50 rounded-xl py-3 px-4 text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-200 text-sm font-medium" required type="password" name="password" id="password" placeholder="Entrez votre mot de passe">
                        </div>
                    </div>

                    <div class="flex flex-col items-center gap-5">
                        <div>
                            <p class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition duration-200 cursor-pointer" id="change-to-login">Entrer dans une compte existant</p>
                        </div>

                        <div class="w-full relative group/btn">
                            <div class="absolute -inset-0.5 bg-gradient-to-r from-indigo-500 to-cyan-500 rounded-xl blur-md opacity-30 group-hover/btn:opacity-60 transition duration-300"></div>
                            <input type="submit" name="buttonfinish" id="envoyer" value="S'inscrire" class="relative w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 px-4 rounded-xl transition duration-200 text-sm tracking-wide shadow-md shadow-indigo-200 cursor-pointer active:scale-[0.99]">
                        </div>
                    </div>
                </div>
            </form>
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