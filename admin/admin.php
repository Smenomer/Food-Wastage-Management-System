<?php
ob_start();
include("connect.php");

if ($_SESSION['name'] == '') {
    header("location: signin.php");
    exit();
}

$connection = mysqli_connect("localhost:3306", "root", "");
$db = mysqli_select_db($connection, 'foodwaste_db1');

$loc = $_SESSION['location'];
$id  = $_SESSION['Aid'];

// Handle "Get Food" assignment
if (isset($_POST['food']) && isset($_POST['delivery_person_id'])) {
    $order_id           = $_POST['order_id'];
    $delivery_person_id = $_POST['delivery_person_id'];

    $check  = "SELECT * FROM food_donations WHERE Fid = $order_id AND assigned_to IS NOT NULL";
    $cresult = mysqli_query($connection, $check);

    if (mysqli_num_rows($cresult) > 0) {
        die("Sorry, this order has already been assigned to someone else.");
    }

    $update = "UPDATE food_donations SET assigned_to = $delivery_person_id WHERE Fid = $order_id";
    mysqli_query($connection, $update);

    header('Location: ' . $_SERVER['REQUEST_URI']);
    ob_end_flush();
    exit();
}

// Stats
$total_users     = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) as count FROM login"))['count'];
$total_feedbacks = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) as count FROM user_feedback"))['count'];
$total_donations = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) as count FROM food_donations"))['count'];

