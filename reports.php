<?php
include 'connect.php';

// Fetch courses for filter
$courses = [];
$csql = "SELECT id, course_name FROM courses ORDER BY course_name";
$cres = mysqli_query($con, $csql);
if ($cres) {
    while ($crow = mysqli_fetch_assoc($cres)) {
        $courses[] = $crow['course_name'];
    }
}

// Read filters from GET
$course_filter = isset($_GET['course']) ? trim($_GET['course']) : '';
$gender_filter = isset($_GET['gender']) ? trim($_GET['gender']) : '';
$min_age = isset($_GET['min_age']) && $_GET['min_age'] !== '' ? intval($_GET['min_age']) : null;
$max_age = isset($_GET['max_age']) && $_GET['max_age'] !== '' ? intval($_GET['max_age']) : null;
$export = isset($_GET['export']) && $_GET['export'] == '1';

// Build query
$where = [];
if ($course_filter !== '') {
    $where[] = "course = '".mysqli_real_escape_string($con,$course_filter)."'";
}
if ($gender_filter !== '') {
    $where[] = "gender = '".mysqli_real_escape_string($con,$gender_filter)."'";
}
if (!is_null($min_age)) {
    $where[] = "age >= " . intval($min_age);
}
if (!is_null($max_age)) {
    $where[] = "age <= " . intval($max_age);
}

$q = "SELECT student_id, name, email, age, gender, course FROM studenttable";
if (!empty($where)) $q .= " WHERE " . implode(' AND ', $where);
$q .= " ORDER BY name";

$res = mysqli_query($con, $q);

if ($export) {
    // Output as Excel-compatible HTML (.xls) so Excel can open the file directly
    header('Content-Type: application/vnd.ms-excel; charset=utf-8');
    header('Content-Disposition: attachment; filename=students_report.xls');

    echo "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8' /></head><body>";
    echo "<table border='1'><tr>";
    echo "<th>Student ID</th><th>Name</th><th>Email</th><th>Age</th><th>Gender</th><th>Course</th>";
    echo "</tr>";
    if ($res) {
        while ($r = mysqli_fetch_assoc($res)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($r['student_id']) . "</td>";
            echo "<td>" . htmlspecialchars($r['name']) . "</td>";
            echo "<td>" . htmlspecialchars($r['email']) . "</td>";
            echo "<td>" . htmlspecialchars($r['age']) . "</td>";
            echo "<td>" . htmlspecialchars($r['gender']) . "</td>";
            echo "<td>" . htmlspecialchars($r['course']) . "</td>";
            echo "</tr>";
        }
    }
    echo "</table></body></html>";
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <title>Reports</title>
</head>
<body>
<nav style="display: flex; justify-content: space-between; align-items: center; padding: 15px 30px; background-color: #333; color: white;">
  <div class="logo"><h2 style="margin:0;">Student Registration System</h2></div>
  <div style="display:flex; gap:12px; align-items:center;">
    <a href="add_student.php" style="color:white; text-decoration:none; padding:8px 12px;">Add Student</a>
    <a href="students.php" style="color:white; text-decoration:none; padding:8px 12px;">View Students</a>
    <a href="courses.php" style="color:white; text-decoration:none; padding:8px 12px;">Manage Courses</a>
    <a href="logout.php" style="color:white; text-decoration:none; padding:8px 12px;">Logout</a>
  </div>
</nav>

<div class="container">
    <h2 style="margin-top:20px;">Generate Student Reports</h2>
    <form method="get" style="margin-top:12px; display:flex; gap:12px; align-items:flex-end; flex-wrap:wrap;">
        <div>
            <label>Course</label>
            <select name="course" class="form-control">
                <option value="">--All courses--</option>
                <?php foreach($courses as $c): ?>
                    <option value="<?php echo htmlspecialchars($c); ?>" <?php if($course_filter===$c) echo 'selected'; ?>><?php echo htmlspecialchars($c); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label>Gender</label>
            <select name="gender" class="form-control">
                <option value="">--Any--</option>
                <option value="Male" <?php if($gender_filter==='Male') echo 'selected'; ?>>Male</option>
                <option value="Female" <?php if($gender_filter==='Female') echo 'selected'; ?>>Female</option>
            </select>
        </div>
        <div>
            <label>Min Age</label>
            <input type="number" name="min_age" class="form-control" value="<?php echo htmlspecialchars($min_age ?? ''); ?>">
        </div>
        <div>
            <label>Max Age</label>
            <input type="number" name="max_age" class="form-control" value="<?php echo htmlspecialchars($max_age ?? ''); ?>">
        </div>
        <div style="display:flex; gap:8px;">
            <button type="submit" class="btn btn-primary">View</button>
            <button type="submit" name="export" value="1" class="btn btn-secondary">Download Report</button>
        </div>
    </form>

    <div style="margin-top:20px;">
        <table class="student-table" style="width:100%;">
            <thead>
                <tr><th>ID</th><th>Name</th><th>Email</th><th>Age</th><th>Gender</th><th>Course</th></tr>
            </thead>
            <tbody>
                <?php if ($res) {
                    while ($r = mysqli_fetch_assoc($res)) {
                        echo '<tr>';
                        echo '<td>'.htmlspecialchars($r['student_id']).'</td>';
                        echo '<td>'.htmlspecialchars($r['name']).'</td>';
                        echo '<td>'.htmlspecialchars($r['email']).'</td>';
                        echo '<td>'.htmlspecialchars($r['age']).'</td>';
                        echo '<td>'.htmlspecialchars($r['gender']).'</td>';
                        echo '<td>'.htmlspecialchars($r['course']).'</td>';
                        echo '</tr>';
                    }
                } ?>
            </tbody>
        </table>
    </div>
</div>

<footer style="background-color:#333;color:white;text-align:center;padding:20px;margin-top:40px;">© A&B University</footer>
</body>
</html>
