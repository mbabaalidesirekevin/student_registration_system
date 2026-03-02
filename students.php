<?php
    include 'connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" href="style.css">
    <title>View students</title>
</head>
<body>
<nav style="display: flex; justify-content: space-between; align-items: center; padding: 15px 30px; background-color: #333; color: white;">
  
  <div class="logo">
    <h2 style="margin: 0;">Student Registration System</h2>
  </div>
  <div style="display: flex; gap: 20px; align-items: center;">
    <div style="position: relative; display: flex; align-items: center;">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 12px; color: #666;">
        <circle cx="11" cy="11" r="8"></circle>
        <path d="m21 21-4.35-4.35"></path>
      </svg>
      <input type="text" id="searchInput" placeholder="Search by Name or ID..." style="padding: 8px 12px 8px 40px; border: 1px solid #555; border-radius: 4px; font-size: 14px; background-color: #fff; color: #333; width: 300px; outline: none;" onfocus="this.style.borderColor='#007bff'" onblur="this.style.borderColor='#555'">
    </div>
    <a href="add_student.php" style="padding: 8px 16px; background-color: rgba(255,255,255,0.1); border-radius: 4px; transition: background-color 0.3s; text-decoration: none; color: white;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.2)'" onmouseout="this.style.backgroundColor='rgba(255,255,255,0.1)'">Add Student</a>
    <a href="courses.php" style="padding: 8px 16px; background-color: rgba(255,255,255,0.1); border-radius: 4px; transition: background-color 0.3s; text-decoration: none; color: white;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.2)'" onmouseout="this.style.backgroundColor='rgba(255,255,255,0.1)'">Manage Courses</a>
    <a href="reports.php" style="padding: 8px 16px; background-color: rgba(255,255,255,0.1); border-radius: 4px; transition: background-color 0.3s; text-decoration: none; color: white;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.2)'" onmouseout="this.style.backgroundColor='rgba(255,255,255,0.1)'">Reports</a>
    <a href="logout.php" style="padding: 8px 16px; background-color: rgba(255,255,255,0.1); border-radius: 4px; transition: background-color 0.3s; text-decoration: none; color: white;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.2)'" onmouseout="this.style.backgroundColor='rgba(255,255,255,0.1)'">Logout</a>
  </div>
</nav>


    <div class="container">
        <?php
        // Get statistics
        $total_sql = "SELECT COUNT(*) as total FROM `studenttable`";
        $total_result = mysqli_query($con, $total_sql);
        $total_row = mysqli_fetch_assoc($total_result);
        $total_students = $total_row['total'];

        $female_sql = "SELECT COUNT(*) as female FROM `studenttable` WHERE gender = 'Female'";
        $female_result = mysqli_query($con, $female_sql);
        $female_row = mysqli_fetch_assoc($female_result);
        $female_count = $female_row['female'];

        $male_sql = "SELECT COUNT(*) as male FROM `studenttable` WHERE gender = 'Male'";
        $male_result = mysqli_query($con, $male_sql);
        $male_row = mysqli_fetch_assoc($male_result);
        $male_count = $male_row['male'];
        ?>

        <div style="display: flex; gap: 20px; margin-bottom: 30px;">
            <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); flex: 1; text-align: center;">
                <h3 style="margin: 0 0 10px 0; color: #333;">Total Students</h3>
                <p style="margin: 0; font-size: 32px; color: #007BFF; font-weight: bold;"><?php echo $total_students; ?></p>
            </div>
            <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); flex: 1; text-align: center;">
                <h3 style="margin: 0 0 10px 0; color: #333;">Female Students</h3>
                <p style="margin: 0; font-size: 32px; color: #FF69B4; font-weight: bold;"><?php echo $female_count; ?></p>
            </div>
            <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); flex: 1; text-align: center;">
                <h3 style="margin: 0 0 10px 0; color: #333;">Male Students</h3>
                <p style="margin: 0; font-size: 32px; color: #4169E1; font-weight: bold;"><?php echo $male_count; ?></p>
            </div>
        </div>
        <table class="student-table" id="studentTable">
  <thead>
    <tr>
      <th >ID</th>
      <th >Name</th>
      <th >Email</th>
      <th >Age</th>
      <th >Gender</th>
      <th >Course</th>
      <th >Operation</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $sql = "select * from `studenttable`";
    $result = mysqli_query($con,$sql);
    if($result){
        while($row = mysqli_fetch_assoc($result)){
            $id = $row['student_id'];
            $name = $row['name'];
            $email = $row['email'];
            $age = $row['age'];
            $gender = $row['gender'];
            $course = $row['course'];
            echo '<tr>
            <th scope="row">'.$id.'</th>
            <td>'.$name.'</td>
            <td>'.$email.'</td>
            <td>'.$age.'</td>
            <td>'.$gender.'</td>
            <td>'.$course.'</td>
            
            <td style="display: flex; gap: 10px; align-items: center;">
            <button type="button" class="view-btn" style="padding:6px 10px; border-radius:4px; background:#17a2b8; color:#fff; border:none; cursor:pointer;">View</button>
            <button class = "update-btn"><a href="update_student.php?updateid='.$id.'" class = "#">Edit</a></button>
            <button class = "delete-btn"><a href="student_delete.php?deleteid='.$id.'" class = "#" onclick="return confirm(\'Are you sure you want to delete this student?\');">Delete</a></button>
            </td>
          </tr>';
        }
    }
    

    ?>


  </tbody>
