<?php
include '../connection.php';
$msg = 0;
$account_error = false;

if (isset($_POST['sign'])) {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $location = $_POST['district'];
    $address  = $_POST['address'];

    $pass   = password_hash($password, PASSWORD_DEFAULT);
    $sql    = "SELECT * FROM admin WHERE email='$email'";
    $result = mysqli_query($connection, $sql);
    $num    = mysqli_num_rows($result);

    if ($num == 1) {
        $account_error = true;
    } else {
        $query     = "INSERT INTO admin(name,email,password,location,address) VALUES('$username','$email','$pass','$location','$address')";
        $query_run = mysqli_query($connection, $query);
        if ($query_run) {
            header("Location: signin.php");
            exit();
        } else {
            echo '<script>alert("Data not saved. Please try again.")</script>';
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
    <title>Food Donate - Admin Register</title>
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

            /* Purple-admin gradient to distinguish from user pages */
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
            padding: 55px 50px;
            width: 100%;
            max-width: 540px;
            animation: slideUp 0.8s ease-out;
        }

        /* ── LOGO ── */
        .logo {
            font-size: 32px;
            font-weight: 700;
            color: #2d3748;
            text-align: center;
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
            text-align: center;
            font-size: 13px;
            color: #718096;
            margin-bottom: 8px;
            animation: fadeIn 1s ease-out 0.3s both;
        }

        /* Admin badge */
        .admin-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 28px;
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

        /* ── ERROR BOX ── */
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

        /* ── FORM GROUPS ── */
        .form-group {
            margin-bottom: 18px;
            text-align: left;
            animation: fadeIn 1s ease-out 0.5s both;
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
            padding: 13px 16px 13px 46px;
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

        /* Textarea */
        .form-group textarea {
            width: 100%;
            padding: 13px 16px 13px 46px;
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            font-size: 15px;
            font-family: 'Poppins', sans-serif;
            color: #2d3748;
            background: #f7fafc;
            transition: all 0.3s ease;
            outline: none;
            resize: vertical;
            min-height: 90px;
        }

        .form-group textarea:focus {
            border-color: #667eea;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.12);
        }

        /* Textarea icon sits at top */
        .input-wrapper.textarea-wrap .field-icon {
            top: 18px;
            transform: none;
        }

        /* Select */
        .form-group select {
            width: 100%;
            padding: 13px 16px 13px 46px;
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            font-size: 15px;
            font-family: 'Poppins', sans-serif;
            color: #2d3748;
            background: #f7fafc;
            transition: all 0.3s ease;
            outline: none;
            appearance: none;
            cursor: pointer;
        }

        .form-group select:focus {
            border-color: #667eea;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.12);
        }

        .select-arrow {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 14px;
            pointer-events: none;
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
            margin-top: 6px;
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

        /* ── LOGIN LINK ── */
        .login-link-wrap {
            margin-top: 24px;
            text-align: center;
            font-size: 14px;
            color: #718096;
            animation: fadeIn 1s ease-out 0.8s both;
        }

        .login-link-wrap a {
            color: #667eea;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
        }

        .login-link-wrap a:hover {
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

        <!-- Logo -->
        <div class="logo">Food <span class="highlight">Donate</span></div>
        <p class="subtitle">Making a difference, one meal at a time</p>

        <!-- Admin badge -->
        <div class="admin-badge">
            <span><i class="fas fa-user-cog"></i> Admin Registration</span>
        </div>

        <!-- Error -->
        <?php if ($account_error): ?>
        <div class="error-box">
            <i class="fas fa-exclamation-circle"></i>
            An account with this email already exists.
        </div>
        <?php endif; ?>

        <form action="" method="post" id="form">

            <!-- Name -->
            <div class="form-group">
                <label for="username">Full Name</label>
                <div class="input-wrapper">
                    <i class="uil uil-user field-icon"></i>
                    <input type="text" id="username" name="username" placeholder="Enter your name" required />
                </div>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email</label>
                <div class="input-wrapper">
                    <i class="uil uil-envelope field-icon"></i>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required />
                </div>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <i class="uil uil-lock field-icon"></i>
                    <input type="password" id="password" name="password" placeholder="Create a password" required />
                    <i class="uil uil-eye-slash eye-toggle" id="showpassword"></i>
                </div>
            </div>

            <!-- Address -->
            <div class="form-group">
                <label for="address">Address</label>
                <div class="input-wrapper textarea-wrap">
                    <i class="uil uil-map-pin field-icon"></i>
                    <textarea id="address" name="address" placeholder="Enter your full address" required></textarea>
                </div>
            </div>

            <!-- District -->
            <div class="form-group">
                <label for="district">District</label>
                <div class="input-wrapper">
                    <i class="uil uil-location-point field-icon"></i>
                    <select id="district" name="district">
                        <option value="chennai">Chennai</option>
                        <option value="kancheepuram">Kancheepuram</option>
                        <option value="thiruvallur">Thiruvallur</option>
                        <option value="vellore">Vellore</option>
                        <option value="tiruvannamalai">Tiruvannamalai</option>
                        <option value="tiruvallur">Tiruvallur</option>
                        <option value="tiruppur">Tiruppur</option>
                        <option value="coimbatore">Coimbatore</option>
                        <option value="erode">Erode</option>
                        <option value="salem">Salem</option>
                        <option value="namakkal">Namakkal</option>
                        <option value="tiruchirappalli">Tiruchirappalli</option>
                        <option value="thanjavur">Thanjavur</option>
                        <option value="pudukkottai">Pudukkottai</option>
                        <option value="karur">Karur</option>
                        <option value="ariyalur">Ariyalur</option>
                        <option value="perambalur">Perambalur</option>
                        <option value="madurai" selected>Madurai</option>
                        <option value="virudhunagar">Virudhunagar</option>
                        <option value="dindigul">Dindigul</option>
                        <option value="ramanathapuram">Ramanathapuram</option>
                        <option value="sivaganga">Sivaganga</option>
                        <option value="thoothukkudi">Thoothukkudi</option>
                        <option value="tirunelveli">Tirunelveli</option>
                        <option value="tenkasi">Tenkasi</option>
                        <option value="kanniyakumari">Kanniyakumari</option>
                    </select>
                    <i class="fas fa-chevron-down select-arrow"></i>
                </div>
            </div>

            <!-- Submit -->
            <button type="submit" name="sign" class="submit-btn">
                <i class="fas fa-user-cog"></i>
                <span>Create Admin Account</span>
            </button>

        </form>

        <p class="login-link-wrap">Already a member? <a href="signin.php">Login Now</a></p>

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