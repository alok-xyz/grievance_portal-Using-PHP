<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db.php';

// Fetch grievances excluding those with status "Resolved"
$result = mysqli_query($conn, "SELECT grievances.id,phone, grievances.complaint, grievances.status, grievances.remark, grievances.created_at, username, users.email FROM grievances JOIN users ON grievances.user_id = users.id WHERE grievances.status != 'Resolved' ORDER BY grievances.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <!-- Navbar -->
    <div class="bg-gray-800 p-4 flex justify-between items-center">
        <h1 class="text-3xl text-white font-bold">Admin Dashboard</h1>
        <a href="admin_logout.php" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Logout</a>
    </div>

    <!-- Main Container -->
    <div class="container mx-auto my-10 p-6">
        <!-- Complaints Table -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">Grievances</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">User</th>
                            <th class="py-2 px-4 border-b">Mobile No</th>
                            <th class="py-2 px-4 border-b">Complaint</th>
                            <th class="py-2 px-4 border-b">Status</th>
                            <th class="py-2 px-4 border-b">Remark</th>
                            <th class="py-2 px-4 border-b">Date</th>
                            <th class="py-2 px-4 border-b">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($row['username']) . "<br><span class='text-gray-500'>" . htmlspecialchars($row['email']) . "</span>"; ?></td>
                                <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($row['phone']); ?></td>
                                <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($row['complaint']); ?></td>
                                <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($row['status']); ?></td>
                                <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($row['remark']); ?></td>
                                <td class="py-2 px-4 border-b"><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                                <td class="py-2 px-4 border-b">
                                    <button onclick="openModal(<?php echo $row['id']; ?>)" class="bg-blue-500 text-white px-4 py-1 rounded hover:bg-blue-600">Update</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Update Modal -->
    <div id="updateModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg w-1/3 p-6">
            <h2 class="text-xl font-bold mb-4">Update Grievance Status</h2>
            <form id="updateForm" method="POST" action="update_grievance.php">
                <input type="hidden" name="grievance_id" id="grievance_id">
                <label class="block text-gray-700 mb-2">Status:</label>
                <select name="status" required class="w-full p-2 border rounded mb-4">
                    <option value="Pending">Pending</option>
                    <option value="UnderProcess">UnderProcess</option>
                    <option value="Resolved">Resolved</option>
                    <option value="Warned">Warned</option>
                </select>
                <label class="block text-gray-700 mb-2">Remark:</label>
                <textarea name="remark" rows="3" class="w-full p-2 border rounded"></textarea>
                <div class="flex justify-end mt-4">
                    <button type="button" onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(grievanceId) {
            $('#grievance_id').val(grievanceId);
            $('#updateModal').removeClass('hidden');
        }
        
        function closeModal() {
            $('#updateModal').addClass('hidden');
        }
    </script>
</body>
</html>
