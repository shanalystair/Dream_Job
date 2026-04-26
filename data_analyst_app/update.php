<?php
// update.php — UPDATE: load existing record, show form, process UPDATE
require_once 'config/database.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)
   ?? filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: index.php');
    exit;
}

$pdo  = getConnection();

// ── Load existing record ──────────────────────────────────────────────────
$stmt = $pdo->prepare("SELECT * FROM applicants WHERE applicant_id = :id");
$stmt->execute([':id' => $id]);
$applicant = $stmt->fetch();

if (!$applicant) {
    header('Location: index.php');
    exit;
}

$errors = [];
$old    = $applicant;   // prefill with DB data; POST overwrites on submission

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $fields = ['first_name', 'last_name', 'email', 'phone_number',
               'years_experience', 'programming_language', 'highest_degree'];

    foreach ($fields as $f) {
        $old[$f] = trim($_POST[$f] ?? '');
    }

    $allowedDegrees = [
        'High School Diploma', "Associate's Degree", "Bachelor's Degree",
        "Master's Degree", 'Doctorate (Ph.D.)'
    ];

    if (empty($old['first_name']))            $errors[] = 'First name is required.';
    if (empty($old['last_name']))             $errors[] = 'Last name is required.';
    if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email is required.';
    if (empty($old['phone_number']))          $errors[] = 'Phone number is required.';
    if (!is_numeric($old['years_experience']) || $old['years_experience'] < 0)
                                              $errors[] = 'Years of experience must be 0 or more.';
    if (empty($old['programming_language']))  $errors[] = 'Primary language / tool is required.';
    if (!in_array($old['highest_degree'], $allowedDegrees, true))
                                              $errors[] = 'Please select a valid degree.';

    if (empty($errors)) {
        $sql = "UPDATE applicants SET
                    first_name           = :first_name,
                    last_name            = :last_name,
                    email                = :email,
                    phone_number         = :phone_number,
                    years_experience     = :years_experience,
                    programming_language = :programming_language,
                    highest_degree       = :highest_degree
                WHERE applicant_id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':first_name'          => $old['first_name'],
            ':last_name'           => $old['last_name'],
            ':email'               => $old['email'],
            ':phone_number'        => $old['phone_number'],
            ':years_experience'    => (int)$old['years_experience'],
            ':programming_language'=> $old['programming_language'],
            ':highest_degree'      => $old['highest_degree'],
            ':id'                  => $id,
        ]);

        header('Location: index.php?msg=updated');
        exit;
    }
}

$pageTitle = 'Edit Application';
include 'includes/header.php';
?>

<div class="container">
    <h1 class="page-title">Edit <span>Application</span></h1>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <strong>Please fix the following:</strong><br>
            <?= implode('<br>', array_map('htmlspecialchars', $errors)) ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <form method="POST" action="update.php">
            <input type="hidden" name="id" value="<?= $id ?>">

            <div class="form-grid">

                <!-- Column 2: First Name -->
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name"
                           value="<?= htmlspecialchars($old['first_name']) ?>" required>
                </div>

                <!-- Column 3: Last Name -->
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name"
                           value="<?= htmlspecialchars($old['last_name']) ?>" required>
                </div>

                <!-- Column 4: Email -->
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email"
                           value="<?= htmlspecialchars($old['email']) ?>" required>
                </div>

                <!-- Column 5: Phone -->
                <div class="form-group">
                    <label for="phone_number">Phone Number</label>
                    <input type="text" id="phone_number" name="phone_number"
                           value="<?= htmlspecialchars($old['phone_number']) ?>" required>
                </div>

                <!-- Column 6: Years Experience -->
                <div class="form-group">
                    <label for="years_experience">Years of Experience</label>
                    <input type="number" id="years_experience" name="years_experience"
                           value="<?= (int)$old['years_experience'] ?>"
                           min="0" max="50" required>
                </div>

                <!-- Column 7: Programming Language / Tool -->
                <div class="form-group">
                    <label for="programming_language">Primary Language / Tool</label>
                    <input type="text" id="programming_language" name="programming_language"
                           value="<?= htmlspecialchars($old['programming_language']) ?>" required>
                </div>

                <!-- Column 8: Highest Degree -->
                <div class="form-group full">
                    <label for="highest_degree">Highest Educational Degree</label>
                    <select id="highest_degree" name="highest_degree" required>
                        <?php
                        $degrees = ['High School Diploma', "Associate's Degree",
                                    "Bachelor's Degree", "Master's Degree", 'Doctorate (Ph.D.)'];
                        foreach ($degrees as $d):
                            $sel = $old['highest_degree'] === $d ? 'selected' : '';
                        ?>
                            <option value="<?= htmlspecialchars($d) ?>" <?= $sel ?>><?= htmlspecialchars($d) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div><!-- /.form-grid -->

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="view.php?id=<?= $id ?>" class="btn btn-secondary">Cancel</a>
            </div>

        </form>
    </div>
</div>

