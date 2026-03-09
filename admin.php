<?php
session_start();

$config = include 'config.php';

// ────────────────────────────────────────────────
//  Analytics & Reset/Export Handling
// ────────────────────────────────────────────────
require_once 'analytics.php';

if (isset($_GET['export_analytics']) && isset($_SESSION['admin_logged_in'])) {
    exportAnalytics();
    exit;
}

if (isset($_GET['reset_analytics']) && isset($_SESSION['admin_logged_in'])) {
    resetAnalytics();
    header('Location: admin.php?reset=success');
    exit;
}

$analytics = getAnalyticsSummary();

// ────────────────────────────────────────────────
//  Login / Logout
// ────────────────────────────────────────────────
$error = '';
$success = '';

if (isset($_POST['login'])) {
    if ($_POST['username'] === $config['admin']['username'] && 
        password_verify($_POST['password'], $config['admin']['password'])) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin.php');
        exit;
    } else {
        $error = "Invalid credentials!";
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}

$isLoggedIn = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'];

// ────────────────────────────────────────────────
//  File Deletion
// ────────────────────────────────────────────────
if ($isLoggedIn && isset($_GET['delete_file'])) {
    $fileToDelete = basename($_GET['delete_file']);
    $allowed = ['profile.jpg', 'background.mp4', 'background.jpg', 'music.mp3', 'music-icon.png'];
    
    if (in_array($fileToDelete, $allowed) && file_exists($fileToDelete)) {
        unlink($fileToDelete);
        header('Location: admin.php?deleted=1');
        exit;
    }
}

// ────────────────────────────────────────────────
//  Settings Update
// ────────────────────────────────────────────────
if ($isLoggedIn && isset($_POST['update_settings'])) {
    $newConfig = $config;

    // Colors
    $newConfig['colors'] = [
        'primary'         => $_POST['color_primary_text'] ?? '#10b981',
        'secondary'       => $_POST['color_secondary_text'] ?? '#888888',
        'glow_color'      => $_POST['color_glow_text'] ?? '#ffffff',
        'card_background' => $_POST['color_card_bg'] ?? 'rgba(20,20,20,0.95)',
        'background_overlay' => $_POST['color_bg_overlay'] ?? 'rgba(0,0,0,0.85)',
    ];

    // Profile
    $newConfig['profile']['name']           = trim($_POST['name'] ?? '');
    $newConfig['profile']['tagline']        = trim($_POST['tagline'] ?? '');
    $newConfig['profile']['description']    = trim($_POST['description'] ?? '');
    $newConfig['profile']['background_type']= $_POST['background_type'] ?? 'video';

    // Skills
    $skills = array_filter(array_map('trim', explode(',', $_POST['skills'] ?? '')));
    $newConfig['skills'] = $skills;

    // Social
    $newConfig['social'] = [
        'discord'  => trim($_POST['discord'] ?? ''),
        'youtube'  => trim($_POST['youtube'] ?? ''),
        'instagram'=> trim($_POST['instagram'] ?? ''),
        'shop'     => trim($_POST['shop'] ?? ''),
    ];

    // Music
    $newConfig['music']['enabled']     = isset($_POST['music_enabled']);
    $newConfig['music']['custom_url']  = trim($_POST['music_custom_url'] ?? '');
    $newConfig['music']['volume']      = floatval($_POST['music_volume'] ?? 0.5);
    $newConfig['music']['icon']        = trim($_POST['music_icon'] ?? 'default');

    // Meta
    $newConfig['meta']['title']       = trim($_POST['title'] ?? '');
    $newConfig['meta']['description'] = trim($_POST['meta_description'] ?? '');
    $newConfig['meta']['footer']      = trim($_POST['footer'] ?? '');

    require_once 'config.php';
    saveConfig($newConfig);

    $success = "Settings updated successfully!";
    $config = $newConfig; // refresh
}

// ────────────────────────────────────────────────
//  Custom Link Add / Delete
// ────────────────────────────────────────────────
if ($isLoggedIn && isset($_POST['add_custom_link'])) {
    $newConfig = $config;
    if (!isset($newConfig['custom_links'])) $newConfig['custom_links'] = [];

    $newLink = [
        'name'  => trim($_POST['link_name'] ?? ''),
        'url'   => trim($_POST['link_url'] ?? ''),
        'icon'  => trim($_POST['link_icon'] ?? 'fas fa-link'),
        'color' => trim($_POST['link_color'] ?? '#10b981')
    ];

    $newConfig['custom_links'][] = $newLink;
    saveConfig($newConfig);
    $success = "Custom link added!";
    $config = $newConfig;
}

