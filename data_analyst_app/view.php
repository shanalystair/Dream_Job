<?php
// view.php — READ: show a single applicant's details
require_once 'config/database.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: index.php');
    exit;
}

$pdo  = getConnection();
$stmt = $pdo->prepare("SELECT * FROM applicants WHERE applicant_id = :id");
$stmt->execute([':id' => $id]);
$applicant = $stmt->fetch();

if (!$applicant) {
    header('Location: index.php');
    exit;
}

$pageTitle = 'View Applicant';
include 'includes/header.php';
?>

<div class="container">
    <h1 class="page-title">Applicant <span>Details</span></h1>

    <div class="card">
        <div class="detail-grid">
            <div class="detail-item">
                <label>Applicant ID</label>
                <p>#<?= $applicant['applicant_id'] ?></p>
            </div>
            <div class="detail-item">
                <label>Full Name</label>
                <p><?= htmlspecialchars($applicant['first_name'] . ' ' . $applicant['last_name']) ?></p>
            </div>
            <div class="detail-item">
                <label>Email</label>
                <p><?= htmlspecialchars($applicant['email']) ?></p>
            </div>
            <div class="detail-item">
                <label>Phone Number</label>
                <p><?= htmlspecialchars($applicant['phone_number']) ?></p>
            </div>
            <div class="detail-item">
                <label>Years of Experience</label>
                <p><?= $applicant['years_experience'] ?> year<?= $applicant['years_experience'] !== 1 ? 's' : '' ?></p>
            </div>
            <div class="detail-item">
                <label>Primary Language / Tool</label>
                <p><span class="badge badge-lang"><?= htmlspecialchars($applicant['programming_language']) ?></span></p>
            </div>
            <div class="detail-item">
                <label>Highest Degree</label>
                <p><span class="badge badge-degree"><?= htmlspecialchars($applicant['highest_degree']) ?></span></p>
            </div>
            <div class="detail-item">
                <label>Date Applied</label>
                <p><?= date('F j, Y \a\t g:i A', strtotime($applicant['date_added'])) ?></p>
            </div>
        </div>

        <div class="form-actions" style="margin-top:2rem">
            <a href="update.php?id=<?= $applicant['applicant_id'] ?>" class="btn btn-edit">Edit</a>
            <a href="delete.php?id=<?= $applicant['applicant_id'] ?>"
               class="btn btn-delete"
               onclick="return confirm('Delete this application? This cannot be undone.')">Delete</a>
            <a href="index.php" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
</div>

