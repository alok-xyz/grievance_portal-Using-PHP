<?php
session_start();

// Include database connection
include 'db.php';

// Function to check for notifications (modify as per your requirements)
function checkNotifications($conn) {
    // Example static notifications; implement your logic here
    return 3; // Assuming there are 3 notifications
}

// Sample data for the graph (replace with real data from your database)
$resolvedGrievances = [20, 30, 40, 25, 50]; // Example data
$months = ['Jan', 'Feb', 'Mar', 'Apr', 'May'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grievance Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Montserrat:wght@500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8fafc;
            overflow-x: hidden;
        }
        .notification-icon {
            position: relative;
        }
        .notification-count {
            position: absolute;
            top: -5px;
            right: -10px;
            background-color: #e53e3e; /* Red color for notifications */
            color: white;
            border-radius: 50%;
            padding: 0.1rem 0.4rem;
            font-size: 0.75rem;
        }
        .hero-section {
            background-image: url('https://source.unsplash.com/1600x900/?grievance,complaint');
            background-size: cover;
            background-position: center;
            height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .fade-in {
            animation: fadeIn 1s ease-in-out forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }
        .leader-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 4px solid #007bff; /* Adjust border color */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .chart-container {
            position: relative;
            width: 100%;
            height: 400px; /* Set a fixed height for the chart */
        }
        footer {
            background-color: #2d3748;
            color: white;
        }
        .leader-image {
    width: 120px; /* Adjusted size for better visibility */
    height: 120px; /* Adjusted size for better visibility */
    border-radius: 50%;
    border: 4px solid #007bff; /* Adjust border color */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s ease;
}

.leader-card:hover .leader-image {
    transform: scale(1.1); /* Slightly enlarges image on hover */
}
/* Loading Animation Styles */
#loading {
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: white; /* Change as needed */
}

