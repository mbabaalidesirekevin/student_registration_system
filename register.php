<?php
session_start();
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

// Function to generate a unique student ID
function generateStudentID($con) {
    $year = date('Y');
    $prefix = 'STU' . $year;
    
    // Get the highest sequential number for this year
    $sql = "SELECT student_id FROM studenttable WHERE student_id LIKE '$prefix%' ORDER BY student_id DESC LIMIT 1";
    $result = mysqli_query($con, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $last_id = $row['student_id'];
        // Extract the number part
        $last_num = intval(substr($last_id, strlen($prefix)));
        $new_num = $last_num + 1;
    } else {
        $new_num = 1;
    }
    
    // Format with leading zeros (e.g., STU2025001)
    $student_id = $prefix . str_pad($new_num, 3, '0', STR_PAD_LEFT);
    
    // Ensure uniqueness (in case of race condition)
    $check_sql = "SELECT student_id FROM studenttable WHERE student_id = '$student_id'";
    $check_result = mysqli_query($con, $check_sql);
    if ($check_result && mysqli_num_rows($check_result) > 0) {
        // If exists, increment and try again
        $new_num++;
        $student_id = $prefix . str_pad($new_num, 3, '0', STR_PAD_LEFT);
    }
    
    return $student_id;
}

if(isset($_POST['submit'])){
  // Sanitize inputs
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $age = intval($_POST['age']);
  $gender = $_POST['gender'];
  $course = $_POST['course'];

  // Server-side check: ensure email is unique
  $email_esc = mysqli_real_escape_string($con, $email);
  $check_sql = "SELECT student_id FROM studenttable WHERE email = '$email_esc' LIMIT 1";
  $check_res = mysqli_query($con, $check_sql);
  if ($check_res && mysqli_num_rows($check_res) > 0) {
    $error = "A student with that email already exists.";
  } else {
    // Generate fresh ID on submission to ensure uniqueness
    $id = generateStudentID($con);

    // Insert sanitized values
    $sql = "INSERT INTO `studenttable` 
    (student_id,name,email,age,gender,course)
    values('".mysqli_real_escape_string($con,$id)."','".mysqli_real_escape_string($con,$name)."','$email_esc','$age','".mysqli_real_escape_string($con,$gender)."','".mysqli_real_escape_string($con,$course)."')";
    $result = mysqli_query($con,$sql);
    if(!$result){
      // If duplicate key error (possible race on student_id), generate new ID and try once more
      if(mysqli_errno($con) == 1062) {
        $id = generateStudentID($con);
        $sql = "INSERT INTO `studenttable` 
        (student_id,name,email,age,gender,course)
        values('".mysqli_real_escape_string($con,$id)."','".mysqli_real_escape_string($con,$name)."','$email_esc','$age','".mysqli_real_escape_string($con,$gender)."','".mysqli_real_escape_string($con,$course)."')";
        $result = mysqli_query($con,$sql);
      }
      if(!$result){
        die("Error: " . mysqli_error($con));
      }
    }

    // Store registration data in session for display on feedback page
    $_SESSION['registration_data'] = array(
      'student_id' => $id,
      'name' => $name,
      'email' => $email,
      'age' => $age,
      'gender' => $gender,
      'course' => $course
    );
    header('location:fedback.php');
    exit();
  }
}

// Generate student ID for display in the form
$auto_generated_id = generateStudentID($con);
$course_options = getAvailableCourses($con, $default_courses);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Register</title>
</head>
<body>
  <nav style ="display: flex; justify-content: center; align-items: left; padding: 10px; background-color: #2c68f4ff; color: white;">
  <div class="logo">
    <h2>Student Registration Form</h2>
  </div>
</nav> 

    <div class="container my-5">
      <?php if (!empty($error ?? '')): ?>
        <div style="color: red; margin-bottom: 12px; font-weight: bold;"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>
      <form method = "post">

        <div class="mb-3">
          <label class="form-label">Student ID <small style="color: #666;">(Auto-generated)</small></label>
          <input type="text" name ="student_id" class="form-control" value="<?php echo htmlspecialchars($auto_generated_id); ?>" readonly style="background-color: #e9ecef; cursor: not-allowed;">
        </div>

  <div class="mb-3">
    <label class="form-label">Name</label>
    <input type="text" name ="name" class="form-control" placeholder ="Enter Name" autocomplete = "off" required>
  </div>

  <div class="mb-3">
    <label class="form-label">Email</label>
    <input type="email" name ="email" class="form-control" placeholder ="Enter Email" autocomplete = "off" required>
  </div>

    <div class="mb-3">
    <label class="form-label">Age</label>
    <input type="number" name ="age" class="form-control" placeholder ="Enter Age" autocomplete = "off" required>
  </div>

<div class="mb-3">
 <label class="form-label">Gender:</label>
<select name="gender" class="form-control" required>
  <option value="">--Please choose an option--</option>
  <option value="male">Male</option>
  <option value="female">Female</option>
  </select>
</div>



    <div class="mb-3">
    <label class="form-label">Course</label>
    <select name="course" class="form-control" required>
        <option value="">--Please choose a coures--</option>

      <?php foreach($course_options as $course_option): ?>
        <option value="<?php echo htmlspecialchars($course_option); ?>" <?php if(isset($_POST['course']) && $_POST['course'] === $course_option) echo 'selected'; ?>>
          <?php echo htmlspecialchars($course_option); ?>
        </option>
      <?php endforeach; ?>
    </select>
    </div>

  <div style="display: flex; gap: 10px; margin-top: 20px;">
    <button type="submit" name ="submit" class="btn btn-primary">Register</button>
    <button type="button" class="btn btn-secondary" onclick="window.location.href='index.php'">Cancel</button>
  </div>
</form>
    </div>

<footer style="background-color: #333; color: white; text-align: center; padding: 20px; margin-top: 40px;">
  <p style="margin: 0;">© A&B University</p>
</footer>
</body>
</html>