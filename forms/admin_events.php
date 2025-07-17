<?php
session_start();
require('db_connect.php');

// Handle additional photo upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_photos'])) {
    $event_id = $_POST['event_id'];
    
    if (isset($_FILES['additional_photos'])) {
        $total_files = count($_FILES['additional_photos']['name']);
        
        for ($i = 0; $i < $total_files; $i++) {
            if ($_FILES['additional_photos']['error'][$i] === 0) {
                $file_name = $_FILES['additional_photos']['name'][$i];
                $file_tmp = $_FILES['additional_photos']['tmp_name'][$i];
                
                // Generate unique filename for photos
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                $new_file_name = 'photo_' . uniqid() . '_' . time() . '.' . $file_ext;
                $target = "uploads/" . $new_file_name;
                
                if (move_uploaded_file($file_tmp, $target)) {
                    // Insert photo record
                    $photo_query = "INSERT INTO event_photos (event_id, photo_name) VALUES (?, ?)";
                    $photo_stmt = mysqli_prepare($conn, $photo_query);
                    mysqli_stmt_bind_param($photo_stmt, "is", $event_id, $new_file_name);
                    mysqli_stmt_execute($photo_stmt);
                    mysqli_stmt_close($photo_stmt);
                }
            }
        }
    }
    header("Location: admin_events.php");
    exit();
}

// Handle event submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $date = $_POST['date'];
    $description = $_POST['description'];
    
    // Handle main event image
    $main_image = '';
    if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] === 0) {
        $file_name = $_FILES['main_image']['name'];
        $file_tmp = $_FILES['main_image']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $new_file_name = 'main_' . uniqid() . '_' . time() . '.' . $file_ext;
        $target = "uploads/" . $new_file_name;
        
        if (move_uploaded_file($file_tmp, $target)) {
            $main_image = $new_file_name;
        }
    }
    
    // Insert event with main image
    $query = "INSERT INTO events (title, date, description, image) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssss", $title, $date, $description, $main_image);
    
    if (mysqli_stmt_execute($stmt)) {
        $event_id = mysqli_insert_id($conn);
        
        // Handle event photos
        if (isset($_FILES['event_photos'])) {
            $total_files = count($_FILES['event_photos']['name']);
            
            for ($i = 0; $i < $total_files; $i++) {
                if ($_FILES['event_photos']['error'][$i] === 0) {
                    $file_name = $_FILES['event_photos']['name'][$i];
                    $file_tmp = $_FILES['event_photos']['tmp_name'][$i];
                    
                    // Generate unique filename for photos
                    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                    $new_file_name = 'photo_' . uniqid() . '_' . time() . '.' . $file_ext;
                    $target = "uploads/" . $new_file_name;
                    
                    if (move_uploaded_file($file_tmp, $target)) {
                        // Insert photo record
                        $photo_query = "INSERT INTO event_photos (event_id, photo_name) VALUES (?, ?)";
                        $photo_stmt = mysqli_prepare($conn, $photo_query);
                        mysqli_stmt_bind_param($photo_stmt, "is", $event_id, $new_file_name);
                        mysqli_stmt_execute($photo_stmt);
                        mysqli_stmt_close($photo_stmt);
                    }
                }
            }
        }
        mysqli_stmt_close($stmt);
    }
}

// Fetch events with their photos
$events_query = "SELECT e.*, GROUP_CONCAT(ep.photo_name SEPARATOR ',') as photos 
                FROM events e 
                LEFT JOIN event_photos ep ON e.event_id = ep.event_id 
                GROUP BY e.event_id, e.title, e.date, e.description, e.image 
                ORDER BY e.date DESC";
$events = mysqli_query($conn, $events_query);