</table>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('studentTable');
    const tbody = table.getElementsByTagName('tbody')[0];
    const rows = tbody.getElementsByTagName('tr');

    searchInput.addEventListener('keyup', function() {
        const filter = searchInput.value.toLowerCase();
        
        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            const cells = row.getElementsByTagName('td');
            const th = row.getElementsByTagName('th')[0];
            
            let found = false;
            
            // Search only in ID (th element)
            if (th) {
                const idText = th.textContent || th.innerText;
                if (idText.toLowerCase().indexOf(filter) > -1) {
                    found = true;
                }
            }
            
            // Search only in Name (first td element - index 0)
            if (!found && cells.length > 0) {
                const nameText = cells[0].textContent || cells[0].innerText;
                if (nameText.toLowerCase().indexOf(filter) > -1) {
                    found = true;
                }
            }
            
            // Show or hide the row based on search match
            row.style.display = found ? '' : 'none';
        }
    });
});
 </script>
<script>
// Modal for viewing student details (use event delegation)
document.addEventListener('DOMContentLoaded', function() {
  const table = document.getElementById('studentTable');
  const modal = document.getElementById('viewModal');
  const closeBtn = document.getElementById('closeModal');

  function openModalWithRow(row) {
    const th = row.querySelector('th');
    const tds = row.querySelectorAll('td');
    const setText = (id, value) => {
      const el = document.getElementById(id);
      if (el) el.textContent = value;
    };
    setText('view-student-id', th ? th.textContent.trim() : '');
    setText('view-name', tds[0] ? tds[0].textContent.trim() : '');
    setText('view-email', tds[1] ? tds[1].textContent.trim() : '');
    setText('view-age', tds[2] ? tds[2].textContent.trim() : '');
    setText('view-gender', tds[3] ? tds[3].textContent.trim() : '');
    setText('view-course', tds[4] ? tds[4].textContent.trim() : '');
    if (modal) modal.style.display = 'flex';
  }

  // Handle clicks on any View button via delegation
  if (table) {
    table.addEventListener('click', function(e) {
      const btn = e.target.closest('.view-btn');
      if (!btn) return;
      const row = btn.closest('tr');
      if (row) openModalWithRow(row);
    });
  }

  if (closeBtn) {
    closeBtn.addEventListener('click', function() {
      if (modal) modal.style.display = 'none';
    });
  }

  // Close modal when clicking outside the container
  if (modal) {
    modal.addEventListener('click', function(e) {
      if (e.target === modal) modal.style.display = 'none';
    });
  }
});

/* Modal markup inserted into DOM via static HTML below */
</script>

<!-- View Modal -->
<div id="viewModal" class="modal-overlay" style="display:none;">
  <div class="feedback-container">
    <div class="success-message">
      <div class="success-icon">ℹ</div>
      Student Details
    </div>
    <div class="details-container">
      <div class="details-title">Student Information</div>

      <div class="detail-row">
        <span class="detail-label">Student ID:</span>
        <span class="detail-value" id="view-student-id"></span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Name:</span>
        <span class="detail-value" id="view-name"></span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Email:</span>
        <span class="detail-value" id="view-email"></span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Age:</span>
        <span class="detail-value" id="view-age"></span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Gender:</span>
        <span class="detail-value" id="view-gender"></span>
      </div>

      <div class="detail-row">
        <span class="detail-label">Course:</span>
        <span class="detail-value" id="view-course"></span>
      </div>
    </div>

    <div class="home-button" style="text-align:center; margin-top:20px;">
      <button id="closeModal" class="container btn btn-secondary" style="background-color:#6c757d; color:white; padding:10px 18px; border-radius:6px; border:none;">Close</button>
    </div>
  </div>
</div>

<footer style="background-color: #333; color: white; text-align: center; padding: 20px; margin-top: 40px;">
  <p style="margin: 0;">© A&B University</p>
</footer>
</body>
</html>