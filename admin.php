<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<!-- title of the project -->
     <h1 style = "text-align:center; margin-top:30px;"> Student Registration System</h1>
    <?php
    if (isset($_GET['error']) && $_GET['error'] == 1) {
        echo '<p style="color: red; text-align: center; font-weight: bold; margin: 20px;">Wrong credentials. Please try again.</p>';
    }
    ?>
    <form action="login.php" method ="post">
            <label for="name">Username</label>
            <input type="text" name ="username"> <br>
            <label for="password">Password</label>
            <input type="password" name="password">
            <button type="submit">Login</button>
    </form>

</body>
</html>
