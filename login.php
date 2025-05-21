<?php 
include("config.php"); 
session_start();



if (isset($_POST["Login"])) {
    if (!empty($_POST["email"]) && !empty($_POST["password"])) {
        $email = mysqli_real_escape_string($conn, $_POST["email"]);
        $password = $_POST["password"];

        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION["email"] = $user["email"];
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["role"] = $user["role"]; 
                $_SESSION['name']=$user['name'];
              
                if ($user["role"] === 'admin') {
                    header("Location: admin_dashboard.php"); }
                     else { header("Location: user_homepage.php"); }
                     exit(); } 
                else { $error_message = "Invalid password. Please try again.";}
        } else {
       $error_message = "No user found with that email.";
        }
    } else {
        $error_message = "Please fill in both fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - Doodly</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gradient-to-b from-[#F9F6FF] via-[#EDFAFF] to-[#FFF0EF]">
  <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
    <img src="Doodly images/DOODLY.png" alt="Doodly Logo" class="mx-auto w-28 h-15" />
    <h2 class="text-2xl font-bold text-center text-gray-900">Welcome Back!</h2>

    <!--Afficher Message d'erreur (mdp ou login incorrect) -->
    <?php if (!empty($error_message)): ?>
      <div class="text-red-500 text-center mb-4"><?= $error_message ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST" class="space-y-4 mt-6">
      <div>
        <label for="email" class="block text-gray-600">Email Address</label>
        <input type="email" id="email" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" />
      </div>
      <div>
        <label for="password" class="block text-gray-600">Password</label>
        <input type="password" id="password" name="password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" />
      </div>
      <button type="submit" name="Login" class="w-full py-2 bg-[#C1A2FF] text-white rounded-full hover:bg-purple-400 transition">Login</button>
    </form>
    <p class="text-center text-gray-600 mt-4">
      Don't have an account?
      <a href="signup.php" class="text-purple-500">Sign Up</a>
    </p>
  </div>
</body>
</html>