if (!$events) {
    die("Error fetching events: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Events</title>
    <link href="../assets/pics/felta-logo (2).png" rel="icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%);
            padding: 30px;
            min-height: 100vh;
        }

        .admin-container {
            max-width: 1200px;
            background: #fff;
            margin: auto;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(36, 30, 98, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 40px;
            color: #241e62;
            font-weight: 600;
            font-size: 28px;
            position: relative;
            padding-bottom: 15px;
        }

        h2:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, #241e62, #3a2f8f);
            border-radius: 3px;
        }

        h3 {
            color: #241e62;
            font-weight: 600;
            margin: 40px 0 20px;
            font-size: 24px;
        }

        .event-form {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(36, 30, 98, 0.05);
            margin-bottom: 40px;
        }

        .form-label {
            color: #241e62;
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #241e62;
            box-shadow: 0 0 0 3px rgba(36, 30, 98, 0.1);
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        .btn {
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-right: 10px;
        }

        .btn-primary {
            background: linear-gradient(90deg, #241e62, #3a2f8f);
            border: none;
            color: white;
        }

        .btn-secondary {
            background: linear-gradient(90deg, #6c757d, #495057);
            border: none;
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(36, 30, 98, 0.1);
        }

        .table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(36, 30, 98, 0.05);
        }

        .table thead {
            background: linear-gradient(90deg, #241e62, #3a2f8f);
            color: white;
        }

        .table th {
            font-weight: 500;
            padding: 15px;
            border: none;
        }

        .table td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid #e9ecef;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .event-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(36, 30, 98, 0.1);
            transition: transform 0.3s ease;
        }

        .event-image:hover {
            transform: scale(1.05);
        }

        .btn-sm {
            padding: 8px 15px;
            font-size: 13px;
        }

        .btn-warning {
            background: linear-gradient(90deg, #ffc107, #ffb300);
            border: none;
            color: #000;
        }

        .btn-danger {
            background: linear-gradient(90deg, #dc3545, #c82333);
            border: none;
            color: white;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }

        .back-btn i {
            font-size: 18px;
        }

        .photos-preview {
            position: relative;
            display: inline-block;
        }

        .image-count {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 12px;
        }

        .text-muted {
            font-size: 0.9em;
            color: #6c757d;
        }

        .modal-photos {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content-photos {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border-radius: 15px;
            width: 80%;
            max-width: 800px;
            position: relative;
        }

        .close-modal {
            position: absolute;
            right: 20px;
            top: 15px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            color: #666;
        }

        .close-modal:hover {
            color: #000;
        }

        .photos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .photo-item {
            position: relative;
            aspect-ratio: 1;
            border-radius: 8px;
            overflow: hidden;
        }

        .photo-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .delete-photo {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(255, 0, 0, 0.7);
            color: white;
            border: none;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .delete-photo:hover {
            background: rgba(255, 0, 0, 0.9);
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <a href="tryadmin.php" class="btn btn-secondary back-btn">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>

        <h2>Manage Events</h2>
        
        <div class="event-form">
            <form action="admin_events.php" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Event Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Event Date</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Main Event Image</label>
                    <input type="file" name="main_image" class="form-control" accept="image/*" required>
                    <small class="text-muted">This will be the main image displayed for the event</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Event Photos</label>
                    <input type="file" name="event_photos[]" class="form-control" multiple accept="image/*">
                    <small class="text-muted">Additional photos for the event (optional)</small>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Event
                </button>
            </form>
        </div>

        <h3>Existing Events</h3>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Main Image</th>
                        <th>Photos</th>
                        <th>Title</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($events)) { 
                        $photos = $row['photos'] ? explode(',', $row['photos']) : [];
                    ?>
                        <tr>
                            <td>
                                <?php if ($row['image']) { ?>
                                    <img src="uploads/<?php echo $row['image']; ?>" class="event-image" alt="<?php echo htmlspecialchars($row['title']); ?>">
                                <?php } else { ?>
                                    <span class="text-muted">No image</span>
                                <?php } ?>
                            </td>
                            <td>
                                <?php if (!empty($photos)) { ?>
                                    <div class="photos-preview">
                                        <img src="uploads/<?php echo $photos[0]; ?>" class="event-image" alt="Photo preview">
                                        <?php if (count($photos) > 1) { ?>
                                            <span class="image-count">+<?php echo count($photos) - 1; ?> more</span>
                                        <?php } ?>
                                    </div>
                                <?php } else { ?>
                                    <span class="text-muted">No photos</span>
                                <?php } ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo date('F d, Y', strtotime($row['date'])); ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td>
                                <button onclick="openPhotoModal(<?php echo $row['event_id']; ?>, '<?php echo htmlspecialchars($row['title']); ?>')" class="btn btn-info btn-sm">
                                    <i class="bi bi-images"></i> Photos
                                </button>
                                <a href="delete_event.php?id=<?php echo $row['event_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this event? This will also delete all associated photos.')">
                                    <i class="bi bi-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Photo Management Modal -->
    <div id="photoModal" class="modal-photos">
        <div class="modal-content-photos">
            <span class="close-modal" onclick="closePhotoModal()">&times;</span>
            <h3 id="modalEventTitle"></h3>
            
            <form action="admin_events.php" method="POST" enctype="multipart/form-data" class="mt-4">
                <input type="hidden" name="event_id" id="modalEventId">
                <input type="hidden" name="add_photos" value="1">
                
                <div class="mb-3">
                    <label class="form-label">Add More Photos</label>
                    <input type="file" name="additional_photos[]" class="form-control" multiple accept="image/*">
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-upload"></i> Upload Photos
                </button>
            </form>

            <div class="photos-grid" id="photosGrid">
                <!-- Photos will be loaded here via AJAX -->
            </div>
        </div>
    </div>

    <script>
        function openPhotoModal(eventId, eventTitle) {
            document.getElementById('modalEventId').value = eventId;
            document.getElementById('modalEventTitle').textContent = eventTitle;
            document.getElementById('photoModal').style.display = 'block';
            loadPhotos(eventId);
        }

        function closePhotoModal() {
            document.getElementById('photoModal').style.display = 'none';
        }

        function loadPhotos(eventId) {
            fetch(`get_event_photos.php?event_id=${eventId}`)
                .then(response => response.json())
                .then(photos => {
                    const grid = document.getElementById('photosGrid');
                    grid.innerHTML = '';
                    
                    photos.forEach(photo => {
                        const photoItem = document.createElement('div');
                        photoItem.className = 'photo-item';
                        photoItem.innerHTML = `
                            <img src="uploads/${photo.photo_name}" alt="Event photo">
                            <button class="delete-photo" onclick="deletePhoto(${photo.photo_id})">
                                <i class="bi bi-x"></i>
                            </button>
                        `;
                        grid.appendChild(photoItem);
                    });
                });
        }

        function deletePhoto(photoId) {
            if (confirm('Are you sure you want to delete this photo?')) {
                fetch('delete_photo.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `photo_id=${photoId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadPhotos(document.getElementById('modalEventId').value);
                    } else {
                        alert('Error deleting photo');
                    }
                });
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('photoModal');
            if (event.target == modal) {
                closePhotoModal();
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
