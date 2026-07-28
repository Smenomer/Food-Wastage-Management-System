<?php
include 'connection.php';

if (isset($_POST['sign'])) {

    $username = $_POST['name'];
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $gender   = $_POST['gender'];

    $pass = password_hash($password, PASSWORD_DEFAULT);

    $sql = "SELECT * FROM login WHERE email='$email'";
    $result = mysqli_query($connection, $sql);

    if ($result === false) {
        die("❌ SQL ERROR: " . mysqli_error($connection) . " | Query: $sql");
    }

    $num = mysqli_num_rows($result);

    if ($num == 1) {
        $error_msg = "An account with this email already exists.";
    } else {
        $query = "INSERT INTO login(name, email, password, gender) 
                  VALUES('$username', '$email', '$pass', '$gender')";
        $query_run = mysqli_query($connection, $query);

        if ($query_run) {
            header("Location: signin.php");
            exit();
        } else {
            die("❌ Insert failed: " . mysqli_error($connection));
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Donate - Create Account</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #06C167;
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 2px, transparent 2px),
                radial-gradient(circle at 80% 80%, rgba(255,255,255,0.1) 2px, transparent 2px),
                radial-gradient(circle at 40% 20%, rgba(255,255,255,0.08) 1px, transparent 1px),
                radial-gradient(circle at 60% 90%, rgba(255,255,255,0.08) 1px, transparent 1px),
                linear-gradient(135deg, #06C167 0%, #048a4a 100%);
            background-size: 50px 50px, 80px 80px, 30px 30px, 60px 60px, 100% 100%;
            background-position: 0 0, 40px 40px, 20px 20px, 50px 50px, 0 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 15px;
            animation: bgMove 20s linear infinite;
        }

        @keyframes bgMove {
            0%   { background-position: 0 0, 40px 40px, 20px 20px, 50px 50px, 0 0; }
            100% { background-position: 50px 50px, 90px 90px, 50px 50px, 100px 100px, 0 0; }
        }

        .container {
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 55px 50px;
            border-radius: 30px;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.3);
            text-align: center;
            max-width: 520px;
            width: 100%;
            animation: slideUp 0.8s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(50px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .logo {
            font-size: 36px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 6px;
            animation: fadeIn 1s ease-out 0.2s both;
        }

        .logo .highlight {
            color: #06C167;
            position: relative;
            display: inline-block;
        }

        .logo .highlight::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 100%;
            height: 4px;
            background: #06C167;
            border-radius: 2px;
            animation: expand 0.8s ease-out 0.8s both;
        }

        @keyframes expand {
            from { width: 0; }
            to   { width: 100%; }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        .subtitle {
            font-size: 14px;
            color: #718096;
            margin-bottom: 10px;
            animation: fadeIn 1s ease-out 0.4s both;
        }

        .heading {
            font-size: 22px;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 28px;
            animation: fadeIn 1s ease-out 0.6s both;
        }

        /* Error message */
        .error-box {
            background: #fff5f5;
            border: 1px solid #fed7d7;
            color: #c53030;
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            animation: fadeIn 0.4s ease-out;
        }

        /* Form fields */
        .form-group {
            margin-bottom: 18px;
            text-align: left;
            animation: fadeIn 1s ease-out 0.7s both;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 7px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 16px;
            transition: color 0.3s;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 14px 16px 14px 44px;
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            font-size: 15px;
            font-family: 'Poppins', sans-serif;
            color: #2d3748;
            background: #f7fafc;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-group input:focus {
            border-color: #06C167;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(6, 193, 103, 0.12);
        }

        .form-group input:focus + i,
        .input-wrapper:focus-within i {
            color: #06C167;
        }

        /* Eye toggle */
        .eye-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #a0aec0;
            font-size: 18px;
            transition: color 0.3s;
            left: unset;
        }

        .eye-toggle:hover {
            color: #06C167;
        }

        /* Gender radio */
        .gender-group {
            margin-bottom: 24px;
            text-align: left;
            animation: fadeIn 1s ease-out 0.75s both;
        }

        .gender-group label.label-title {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .radio-options {
            display: flex;
            gap: 16px;
        }

        .radio-card {
            flex: 1;
        }

        .radio-card input[type="radio"] {
            display: none;
        }

        .radio-card label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 13px;
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            color: #718096;
            background: #f7fafc;
            transition: all 0.3s ease;
        }

        .radio-card input[type="radio"]:checked + label {
            border-color: #06C167;
            background: rgba(6, 193, 103, 0.08);
            color: #048a4a;
            box-shadow: 0 0 0 3px rgba(6, 193, 103, 0.12);
        }

        .radio-card label:hover {
            border-color: #06C167;
            color: #048a4a;
        }

        .radio-card label i {
            font-size: 18px;
        }

        /* Submit button */
        .submit-btn {
            width: 100%;
            padding: 16px;
            font-size: 17px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            color: #fff;
            background: linear-gradient(135deg, #06C167 0%, #048a4a 100%);
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px rgba(6, 193, 103, 0.35);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            animation: fadeIn 1s ease-out 0.85s both;
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: rgba(255,255,255,0.2);
            transition: left 0.5s ease;
        }

        .submit-btn:hover::before { left: 100%; }
        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(6, 193, 103, 0.45);
        }

        .submit-btn:active { transform: translateY(-1px); }

        .submit-btn i {
            font-size: 20px;
            width: 38px; height: 38px;
            display: flex; align-items: center; justify-content: center;
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .submit-btn:hover i {
            background: rgba(255,255,255,0.3);
            transform: scale(1.1) rotate(5deg);
        }

        /* Sign in link */
        .signin-link {
            margin-top: 24px;
            font-size: 14px;
            color: #718096;
            animation: fadeIn 1s ease-out 1s both;
        }

        .signin-link a {
            color: #06C167;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
        }

        .signin-link a:hover { color: #048a4a; text-decoration: underline; }

        /* Mobile */
        @media (max-width: 480px) {
            .container { padding: 40px 25px; }
            .logo { font-size: 28px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="logo">Food <span class="highlight">Donate</span></h1>
        <p class="subtitle">Making a difference, one meal at a time</p>
        <p class="heading">Create your account</p>

        <?php if (!empty($error_msg)): ?>
            <div class="error-box">
                <i class="fas fa-exclamation-circle"></i>
                <?= htmlspecialchars($error_msg) ?>
            </div>
        <?php endif; ?>

        <form action="" method="post">

            <!-- Username -->
            <div class="form-group">
                <label for="name">Username</label>
                <div class="input-wrapper">
                    <i class="uil uil-user"></i>
                    <input type="text" id="name" name="name" placeholder="Enter your name" required />
                </div>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email</label>
                <div class="input-wrapper">
                    <i class="uil uil-envelope"></i>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required />
                </div>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <i class="uil uil-lock"></i>
                    <input type="password" id="password" name="password" placeholder="Create a password" required />
                    <i class="uil uil-eye-slash eye-toggle" id="showpassword"></i>
                </div>
            </div>

            <!-- Gender -->
            <div class="gender-group">
                <label class="label-title">Gender</label>
                <div class="radio-options">
                    <div class="radio-card">
                        <input type="radio" name="gender" id="male" value="male" required />
                        <label for="male">
                            <i class="fas fa-mars"></i> Male
                        </label>
                    </div>
                    <div class="radio-card">
                        <input type="radio" name="gender" id="female" value="female" />
                        <label for="female">
                            <i class="fas fa-venus"></i> Female
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <button type="submit" name="sign" class="submit-btn">
                <i class="fas fa-user-plus"></i>
                <span>Create Account</span>
            </button>

        </form>

        <p class="signin-link">Already have an account? <a href="signin.php">Sign in</a></p>
    </div>

    <script>
        const toggleBtn = document.getElementById('showpassword');
        const passwordInput = document.getElementById('password');

        toggleBtn.addEventListener('click', () => {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleBtn.classList.replace('uil-eye-slash', 'uil-eye');
            } else {
                passwordInput.type = 'password';
                toggleBtn.classList.replace('uil-eye', 'uil-eye-slash');
            }
        });
    </script>
</body>
</html>