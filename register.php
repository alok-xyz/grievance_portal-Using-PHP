<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];

    // Validate input
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } else {
        // Insert user into the database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, phone) VALUES (?, ?, ?, ?)");
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param("ssss", $username, $email, $hashed_password, $phone);

        if ($stmt->execute()) {
            header("Location: login.php?message=Registration successful. Please log in.");
            exit();
        } else {
            $error_message = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 flex items-center justify-center h-screen">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-2xl font-bold mb-4">Grievance Register 2024</h2>
        <?php if (isset($error_message)): ?>
            <p class="text-red-500 mb-4"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="mb-4">
                <label for="username" class="block text-gray-700">Full Name</label>
                <input type="text" name="username" required class="w-full p-2 border border-gray-300 rounded mt-1 focus:ring-2 focus:ring-green-500">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email</label>
                <input type="email" name="email" required class="w-full p-2 border border-gray-300 rounded mt-1 focus:ring-2 focus:ring-green-500">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-gray-700">Phone Number</label>
                <input type="text" name="phone" required class="w-full p-2 border border-gray-300 rounded mt-1 focus:ring-2 focus:ring-green-500">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700">Password</label>
                <input type="password" name="password" required class="w-full p-2 border border-gray-300 rounded mt-1 focus:ring-2 focus:ring-green-500">
            </div>
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition duration-200">Register</button>
        </form>
        <p class="mt-3 text-center">Alredy have an account? <a href="login.php">Login </a></p>
        <hr>
        <p class="mt-3 text-center"> <a href="index.php">Home</a></p>
    </div>
</body>
</html>
