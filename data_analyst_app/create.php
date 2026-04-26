<?php
// create.php — CREATE: show form & process INSERT
require_once 'config/database.php';

$errors = [];
$old    = [];  // repopulate fields on error

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ── Sanitize & validate ───────────────────────────────────────────────
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

    // ── Insert if no errors ───────────────────────────────────────────────
    if (empty($errors)) {
        $pdo  = getConnection();
        $sql  = "INSERT INTO applicants
                    (first_name, last_name, email, phone_number,
                     years_experience, programming_language, highest_degree)
                 VALUES
                    (:first_name, :last_name, :email, :phone_number,
                     :years_experience, :programming_language, :highest_degree)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':first_name'          => $old['first_name'],
            ':last_name'           => $old['last_name'],
            ':email'               => $old['email'],
            ':phone_number'        => $old['phone_number'],
            ':years_experience'    => (int)$old['years_experience'],
            ':programming_language'=> $old['programming_language'],
            ':highest_degree'      => $old['highest_degree'],
        ]);

        header('Location: index.php?msg=created');
        exit;
    }
}

$pageTitle = 'New Application';
include 'includes/header.php';
?>

<div class="container">
    <h1 class="page-title">New <span>Application</span></h1>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <strong>Please fix the following:</strong><br>
            <?= implode('<br>', array_map('htmlspecialchars', $errors)) ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <form method="POST" action="create.php">

            <div class="form-grid">

                <!-- Column 2: First Name -->
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name"
                           value="<?= htmlspecialchars($old['first_name'] ?? '') ?>"
                           placeholder="e.g. Maria" required>
                </div>

                <!-- Column 3: Last Name -->
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name"
                           value="<?= htmlspecialchars($old['last_name'] ?? '') ?>"
                           placeholder="e.g. Santos" required>
                </div>

                <!-- Column 4: Email -->
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email"
                           value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                           placeholder="e.g. maria@email.com" required>
                </div>

                <!-- Column 5: Phone -->
                <div class="form-group">
                    <label for="phone_number">Phone Number</label>
                    <input type="text" id="phone_number" name="phone_number"
                           value="<?= htmlspecialchars($old['phone_number'] ?? '') ?>"
                           placeholder="e.g. 09171234567" required>
                </div>

                <!-- Column 6: Years Experience -->
                <div class="form-group">
                    <label for="years_experience">Years of Experience</label>
                    <input type="number" id="years_experience" name="years_experience"
                           value="<?= htmlspecialchars($old['years_experience'] ?? '0') ?>"
                           min="0" max="50" required>
                </div>

                <!-- Column 7: Programming Language / Tool -->
                <div class="form-group">
                    <label for="programming_language">Primary Language / Tool</label>
                    <input type="text" id="programming_language" name="programming_language"
                           value="<?= htmlspecialchars($old['programming_language'] ?? '') ?>"
                           placeholder="e.g. Python, R, SQL, Power BI" required>
                </div>

                <!-- Column 8: Highest Degree -->
                <div class="form-group full">
                    <label for="highest_degree">Highest Educational Degree</label>
                    <select id="highest_degree" name="highest_degree" required>
                        <option value="" disabled <?= empty($old['highest_degree']) ? 'selected' : '' ?>>— Select degree —</option>
                        <?php
                        $degrees = ['High School Diploma', "Associate's Degree",
                                    "Bachelor's Degree", "Master's Degree", 'Doctorate (Ph.D.)'];
                        foreach ($degrees as $d):
                            $sel = isset($old['highest_degree']) && $old['highest_degree'] === $d ? 'selected' : '';
                        ?>
                            <option value="<?= htmlspecialchars($d) ?>" <?= $sel ?>><?= htmlspecialchars($d) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div><!-- /.form-grid -->

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Submit Application</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </div>

        </form>
    </div>
</div>

