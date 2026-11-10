<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db.php';
require_once 'cookie.php';


if (isset($_COOKIE['utilisateur'])) {    
        $text = "Bienvenue, <span class='font-black bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent'>" . htmlspecialchars($_SESSION['login']) . "</span> !";

} else {
    $text = "Bonjour <span class='font-bold text-slate-400'>invité</span> ! Découvrez nos dernières nouveautés.";
}
?>
<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
    <title>Accueil - DemoMot</title> 
</head>
<body class="bg-slate-950 text-slate-100 font-sans min-h-screen flex flex-col relative overflow-x-hidden selection:bg-indigo-500/30">

    <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none opacity-40">
        <div class="absolute top-[-10%] left-[-10%] w-[50vw] h-[50vw] rounded-full bg-radial from-[#7521fc]/30 via-[#2521fc]/10 to-transparent blur-3xl animate-glow-1"></div>
        <div class="absolute bottom-[10%] right-[-5%] w-[45vw] h-[45vw] rounded-full bg-radial from-[#2480fc]/20 via-[#7521fc]/5 to-transparent blur-3xl animate-glow-2"></div>
    </div>
    <main class="relative z-10 w-full max-w-7xl mx-auto px-6 py-20 flex-grow flex flex-col justify-center items-center">
        
        <div class="w-full max-w-2xl bg-slate-900/40 backdrop-blur-xl border border-slate-800/80 rounded-3xl p-10 text-center shadow-2xl shadow-black/50 relative overflow-hidden group">
            
            <div class="absolute top-0 inset-x-0 h-[2px] bg-gradient-to-r from-transparent via-indigo-500 to-transparent opacity-70"></div>
            
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-slate-800/40 border border-slate-700/30 rounded-full text-xs font-semibold text-indigo-400 mb-6 tracking-wide uppercase">
                <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full animate-pulse"></span>
                Espace Client Authentifié
            </div>

            <h2 class="text-4xl font-black text-white tracking-tight mb-4">
                <?= $text; ?>
            </h2>
            
            <p class="text-lg text-slate-400 font-medium leading-relaxed max-w-md mx-auto">
                On est content de vous voir.
            </p>

            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="index.php" class="w-full sm:w-auto inline-flex items-center justify-center bg-white hover:bg-slate-200 text-slate-950 font-bold py-3 px-6 rounded-xl transition text-sm shadow-lg shadow-white/5 cursor-pointer">
                    Explorer la boutique
                </a>
                <a href="panier.php" class="w-full sm:w-auto inline-flex items-center justify-center bg-slate-900 hover:bg-slate-800 text-slate-300 border border-slate-800 hover:border-slate-700 font-bold py-3 px-6 rounded-xl transition text-sm cursor-pointer">
                    Suivre mes commandes
                </a>
            </div>
        </div>
    </main>
</body>

</html>