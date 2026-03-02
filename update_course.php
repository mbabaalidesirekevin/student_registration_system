<?php
include 'connect.php';

if (!isset($_GET['id'])) {
    header('Location: courses.php');
    exit;
}
$id = intval($_GET['id']);

$sql = "SELECT course_name FROM courses WHERE id = $id LIMIT 1";
$result = mysqli_query($con, $sql);
if (!$result || mysqli_num_rows($result) == 0) {
    header('Location: courses.php');
    exit;
}
$row = mysqli_fetch_assoc($result);
$course_name = $row['course_name'];

if (isset($_POST['submit'])) {
    $new_name = trim($_POST['course_name']);
    if ($new_name !== '') {
        $new_name_esc = mysqli_real_escape_string($con, $new_name);
        $update_sql = "UPDATE courses SET course_name = '$new_name_esc' WHERE id = $id";
        $res = mysqli_query($con, $update_sql);
        if (!$res) {
            if (mysqli_errno($con) == 1062) {
                $error = "Course name already exists.";
            } else {
                $error = "Error: " . mysqli_error($con);
            }
        } else {
            header('Location: courses.php');
            exit;
        }
    } else {
        $error = "Course name cannot be empty.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Edit Course</title>
</head>
<body>
<nav style="display: flex; justify-content: space-between; align-items: center; padding: 15px 30px; background-color: #333; color: white;">
  <div class="logo">
    <h2 style="margin: 0;">Student Registration System</h2>
  </div>
  <div style="display: flex; gap: 20px; align-items: center;">
    <a href="add_student.php" style="padding: 8px 16px; background-color: rgba(255,255,255,0.1); border-radius: 4px; text-decoration: none; color: white;">Add Student</a>
    <a href="students.php" style="padding: 8px 16px; background-color: rgba(255,255,255,0.1); border-radius: 4px; text-decoration: none; color: white;">View Students</a>
    <a href="courses.php" style="padding: 8px 16px; background-color: rgba(255,255,255,0.1); border-radius: 4px; text-decoration: none; color: white;">Manage Courses</a>
    <a href="logout.php" style="padding: 8px 16px; background-color: rgba(255,255,255,0.1); border-radius: 4px; text-decoration: none; color: white;">Logout</a>
  </div>
</nav>

<div class="container">
    <h2 style="margin-top: 20px;">Edit Course</h2>
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="post" style="margin-top: 10px;">
        <div class="mb-3">
            <label class="form-label">Course Name</label>
            <input type="text" name="course_name" class="form-control" value="<?php echo htmlspecialchars($course_name); ?>" required>
        </div>
        <div style="display:flex; gap:10px; margin-top:10px;">
            <button type="submit" name="submit" class="btn btn-primary">Save</button>
            <button type="button" class="btn btn-secondary" onclick="window.location.href='courses.php'">Cancel</button>
        </div>
    </form>
</div>

<footer style="background-color: #333; color: white; text-align: center; padding: 20px; margin-top: 40px;">
  <p style="margin: 0;">© A&B University</p>
</footer>
</body>
</html>
