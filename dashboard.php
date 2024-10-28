<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

// Fetch user grievances
$user_id = $_SESSION['user_id'];
$result = mysqli_query($conn, "SELECT id, complaint, status, remark, created_at FROM grievances WHERE user_id = $user_id ORDER BY created_at DESC");

// Check for unresolved grievances
$has_unresolved = false;
while ($row = mysqli_fetch_assoc($result)) {
    if ($row['status'] !== 'Resolved') {
        $has_unresolved = true;
        break;
    }
}

// Reset result for later use
mysqli_data_seek($result, 0);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$has_unresolved) {
    $complaint = $_POST['complaint'];
    $stmt = $conn->prepare("INSERT INTO grievances (user_id, complaint, status, created_at) VALUES (?, ?, 'Pending', NOW())");
    $stmt->bind_param("is", $user_id, $complaint);

    if ($stmt->execute()) {
        header("Location: dashboard.php?message=Complaint submitted successfully");
        exit();
    } else {
        $error_message = "Error submitting complaint: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch user information for the welcome message
$user_query = mysqli_query($conn, "SELECT username FROM users WHERE id = $user_id");

$user = mysqli_fetch_assoc($user_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Montserrat:wght@500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@1.9.6/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <style>
        
        body {
            font-family: 'Roboto', sans-serif;
            animation: fadeIn 0.5s ease-in;
        }
        .header {
            font-family: 'Montserrat', sans-serif;
        }
        .status {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            font-weight: bold;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        .hover-effect:hover {
            transform: translateY(-3px);
            transition: transform 0.3s ease;
        }
        /* Blinking effect for the welcome message */
        @keyframes blink {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }
        .blinking {
            animation: blink 1s infinite;
        }
    </style>
</head>
<body class="bg-gray-50 leading-normal tracking-normal flex">
    <!-- Sidebar -->
    <div class="bg-green-600 w-64 min-h-screen p-5 flex flex-col shadow-md">
        <h1 class="text-3xl text-white font-bold mb-4">Grievance Portal 2024</h1>
        <p class="text-white mb-6 blinking">Welcome, <?php echo htmlspecialchars($user['username']); ?>!</p>
       
        <nav>
            <ul class="space-y-2">
                <li><a href="dashboard.php" class="btn btn-light text-black hover:bg-green-500 px-3 py-2 rounded transition duration-200">Home</a></li>
                <li><a href="logout.php" class="btn btn-danger text-red-600 hover:bg-red-500 px-3 py-2 rounded transition duration-200">Logout</a></li>
            </ul>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-10">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Announcement Card -->
            <div class="bg-yellow-200 p-4 rounded-lg shadow-lg flex items-center">
                <i class="fas fa-bullhorn text-2xl text-yellow-600 mr-2"></i> <!-- Announcement Icon -->
                <div>
                    <h2 class="header text-lg font-bold mb-2">Announcement</h2>
                    <p class="text-red-600">Unnecessary & Meaningless Grievance or Application is not tolerated! We take legal action on you!</p>
                </div>
            </div>

            <!-- Grievance Submission Card -->
            <div class="bg-white p-6 rounded-lg shadow-lg transition transform hover:scale-105 hover-effect">
                <h2 class="header text-2xl font-bold mb-4 text-gray-800">Submit a Grievance</h2>
                <?php if ($has_unresolved): ?>
                    <p class="text-red-500 mb-4">You have an unresolved grievance. Please wait until it is resolved before submitting a new one.</p>
                <?php else: ?>
                    <?php if (isset($error_message)): ?>
                        <p class="text-red-500 mb-4"><?php echo htmlspecialchars($error_message); ?></p>
                    <?php endif; ?>
                    <form action="" method="POST">
                        <div class="mb-4">
                            <label for="complaint" class="block text-gray-700">Your Complaint</label>
                            <textarea name="complaint" id="complaint" rows="4" required class="w-full p-2 border border-gray-300 rounded mt-1 focus:ring-2 focus:ring-green-500"></textarea>
                        </div>
                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition duration-200">Submit</button>
                    </form>
                <?php endif; ?>
            </div>

            <!-- Grievances Table Card -->
            <div class="bg-white p-6 rounded-lg shadow-lg transition transform hover:scale-105 hover-effect">
                <h2 class="header text-2xl font-bold mb-4 text-gray-800">My Grievances</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="py-2 px-4 border-b">Complaint</th>
                                <th class="py-2 px-4 border-b">Status</th>
                                <th class="py-2 px-4 border-b">Remark</th>
                                <th class="py-2 px-4 border-b">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr class="hover:bg-gray-100 transition duration-200">
                                    <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($row['complaint']); ?></td>
                                    <td class="py-2 px-4 border-b">
                                        <span class="status <?php echo getStatusClass($row['status']); ?>">
                                            <?php echo htmlspecialchars($row['status']); ?>
                                        </span>
                                    </td>
                                    <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($row['remark']); ?></td>
                                    <td class="py-2 px-4 border-b"><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php
    // Function to get the appropriate CSS class based on status
    function getStatusClass($status) {
        switch ($status) {
            case 'Pending':
                return 'text-yellow-600 bg-yellow-100'; // Yellow background
            case 'UnderProcess':
                return 'text-blue-600 bg-blue-200'; // Blue background
            case 'Resolved':
                return 'text-green-600 bg-green-100'; // Green background
            case 'Warned':
                    return 'text-red-600 bg-red-100';
            default:
                return 'text-gray-500 bg-gray-100'; // Default gray background
        }
    }
    ?>
</body>
</html>