// Recent donations for this admin's location
$sql    = "SELECT * FROM food_donations WHERE assigned_to IS NULL AND location='$loc' ORDER BY date DESC";
$result = mysqli_query($connection, $sql);
$data   = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Donate - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <style>
        :root {
            --purple: #667eea;
            --purple-dark: #764ba2;
            --purple-light: rgba(102, 126, 234, 0.12);
            --green: #06C167;
            --text-dark: #1a202c;
            --text-mid: #4a5568;
            --text-light: #718096;
            --white: #ffffff;
            --off-white: #f7fafc;
            --border: #e2e8f0;
            --sidebar-w: 260px;
            --sidebar-collapsed: 72px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--off-white);
            color: var(--text-dark);
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ══════════════════════════════
           SIDEBAR
        ══════════════════════════════ */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            width: var(--sidebar-w);
            background: linear-gradient(160deg, #1a1a2e 0%, #16213e 60%, #0f3460 100%);
            display: flex;
            flex-direction: column;
            z-index: 200;
            transition: width 0.35s cubic-bezier(.4,0,.2,1);
            overflow: hidden;
        }

        body.collapsed .sidebar { width: var(--sidebar-collapsed); }

        /* Brand */
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 28px 20px 24px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
            white-space: nowrap;
        }

        .brand-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, var(--purple), var(--purple-dark));
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
            color: #fff;
            flex-shrink: 0;
        }

        .brand-text {
            font-size: 18px;
            font-weight: 800;
            color: #fff;
            transition: opacity 0.2s;
        }

        .brand-text span { color: var(--green); }

        body.collapsed .brand-text,
        body.collapsed .link-name,
        body.collapsed .mode-label { opacity: 0; pointer-events: none; }

        /* Nav links */
        .nav-links {
            list-style: none;
            padding: 16px 0;
            flex: 1;
        }

        .nav-links li a,
        .logout-mode li a {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 13px 20px;
            color: rgba(255,255,255,0.55);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            white-space: nowrap;
            border-radius: 0;
            transition: all 0.25s ease;
            position: relative;
        }

        .nav-links li a i,
        .logout-mode li a i {
            font-size: 20px;
            width: 32px;
            flex-shrink: 0;
            text-align: center;
            transition: color 0.25s;
        }

        .nav-links li a::before {
            content: '';
            position: absolute;
            left: 0; top: 0;
            width: 4px; height: 100%;
            background: linear-gradient(var(--purple), var(--purple-dark));
            border-radius: 0 4px 4px 0;
            transform: scaleY(0);
            transition: transform 0.25s;
        }

        .nav-links li a:hover,
        .nav-links li a.active {
            background: rgba(255,255,255,0.06);
            color: #fff;
        }

        .nav-links li a:hover::before,
        .nav-links li a.active::before { transform: scaleY(1); }

        .nav-links li a:hover i,
        .nav-links li a.active i { color: var(--purple); }

        /* Logout + dark mode at bottom */
        .logout-mode {
            list-style: none;
            padding: 12px 0 20px;
            border-top: 1px solid rgba(255,255,255,0.07);
        }

        .logout-mode li a:hover { background: rgba(255,255,255,0.06); color: #fff; }

        /* Dark mode toggle */
        .mode-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 13px 20px;
            color: rgba(255,255,255,0.55);
            cursor: pointer;
            white-space: nowrap;
        }

        .mode-item i { font-size: 20px; width: 32px; text-align: center; flex-shrink: 0; }

        .toggle-wrap { margin-left: auto; }

        .toggle-switch {
            width: 40px; height: 22px;
            background: rgba(255,255,255,0.15);
            border-radius: 11px;
            position: relative;
            cursor: pointer;
            transition: background 0.3s;
        }

        .toggle-switch.on { background: var(--purple); }

        .toggle-switch::after {
            content: '';
            position: absolute;
            top: 3px; left: 3px;
            width: 16px; height: 16px;
            background: #fff;
            border-radius: 50%;
            transition: left 0.3s;
        }

        .toggle-switch.on::after { left: 21px; }

        /* ══════════════════════════════
           MAIN CONTENT
        ══════════════════════════════ */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.35s cubic-bezier(.4,0,.2,1);
            min-width: 0;
        }

        body.collapsed .main { margin-left: var(--sidebar-collapsed); }

        /* Top bar */
        .topbar {
            position: sticky;
            top: 0;
            z-index: 100;
            height: 68px;
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 32px;
            gap: 16px;
        }

        .sidebar-toggle {
            font-size: 22px;
            color: var(--text-mid);
            cursor: pointer;
            transition: color 0.2s;
            flex-shrink: 0;
        }

        .sidebar-toggle:hover { color: var(--purple); }

        .topbar-logo {
            font-size: 22px;
            font-weight: 800;
            color: var(--text-dark);
        }

        .topbar-logo span { color: var(--green); }

        .topbar-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .admin-chip {
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--purple-light);
            color: var(--purple-dark);
            font-size: 13px;
            font-weight: 600;
            padding: 7px 16px;
            border-radius: 50px;
        }

        .admin-chip i { font-size: 15px; }

        /* ── CONTENT ── */
        .content {
            padding: 32px;
            flex: 1;
        }

        /* Page title */
        .page-title {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 28px;
        }

        .page-title i {
            width: 44px; height: 44px;
            background: var(--purple-light);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            color: var(--purple);
        }

        .page-title h1 {
            font-size: 22px;
            font-weight: 700;
            color: var(--text-dark);
        }

        /* ── STAT CARDS ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: var(--white);
            border-radius: 20px;
            padding: 24px 28px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            display: flex;
            align-items: center;
            gap: 18px;
            border: 1.5px solid var(--border);
            transition: all 0.3s ease;
            animation: fadeUp 0.6s ease-out both;
        }

        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 35px rgba(102,126,234,0.15);
            border-color: var(--purple);
        }

        .stat-icon {
            width: 56px; height: 56px;
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }

        .stat-icon.purple { background: var(--purple-light); color: var(--purple); }
        .stat-icon.green  { background: rgba(6,193,103,0.12); color: var(--green); }
        .stat-icon.pink   { background: rgba(240,147,251,0.12); color: #d63af9; }

        .stat-info .label {
            font-size: 13px;
            color: var(--text-light);
            font-weight: 500;
            margin-bottom: 4px;
        }

        .stat-info .number {
            font-size: 30px;
            font-weight: 800;
            color: var(--text-dark);
            line-height: 1;
        }

        /* ── DONATIONS TABLE CARD ── */
        .table-card {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            border: 1.5px solid var(--border);
            overflow: hidden;
            animation: fadeUp 0.6s ease-out 0.35s both;
        }

        .table-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 22px 28px;
            border-bottom: 1.5px solid var(--border);
        }

        .table-card-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 16px;
            font-weight: 700;
            color: var(--text-dark);
        }

        .table-card-title i {
            width: 36px; height: 36px;
            background: var(--purple-light);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: var(--purple);
            font-size: 16px;
        }

        .location-badge {
            display: flex;
            align-items: center;
            gap: 6px;
            background: rgba(6,193,103,0.1);
            color: #048a4a;
            font-size: 12px;
            font-weight: 600;
            padding: 5px 14px;
            border-radius: 50px;
            text-transform: capitalize;
        }

        /* Table */
        .table-scroll { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 700px;
        }

        thead tr { background: var(--off-white); }

        thead th {
            padding: 13px 20px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: var(--text-light);
            border-bottom: 1.5px solid var(--border);
            white-space: nowrap;
        }

        tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background 0.2s;
        }

        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: #fafbff; }

        tbody td {
            padding: 14px 20px;
            font-size: 14px;
            color: var(--text-mid);
            vertical-align: middle;
        }

        tbody td:first-child {
            font-weight: 600;
            color: var(--text-dark);
        }

        /* Action button */
        .get-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            color: #fff;
            background: linear-gradient(135deg, var(--purple), var(--purple-dark));
            border: none;
            padding: 8px 18px;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(102,126,234,0.3);
        }

        .get-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102,126,234,0.4);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 50px;
        }

        .status-mine {
            background: rgba(6,193,103,0.1);
            color: #048a4a;
        }

        .status-other {
            background: rgba(0,0,0,0.05);
            color: var(--text-light);
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-light);
        }

        .empty-state i {
            font-size: 48px;
            color: var(--border);
            display: block;
            margin-bottom: 14px;
        }

        /* ══════════════════════════════
           DARK MODE
        ══════════════════════════════ */
        body.dark {
            background: #0f172a;
            color: #e2e8f0;
        }

        body.dark .topbar,
        body.dark .stat-card,
        body.dark .table-card {
            background: #1e293b;
            border-color: #334155;
        }

        body.dark .topbar { background: rgba(30,41,59,0.95); }

        body.dark .topbar-logo,
        body.dark .stat-info .number,
        body.dark .table-card-title,
        body.dark .page-title h1,
        body.dark tbody td:first-child { color: #f1f5f9; }

        body.dark thead tr,
        body.dark tbody tr:hover { background: #1a2744; }

        body.dark thead th,
        body.dark .stat-info .label { color: #64748b; }

        body.dark tbody td { color: #94a3b8; }
        body.dark .location-badge { background: rgba(6,193,103,0.15); }

        /* ══════════════════════════════
           RESPONSIVE
        ══════════════════════════════ */
        @media (max-width: 900px) {
            .stats-grid { grid-template-columns: 1fr 1fr; }
        }

        @media (max-width: 640px) {
            .sidebar { width: var(--sidebar-collapsed); }
            .main    { margin-left: var(--sidebar-collapsed); }
            body.collapsed .sidebar { width: 0; }
            body.collapsed .main    { margin-left: 0; }
            .stats-grid { grid-template-columns: 1fr; }
            .content { padding: 20px 16px; }
            .topbar  { padding: 0 16px; }
            .admin-chip .chip-name { display: none; }
        }
    </style>
</head>
<body>

    <!-- ══ SIDEBAR ══ -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon"><i class="fas fa-seedling"></i></div>
            <span class="brand-text">Food <span>Donate</span></span>
        </div>

        <ul class="nav-links">
            <li>
                <a href="#" class="active">
                    <i class="uil uil-estate"></i>
                    <span class="link-name">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="analytics.php">
                    <i class="uil uil-chart"></i>
                    <span class="link-name">Analytics</span>
                </a>
            </li>
            <li>
                <a href="donate.php">
                    <i class="uil uil-heart"></i>
                    <span class="link-name">Donates</span>
                </a>
            </li>
            <li>
                <a href="feedback.php">
                    <i class="uil uil-comments"></i>
                    <span class="link-name">Feedbacks</span>
                </a>
            </li>
            <li>
                <a href="adminprofile.php">
                    <i class="uil uil-user"></i>
                    <span class="link-name">Profile</span>
                </a>
            </li>
        </ul>

        <ul class="logout-mode">
            <li>
                <a href="../logout.php">
                    <i class="uil uil-signout"></i>
                    <span class="link-name">Logout</span>
                </a>
            </li>
            <li>
                <div class="mode-item" id="modeToggle">
                    <i class="uil uil-moon"></i>
                    <span class="link-name mode-label">Dark Mode</span>
                    <div class="toggle-wrap">
                        <div class="toggle-switch" id="toggleSwitch"></div>
                    </div>
                </div>
            </li>
        </ul>
    </aside>

    <!-- ══ MAIN ══ -->
    <div class="main">

        <!-- Top bar -->
        <div class="topbar">
            <i class="uil uil-bars sidebar-toggle" id="sidebarToggle"></i>
            <div class="topbar-logo">Food <span>Donate</span></div>
            <div class="topbar-right">
                <div class="admin-chip">
                    <i class="fas fa-user-cog"></i>
                    <span class="chip-name"><?php echo htmlspecialchars($_SESSION['name']); ?></span>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="content">

            <!-- Page title -->
            <div class="page-title">
                <i class="uil uil-tachometer-fast-alt"></i>
                <h1>Dashboard</h1>
            </div>

            <!-- Stat cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon purple"><i class="uil uil-user"></i></div>
                    <div class="stat-info">
                        <div class="label">Total Users</div>
                        <div class="number"><?php echo $total_users; ?></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon pink"><i class="uil uil-comments"></i></div>
                    <div class="stat-info">
                        <div class="label">Feedbacks</div>
                        <div class="number"><?php echo $total_feedbacks; ?></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green"><i class="uil uil-heart"></i></div>
                    <div class="stat-info">
                        <div class="label">Total Donations</div>
                        <div class="number"><?php echo $total_donations; ?></div>
                    </div>
                </div>
            </div>

            <!-- Donations table -->
            <div class="table-card">
                <div class="table-card-header">
                    <div class="table-card-title">
                        <i class="uil uil-clock-three"></i>
                        Recent Donations
                    </div>
                    <div class="location-badge">
                        <i class="fas fa-map-marker-alt"></i>
                        <?php echo htmlspecialchars(ucfirst($loc)); ?>
                    </div>
                </div>

                <div class="table-scroll">
                    <?php if (count($data) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Food</th>
                                <th>Category</th>
                                <th>Phone</th>
                                <th>Date / Time</th>
                                <th>Address</th>
                                <th>Qty</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($data as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['food']); ?></td>
                                <td><?php echo htmlspecialchars($row['category']); ?></td>
                                <td><?php echo htmlspecialchars($row['phoneno']); ?></td>
                                <td><?php echo htmlspecialchars($row['date']); ?></td>
                                <td><?php echo htmlspecialchars($row['address']); ?></td>
                                <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                                <td>
                                    <?php if ($row['assigned_to'] == null): ?>
                                        <form method="post" action="">
                                            <input type="hidden" name="order_id" value="<?= $row['Fid'] ?>">
                                            <input type="hidden" name="delivery_person_id" value="<?= $id ?>">
                                            <button type="submit" name="food" class="get-btn">
                                                <i class="fas fa-truck"></i> Assign
                                            </button>
                                        </form>
                                    <?php elseif ($row['assigned_to'] == $id): ?>
                                        <span class="status-badge status-mine">
                                            <i class="fas fa-check-circle"></i> Assigned to you
                                        </span>
                                    <?php else: ?>
                                        <span class="status-badge status-other">
                                            <i class="fas fa-lock"></i> Taken
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-box-open"></i>
                        <p>No pending donations in <strong><?php echo htmlspecialchars(ucfirst($loc)); ?></strong> right now.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

        </div><!-- /content -->
    </div><!-- /main -->

    <script>
        // Sidebar toggle
        const sidebarToggle = document.getElementById('sidebarToggle');
        sidebarToggle.addEventListener('click', () => {
            document.body.classList.toggle('collapsed');
        });

        // Dark mode toggle
        const modeToggle  = document.getElementById('modeToggle');
        const toggleSwitch = document.getElementById('toggleSwitch');
        let dark = localStorage.getItem('adminDark') === 'true';

        function applyDark(d) {
            document.body.classList.toggle('dark', d);
            toggleSwitch.classList.toggle('on', d);
            localStorage.setItem('adminDark', d);
        }

        applyDark(dark);

        modeToggle.addEventListener('click', () => {
            dark = !dark;
            applyDark(dark);
        });
    </script>
</body>
</html>