<?php
// delete.php — DELETE: remove a record then redirect
require_once 'config/database.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: index.php');
    exit;
}

$pdo  = getConnection();
$stmt = $pdo->prepare("DELETE FROM applicants WHERE applicant_id = :id");
$stmt->execute([':id' => $id]);

header('Location: index.php?msg=deleted');
exit;
