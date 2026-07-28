<?php
ob_start();
include("connect.php");

if ($_SESSION['name'] == '') {
    header("location: signin.php");
    exit();
}

$connection = mysqli_connect("localhost:3306", "root", "");
$db = mysqli_select_db($connection, 'foodwaste_db1');

// Stats
$total_users     = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) as count FROM login"))['count'];
$total_feedbacks = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) as count FROM user_feedback"))['count'];
$total_donations = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) as count FROM food_donations"))['count'];

// Gender counts
$male   = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) as count FROM login WHERE gender='male'"))['count'];
$female = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) as count FROM login WHERE gender='female'"))['count'];

// Donations by location
$locations = ['madurai', 'chennai', 'coimbatore', 'coimbatore', 'vellore', 'salem', 'tiruchirappalli', 'tirunelveli'];
$loc_counts = [];
$loc_labels = [];
$all_locs_q = "SELECT location, COUNT(*) as count FROM food_donations GROUP BY location ORDER BY count DESC LIMIT 6";
$all_locs_r = mysqli_query($connection, $all_locs_q);
while ($lr = mysqli_fetch_assoc($all_locs_r)) {
    $loc_labels[] = ucfirst($lr['location']);
    $loc_counts[] = $lr['count'];
}

// Donations by category
$cat_labels = [];
$cat_counts = [];
$cat_q = "SELECT category, COUNT(*) as count FROM food_donations GROUP BY category ORDER BY count DESC";
$cat_r = mysqli_query($connection, $cat_q);
while ($cr = mysqli_fetch_assoc($cat_r)) {
    $cat_labels[] = ucfirst($cr['category']);
    $cat_counts[] = $cr['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Donate - Analytics</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <style>
        :root {
            --purple: #667eea;
            --purple-dark: #764ba2;
            --purple-light: rgba(102, 126, 234, 0.12);
            --green: #06C167;
            --green-light: rgba(6, 193, 103, 0.12);
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

        /* ══ SIDEBAR ══ */
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
            font-size: 18px; color: #fff;
            flex-shrink: 0;
        }

        .brand-text {
            font-size: 18px; font-weight: 800; color: #fff;
            transition: opacity 0.2s;
        }

        .brand-text span { color: var(--green); }

        body.collapsed .brand-text,
        body.collapsed .link-name,
        body.collapsed .mode-label { opacity: 0; pointer-events: none; }

        .nav-links { list-style: none; padding: 16px 0; flex: 1; }

        .nav-links li a,
        .logout-mode li a {
            display: flex; align-items: center; gap: 14px;
            padding: 13px 20px;
            color: rgba(255,255,255,0.55);
            text-decoration: none;
            font-size: 14px; font-weight: 500;
            white-space: nowrap;
            transition: all 0.25s ease;
            position: relative;
        }

        .nav-links li a i,
        .logout-mode li a i {
            font-size: 20px; width: 32px; flex-shrink: 0; text-align: center;
        }

        .nav-links li a::before {
            content: ''; position: absolute; left: 0; top: 0;
            width: 4px; height: 100%;
            background: linear-gradient(var(--purple), var(--purple-dark));
            border-radius: 0 4px 4px 0;
            transform: scaleY(0); transition: transform 0.25s;
        }

        .nav-links li a:hover,
        .nav-links li a.active { background: rgba(255,255,255,0.06); color: #fff; }

        .nav-links li a:hover::before,
        .nav-links li a.active::before { transform: scaleY(1); }

        .nav-links li a:hover i,
        .nav-links li a.active i { color: var(--purple); }

        .logout-mode {
            list-style: none; padding: 12px 0 20px;
            border-top: 1px solid rgba(255,255,255,0.07);
        }

        .logout-mode li a:hover { background: rgba(255,255,255,0.06); color: #fff; }

        .mode-item {
            display: flex; align-items: center; gap: 14px;
            padding: 13px 20px;
            color: rgba(255,255,255,0.55); cursor: pointer; white-space: nowrap;
        }

        .mode-item i { font-size: 20px; width: 32px; text-align: center; flex-shrink: 0; }

        .toggle-wrap { margin-left: auto; }

        .toggle-switch {
            width: 40px; height: 22px;
            background: rgba(255,255,255,0.15);
            border-radius: 11px; position: relative;
            cursor: pointer; transition: background 0.3s;
        }

        .toggle-switch.on { background: var(--purple); }

        .toggle-switch::after {
            content: ''; position: absolute;
            top: 3px; left: 3px;
            width: 16px; height: 16px;
            background: #fff; border-radius: 50%;
            transition: left 0.3s;
        }

        .toggle-switch.on::after { left: 21px; }

        /* ══ MAIN ══ */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1; display: flex; flex-direction: column;
            transition: margin-left 0.35s cubic-bezier(.4,0,.2,1);
            min-width: 0;
        }

        body.collapsed .main { margin-left: var(--sidebar-collapsed); }

        /* Topbar */
        .topbar {
            position: sticky; top: 0; z-index: 100;
            height: 68px;
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center;
            padding: 0 32px; gap: 16px;
        }

        .sidebar-toggle {
            font-size: 22px; color: var(--text-mid);
            cursor: pointer; transition: color 0.2s; flex-shrink: 0;
        }

        .sidebar-toggle:hover { color: var(--purple); }

        .topbar-logo { font-size: 22px; font-weight: 800; color: var(--text-dark); }
        .topbar-logo span { color: var(--green); }

        .topbar-right { margin-left: auto; display: flex; align-items: center; gap: 14px; }

        .admin-chip {
            display: flex; align-items: center; gap: 8px;
            background: var(--purple-light); color: var(--purple-dark);
            font-size: 13px; font-weight: 600;
            padding: 7px 16px; border-radius: 50px;
        }

        /* ══ CONTENT ══ */
        .content { padding: 32px; flex: 1; }

        .page-title {
            display: flex; align-items: center; gap: 12px; margin-bottom: 28px;
        }

        .page-title i {
            width: 44px; height: 44px;
            background: var(--purple-light); border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; color: var(--purple);
        }

        .page-title h1 { font-size: 22px; font-weight: 700; color: var(--text-dark); }

        /* Stat cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px; margin-bottom: 32px;
        }

        .stat-card {
            background: var(--white); border-radius: 20px;
            padding: 24px 28px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            display: flex; align-items: center; gap: 18px;
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
            width: 56px; height: 56px; border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; flex-shrink: 0;
        }

        .stat-icon.purple { background: var(--purple-light); color: var(--purple); }
        .stat-icon.green  { background: var(--green-light); color: var(--green); }
        .stat-icon.pink   { background: rgba(240,147,251,0.12); color: #d63af9; }

        .stat-info .label { font-size: 13px; color: var(--text-light); font-weight: 500; margin-bottom: 4px; }
        .stat-info .number { font-size: 30px; font-weight: 800; color: var(--text-dark); line-height: 1; }

        /* Charts grid */
        .charts-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 24px;
        }

        .chart-card {
            background: var(--white);
            border-radius: 20px;
            padding: 28px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            border: 1.5px solid var(--border);
            animation: fadeUp 0.6s ease-out 0.4s both;
        }

        .chart-card.full { grid-column: 1 / -1; }

        .chart-header {
            display: flex; align-items: center; gap: 10px;
            margin-bottom: 24px;
        }

        .chart-header i {
            width: 36px; height: 36px;
            background: var(--purple-light); border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: var(--purple); font-size: 16px;
        }

        .chart-header h3 { font-size: 15px; font-weight: 700; color: var(--text-dark); }

        .chart-wrap {
            position: relative;
            height: 260px;
        }

        .chart-wrap.tall { height: 300px; }

        /* Dark mode */
        body.dark { background: #0f172a; color: #e2e8f0; }

        body.dark .topbar { background: rgba(30,41,59,0.95); border-color: #334155; }
        body.dark .topbar-logo { color: #f1f5f9; }

        body.dark .stat-card,
        body.dark .chart-card {
            background: #1e293b; border-color: #334155;
        }

        body.dark .stat-info .number,
        body.dark .chart-header h3,
        body.dark .page-title h1 { color: #f1f5f9; }

        body.dark .stat-info .label { color: #64748b; }

        /* Responsive */
        @media (max-width: 900px) {
            .stats-grid { grid-template-columns: 1fr 1fr; }
            .charts-grid { grid-template-columns: 1fr; }
            .chart-card.full { grid-column: 1; }
        }

        @media (max-width: 640px) {
            .sidebar { width: var(--sidebar-collapsed); }
            .main    { margin-left: var(--sidebar-collapsed); }
            body.collapsed .sidebar { width: 0; }
            body.collapsed .main    { margin-left: 0; }
            .stats-grid { grid-template-columns: 1fr; }
            .content { padding: 20px 16px; }
            .topbar  { padding: 0 16px; }
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
            <li><a href="admin.php"><i class="uil uil-estate"></i><span class="link-name">Dashboard</span></a></li>
            <li><a href="#" class="active"><i class="uil uil-chart"></i><span class="link-name">Analytics</span></a></li>
            <li><a href="donate.php"><i class="uil uil-heart"></i><span class="link-name">Donates</span></a></li>
            <li><a href="feedback.php"><i class="uil uil-comments"></i><span class="link-name">Feedbacks</span></a></li>
            <li><a href="adminprofile.php"><i class="uil uil-user"></i><span class="link-name">Profile</span></a></li>
        </ul>

        <ul class="logout-mode">
            <li><a href="../logout.php"><i class="uil uil-signout"></i><span class="link-name">Logout</span></a></li>
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

        <div class="topbar">
            <i class="uil uil-bars sidebar-toggle" id="sidebarToggle"></i>
            <div class="topbar-logo">Food <span>Donate</span></div>
            <div class="topbar-right">
                <div class="admin-chip">
                    <i class="fas fa-user-cog"></i>
                    <span><?php echo htmlspecialchars($_SESSION['name']); ?></span>
                </div>
            </div>
        </div>

        <div class="content">

            <div class="page-title">
                <i class="uil uil-chart-growth"></i>
                <h1>Analytics</h1>
            </div>

            <!-- Stats -->
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

            <!-- Charts -->
            <div class="charts-grid">

                <!-- Gender chart (doughnut) -->
                <div class="chart-card">
                    <div class="chart-header">
                        <i class="fas fa-venus-mars"></i>
                        <h3>User Gender Breakdown</h3>
                    </div>
                    <div class="chart-wrap">
                        <canvas id="genderChart"></canvas>
                    </div>
                </div>

                <!-- Donations by location (bar) -->
                <div class="chart-card">
                    <div class="chart-header">
                        <i class="fas fa-map-marker-alt"></i>
                        <h3>Donations by Location</h3>
                    </div>
                    <div class="chart-wrap">
                        <canvas id="locationChart"></canvas>
                    </div>
                </div>

                <!-- Donations by category (horizontal bar) -->
                <div class="chart-card full">
                    <div class="chart-header">
                        <i class="fas fa-utensils"></i>
                        <h3>Donations by Food Category</h3>
                    </div>
                    <div class="chart-wrap tall">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        // ── Sidebar toggle
        document.getElementById('sidebarToggle').addEventListener('click', () => {
            document.body.classList.toggle('collapsed');
        });

        // ── Dark mode
        const toggleSwitch = document.getElementById('toggleSwitch');
        let dark = localStorage.getItem('adminDark') === 'true';

        function applyDark(d) {
            document.body.classList.toggle('dark', d);
            toggleSwitch.classList.toggle('on', d);
            localStorage.setItem('adminDark', d);
        }

        applyDark(dark);
        document.getElementById('modeToggle').addEventListener('click', () => {
            dark = !dark;
            applyDark(dark);
        });

        // ── Chart defaults
        Chart.defaults.font.family = 'Poppins';
        Chart.defaults.color = '#718096';

        const purple = '#667eea';
        const purpleDark = '#764ba2';
        const green  = '#06C167';
        const pink   = '#f093fb';
        const orange = '#f5576c';
        const blue   = '#4facfe';
        const teal   = '#43e97b';

        // ── Gender Doughnut
        new Chart(document.getElementById('genderChart'), {
            type: 'doughnut',
            data: {
                labels: ['Male', 'Female'],
                datasets: [{
                    data: [<?php echo $male; ?>, <?php echo $female; ?>],
                    backgroundColor: [purple, pink],
                    borderWidth: 0,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 20, font: { size: 13, weight: '600' } }
                    },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` ${ctx.label}: ${ctx.raw} users`
                        }
                    }
                }
            }
        });

        // ── Location Bar
        new Chart(document.getElementById('locationChart'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($loc_labels); ?>,
                datasets: [{
                    label: 'Donations',
                    data: <?php echo json_encode($loc_counts); ?>,
                    backgroundColor: [purple, green, pink, orange, blue, teal],
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: { stepSize: 1 }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        // ── Category Horizontal Bar
        new Chart(document.getElementById('categoryChart'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($cat_labels ?: ['No data']); ?>,
                datasets: [{
                    label: 'Donations',
                    data: <?php echo json_encode($cat_counts ?: [0]); ?>,
                    backgroundColor: [green, purple, pink, orange, blue, teal, purpleDark],
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: { stepSize: 1 }
                    },
                    y: {
                        grid: { display: false }
                    }
                }
            }
        });
    </script>
</body>
</html>