<?php
require_once '../forms/db_connect.php';

$sql = "SELECT * FROM contact_messages ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Contact Messages</title>
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
            text-align: center;
        }

        .table td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid #e9ecef;
            text-align: center;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .message-cell {
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .message-cell:hover {
            white-space: normal;
            overflow: visible;
            position: relative;
            z-index: 1;
            background: white;
            box-shadow: 0 5px 15px rgba(36, 30, 98, 0.1);
            border-radius: 10px;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
            background: linear-gradient(90deg, #6c757d, #495057);
            border: none;
            color: white;
            text-decoration: none;
        }

        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(36, 30, 98, 0.1);
            color: white;
        }

        .back-btn i {
            font-size: 18px;
        }

        .email-link {
            color: #241e62;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .email-link:hover {
            color: #3a2f8f;
            text-decoration: underline;
        }

        .date-badge {
            background: linear-gradient(90deg, #241e62, #3a2f8f);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
        }

        .no-messages {
            text-align: center;
            padding: 40px;
            color: #6c757d;
            font-size: 16px;
            background: #f8f9fa;
            border-radius: 15px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <a href="tryadmin.php" class="back-btn">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>

        <h2><i class="bi bi-envelope"></i> Contact Form Submissions</h2>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Submitted At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']) ?></td>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td>
                                    <a href="mailto:<?= htmlspecialchars($row['email']) ?>" class="email-link">
                                        <?= htmlspecialchars($row['email']) ?>
                                    </a>
                                </td>
                                <td><?= htmlspecialchars($row['subject']) ?></td>
                                <td>
                                    <div class="message-cell" title="<?= htmlspecialchars($row['message']) ?>">
                                        <?= nl2br(htmlspecialchars($row['message'])) ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="date-badge">
                                        <?= date('M d, Y H:i', strtotime($row['created_at'])) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">
                                <div class="no-messages">
                                    <i class="bi bi-inbox" style="font-size: 24px; margin-bottom: 10px;"></i>
                                    <p>No messages found.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