.loader {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            animation: spin 1.2s linear infinite;
            position: relative;
            overflow: hidden;
        }

        .loader::before,
        .loader::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 50%;
            animation: bounce 1.2s infinite;
        }

        .loader::before {
            background: radial-gradient(circle at 50% 50%, #FF5733, #FFBD33, #FF33F6, #33FF57, #33F6FF);
            animation-delay: 0s;
        }

        .loader::after {
            background: radial-gradient(circle at 50% 50%, #33FFBD, #3357FF, #F633FF, #FF5733, #FF33F6);
            animation-delay: 0.6s;
        }

        /* Keyframes for spinning and bouncing */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes bounce {
            0%, 100% { transform: scale(0.5); }
            50% { transform: scale(1); }
        }

        .notification-top-bar {
  position: fixed;
  top: 0;
  left: 0;
  height: 30px;
  line-height: 30px;
  width: 100%;
  background: #e61636;
  text-align: center;
  color: #FFFFFF;
  font-family: sans-serif;
  font-weight: lighter;
  font-size: 14px;
}
.notification-top-bar p {
  padding: 0;
  margin: 0;
}
.notification-top-bar p a {
  padding: 5px 10px;
  border-radius: 3px;
  background: #FFF;
  color: #1ABC9C;
  font-weight: bold;
  text-decoration: none;
}

    </style>
</head>
<body>
<div id="loading" class="fixed inset-0 flex items-center justify-center bg-white z-50">
    <div class="loader"></div>
</div>
<div class="notification-top-bar">
  <p>Open This Site only PC! Not Optimized for Mobile</p>
</div>

    <header class="bg-blue-800 text-white py-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-3xl font-bold">Grievance Portal 2024</h1>
            <div class="notification-icon">
                <a href="#" class="text-white" title="View Notifications">
                    <i class="fas fa-bell text-2xl"></i>
                    <span class="notification-count"><?php echo htmlspecialchars(checkNotifications($conn)); ?></span>
                </a>
            </div>
        </div>
    </header>

    <section class="hero-section fade-in text-white">
        <div class="bg-black bg-opacity-60 p-8 rounded-lg text-center">
            <h2 class="text-5xl font-bold mb-4">Your Voice Matters</h2>
            <p class="mb-6 text-lg">Welcome to the Grievance Portal. Submit your grievances and track their status easily.</p>
            <a href="login.php" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg mr-3 transition duration-300 transform hover:scale-105">Login</a>
            <a href="register.php" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 transform hover:scale-105">Sign Up</a>
        </div>
    </section>

    <main class="container mx-auto py-10">
        <h2 class="text-4xl font-bold text-center mb-10">How It Works</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="card bg-white p-6 rounded-lg shadow-lg">
                <i class="fas fa-edit text-blue-600 text-5xl mb-4"></i>
                <h3 class="font-semibold text-xl mb-2">Submit a Grievance</h3>
                <p>Fill out a simple form to submit your grievance.</p>
            </div>
            <div class="card bg-white p-6 rounded-lg shadow-lg">
                <i class="fas fa-spinner text-yellow-600 text-5xl mb-4"></i>
                <h3 class="font-semibold text-xl mb-2">Under Review</h3>
                <p>Your grievance will be reviewed by our team.</p>
            </div>
            <div class="card bg-white p-6 rounded-lg shadow-lg">
                <i class="fas fa-check-circle text-green-600 text-5xl mb-4"></i>
                <h3 class="font-semibold text-xl mb-2">Resolution</h3>
                <p>Receive updates and notifications about your grievance.</p>
            </div>
        </div>

        <section class="mt-12">
            <h2 class="text-4xl font-bold text-center mb-6">Why Choose Us?</h2>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <ul class="list-disc pl-8 text-lg">
                    <li>Transparent and accountable process</li>
                    <li>Responsive customer service</li>
                    <li>Timely updates on your grievances</li>
                    <li>Legal protection against frivolous complaints</li>
                </ul>
            </div>
        </section>

        <section class="mt-12 text-center">
            <h2 class="text-4xl font-bold mb-4">Total Grievances Resolved</h2>
            <div class="chart-container mx-auto">
                <canvas id="resolvedGrievancesChart"></canvas>
            </div>
        </section>

        <section class="mt-12 text-center">
    <h2 class="text-4xl font-bold mb-4">Meet Our Leaders</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mx-auto">
        <div class="leader-card bg-white rounded-lg shadow-lg p-6 flex flex-col items-center">
            <img src="img/minis.jpg" alt="Minister" class="leader-image mb-4">
            <h3 class="mt-2 font-semibold text-xl">Hon'ble Technnology Minister</h3>
            <h4 class="mt-1 font-serif text-lg">Mr. Rahul Bose</h4>
            <p class="text-sm text-gray-700">Committed to ensuring that every grievance is addressed effectively and efficiently.</p>
        </div>
        <div class="leader-card bg-white rounded-lg shadow-lg p-6 flex flex-col items-center">
            <img src="img/sec.jpg" alt="Chief Secretary" class="leader-image mb-4">
            <h3 class="mt-2 font-semibold text-xl">Chief Secretary</h3>
            <h4 class="mt-1 font-serif text-lg">Mr. Monojit Upadhayay</h4>
            <p class="text-sm text-gray-700">Leading our team with integrity and dedication to public service.</p>
        </div>
        <!-- You can add more leader cards here -->
        <div class="leader-card bg-white rounded-lg shadow-lg p-6 flex flex-col items-center">
            <img src="img/oic.jpg" alt="Additional Leader" class="leader-image mb-4">
            <h3 class="mt-2 font-semibold text-xl">Officier In-Charge</h3>
            <h4 class="mt-1 font-serif text-lg">Mr. Alok Guha Roy</h4>
            <p class="text-sm text-gray-700">Focused on improving the grievance resolution process.</p>
        </div>
        
    </div>
</section>

        <section class="mt-12 text-center">
            <h2 class="text-4xl font-bold mb-4">Latest Announcements</h2>
            <div class="bg-yellow-100 border-l-4 border-yellow-600 text-yellow-800 p-4 rounded-lg shadow-md">
                <div class="flex items-center">
                    <i class="fas fa-bullhorn text-2xl mr-3"></i>
                    <div>
                        <p class="font-bold">Important Announcement:</p>
                        <p>Unnecessary and meaningless grievances or applications will not be tolerated! Legal action will be taken against offenders.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-gray-800 text-white py-4">
        <div class="container mx-auto flex flex-col md:flex-row justify-between items-center">
            <p>&copy; <?php echo date("Y"); ?> Grievance Portal. All rights reserved.</p>
            <div class="mt-2 md:mt-0">
                <h3 class="font-semibold">Contact Us</h3>
                <p>Email: info@grievanceportal.com</p>
                <p>Phone: +91 90649520**</p>
            </div>
        </div>
    </footer>

    <script>
        const ctx = document.getElementById('resolvedGrievancesChart').getContext('2d');
        const resolvedGrievancesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($months); ?>,
                datasets: [{
                    label: 'Resolved Grievances',
                    data: <?php echo json_encode($resolvedGrievances); ?>,
                    borderColor: 'rgba(29, 78, 216, 1)',
                    backgroundColor: 'rgba(29, 78, 216, 0.2)',
                    borderWidth: 2,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Grievances'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            color: 'black'
                        }
                    }
                }
            }
        });
    </script>
   <script>
    window.addEventListener('load', function() {
        const loader = document.getElementById('loading');
        setTimeout(function() {
            loader.style.display = 'none'; // Hide the loader after 4 seconds
        }, 2000); // 4000 milliseconds = 4 seconds
    });
</script>

</body>
</html>
