<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $valid_username = "slm";
    $valid_password = "123";

    $username = $_POST["username"] ?? '';
    $password = $_POST["password"] ?? '';

    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['username'] = $username;
        header("Location: report.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>User Login - Clinic Sok Leap Metrey</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-600 to-blue-800 min-h-screen flex items-center justify-center p-4">
<div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md animate-fade-in">
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-blue-600">Clinic Login</h2>
    </div>

    <?php if (!empty($error)): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
            <p><?= htmlspecialchars($error) ?></p>
        </div>
    <?php endif; ?>

    <form method="POST" action="" class="space-y-6">
        <div>
            <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
            <input
                    type="text"
                    id="username"
                    name="username"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                    placeholder="Enter your username"
                    required
            />
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input
                    type="password"
                    id="password"
                    name="password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                    placeholder="Enter your password"
                    required
            />
        </div>

        <button
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200"
        >
            Login
        </button>
    </form>

    <div class="text-center mt-6 text-sm text-gray-600">
        <a href="#" class="text-blue-600 hover:text-blue-800 hover:underline">Forgot your password?</a>
    </div>
</div>

<style>
    .animate-fade-in {
        animation: fadeIn 0.6s ease;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
</body>
</html>