if ($isLoggedIn && isset($_GET['delete_link'])) {
    $index = (int)$_GET['delete_link'];
    if (isset($config['custom_links'][$index])) {
        array_splice($config['custom_links'], $index, 1);
        saveConfig($config);
        header('Location: admin.php?link_deleted=1');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - SUBHAN DEV Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* ────────────────────────────────────────────────
           Sab styles yahan ek hi jagah (duplicate nahi)
        ──────────────────────────────────────────────── */
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #1a1a1a, #2d2d2d);
            color: #ddd;
            min-height: 100vh;
            padding: 20px;
        }
        .admin-container, .login-container {
            max-width: 1100px;
            margin: 30px auto;
            background: rgba(40,40,40,0.95);
            padding: 35px;
            border-radius: 16px;
            border: 1px solid rgba(255,255,255,0.08);
            box-shadow: 0 10px 40px rgba(0,0,0,0.6);
        }
        h1, h2, h3 { color: #fff; }
        .section {
            background: rgba(30,30,30,0.7);
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 30px;
            border: 1px solid rgba(255,255,255,0.06);
        }
        .form-group { margin-bottom: 20px; }
        label { display:block; color:#bbb; margin-bottom:8px; font-weight:600; }
        input[type=text], input[type=password], input[type=number], textarea, select {
            width:100%; padding:12px; background:rgba(20,20,20,0.9);
            border:1px solid rgba(255,255,255,0.15); border-radius:8px; color:#fff;
        }
        textarea { min-height:90px; resize:vertical; }
        .btn {
            padding:12px 24px; background:linear-gradient(135deg,#10b981,#059669);
            color:white; border:none; border-radius:8px; cursor:pointer; font-weight:600;
        }
        .btn:hover { opacity:0.92; transform:translateY(-1px); }
        .alert-success { background:rgba(16,185,129,0.2); color:#10b981; padding:15px; border-radius:8px; margin-bottom:20px; }
        .alert-error   { background:rgba(220,53,69,0.2);   color:#dc3545;   padding:15px; border-radius:8px; margin-bottom:20px; }
        .file-upload-btn {
            display:block; padding:14px; background:rgba(20,20,20,0.9);
            border:2px dashed #444; border-radius:8px; text-align:center; cursor:pointer;
        }
        .file-upload-btn:hover { border-color:#10b981; }
        .progress-bar { height:30px; background:#10b981; width:0%; transition:width 0.3s; }
    </style>
</head>
<body>

<?php if (!$isLoggedIn): ?>
    <!-- ─── Login Form ─── -->
    <div class="login-container">
        <h1><i class="fas fa-lock"></i> Admin Login</h1>
        <?php if ($error): ?>
            <div class="alert-error"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" name="login" class="btn">Login</button>
        </form>
        <p style="text-align:center; margin-top:20px; color:#888;">Default: admin / admin123</p>
    </div>

<?php else: ?>
    <!-- ─── ADMIN PANEL ─── -->
    <div class="admin-container">

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
            <h1><i class="fas fa-cog"></i> Portal Settings</h1>
            <div>
                <a href="index.php" target="_blank" class="btn" style="background:#3b82f6; margin-right:10px;">
                    <i class="fas fa-eye"></i> Preview
                </a>
                <a href="?logout" class="btn" style="background:#dc3545;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>

        <?php if ($success): ?>
            <div class="alert-success"><?= $success ?></div>
        <?php endif; ?>
        <?php if (isset($_GET['deleted'])): ?>
            <div class="alert-success">File deleted successfully!</div>
        <?php endif; ?>
        <?php if (isset($_GET['link_deleted'])): ?>
            <div class="alert-success">Custom link deleted!</div>
        <?php endif; ?>
        <?php if (isset($_GET['reset'])): ?>
            <div class="alert-success">Analytics reset ho gaya!</div>
        <?php endif; ?>

        <!-- Analytics Dashboard -->
        <div class="section">
            <h2><i class="fas fa-chart-line"></i> Analytics Overview</h2>
            <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:20px; margin:25px 0;">
                <div style="background:rgba(30,30,30,0.8); padding:20px; border-radius:12px; text-align:center;">
                    <i class="fas fa-eye" style="font-size:36px; color:#10b981;"></i>
                    <div style="font-size:32px; font-weight:bold; color:#fff;"><?= number_format($analytics['total_views']) ?></div>
                    <div style="color:#aaa;">Total Views</div>
                </div>
                <!-- Baqi stats bhi isi tarah daal sakte ho -->
            </div>

            <div style="margin-top:25px; display:flex; gap:15px; flex-wrap:wrap;">
                <a href="?export_analytics=1" class="btn" style="background:#3b82f6;">
                    <i class="fas fa-download"></i> Export Analytics
                </a>
                <a href="?reset_analytics=1" class="btn" onclick="return confirm('Analytics reset karna chahte ho?')" style="background:#dc3545;">
                    <i class="fas fa-trash"></i> Reset Analytics
                </a>
            </div>
        </div>

        <form method="POST" enctype="multipart/form-data">

            <!-- Profile + Colors + Skills + Social + Music + Meta + Custom Links sab yahan -->

            <!-- Color Customization -->
            <div class="section">
                <h3><i class="fas fa-palette"></i> Colors</h3>
                <div class="form-group">
                    <label>Primary Color</label>
                    <input type="color" id="color_primary" value="<?= htmlspecialchars($config['colors']['primary']) ?>">
                    <input type="text" name="color_primary_text" value="<?= htmlspecialchars($config['colors']['primary']) ?>">
                </div>
                <!-- Baqi colors isi tarah -->
            </div>

            <!-- Baqi sections copy-paste kar ke rakh dena (profile, skills, social, music, meta) -->

            <button type="submit" name="update_settings" class="btn" style="font-size:18px; padding:16px;">
                <i class="fas fa-save"></i> Save All Settings
            </button>
        </form>

        <!-- Custom Links, File Uploads etc. bhi isi form ke andar ya alag section mein daal dena -->

    </div>
<?php endif; ?>

<script>
// File upload progress wala code yahan paste kar dena (jo pehle tha)
</script>

</body>
</html>
