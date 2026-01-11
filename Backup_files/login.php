<?php
session_start();
include("connections.php");

// Google OAuth2 config
$google_client_id = "YOUR_GOOGLE_CLIENT_ID.apps.googleusercontent.com"; // replace this
$google_redirect_uri = "http://localhost/google_login_callback.php"; // adjust for your domain
$google_auth_url = "https://accounts.google.com/o/oauth2/v2/auth";
$google_scope = "https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile";
$google_login_url = $google_auth_url . "?client_id={$google_client_id}&redirect_uri={$google_redirect_uri}&response_type=code&scope=" . urlencode($google_scope);

$Email = $Emailerr = "";
$password = $passwordErr = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // --- reCAPTCHA ---
    if (empty($_POST['g-recaptcha-response'])) {
        $passwordErr = "Please verify that you are not a robot.";
    } else {
        $recaptcha = $_POST['g-recaptcha-response'];
        $secretKey = "6LcyveArAAAAAHeKsCOpPLf6cj4Vzqk7KZb2FEZ5";
        $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$recaptcha}");
        $captchaSuccess = json_decode($verify);
        if (!$captchaSuccess->success) {
            $passwordErr = "Captcha verification failed!";
        }
    }

    // --- Input validation ---
    if (empty($_POST["Email"])) $Emailerr = "Email is required!";
    else $Email = trim($_POST["Email"]);

    if (empty($_POST["password"])) $passwordErr = "Password is required!";
    else $password = trim($_POST["password"]);

    // --- Login process ---
    if ($Email && $password && empty($Emailerr) && empty($passwordErr)) {
        $stmt = $connections->prepare("SELECT * FROM login_accounts WHERE email = ?");
        $stmt->bind_param("s", $Email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $db_password = $row["password_hash"];
            $db_account_type = strtolower(trim($row["account_type"]));

            // Support SHA2 hash or password_hash
            if (hash('sha256', $password) === $db_password || password_verify($password, $db_password)) {
                $_SESSION["login-status"] = "Valid";
                $_SESSION["user_email"] = $row["email"];
                $_SESSION["user_role"] = $db_account_type;

                if ($db_account_type === "admin") {
                    header("Location: admin/dashboard.php");
                } elseif ($db_account_type === "employee") {
                    header("Location: employee/dashboard.php");
                } else {
                    header("Location: public/dashboard.php");
                }
                exit();
            } else {
                $passwordErr = "Incorrect password!";
            }
        } else {
            $Emailerr = "Email not found!";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>HR1 — Login</title>
  <link rel="icon" href="img/logo2.png">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>

  <style>
    :root {
      --blue-600: #1b2f73;
      --blue-800: #0f1c49;
      --blue-a: #2342a6;
      --gold: #d4af37;
      --ink: #0f172a;
      --card-bg: rgba(255,255,255,.95);
      --card-border: rgba(226,232,240,.9);
    }
    body {
      min-height: 100vh;
      margin: 0;
      color: var(--ink);
      background: linear-gradient(140deg, rgba(15,28,73,1) 50%, rgba(255,255,255,1) 50%);
    }
    .card {
      background: var(--card-bg);
      border: 1px solid var(--card-border);
      border-radius: 18px;
      box-shadow: 0 16px 48px rgba(2,6,23,.18);
    }
    .input {
      width: 100%;
      border: 1px solid #e5e7eb;
      border-radius: 12px;
      padding: 1rem .95rem;
    }
    .btn {
      width: 100%;
      background: linear-gradient(180deg, var(--blue-600), var(--blue-800));
      color: #fff;
      font-weight: 800;
      border-radius: 14px;
      padding: .95rem 1rem;
    }
    .error-message {
      color: #b91c1c;
      font-size: .85rem;
      display: block;
      margin-top: .25rem;
    }
    .text-gold { color: var(--gold); }
  </style>
</head>

<body class="grid md:grid-cols-2 gap-0 place-items-center p-6 md:p-10">

  <!-- Left panel -->
  <section class="hidden md:flex w-full h-full items-center justify-center">
    <div class="max-w-lg text-white px-6">
      <img src="img/logo.png" alt="ATIERA" class="w-56 mb-6 drop-shadow-xl">
      <h1 class="text-4xl font-extrabold leading-tight tracking-tight">
        HR1 <span class="text-gold">Talent</span> Portal
      </h1>
      <p class="mt-4 text-white/90 text-lg">Secure • Fast • Intuitive</p>
    </div>
  </section>

  <!-- Right: Login -->
  <main class="w-full max-w-md md:ml-auto">
    <div class="card p-6 sm:p-8 mt-16">
      <h3 class="text-lg sm:text-xl font-semibold mb-1">Login</h3>
      <p class="text-sm text-slate-500 mb-5">Access your account below.</p>

      <!-- Google Login button -->
      <a href="<?php echo htmlspecialchars($google_login_url); ?>" 
         class="flex items-center justify-center gap-3 bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg mb-4 transition">
        <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5" alt="">
        Continue with Google
      </a>

      <!-- Divider -->
      <div class="flex items-center my-4">
        <hr class="flex-1 border-gray-300">
        <span class="px-3 text-gray-500 text-sm">or</span>
        <hr class="flex-1 border-gray-300">
      </div>

      <form method="POST" action="" class="space-y-4">
        <div>
          <input type="text" name="Email" class="input" placeholder="Email" value="<?php echo htmlspecialchars($Email); ?>" required>
          <?php if (!empty($Emailerr)) echo "<span class='error-message'>$Emailerr</span>"; ?>
        </div>
        <div>
          <input type="password" name="password" class="input" placeholder="Password" required>
          <?php if (!empty($passwordErr) && strpos($passwordErr, 'Captcha') === false) echo "<span class='error-message'>$passwordErr</span>"; ?>
        </div>

        <div class="g-recaptcha" data-sitekey="6LcyveArAAAAANi6N7dPGx2-ZGvAf0N10ZKnAkNF"></div>
        <?php if (!empty($passwordErr) && strpos($passwordErr, 'Captcha') !== false) echo "<span class='error-message'>$passwordErr</span>"; ?>

        <button type="submit" class="btn">Login</button>
      </form>

      <p class="text-xs text-center text-slate-500 mt-3">Don’t have an account? 
        <a href="signup.php" class="text-blue-700 font-semibold">Register here</a>
      </p>
    </div>
  </main>
</body>
</html>
