<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']); 

    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['admin'] = $username;
        header('Location: dashboard.php');
    } else {
        $error = "Invalid Username or Password!";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Snack Inn Admin Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa, #d6e0f0);
            font-family: 'Arial', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .card {
            width: 400px;
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .btn-primary {
            background: linear-gradient(45deg, #4e73df, #1cc88a);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, #1cc88a, #4e73df);
        }

        .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 5px rgba(78, 115, 223, 0.5);
        }

        .text-dark {
            font-weight: bold;
        }

        .text-danger {
            font-size: 0.9rem;
        }

        .logo {
            display: block;
            margin: 0 auto 15px auto;
            width: 80px;
        }
    </style>
</head>
<body>
    <div class="card p-4 shadow-sm">
        <img src="icons/images.jpg" alt="Snack Inn Logo" class="logo">
        <h3 class="text-center text-dark">Snack Inn Admin Login</h3>
        <p class="text-muted text-center">Manage your Snack Inn Inventory</p>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username" class="form-control" placeholder="Enter Username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
        <?php if (isset($error)) { echo "<p class='text-danger mt-3 text-center'>$error</p>"; } ?>
    </div>
</body>
</html>
