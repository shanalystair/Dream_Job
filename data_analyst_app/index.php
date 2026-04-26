<?php
// index.php  — READ: list all applicants
require_once 'config/database.php';

$pdo = getConnection();

// ── Fetch all applicants (ORDER BY newest first) ──────────────────────────
$stmt = $pdo->query("SELECT * FROM applicants ORDER BY applicant_id ASC");
$applicants = $stmt->fetchAll();

// ── Flash messages ────────────────────────────────────────────────────────
$flash = '';
if (isset($_GET['msg'])) {
    $messages = [
        'created' => ['success', 'Application submitted successfully!'],
        'updated' => ['success', 'Application updated successfully!'],
        'deleted' => ['success', 'Application deleted.'],
    ];
    if (isset($messages[$_GET['msg']])) {
        [$type, $text] = $messages[$_GET['msg']];
        $flash = "<div class='alert alert-{$type}'>{$text}</div>";
    }
}

$pageTitle = 'All Applicants';
include 'includes/header.php';
?>

<div class="container">
    <h1 class="page-title">Applicants <span>List</span></h1>

    <?= $flash ?>

    <div class="card">
        <?php if (empty($applicants)): ?>
            <div class="empty">
                <p>No applications yet. <a href="create.php" style="color:var(--accent)">Submit the first one!</a></p>
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Exp (yrs)</th>
                            <th>Language</th>
                            <th>Degree</th>
                            <th>Date Added</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applicants as $row): ?>
                        <tr>
                            <td><?= $row['applicant_id'] ?></td>
                            <td><strong><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></strong></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['phone_number']) ?></td>
                            <td style="text-align:center"><?= $row['years_experience'] ?></td>
                            <td><span class="badge badge-lang"><?= htmlspecialchars($row['programming_language']) ?></span></td>
                            <td><span class="badge badge-degree"><?= htmlspecialchars($row['highest_degree']) ?></span></td>
                            <td style="color:var(--muted);font-size:.82rem"><?= date('M j, Y', strtotime($row['date_added'])) ?></td>
                            <td style="display:flex;gap:.5rem;flex-wrap:wrap">
                                <a href="view.php?id=<?= $row['applicant_id'] ?>" class="btn btn-secondary" style="padding:.35rem .75rem;font-size:.8rem">View</a>
                                <a href="update.php?id=<?= $row['applicant_id'] ?>" class="btn btn-edit" style="padding:.35rem .75rem;font-size:.8rem">Edit</a>
                                <a href="delete.php?id=<?= $row['applicant_id'] ?>"
                                   class="btn btn-delete"
                                   style="padding:.35rem .75rem;font-size:.8rem"
                                   onclick="return confirm('Delete this application? This cannot be undone.')">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>


