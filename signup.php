<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $check_email_sql = "SELECT * FROM users WHERE email = '$email'";
    $check_name_sql = "SELECT * FROM users WHERE name = '$name'";
    
    $email_result = mysqli_query($conn, $check_email_sql);
    $name_result = mysqli_query($conn, $check_name_sql);

    if (mysqli_num_rows($email_result) > 0) {
        $error_message = "Email is already taken. Please choose another one.";
    } elseif (mysqli_num_rows($name_result) > 0) {
        $error_message = "Username is already taken. Please choose another one.";
    } else {
        // Hashing password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        
        $insert_sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashed_password')";
        
        if (mysqli_query($conn, $insert_sql)) {
            $success_message = "Account created successfully! <a href='login.php' class='text-purple-500'>Login here</a>";
        } else {
            $error_message = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign Up - Doodly</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gradient-to-b from-[#F9F6FF] via-[#EDFAFF] to-[#FFF0EF]">
  <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
    
    <!-- Error or Success Message -->
    <div class="mb-4">
      <?php if (!empty($error_message)) : ?>
        <div class="w-full bg-red-100 text-red-600 p-4 rounded-lg text-center">
          <?php echo $error_message; ?>
        </div>
      <?php elseif (!empty($success_message)) : ?>
        <div class="w-full bg-green-100 text-green-600 p-4 rounded-lg text-center">
          <?php echo $success_message; ?>
        </div>
      <?php endif; ?>
    </div>

    <img src="Doodly images/DOODLY.png" alt="Doodly Logo" class="mx-auto w-28 h-15" />
    <h2 class="text-2xl font-bold text-center text-gray-900">Create an Account</h2>

    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST" class="space-y-4 mt-6">
      <div>
        <label for="name" class="block text-gray-600">Username</label>
        <input type="text" id="name" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" />
      </div>
      <div>
        <label for="email" class="block text-gray-600">Email Address</label>
        <input type="email" id="email" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" />
      </div>
      <div>
        <label for="password" class="block text-gray-600">Password</label>
        <input type="password" id="password" name="password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" />
      </div>
      <button type="submit" class="w-full py-2 bg-[#C1A2FF] text-white rounded-full hover:bg-purple-400 transition">Sign Up</button>
    </form>
    <p class="text-center text-gray-600 mt-4">
      Already have an account?
      <a href="login.php" class="text-purple-500">Login</a>
    </p>
  </div>

</body>
</html>
