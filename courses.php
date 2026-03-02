<?php
include 'connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Manage Courses</title>
</head>
<body>
<nav style="display: flex; justify-content: space-between; align-items: center; padding: 15px 30px; background-color: #333; color: white;">
  <div class="logo">
    <h2 style="margin: 0;">Student Registration System</h2>
  </div>
  <div style="display: flex; gap: 20px; align-items: center;">
    <a href="add_student.php" style="padding: 8px 16px; background-color: rgba(255,255,255,0.1); border-radius: 4px; text-decoration: none; color: white;">Add Student</a>
    <a href="students.php" style="padding: 8px 16px; background-color: rgba(255,255,255,0.1); border-radius: 4px; text-decoration: none; color: white;">View Students</a>
    <a href="add_course.php" style="padding: 8px 16px; background-color: rgba(255,255,255,0.1); border-radius: 4px; text-decoration: none; color: white;">Add Course</a>
    <a href="reports.php" style="padding: 8px 16px; background-color: rgba(255,255,255,0.1); border-radius: 4px; text-decoration: none; color: white;">Reports</a>
    <a href="logout.php" style="padding: 8px 16px; background-color: rgba(255,255,255,0.1); border-radius: 4px; text-decoration: none; color: white;">Logout</a>
  </div>
</nav>

<div class="container">
    <h2 style="margin-top: 20px;">Courses</h2>
    <table class="student-table" style="margin-top: 20px; width: 100%;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Course Name</th>
                <th>Operation</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT id, course_name FROM courses ORDER BY course_name";
            $result = mysqli_query($con, $sql);
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $id = $row['id'];
                    $name = htmlspecialchars($row['course_name']);
                    echo "<tr>\n";
                    echo "<td>" . $id . "</td>\n";
                    echo "<td>" . $name . "</td>\n";
                    echo "<td style=\"display:flex;gap:10px;\">";
                    echo "<button class='update-btn'><a href=\"update_course.php?id=$id\">Edit</a></button>";
                    echo "<button class='delete-btn'><a href=\"course_delete.php?id=$id\" onclick=\"return confirm('Are you sure you want to delete this course?');\">Delete</a></button>";
                    echo "</td>\n";
                    echo "</tr>\n";
                }
            }
            ?>
        </tbody>
    </table>
</div>

<footer style="background-color: #333; color: white; text-align: center; padding: 20px; margin-top: 40px;">
  <p style="margin: 0;">© A&B University</p>
</footer>
</body>
</html>
