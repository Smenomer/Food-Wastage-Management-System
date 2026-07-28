<?php
include("login.php");
if ($_SESSION['name'] == '') {
    header("location: signin.php");
    exit();
}

$emailid    = $_SESSION['email'];
$connection = mysqli_connect("localhost", "root", "");
$db         = mysqli_select_db($connection, 'foodwaste_db1');
$success    = false;
$error      = false;

if (isset($_POST['submit'])) {
    $foodname = mysqli_real_escape_string($connection, $_POST['foodname']);
    $meal     = mysqli_real_escape_string($connection, $_POST['meal']);
    $category = $_POST['image-choice'];
    $quantity = mysqli_real_escape_string($connection, $_POST['quantity']);
    $phoneno  = mysqli_real_escape_string($connection, $_POST['phoneno']);
    $district = mysqli_real_escape_string($connection, $_POST['district']);
    $address  = mysqli_real_escape_string($connection, $_POST['address']);
    $name     = mysqli_real_escape_string($connection, $_POST['name']);

    $query     = "INSERT INTO food_donations(email,food,type,category,phoneno,location,address,name,quantity) VALUES('$emailid','$foodname','$meal','$category','$phoneno','$district','$address','$name','$quantity')";
    $query_run = mysqli_query($connection, $query);

    if ($query_run) {
        header("location: delivery.html");
        exit();
    } else {
        $error = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Donate - Donate Food</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 40px 15px;
            background: #06C167;
            background-image:
                radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 2px, transparent 2px),
                radial-gradient(circle at 80% 80%, rgba(255,255,255,0.1) 2px, transparent 2px),
                radial-gradient(circle at 40% 20%, rgba(255,255,255,0.08) 1px, transparent 1px),
                linear-gradient(135deg, #06C167 0%, #048a4a 100%);
            background-size: 50px 50px, 80px 80px, 30px 30px, 100% 100%;
            animation: bgMove 20s linear infinite;
        }

        @keyframes bgMove {
            0%   { background-position: 0 0, 40px 40px, 20px 20px, 0 0; }
            100% { background-position: 50px 50px, 90px 90px, 50px 50px, 0 0; }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
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
        .card {
            background: rgba(255,255,255,0.97);
            border-radius: 30px;
            box-shadow: 0 30px 80px rgba(0,0,0,0.25);
            padding: 50px 48px;
            width: 100%;
            max-width: 600px;
            animation: slideUp 0.8s ease-out;
        }

        /* ── LOGO ── */
        .logo {
            font-size: 30px;
            font-weight: 800;
            color: #2d3748;
            text-align: center;
            margin-bottom: 4px;
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
            background: #06C167; border-radius: 2px;
            animation: expand 0.8s ease-out 0.8s both;
        }

        .subtitle {
            text-align: center;
            font-size: 13px;
            color: #718096;
            margin-bottom: 8px;
            animation: fadeIn 1s ease-out 0.3s both;
        }

        .page-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
            animation: fadeIn 1s ease-out 0.4s both;
        }

        .page-badge span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(6,193,103,0.12);
            color: #048a4a;
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
        }

        /* ── SECTION DIVIDER ── */
        .section-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: #a0aec0;
            margin: 24px 0 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        /* ── FORM GROUPS ── */
        .form-group {
            margin-bottom: 18px;
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

        .input-wrapper { position: relative; }

        .input-wrapper .field-icon {
            position: absolute;
            left: 16px; top: 50%;
            transform: translateY(-50%);
            color: #a0aec0; font-size: 18px;
            pointer-events: none;
            transition: color 0.3s;
        }

        .input-wrapper:focus-within .field-icon { color: #06C167; }

        .form-group input[type="text"],
        .form-group input[type="tel"],
        .form-group textarea,
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
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            border-color: #06C167;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(6,193,103,0.12);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 85px;
        }

        .input-wrapper.textarea-wrap .field-icon {
            top: 18px; transform: none;
        }

        .form-group select { appearance: none; cursor: pointer; }

        .select-arrow {
            position: absolute;
            right: 16px; top: 50%;
            transform: translateY(-50%);
            color: #a0aec0; font-size: 13px;
            pointer-events: none;
        }

        /* Two-col grid for name/phone */
        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

        /* ── MEAL TYPE RADIO ── */
        .radio-row {
            display: flex; gap: 14px;
        }

        .radio-pill input[type="radio"] { display: none; }

        .radio-pill label {
            display: flex; align-items: center; gap: 8px;
            padding: 12px 24px;
            border: 2px solid #e2e8f0;
            border-radius: 50px;
            cursor: pointer;
            font-size: 14px; font-weight: 600;
            color: #718096;
            background: #f7fafc;
            transition: all 0.3s ease;
        }

        .radio-pill input[type="radio"]:checked + label {
            border-color: #06C167;
            background: rgba(6,193,103,0.08);
            color: #048a4a;
            box-shadow: 0 0 0 3px rgba(6,193,103,0.12);
        }

        .radio-pill label:hover { border-color: #06C167; color: #048a4a; }
        .radio-pill label i { font-size: 16px; }

        /* ── CATEGORY IMAGE RADIO ── */
        .image-radio-group {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
        }

        .image-radio-group input[type="radio"] { display: none; }

        .image-radio-group label {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            padding: 18px 10px;
            border: 2px solid #e2e8f0;
            border-radius: 18px;
            cursor: pointer;
            background: #f7fafc;
            transition: all 0.3s ease;
            font-size: 13px;
            font-weight: 600;
            color: #718096;
            text-align: center;
        }

        .image-radio-group label img {
            width: 60px; height: 60px;
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .image-radio-group label:hover {
            border-color: #06C167;
            color: #048a4a;
        }

        .image-radio-group label:hover img { transform: scale(1.1); }

        .image-radio-group input[type="radio"]:checked + label {
            border-color: #06C167;
            background: rgba(6,193,103,0.08);
            color: #048a4a;
            box-shadow: 0 0 0 3px rgba(6,193,103,0.12);
        }

        .image-radio-group input[type="radio"]:checked + label img {
            transform: scale(1.08);
        }

        /* Category names under images */
        .cat-name { font-size: 12px; font-weight: 700; }

        /* ── SUBMIT BUTTON ── */
        .submit-btn {
            width: 100%;
            padding: 16px;
            font-size: 17px; font-weight: 600;
            font-family: 'Poppins', sans-serif;
            color: #fff;
            background: linear-gradient(135deg, #06C167 0%, #048a4a 100%);
            border: none; border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px rgba(6,193,103,0.35);
            position: relative; overflow: hidden;
            display: flex; align-items: center; justify-content: center; gap: 10px;
            margin-top: 28px;
        }

        .submit-btn::before {
            content: '';
            position: absolute; top: 0; left: -100%;
            width: 100%; height: 100%;
            background: rgba(255,255,255,0.2);
            transition: left 0.5s ease;
        }

        .submit-btn:hover::before { left: 100%; }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(6,193,103,0.45);
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

        /* ── RESPONSIVE ── */
        @media (max-width: 560px) {
            .card { padding: 36px 22px; }
            .logo { font-size: 24px; }
            .two-col { grid-template-columns: 1fr; }
            .image-radio-group { grid-template-columns: repeat(3, 1fr); gap: 10px; }
            .image-radio-group label { padding: 14px 6px; }
            .image-radio-group label img { width: 44px; height: 44px; }
        }
    </style>
</head>
<body>
    <div class="card">

        <!-- Logo -->
        <div class="logo">Food <span class="highlight">Donate</span></div>
        <p class="subtitle">Making a difference, one meal at a time</p>

        <div class="page-badge">
            <span><i class="fas fa-hand-holding-heart"></i> &nbsp;Food Donation Form</span>
        </div>

        <?php if ($error): ?>
        <div class="error-box">
            <i class="fas fa-exclamation-circle"></i>
            Something went wrong. Please try again.
        </div>
        <?php endif; ?>

        <form action="" method="post">

            <!-- ── Food Details ── -->
            <div class="section-label">Food Details</div>

            <!-- Food Name -->
            <div class="form-group">
                <label for="foodname">Food Name</label>
                <div class="input-wrapper">
                    <i class="uil uil-restaurant field-icon"></i>
                    <input type="text" id="foodname" name="foodname" placeholder="e.g. Biryani, Rice, Bread" required />
                </div>
            </div>

            <!-- Meal Type -->
            <div class="form-group">
                <label>Meal Type</label>
                <div class="radio-row">
                    <div class="radio-pill">
                        <input type="radio" name="meal" id="veg" value="veg" required />
                        <label for="veg"><i class="fas fa-leaf"></i> Veg</label>
                    </div>
                    <div class="radio-pill">
                        <input type="radio" name="meal" id="nonveg" value="Non-veg" />
                        <label for="nonveg"><i class="fas fa-drumstick-bite"></i> Non-Veg</label>
                    </div>
                </div>
            </div>

            <!-- Category -->
            <div class="form-group">
                <label>Food Category</label>
                <div class="image-radio-group">
                    <div>
                        <input type="radio" id="raw-food" name="image-choice" value="raw-food" />
                        <label for="raw-food">
                            <img src="img/raw-food.png" alt="Raw Food" />
                            <span class="cat-name">Raw Food</span>
                        </label>
                    </div>
                    <div>
                        <input type="radio" id="cooked-food" name="image-choice" value="cooked-food" checked />
                        <label for="cooked-food">
                            <img src="img/cooked-food.png" alt="Cooked Food" />
                            <span class="cat-name">Cooked Food</span>
                        </label>
                    </div>
                    <div>
                        <input type="radio" id="packed-food" name="image-choice" value="packed-food" />
                        <label for="packed-food">
                            <img src="img/packed-food.png" alt="Packed Food" />
                            <span class="cat-name">Packed Food</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Quantity -->
            <div class="form-group">
                <label for="quantity">Quantity <span style="font-weight:400;text-transform:none;font-size:11px;color:#a0aec0">(no. of persons / kg)</span></label>
                <div class="input-wrapper">
                    <i class="uil uil-layers field-icon"></i>
                    <input type="text" id="quantity" name="quantity" placeholder="e.g. 10 persons or 5 kg" required />
                </div>
            </div>

            <!-- ── Contact Details ── -->
            <div class="section-label">Contact Details</div>

            <div class="two-col">
                <div class="form-group">
                    <label for="name">Your Name</label>
                    <div class="input-wrapper">
                        <i class="uil uil-user field-icon"></i>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($_SESSION['name']); ?>" required />
                    </div>
                </div>
                <div class="form-group">
                    <label for="phoneno">Phone Number</label>
                    <div class="input-wrapper">
                        <i class="uil uil-phone field-icon"></i>
                        <input type="tel" id="phoneno" name="phoneno" placeholder="10-digit number" maxlength="10" pattern="[0-9]{10}" required />
                    </div>
                </div>
            </div>

            <!-- ── Pickup Details ── -->
            <div class="section-label">Pickup Details</div>

            <!-- District -->
            <div class="form-group">
                <label for="district">District</label>
                <div class="input-wrapper">
                    <i class="uil uil-location-point field-icon"></i>
                    <select id="district" name="district">
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

            <!-- Address -->
            <div class="form-group">
                <label for="address">Pickup Address</label>
                <div class="input-wrapper textarea-wrap">
                    <i class="uil uil-map-pin field-icon"></i>
                    <textarea id="address" name="address" placeholder="Enter your full pickup address" required></textarea>
                </div>
            </div>

            <!-- Submit -->
            <button type="submit" name="submit" class="submit-btn">
                <i class="fas fa-hand-holding-heart"></i>
                <span>Submit Donation</span>
            </button>

        </form>
    </div>
</body>
</html>