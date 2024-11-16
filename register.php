<?php
session_start(); 

$studentsFile = 'students.json';
$students = [];

if (file_exists($studentsFile)) {
    $students = json_decode(file_get_contents($studentsFile), true);
}

$studentId = $firstName = $lastName = '';
$studentIdErr = $firstNameErr = $lastNameErr = '';
$errorDetails = [];
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $studentId = trim($_POST['studentId']);
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);

    if (empty($studentId)) {
        $studentIdErr = 'Student ID is required.';
        $errorDetails[] = $studentIdErr;
    } elseif (!is_numeric($studentId)) {
        $studentIdErr = 'Student ID must be a numeric value.';
        $errorDetails[] = $studentIdErr;
    } elseif (array_key_exists($studentId, $students)) {
        $studentIdErr = 'Duplicate Student ID.';
        $errorDetails[] = $studentIdErr;
    }

    if (empty($firstName)) {
        $firstNameErr = 'First Name is required.';
        $errorDetails[] = $firstNameErr;
    }

    if (empty($lastName)) {
        $lastNameErr = 'Last Name is required.';
        $errorDetails[] = $lastNameErr;
    }

    if (empty($errorDetails)) {
        $students[$studentId] = [
            'firstName' => $firstName,
            'lastName' => $lastName
        ];

        file_put_contents($studentsFile, json_encode($students));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register a New Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h3 class="card-title">Register a New Student</h3><br>

        <div class="container">
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../subject/dashboard.php">Dashboard</a></li> 
                <li class="breadcrumb-item active" aria-current="page">Registration</li>
            </ol>
        </nav>
    </div>
</nav>

    </div>
</nav>


        </div><br>
        <?php if (!empty($errorDetails)): ?>
            <div id="error-box" class="alert alert-danger" role="alert">
                <strong>System Errors:</strong>
                <ul>
                    <?php foreach ($errorDetails as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($successMessage): ?>
            <div class="alert alert-success" role="alert"><?= $successMessage; ?></div>
        <?php endif; ?>
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="studentId" class="form-label">Student ID</label>
                        <input type="text" class="form-control" id="studentId" name="studentId" value="<?php echo htmlspecialchars($studentId); ?>" placeholder="Enter Student ID">
                    </div>
                    <div class="mb-3">
                        <label for="firstName" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo htmlspecialchars($firstName); ?>" placeholder="Enter First Name">
                    </div>
                    <div class="mb-3">
                        <label for="lastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo htmlspecialchars($lastName); ?>" placeholder="Enter Last Name">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Register Student</button>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h3 class="card-title">Student List</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
            
                        foreach ($students as $id => $student) {
                            echo "<tr>
                                    <td>{$id}</td>
                                    <td>{$student['firstName']}</td>
                                    <td>{$student['lastName']}</td>
                                    <td>
                                        <a href='edit.php?id={$id}' class='btn btn-primary btn-sm'>Edit</a>
                                        <a href='delete.php?id={$id}' class='btn btn-danger btn-sm'>Delete</a>
                                        <button class='btn btn-warning btn-sm' disabled>Attach Subject</button> <!-- Empty button, no link, and disabled -->
                                    </td>
                                  </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>