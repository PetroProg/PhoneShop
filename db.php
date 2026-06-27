<?php
$host = '127.0.0.1';
$dbname = 'db_magasin_electronique';
$user = 'site_user';
$pass = 'user_password_123';

// Etablir la connexion à la base de données avec PDO
try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    // Set the error mode to exception for better error handling
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$sql = "SELECT 
            t_produit.produit_id, 
            t_produit.nom, 
            t_produit.prix,
            t_produit.quantite AS quantite, 
            t_image.url,
            MAX(v_storage.valeur) AS storage_value,
            GROUP_CONCAT(DISTINCT v.valeur SEPARATOR ', ') AS colors_list,
            GROUP_CONCAT(DISTINCT v.valeur_id SEPARATOR ', ') AS colors_ids
        FROM t_produit
        LEFT JOIN t_image ON t_produit.produit_id = t_image.produit_fk
        LEFT JOIN t_produit_attribut pa ON t_produit.produit_id = pa.produit_fk
        LEFT JOIN t_valeur v ON pa.valeur_fk = v.valeur_id
        LEFT JOIN t_attribut a ON v.attribut_fk = a.attribut_id AND a.nom = 'Color'
        LEFT JOIN t_produit_attribut pa_storage ON t_produit.produit_id = pa_storage.produit_fk
        LEFT JOIN t_valeur v_storage ON pa_storage.valeur_fk = v_storage.valeur_id AND v_storage.attribut_fk = 2";

$conditions = ["1=1"];
$params = [];

$selectedBrands = [];
if (isset($_GET['iPhone'])) $selectedBrands[] = 1;
if (isset($_GET['Samsung'])) $selectedBrands[] = 2;
if (isset($_GET['Xiaomi'])) $selectedBrands[] = 3;
if (isset($_GET['Google'])) $selectedBrands[] = 4;
if (isset($_GET['OnePlus'])) $selectedBrands[] = 5;
if (isset($_GET['Nothing'])) $selectedBrands[] = 6;

if (!empty($selectedBrands)) {
    $placeholders = implode(',', array_fill(0, count($selectedBrands), '?'));
    $conditions[] = "t_produit.categorie_fk IN ($placeholders)";
    $params = array_merge($params, $selectedBrands);
}


if (isset($_GET['couleurs']) && is_array($_GET['couleurs'])) {
    $selected_colors = $_GET['couleurs'];

    $color_placeholders = implode(',', array_fill(0, count($selected_colors), '?'));

    $conditions[] = "t_produit.produit_id IN (
        SELECT t_produit_attribut.produit_fk 
        FROM t_produit_attribut
        INNER JOIN t_valeur ON t_produit_attribut.valeur_fk = t_valeur.valeur_id
        INNER JOIN t_attribut ON t_valeur.attribut_fk = t_attribut.attribut_id
        WHERE t_attribut.nom = 'Color' AND t_valeur.valeur IN ($color_placeholders)
    )";

    foreach ($selected_colors as $color) {
        $params[] = $color;
    }
}

// La barre de recherche sur la page error.php
$brands = ['iPhone', 'Samsung', 'Xiaomi', 'Google', 'OnePlus', 'Nothing'];
$search = '';

if (!empty($_GET['search'])) {
    $user_input = trim($_GET['search']);
    $closest_match = '';
    $highest_sim = 0;

    foreach ($brands as $brand) {
        similar_text(strtolower($user_input), strtolower($brand), $percent);

        if ($percent > $highest_sim) {
            $highest_sim = $percent;
            $closest_match = $brand;
        }
    }

    if ($highest_sim > 50) {
        $search = $closest_match;

        if ($closest_match === 'iPhone')  $selectedBrands[] = 1;
        if ($closest_match === 'Samsung') $selectedBrands[] = 2;
        if ($closest_match === 'Xiaomi')  $selectedBrands[] = 3;
        if ($closest_match === 'Google')  $selectedBrands[] = 4;
        if ($closest_match === 'OnePlus') $selectedBrands[] = 5;
        if ($closest_match === 'Nothing') $selectedBrands[] = 6;

        $placeholders = implode(',', array_fill(0, count($selectedBrands), '?'));
        $conditions[] = "t_produit.categorie_fk IN ($placeholders)";
        $params = array_merge($params, $selectedBrands);
    } else {
        $conditions[] = "t_produit.nom LIKE ?";
        $params[] = "%" . $user_input . "%";
        $search = $user_input;
    }
}

// Gestion des filtres de disponibilité
if (isset($_GET['dispo'])) {

    switch ($_GET['dispo']) {
        case 'en_stock':
            $conditions[] = "t_produit.quantite > 5";
            break;

        case 'bientot':
            $conditions[] = "t_produit.quantite = 0";
            break;

        case 'epuise':
            $conditions[] = "t_produit.quantite > 0 AND t_produit.quantite < 5";
            break;
    }
}

// Gestion des filtres de stockage
$selectedStorages = [];

if (isset($_GET['storage']) && is_array($_GET['storage'])) {
    $selectedStorages = $_GET['storage'];
}

if (!empty($selectedStorages)) {
    $placeholders = implode(',', array_fill(0, count($selectedStorages), '?'));
    $conditions[] = "v_storage.valeur IN ($placeholders)";
    $params = array_merge($params, $selectedStorages);
}


// Gestion des filtres de prix
if (isset($_GET['price_min']) && $_GET['price_min'] !== '') {
    $conditions[] = "t_produit.prix >= ?";
    $params[] = (float)$_GET['price_min'];
}

if (isset($_GET['price_max']) && $_GET['price_max'] !== '') {
    $conditions[] = "t_produit.prix <= ?";
    $params[] = (float)$_GET['price_max'];
}

// La fin de la requête SQL avec les conditions et le tri
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$sql .= " GROUP BY t_produit.produit_id";

$sql .= " ORDER BY CAST(MAX(v_storage.valeur) AS UNSIGNED) ASC, t_produit.nom ASC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
