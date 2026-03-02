<?php
include 'connect.php';

$default_courses = [
    'Philosophy',
    'Psychology',
    'Sociology',
    'Political Science',
    'History',
    'Economics',
    'Accounting',
    'Marketing',
    'Entrepreneurship',
    'Finance',
    'Computer Science',
    'Information Technology',
    'Biology',
    'Chemistry',
    'Physics',
    'Engineering',
    'Law',
    'Medicine',
    'Education',
    'Architecture'
];

function getAvailableCourses($con, $fallbackCourses) {
    $courses = [];
    $sql = "SELECT course_name FROM courses ORDER BY course_name";
    $result = mysqli_query($con, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $courses[] = $row['course_name'];
        }
    }
    if (empty($courses)) {
        $courses = $fallbackCourses;
    }
    return $courses;
}

$course_options = getAvailableCourses($con, $default_courses);

// Check if updateid is provided
if (isset($_GET['updateid'])) {
    $id = $_GET['updateid'];

    // Fetch student data
    $sql = "SELECT * FROM studenttable WHERE student_id = '$id'";
    $result = mysqli_query($con, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $student_id = $row['student_id'];
        $name = $row['name'];
        $email = $row['email'];
        $age = $row['age'];
        $gender = $row['gender'];
        $course = $row['course'];

    } else {
        echo "<div class='container my-5'><div class='alert alert-danger'>Student not found.</div></div>";
        exit();
    }
} else {
    echo "<div class='container my-5'><div class='alert alert-warning'>No student ID provided.</div></div>";
    exit();
}

// Handle form submission
if (isset($_POST['submit'])) {
    $new_student_id = trim($_POST['student_id']);
    $new_name = trim($_POST['name']);
    $new_email = trim($_POST['email']);
    $new_age = intval($_POST['age']);
    $new_gender = $_POST['gender'];
    $new_course = $_POST['course'];

    // Check whether new email is used by another student
    $new_email_esc = mysqli_real_escape_string($con, $new_email);
    $check_sql = "SELECT student_id FROM studenttable WHERE email = '$new_email_esc' AND student_id != '".mysqli_real_escape_string($con,$id)."' LIMIT 1";
    $check_res = mysqli_query($con, $check_sql);
    if ($check_res && mysqli_num_rows($check_res) > 0) {
        $error = "Another student is already using that email.";
    } else {
        $update_sql = "UPDATE studenttable SET 
        student_id = '".mysqli_real_escape_string($con,$new_student_id)."',
        name = '".mysqli_real_escape_string($con,$new_name)."',
        email = '$new_email_esc',
        age = '$new_age',
        gender = '".mysqli_real_escape_string($con,$new_gender)."',
        course = '".mysqli_real_escape_string($con,$new_course)."'
        WHERE student_id = '".mysqli_real_escape_string($con,$id)."'";

        $update_result = mysqli_query($con, $update_sql);

        if ($update_result) {
            header("Location: students.php");
            exit();
        } else {
            $error = "Error updating student: " . mysqli_error($con);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Update Student</title>
</head>
<body>
<nav style="display: flex; justify-content: space-between; align-items: center; padding: 15px 30px; background-color: #333; color: white;">
  
  <div class="logo">
    <h2 style="margin: 0;">Student Registration System</h2>
  </div>
  <div style="display: flex; gap: 20px; align-items: center;">
    <a href="add_student.php" style="padding: 8px 16px; background-color: rgba(255,255,255,0.1); border-radius: 4px; transition: background-color 0.3s; text-decoration: none; color: white;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.2)'" onmouseout="this.style.backgroundColor='rgba(255,255,255,0.1)'">Add Student</a>
    <a href="students.php"style="padding: 8px 16px; background-color: rgba(255,255,255,0.1); border-radius: 4px; transition: background-color 0.3s; text-decoration: none; color: white;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.2)'" onmouseout="this.style.backgroundColor='rgba(255,255,255,0.1)'">View Students</a>
    <a href="logout.php" style="padding: 8px 16px; background-color: rgba(255,255,255,0.1); border-radius: 4px; transition: background-color 0.3s; text-decoration: none; color: white;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.2)'" onmouseout="this.style.backgroundColor='rgba(255,255,255,0.1)'">Logout</a>
  </div>
</nav>

<div class="container my-5">
    <h2 class="mb-4">Update Student Information</h2>
    <?php if (!empty($error ?? '')): ?>
        <div style="color: red; margin-bottom: 12px; font-weight: bold;"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Student ID</label>
            <input type="text" class="form-control" name="student_id" value="<?php echo $student_id; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" class="form-control" name="name" value="<?php echo $name; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="<?php echo $email; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Age</label>
            <input type="number" class="form-control" name="age" value="<?php echo $age; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Gender</label>
            <select class="form-control" name="gender" required>
                <option value="Male" <?php if ($gender == 'Male') echo 'selected'; ?>>Male</option>
                <option value="Female" <?php if ($gender == 'Female') echo 'selected'; ?>>Female</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Course</label>
            <select class="form-control" name="course" required>
                <option value="">--Select a course--</option>
                <?php foreach($course_options as $course_option): ?>
                    <option value="<?php echo htmlspecialchars($course_option); ?>" <?php echo (strcasecmp($course_option, $course) === 0) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($course_option); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-success" name="submit">Update</button>
</form>
    </div>

<footer style="background-color: #333; color: white; text-align: center; padding: 20px; margin-top: 40px;">
  <p style="margin: 0;">© A&B University</p>
</footer>
</body>
</html>