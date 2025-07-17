<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); // Redirect to login if not admin
    exit();
}

require_once "db_connect.php";

// Get counts for dashboard
$counts = [
    'students' => 0,
    'teachers' => 0,
    'events' => 0,
    'courses' => 0
];

// Get student count
$result = $conn->query("SELECT COUNT(*) as count FROM students");
if ($result) {
    $counts['students'] = $result->fetch_assoc()['count'];
}

// Get teacher count
$result = $conn->query("SELECT COUNT(*) as count FROM teachers");
if ($result) {
    $counts['teachers'] = $result->fetch_assoc()['count'];
}

// Get event count
$result = $conn->query("SELECT COUNT(*) as count FROM events");
if ($result) {
    $counts['events'] = $result->fetch_assoc()['count'];
}

// Get course count
$result = $conn->query("SELECT COUNT(*) as count FROM courses");
if ($result) {
    $counts['courses'] = $result->fetch_assoc()['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="../assets/pics/felta-logo (2).png" rel="icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="calendar.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .dashboard-container {
            padding: 30px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .welcome-header {
            background: linear-gradient(135deg, #241e62, #1a1647);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(36, 30, 98, 0.15);
            margin-bottom: 30px;
            color: white;
        }

        .welcome-header h2 {
            font-size: 28px;
            font-weight: 600;
            margin: 0;
            letter-spacing: 0.5px;
        }

        .welcome-header p {
            margin: 10px 0 0;
            opacity: 0.9;
            font-size: 16px;
        }

        .sidebar {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(36, 30, 98, 0.08);
            height: fit-content;
        }

        .list-group-item {
            border: none;
            padding: 15px 20px;
            margin-bottom: 8px;
            border-radius: 12px !important;
            color: #555;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .list-group-item:last-child {
            margin-bottom: 0;
        }

        .list-group-item:hover {
            background: #f8f9fa;
            color: #241e62;
            transform: translateX(5px);
        }

        .list-group-item.active {
            background: linear-gradient(135deg, #241e62, #1a1647);
            color: white;
        }

        .list-group-item i {
            margin-right: 12px;
            font-size: 20px;
        }

        .main-content {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(36, 30, 98, 0.08);
        }

        .calendar-container {
            background: white;
            border-radius: 20px;
            padding: 30px;
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .calendar-header button {
            background: linear-gradient(135deg, #241e62, #1a1647);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .calendar-header button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(36, 30, 98, 0.2);
        }

        #monthYear {
            font-size: 24px;
            font-weight: 600;
            color: #241e62;
            letter-spacing: 0.5px;
        }

        .weekday-row {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
            margin-bottom: 15px;
        }

        .weekday {
            text-align: center;
            font-weight: 600;
            color: #241e62;
            padding: 15px;
            font-size: 15px;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
        }

        .calendar-day {
            aspect-ratio: 1;
            padding: 10px;
            border: 2px solid #f0f0f0;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            background: white;
            border-radius: 15px;
            overflow: hidden;
        }

        .calendar-day:hover {
            background: #f8f9fa;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(36, 30, 98, 0.08);
            border-color: #241e62;
        }

        .calendar-day.selected {
            background: rgba(36, 30, 98, 0.05);
            border-color: #241e62;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(36, 30, 98, 0.1);
        }

        .calendar-day.today {
            background: rgba(76, 175, 80, 0.05);
            border-color: #4CAF50;
        }

        .calendar-day-number {
            font-size: 16px;
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
        }

        .event-list {
            display: flex;
            flex-direction: column;
            gap: 5px;
            max-height: calc(100% - 30px);
            overflow-y: auto;
            padding-right: 5px;
        }

        .event {
            font-size: 12px;
            padding: 6px 10px;
            border-radius: 8px;
            color: white;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            transition: all 0.2s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .event:hover {
            transform: translateX(3px);
        }

        .event-content {
            flex: 1;
            overflow: hidden;
        }

        .event-content span {
            display: block;
            font-weight: 500;
            margin-bottom: 2px;
        }

        .event-content small {
            display: block;
            opacity: 0.9;
            font-size: 10px;
        }

        .delete-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            cursor: pointer;
            padding: 2px 6px;
            font-size: 14px;
            border-radius: 50%;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
        }

        .delete-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.1);
        }

        .event-entry {
            margin-top: 40px;
            padding: 35px;
            background: white;
            border-radius: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            box-shadow: 0 15px 40px rgba(36, 30, 98, 0.1);
            position: relative;
            overflow: hidden;
        }

        .event-entry::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(to right, #241e62, #fee800);
        }

        #selectedDate {
            font-weight: 600;
            color: #241e62;
            font-size: 16px;
            padding: 15px 25px;
            background: white;
            border-radius: 15px;
            border: 2px solid rgba(36, 30, 98, 0.1);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 5px 15px rgba(36, 30, 98, 0.05);
            text-align: center;
            line-height: 1.4;
            height: 100%;
        }

        .event-entry select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 12 12'%3E%3Cpath fill='%23241e62' d='M6 8.825L1.175 4 2.05 3.125 6 7.075 9.95 3.125 10.825 4z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 20px center;
            padding: 15px 25px;
            padding-right: 45px;
            font-size: 16px;
            font-weight: 600;
            color: #241e62;
            background-color: white;
            border-radius: 15px;
            border: 2px solid rgba(36, 30, 98, 0.1);
            box-shadow: 0 5px 15px rgba(36, 30, 98, 0.05);
            height: 100%;
        }

        .event-entry input[type="text"] {
            width: 100%;
            height: 100%;
        }

        .event-entry textarea {
            grid-column: 1 / -1;
            resize: vertical;
            min-height: 80px;
        }

        .event-entry button {
            grid-column: 1 / -1;
            background: linear-gradient(135deg, #241e62, #1a1647);
            color: white;
            border: none;
            padding: 15px 35px;
            border-radius: 15px;
            transition: all 0.3s ease;
            font-weight: 600;
            font-size: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            letter-spacing: 0.5px;
            box-shadow: 0 8px 20px rgba(36, 30, 98, 0.2);
            margin-top: 10px;
        }

        .event-entry input[type="text"],
        .event-entry textarea,
        .event-entry select {
            padding: 15px 25px;
            border: 2px solid rgba(36, 30, 98, 0.1);
            border-radius: 15px;
            transition: all 0.3s ease;
            font-size: 15px;
            background: white;
            color: #333;
            box-shadow: 0 5px 15px rgba(36, 30, 98, 0.05);
        }

        .event-entry input[type="text"]:focus,
        .event-entry textarea:focus,
        .event-entry select:focus {
            border-color: #241e62;
            outline: none;
            box-shadow: 0 8px 20px rgba(36, 30, 98, 0.1);
            transform: translateY(-2px);
        }

        .event-entry button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(36, 30, 98, 0.3);
        }

        .event-details-section {
            display: none;
            background: white;
            border-radius: 20px;
            padding: 30px;
            margin-top: 40px;
            box-shadow: 0 15px 40px rgba(36, 30, 98, 0.1);
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.3s ease;
        }

        .event-details-section.active {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }

        .event-details-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }

        .event-details-header h3 {
            color: #241e62;
            margin: 0;
            font-size: 24px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .event-details-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
        }

        .event-detail-item {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 15px;
            transition: all 0.3s ease;
        }

        .event-detail-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(36, 30, 98, 0.08);
        }

        .event-detail-item strong {
            display: block;
            color: #241e62;
            margin-bottom: 8px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .event-detail-item span {
            color: #333;
            font-size: 16px;
            line-height: 1.5;
            display: block;
            word-break: break-word;
        }

        .close-details {
            background: none;
            border: none;
            color: #241e62;
            font-size: 28px;
            cursor: pointer;
            padding: 0;
            line-height: 1;
            transition: all 0.3s ease;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .close-details:hover {
            background: #f8f9fa;
            transform: rotate(90deg);
        }

        @media (max-width: 768px) {
            .event-entry {
                grid-template-columns: 1fr;
                padding: 25px;
                gap: 15px;
            }
            
            .event-entry textarea {
                min-height: 100px;
            }
            
            .event-entry button {
                margin-top: 5px;
            }

            .event-details-content {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="welcome-header">
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
            <p>Admin Dashboard</p>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="sidebar">
                    <div class="list-group">
                        <a href="tryadmin.php" class="list-group-item list-group-item-action active">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                        <a href="add_user.php" class="list-group-item list-group-item-action">
                            <i class="bi bi-people"></i> Manage Users
                        </a>
                        <a href="manage_student.php" class="list-group-item list-group-item-action">
                            <i class="bi bi-person-video3"></i> Manage Students
                        </a>
                        <a href="admin_events.php" class="list-group-item list-group-item-action">
                            <i class="bi bi-calendar-event"></i> Manage Events
                        </a>
                        <a href="admin_teachers.php" class="list-group-item list-group-item-action">
                            <i class="bi bi-person-workspace"></i> Manage Teachers
                        </a>
                        <a href="manage_counts.php" class="list-group-item list-group-item-action">
                            <i class="bi bi-graph-up"></i> Manage Statistics
                        </a>
                        <a href="contact.php" class="list-group-item list-group-item-action">
                            <i class="bi bi-envelope"></i> Contact Us
                        </a>
                        <a href="logout.php" class="list-group-item list-group-item-action text-danger">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="main-content">
                    <div class="calendar-container">
                        <div class="calendar-header">
                            <button id="prevMonth"><i class="bi bi-chevron-left"></i> Previous</button>
                            <div id="monthYear"></div>
                            <button id="nextMonth">Next <i class="bi bi-chevron-right"></i></button>
                        </div>

                        <div class="weekday-row">
                            <div class="weekday">Sun</div>
                            <div class="weekday">Mon</div>
                            <div class="weekday">Tue</div>
                            <div class="weekday">Wed</div>
                            <div class="weekday">Thu</div>
                            <div class="weekday">Fri</div>
                            <div class="weekday">Sat</div>
                        </div>

                        <div class="calendar-grid" id="calendar"></div>

                        <div class="event-entry">
                            <span id="selectedDate">No date selected</span>
                            <select id="eventColor" class="form-select">
                                <option value="#241e62">Blue</option>
                                <option value="#4CAF50">Green</option>
                                <option value="#F44336">Red</option>
                                <option value="#FF9800">Orange</option>
                                <option value="#9C27B0">Purple</option>
                            </select>
                            <input type="text" id="eventInput" placeholder="Event Title..." />
                            <textarea id="eventDetails" placeholder="Event Details..." rows="2"></textarea>
                            <button id="addEvent">Add Event</button>
                        </div>
                    </div>

                    <!-- Event Details Section -->
                    <div class="event-details-section" id="eventDetailsSection">
                        <div class="event-details-header">
                            <h3>Event Details</h3>
                            <button class="close-details" onclick="closeEventDetails()">&times;</button>
                        </div>
                        <div class="event-details-content">
                            <div class="event-detail-item">
                                <strong>Date</strong>
                                <span id="eventDate"></span>
                            </div>
                            <div class="event-detail-item">
                                <strong>Event Title</strong>
                                <span id="eventTitle"></span>
                            </div>
                            <div class="event-detail-item">
                                <strong>Details</strong>
                                <span id="eventDetailsText"></span>
                            </div>
                            <div class="event-detail-item">
                                <strong>Color</strong>
                                <span id="eventColorText"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="calendar.js"></script>
    <script>
        function showEventDetails(event) {
            document.getElementById('eventDate').textContent = event.date;
            document.getElementById('eventTitle').textContent = event.title;
            document.getElementById('eventColor').textContent = event.color;
            
            const detailsSection = document.getElementById('eventDetails');
            detailsSection.classList.add('active');
            
            // Scroll to the details section
            detailsSection.scrollIntoView({ behavior: 'smooth' });
        }

        function closeEventDetails() {
            document.getElementById('eventDetails').classList.remove('active');
        }
    </script>
</body>
</html>
