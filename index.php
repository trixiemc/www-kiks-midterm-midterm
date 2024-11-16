<?php
session_start(); 

if (isset($_SESSION['email'])) {
    header('Location: dashboard.php');
    exit;
}

$users = [
    'kiks10@email.com' => 'kiks5', 
    'kiks9@email.com' => 'kiks4', 
    'kiks8@email.com' => 'kiks3', 
    'kiks7@email.com' => 'kiks2', 
    'kiks6@email.com' => 'kiks1'  
];

$email = $password = '';
$emailErr = $passwordErr = '';
$errorDetails = [];
$loginError = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']); 
    $password = $_POST['password'];

    if (empty($email)) {
        $emailErr = 'Email is required.';
        $errorDetails[] = $emailErr; 
    } else {
      
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = 'Invalid email format.';
            $errorDetails[] = $emailErr;
        }
    }

    if (empty($password)) {
        $passwordErr = 'Password is required.';
        $errorDetails[] = $passwordErr; 
    }

    if (empty($emailErr) && empty($passwordErr)) {
        $normalizedEmail = strtolower($email);

        if (array_key_exists($normalizedEmail, $users)) {
            if ($users[$normalizedEmail] !== $password) {
                $errorDetails[] = 'Password is incorrect.';
            } else {
               
                $_SESSION['email'] = $email; 
                header('Location: dashboard.php'); 
                exit; 
            }
        } else {
            $errorDetails[] = 'Email not found.';
        }
    }

    if (!empty($errorDetails)) {
        $loginError = 'System Errors:';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="w-100" style="max-width: 400px;">
            
            <?php if (!empty($loginError)): ?>
                <div id="error-box" class="alert alert-danger" role="alert">
                    <strong><?php echo $loginError; ?></strong>
                    <ul>
                        <?php foreach ($errorDetails as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title text-center mb-4">Login</h3>
                    <form method="POST" id="login-form">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="Enter email">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>