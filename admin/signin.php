<?php
session_start();
include '../connection.php';

$msg = 0;
$account_error = false;

if (isset($_POST['sign'])) {
    $email    = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);

    $sql    = "SELECT * FROM admin WHERE email='$email'";
    $result = mysqli_query($connection, $sql);
    $num    = mysqli_num_rows($result);

    if ($num == 1) {
        while ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['email']    = $email;
                $_SESSION['name']     = $row['name'];
                $_SESSION['location'] = $row['location'];
                $_SESSION['Aid']      = $row['Aid'];
                header("Location: admin.php");
                exit();
            } else {
                $msg = 1;
            }
        }
    } else {
        $account_error = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Donate - Admin Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-image:
                radial-gradient(circle at 20% 50%, rgba(255,255,255,0.08) 2px, transparent 2px),
                radial-gradient(circle at 80% 80%, rgba(255,255,255,0.08) 2px, transparent 2px),
                radial-gradient(circle at 40% 20%, rgba(255,255,255,0.06) 1px, transparent 1px),
                linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-size: 50px 50px, 80px 80px, 30px 30px, 100% 100%;
            animation: bgMove 20s linear infinite;
        }

        @keyframes bgMove {
            0%   { background-position: 0 0, 40px 40px, 20px 20px, 0 0; }
            100% { background-position: 50px 50px, 90px 90px, 50px 50px, 0 0; }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(50px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        @keyframes expand {
            from { width: 0; }
            to   { width: 100%; }
        }

        /* ── CARD ── */
        .container {
            background: rgba(255,255,255,0.96);
            backdrop-filter: blur(10px);
            border-radius: 30px;
            box-shadow: 0 30px 80px rgba(0,0,0,0.3);
            padding: 60px 50px;
            width: 100%;
            max-width: 480px;
            animation: slideUp 0.8s ease-out;
            text-align: center;
        }

        /* ── LOGO ── */
        .logo {
            font-size: 32px;
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
            bottom: -4px; left: 0;
            width: 100%; height: 4px;
            background: #06C167;
            border-radius: 2px;
            animation: expand 0.8s ease-out 0.8s both;
        }

        .subtitle {
            font-size: 13px;
            color: #718096;
            margin-bottom: 10px;
            animation: fadeIn 1s ease-out 0.3s both;
        }

        /* Admin badge */
        .admin-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            animation: fadeIn 1s ease-out 0.4s both;
        }

        .admin-badge span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 5px 16px;
            border-radius: 50px;
        }

        .heading {
            font-size: 20px;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 28px;
            animation: fadeIn 1s ease-out 0.5s both;
        }

        /* ── ERROR BOXES ── */
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
            text-align: left;
            animation: fadeIn 0.4s ease-out;
        }

        .error-box i { flex-shrink: 0; font-size: 16px; }

        /* ── FORM GROUPS ── */
        .form-group {
            margin-bottom: 18px;
            text-align: left;
            animation: fadeIn 1s ease-out 0.55s both;
        }

        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #4a5568;
            margin-bottom: 7px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper .field-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 18px;
            transition: color 0.3s;
            pointer-events: none;
        }

        .input-wrapper:focus-within .field-icon {
            color: #667eea;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 14px 48px 14px 46px;
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
            border-color: #667eea;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.12);
        }

        .form-group input.input-error {
            border-color: #fc8181;
            background: #fff5f5;
        }

        /* Eye toggle */
        .eye-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #a0aec0;
            font-size: 20px;
            transition: color 0.3s;
        }

        .eye-toggle:hover { color: #667eea; }

        /* ── SUBMIT BUTTON ── */
        .submit-btn {
            width: 100%;
            padding: 16px;
            font-size: 17px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            color: #fff;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.35);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 8px;
            animation: fadeIn 1s ease-out 0.7s both;
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: rgba(255,255,255,0.15);
            transition: left 0.5s ease;
        }

        .submit-btn:hover::before { left: 100%; }
        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.45);
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

        /* ── REGISTER LINK ── */
        .register-link {
            margin-top: 24px;
            font-size: 14px;
            color: #718096;
            animation: fadeIn 1s ease-out 0.85s both;
        }

        .register-link a {
            color: #667eea;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
        }

        .register-link a:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 480px) {
            .container { padding: 40px 22px; }
            .logo { font-size: 26px; }
        }
    </style>
</head>
<body>
    <div class="container">

        <div class="logo">Food <span class="highlight">Donate</span></div>
        <p class="subtitle">Making a difference, one meal at a time</p>

        <div class="admin-badge">
            <span><i class="fas fa-user-cog"></i> Admin Portal</span>
        </div>

        <p class="heading">Welcome back!</p>

        <!-- Account not found -->
        <?php if ($account_error): ?>
        <div class="error-box">
            <i class="fas fa-user-slash"></i>
            No admin account found with that email.
        </div>
        <?php endif; ?>

        <!-- Wrong password -->
        <?php if ($msg == 1): ?>
        <div class="error-box">
            <i class="fas fa-exclamation-circle"></i>
            Incorrect password. Please try again.
        </div>
        <?php endif; ?>

        <form action="" method="post" id="form">

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email</label>
                <div class="input-wrapper">
                    <i class="uil uil-envelope field-icon"></i>
                    <input
                        type="text"
                        id="email"
                        name="email"
                        placeholder="Enter your email"
                        required
                        <?= $account_error ? 'class="input-error"' : '' ?>
                    />
                </div>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <i class="uil uil-lock field-icon"></i>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                        required
                        <?= $msg == 1 ? 'class="input-error"' : '' ?>
                    />
                    <i class="uil uil-eye-slash eye-toggle" id="showpassword"></i>
                </div>
            </div>

            <!-- Submit -->
            <button type="submit" name="sign" class="submit-btn">
                <i class="fas fa-sign-in-alt"></i>
                <span>Login</span>
            </button>

        </form>

        <p class="register-link">Don't have an account? <a href="signup.php">Register</a></p>

    </div>

    <script>
        const toggleBtn     = document.getElementById('showpassword');
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