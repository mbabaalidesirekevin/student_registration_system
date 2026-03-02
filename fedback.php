<?php
session_start();

// Check if registration data exists in session
if(isset($_SESSION['registration_data'])){
    $data = $_SESSION['registration_data'];
    // Clear the session data after retrieving it
    unset($_SESSION['registration_data']);
} else {
    // If no data, redirect to register page
    header('location:register.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Registration Success</title>
	<link rel="stylesheet" href="style.css">
	<style>
		body.feedback-page {
			display: flex;
			align-items: center;
			justify-content: center;
			min-height: 100vh;
			margin: 0;
			background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
			font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
		}

		.feedback-container {
			background: white;
			border-radius: 10px;
			box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
			padding: 40px;
			max-width: 600px;
			width: 90%;
		}

		.success-message {
			text-align: center;
			color: #28a745;
			font-size: 24px;
			font-weight: bold;
			margin-bottom: 30px;
			padding: 15px;
			background-color: #d4edda;
			border-radius: 5px;
			border: 1px solid #c3e6cb;
		}

		.success-icon {
			font-size: 48px;
			margin-bottom: 10px;
		}

		.details-container {
			margin-top: 20px;
		}

		.details-title {
			font-size: 20px;
			font-weight: bold;
			color: #333;
			margin-bottom: 20px;
			text-align: center;
		}

		.detail-row {
			display: flex;
			justify-content: space-between;
			padding: 12px 0;
			border-bottom: 1px solid #e0e0e0;
		}

		.detail-row:last-child {
			border-bottom: none;
		}

		.detail-label {
			font-weight: 600;
			color: #555;
		}

		.detail-value {
			color: #333;
		}

		.home-button {
			display: block;
			text-align: center;
			margin-top: 30px;
		}

		.home-button a {
			display: inline-block;
			padding: 12px 30px;
			background-color: #007bff;
			color: white;
			text-decoration: none;
			border-radius: 5px;
			font-weight: 500;
			transition: background-color 0.3s;
		}

		.home-button a:hover {
			background-color: #0056b3;
		}
	</style>
</head>
<body class="feedback-page">
	<div class="feedback-container">
		<div class="success-message">
			<div class="success-icon">✓</div>
			Your registration was successful!
		</div>
		
		<div class="details-container">
			<div class="details-title">Your Registration Details</div>
			
			<div class="detail-row">
				<span class="detail-label">Student ID:</span>
				<span class="detail-value"><?php echo htmlspecialchars($data['student_id']); ?></span>
			</div>
			
			<div class="detail-row">
				<span class="detail-label">Name:</span>
				<span class="detail-value"><?php echo htmlspecialchars($data['name']); ?></span>
			</div>
			
			<div class="detail-row">
				<span class="detail-label">Email:</span>
				<span class="detail-value"><?php echo htmlspecialchars($data['email']); ?></span>
			</div>
			
			<div class="detail-row">
				<span class="detail-label">Age:</span>
				<span class="detail-value"><?php echo htmlspecialchars($data['age']); ?></span>
			</div>
			
			<div class="detail-row">
				<span class="detail-label">Gender:</span>
				<span class="detail-value"><?php echo htmlspecialchars(ucfirst($data['gender'])); ?></span>
			</div>
			
			<div class="detail-row">
				<span class="detail-label">Course:</span>
				<span class="detail-value"><?php echo htmlspecialchars($data['course']); ?></span>
			</div>
		</div>

		<div class="home-button">
			<a href="index.php">Return to Home</a>
		</div>
	</div>

<footer style="background-color: #333; color: white; text-align: center; padding: 20px; margin-top: 40px;">
  <p style="margin: 0;">© A&B University</p>
</footer>
</body>
</html>

