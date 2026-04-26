<?php
// includes/header.php
// Call: include 'includes/header.php'; — set $pageTitle before including.
if (!isset($pageTitle)) $pageTitle = 'Data Analyst Applications';
$root = dirname(__DIR__); // absolute path to project root (not used for URLs)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> — DataHire</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header class="site-header">
    <div class="logo">Data<span>Hire</span></div>
    <p style="color:var(--muted);font-size:.85rem;">Data Analyst Application Portal</p>
    <nav>
        <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>">All Applicants</a>
        <a href="create.php" class="<?= basename($_SERVER['PHP_SELF']) === 'create.php' ? 'active' : '' ?>">New Application</a>
    </nav>
</header>
