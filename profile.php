<?php
include("login.php");

if ($_SESSION['name'] == '') {
    header("location: signup.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Donate - Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --green: #06C167;
            --green-dark: #048a4a;
            --green-light: rgba(6, 193, 103, 0.12);
            --text-dark: #1a202c;
            --text-mid: #4a5568;
            --text-light: #718096;
            --white: #ffffff;
            --off-white: #f7faf8;
            --border: #e2e8f0;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--off-white);
            color: var(--text-dark);
            min-height: 100vh;
        }

        /* ── HEADER ── */
        header {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 40px;
            height: 70px;
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(14px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
        }

        .logo {
            font-size: 26px;
            font-weight: 800;
            color: var(--text-dark);
        }

        .logo span { color: var(--green); }

        nav.nav-bar ul {
            list-style: none;
            display: flex;
            gap: 10px;
        }

        nav.nav-bar ul li a {
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            color: var(--text-mid);
            padding: 8px 18px;
            border-radius: 50px;
            transition: all 0.25s ease;
        }

        nav.nav-bar ul li a:hover,
        nav.nav-bar ul li a.active {
            background: var(--green);
            color: #fff;
        }

        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
        }

        .hamburger .line {
            width: 26px; height: 3px;
            background: var(--text-dark);
            border-radius: 3px;
            transition: all 0.3s ease;
        }

        /* ── PAGE WRAPPER ── */
        .page-wrapper {
            padding-top: 100px;
            padding-bottom: 60px;
            min-height: 100vh;
            background: var(--off-white);
            background-image:
                radial-gradient(circle at 10% 20%, rgba(6,193,103,0.06) 0%, transparent 50%),
                radial-gradient(circle at 90% 80%, rgba(6,193,103,0.04) 0%, transparent 50%);
        }

        .container {
            max-width: 860px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* ── PROFILE HERO CARD ── */
        .profile-card {
            background: var(--white);
            border-radius: 28px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            overflow: hidden;
            margin-bottom: 28px;
            animation: slideUp 0.7s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Banner strip */
        .profile-banner {
            height: 110px;
            background: linear-gradient(135deg, #06C167 0%, #048a4a 100%);
            background-image:
                radial-gradient(circle at 20% 50%, rgba(255,255,255,0.12) 2px, transparent 2px),
                radial-gradient(circle at 80% 30%, rgba(255,255,255,0.1) 2px, transparent 2px),
                linear-gradient(135deg, #06C167 0%, #048a4a 100%);
            background-size: 40px 40px, 60px 60px, 100% 100%;
            animation: bgMove 20s linear infinite;
            position: relative;
        }

        @keyframes bgMove {
            0%   { background-position: 0 0, 30px 30px, 0 0; }
            100% { background-position: 40px 40px, 70px 70px, 0 0; }
        }

        /* Avatar */
        .avatar-wrap {
            position: absolute;
            bottom: -40px;
            left: 40px;
        }

        .avatar {
            width: 80px; height: 80px;
            border-radius: 50%;
            background: var(--white);
            border: 4px solid var(--white);
            box-shadow: 0 6px 20px rgba(0,0,0,0.12);
            display: flex; align-items: center; justify-content: center;
            font-size: 32px;
            color: var(--green);
        }

        /* Profile body */
        .profile-body {
            padding: 56px 40px 36px;
        }

        .profile-name {
            font-size: 26px;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .profile-email-tag {
            font-size: 14px;
            color: var(--text-light);
            margin-bottom: 28px;
        }

        /* Info grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 28px;
        }

        .info-chip {
            background: var(--off-white);
            border: 1.5px solid var(--border);
            border-radius: 16px;
            padding: 16px 20px;
            transition: all 0.3s ease;
        }

        .info-chip:hover {
            border-color: var(--green);
            box-shadow: 0 4px 16px rgba(6,193,103,0.1);
        }

        .info-chip .chip-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--text-light);
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .info-chip .chip-label i {
            color: var(--green);
            font-size: 13px;
        }

        .info-chip .chip-value {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-dark);
        }

        /* Logout button */
        .logout-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            color: #e53e3e;
            background: #fff5f5;
            border: 1.5px solid #fed7d7;
            padding: 10px 22px;
            border-radius: 50px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: #e53e3e;
            border-color: #e53e3e;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(229,62,62,0.25);
        }

        /* ── DONATIONS CARD ── */
        .donations-card {
            background: var(--white);
            border-radius: 28px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            padding: 36px 40px;
            animation: slideUp 0.7s ease-out 0.15s both;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
        }

        .card-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-title i {
            width: 38px; height: 38px;
            background: var(--green-light);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            color: var(--green);
            font-size: 16px;
        }

        .donation-count {
            background: var(--green-light);
            color: var(--green-dark);
            font-size: 13px;
            font-weight: 700;
            padding: 5px 14px;
            border-radius: 50px;
        }

        /* Table */
        .table-wrapper {
            overflow-x: auto;
            border-radius: 16px;
            border: 1.5px solid var(--border);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead tr {
            background: var(--off-white);
        }

        thead th {
            padding: 14px 20px;
            text-align: left;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: var(--text-light);
            border-bottom: 1.5px solid var(--border);
        }

        tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background 0.2s ease;
        }

        tbody tr:last-child { border-bottom: none; }

        tbody tr:hover { background: var(--off-white); }

        tbody td {
            padding: 14px 20px;
            font-size: 14px;
            color: var(--text-mid);
            font-weight: 500;
        }

        tbody td:first-child {
            font-weight: 600;
            color: var(--text-dark);
        }

        /* Type badge */
        .badge {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-veg   { background: #f0fff4; color: #276749; border: 1px solid #9ae6b4; }
        .badge-nonveg { background: #fff5f5; color: #c53030; border: 1px solid #feb2b2; }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-light);
        }

        .empty-state i {
            font-size: 48px;
            color: var(--border);
            margin-bottom: 16px;
            display: block;
        }

        .empty-state p {
            font-size: 15px;
            margin-bottom: 20px;
        }

        .donate-cta {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            color: #fff;
            background: linear-gradient(135deg, var(--green), var(--green-dark));
            padding: 11px 24px;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(6,193,103,0.3);
        }

        .donate-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(6,193,103,0.4);
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 700px) {
            header { padding: 0 20px; }
            .hamburger { display: flex; }
            nav.nav-bar {
                display: none;
                position: fixed;
                top: 70px; left: 0; right: 0;
                background: rgba(255,255,255,0.97);
                backdrop-filter: blur(10px);
                padding: 20px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            }
            nav.nav-bar.active { display: block; }
            nav.nav-bar ul { flex-direction: column; gap: 6px; }
            nav.nav-bar ul li a { display: block; text-align: center; }

            .profile-body { padding: 52px 20px 28px; }
            .avatar-wrap  { left: 20px; }
            .info-grid    { grid-template-columns: 1fr 1fr; }
            .donations-card { padding: 28px 20px; }
        }

        @media (max-width: 460px) {
            .info-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <!-- ── HEADER ── -->
    <header>
        <div class="logo">Food <span>Donate</span></div>
        <div class="hamburger" id="hamburger">
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
        </div>
        <nav class="nav-bar" id="navBar">
            <ul>
                <li><a href="home.html">Home</a></li>
                <li><a href="about.html">About</a></li>
                <li><a href="contact.html">Contact</a></li>
                <li><a href="profile.php" class="active">Profile</a></li>
            </ul>
        </nav>
    </header>

    <!-- ── MAIN ── -->
    <div class="page-wrapper">
        <div class="container">

            <!-- Profile Card -->
            <div class="profile-card">
                <div class="profile-banner">
                    <div class="avatar-wrap">
                        <div class="avatar">
                            <?php
                                $gender = strtolower($_SESSION['gender'] ?? 'male');
                                echo $gender === 'female' ? '👩' : '👨';
                            ?>
                        </div>
                    </div>
                </div>

                <div class="profile-body">
                    <div class="profile-name"><?php echo htmlspecialchars($_SESSION['name']); ?></div>
                    <div class="profile-email-tag"><?php echo htmlspecialchars($_SESSION['email']); ?></div>

                    <div class="info-grid">
                        <div class="info-chip">
                            <div class="chip-label"><i class="fas fa-user"></i> Name</div>
                            <div class="chip-value"><?php echo htmlspecialchars($_SESSION['name']); ?></div>
                        </div>
                        <div class="info-chip">
                            <div class="chip-label"><i class="fas fa-envelope"></i> Email</div>
                            <div class="chip-value" style="font-size:14px;"><?php echo htmlspecialchars($_SESSION['email']); ?></div>
                        </div>
                        <div class="info-chip">
                            <div class="chip-label"><i class="fas fa-venus-mars"></i> Gender</div>
                            <div class="chip-value"><?php echo htmlspecialchars(ucfirst($_SESSION['gender'])); ?></div>
                        </div>
                    </div>

                    <a href="logout.php" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>

            <!-- Donations Card -->
            <div class="donations-card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fas fa-hand-holding-heart"></i>
                        Your Donations
                    </div>
                    <?php
                        $email  = $_SESSION['email'];
                        $query  = "SELECT * FROM food_donations WHERE email='$email'";
                        $result = mysqli_query($connection, $query);
                        $count  = $result ? mysqli_num_rows($result) : 0;
                        echo '<span class="donation-count">' . $count . ' donation' . ($count !== 1 ? 's' : '') . '</span>';
                    ?>
                </div>

                <?php if ($count > 0): ?>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Food Item</th>
                                <th>Type</th>
                                <th>Category</th>
                                <th>Date / Time</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            mysqli_data_seek($result, 0);
                            while ($row = mysqli_fetch_assoc($result)):
                                $type      = htmlspecialchars($row['type']);
                                $badgeClass = (strtolower($type) === 'veg') ? 'badge-veg' : 'badge-nonveg';
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['food']); ?></td>
                                <td><span class="badge <?php echo $badgeClass; ?>"><?php echo $type; ?></span></td>
                                <td><?php echo htmlspecialchars($row['category']); ?></td>
                                <td><?php echo htmlspecialchars($row['date']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-box-open"></i>
                    <p>You haven't made any donations yet.</p>
                    <a href="fooddonateform.php" class="donate-cta">
                        <i class="fas fa-hand-holding-heart"></i> Donate Now
                    </a>
                </div>
                <?php endif; ?>
            </div>

        </div>
    </div>

    <script>
        const hamburger = document.getElementById('hamburger');
        const navBar    = document.getElementById('navBar');

        hamburger.addEventListener('click', () => navBar.classList.toggle('active'));
        document.querySelectorAll('.nav-bar a').forEach(link =>
            link.addEventListener('click', () => navBar.classList.remove('active'))
        );
    </script>
</body>
</html>