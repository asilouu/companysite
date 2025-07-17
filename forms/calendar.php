<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); // Redirect to login if not admin
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="calendar.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .event {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .event:hover {
            transform: scale(1.05);
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .modal-content {
            border-radius: 15px;
            border: none;
        }

        .modal-header {
            background: #241e62;
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 20px;
        }

        .modal-body {
            padding: 25px;
        }

        .event-details {
            margin-bottom: 20px;
        }

        .event-details p {
            margin-bottom: 10px;
            font-size: 16px;
        }

        .event-details strong {
            color: #241e62;
            margin-right: 10px;
        }

        .modal-footer {
            border-top: 1px solid #eee;
            padding: 15px 25px;
        }

        .btn-close {
            color: white;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Welcome, <?php echo $_SESSION['username']; ?> (Admin)</h2>
        <hr>
        <div class="row">
            <div class="col-md-3">
                <div class="list-group">
                    <a href="tryadmin.php" class="list-group-item list-group-item-action active">Dashboard</a>
                    <a href="users.php" class="list-group-item list-group-item-action">Manage Users</a>
                    <a href="admin_events.php" class="list-group-item list-group-item-action">Manage Events</a>
                    <a href="admin_teachers.php" class="list-group-item list-group-item-action">Manage Teachers</a>
                    <a href="contact.php" class="list-group-item list-group-item-action">Contact Us</a>
                    <a href="settings.php" class="list-group-item list-group-item-action">Settings</a>
                    <a href="logout.php" class="list-group-item list-group-item-action text-danger">Logout</a>
                </div>
            </div>
            <div class="col-md-9">
                <h4>Dashboard Overview</h4>
                <p>Here you can manage users and settings.</p>
                
                <!-- Calendar Container -->
                <div class="calendar-container">
                    <div class="calendar-header">
                        <button id="prevMonth">←</button>
                        <h2 id="monthYear"></h2>
                        <button id="nextMonth">→</button>
                    </div>

                    <!-- Event Input -->
                    <div class="event-entry">
                        <span id="selectedDate">No date selected</span>
                        <input type="text" id="eventInput" placeholder="Add event..." />
                        <input type="color" id="eventColor" value="#4CAF50" title="Pick a color" />
                        <button id="addEvent">Add</button>
                    </div>

                    <div class="calendar-grid" id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Details Modal -->
    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">Event Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="event-details">
                        <p><strong>Date:</strong> <span id="modalEventDate"></span></p>
                        <p><strong>Event:</strong> <span id="modalEventTitle"></span></p>
                        <p><strong>Color:</strong> <span id="modalEventColor"></span></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" id="deleteEvent">Delete Event</button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="calendar.js"></script>
    <script>
        // Initialize Bootstrap Modal
        const eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
        let currentEventId = null;

        // Function to show event details in modal
        function showEventDetails(event) {
            const modalEventDate = document.getElementById('modalEventDate');
            const modalEventTitle = document.getElementById('modalEventTitle');
            const modalEventColor = document.getElementById('modalEventColor');
            
            modalEventDate.textContent = event.date;
            modalEventTitle.textContent = event.title;
            modalEventColor.textContent = event.color;
            currentEventId = event.id;
            
            eventModal.show();
        }

        // Add click event listener to delete button
        document.getElementById('deleteEvent').addEventListener('click', function() {
            if (currentEventId) {
                // Call your delete event function here
                deleteEvent(currentEventId);
                eventModal.hide();
            }
        });
    </script>
</body>
</html>