<?php
session_start();

$config = include 'config.php';

// Handle Analytics Export
if ($isLoggedIn && isset($_GET['export_analytics'])) {
    require_once 'analytics.php';
    exportAnalytics();
    exit;
}

// Handle Analytics Reset
if ($isLoggedIn && isset($_GET['reset_analytics'])) {
    require_once 'analytics.php';
    resetAnalytics();
    header('Location: admin.php?reset=success');
    exit;
}

// Handle Login
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

// Handle Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}

// Check if logged in
$isLoggedIn = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'];

// Handle File Deletion
if ($isLoggedIn && isset($_GET['delete_file'])) {
    $fileToDelete = $_GET['delete_file'];
    $allowedFiles = ['profile.jpg', 'background.mp4', 'music.mp3', 'music-icon.png'];
    
    if (in_array($fileToDelete, $allowedFiles) && file_exists($fileToDelete)) {
        unlink($fileToDelete);
        header('Location: admin.php?deleted=1');
        exit;
    }
}

// Load Analytics
require_once 'analytics.php';
$analytics = getAnalyticsSummary();
?>

<!-- Analytics Dashboard -->
<div class="analytics-dashboard" style="margin-bottom: 40px;">
    <h2 style="color: #fff; margin-bottom: 25px;">
        <i class="fas fa-chart-line"></i> Analytics Dashboard
    </h2>
    
    <div class="analytics-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <!-- Total Views -->
        <div class="stat-card" style="background: rgba(30, 30, 30, 0.8); padding: 25px; border-radius: 15px; border: 1px solid rgba(255, 255, 255, 0.1); text-align: center;">
            <i class="fas fa-eye" style="font-size: 32px; color: #10b981; margin-bottom: 10px;"></i>
            <div style="font-size: 28px; font-weight: 700; color: #fff; margin-bottom: 5px;">
                <?php echo number_format($analytics['total_views']); ?>
            </div>
            <div style="color: #888; font-size: 14px;">Total Views</div>
        </div>
        
        <!-- Unique Visitors -->
        <div class="stat-card" style="background: rgba(30, 30, 30, 0.8); padding: 25px; border-radius: 15px; border: 1px solid rgba(255, 255, 255, 0.1); text-align: center;">
            <i class="fas fa-users" style="font-size: 32px; color: #3b82f6; margin-bottom: 10px;"></i>
            <div style="font-size: 28px; font-weight: 700; color: #fff; margin-bottom: 5px;">
                <?php echo number_format($analytics['unique_visitors']); ?>
            </div>
            <div style="color: #888; font-size: 14px;">Unique Visitors</div>
        </div>
        
        <!-- Live Visitors -->
        <div class="stat-card" style="background: rgba(30, 30, 30, 0.8); padding: 25px; border-radius: 15px; border: 1px solid rgba(255, 255, 255, 0.1); text-align: center;">
            <i class="fas fa-signal" style="font-size: 32px; color: #f59e0b; margin-bottom: 10px; animation: pulse 2s infinite;"></i>
            <div style="font-size: 28px; font-weight: 700; color: #fff; margin-bottom: 5px;">
                <?php echo $analytics['live_visitors']; ?>
            </div>
            <div style="color: #888; font-size: 14px;">Live Now</div>
        </div>
    </div>
    
    <!-- Top Countries -->
    <div class="section" style="background: rgba(30, 30, 30, 0.6); padding: 25px; border-radius: 10px; margin-bottom: 20px;">
        <h3 style="color: #888; margin-bottom: 15px; font-size: 18px;">
            <i class="fas fa-globe"></i> Top Countries
        </h3>
        <?php foreach ($analytics['top_countries'] as $country => $count): ?>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px; margin-bottom: 8px; background: rgba(20, 20, 20, 0.5); border-radius: 6px;">
                <span style="color: #fff;"><?php echo $country; ?></span>
                <span style="color: #10b981; font-weight: 700;"><?php echo $count; ?> visits</span>
            </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Device & Browser Stats -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <!-- Devices -->
        <div class="section" style="background: rgba(30, 30, 30, 0.6); padding: 25px; border-radius: 10px;">
            <h3 style="color: #888; margin-bottom: 15px; font-size: 18px;">
                <i class="fas fa-mobile-alt"></i> Devices
            </h3>
            <?php foreach ($analytics['device_breakdown'] as $device => $count): ?>
                <div style="display: flex; justify-content: space-between; padding: 8px 0; color: #bbb;">
                    <span><?php echo $device; ?></span>
                    <strong style="color: #fff;"><?php echo $count; ?></strong>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Browsers -->
        <div class="section" style="background: rgba(30, 30, 30, 0.6); padding: 25px; border-radius: 10px;">
            <h3 style="color: #888; margin-bottom: 15px; font-size: 18px;">
                <i class="fas fa-globe"></i> Browsers
            </h3>
            <?php foreach ($analytics['browser_breakdown'] as $browser => $count): ?>
                <div style="display: flex; justify-content: space-between; padding: 8px 0; color: #bbb;">
                    <span><?php echo $browser; ?></span>
                    <strong style="color: #fff;"><?php echo $count; ?></strong>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Actions -->
    <div style="margin-top: 20px; display: flex; gap: 10px;">
        <a href="?export_analytics=1" class="btn" style="background: #3b82f6; padding: 12px 20px; border-radius: 8px; color: white; text-decoration: none; display: inline-block;">
            <i class="fas fa-download"></i> Export Data
        </a>
        <a href="?reset_analytics=1" class="btn" onclick="return confirm('Reset all analytics data?')" style="background: #dc3545; padding: 12px 20px; border-radius: 8px; color: white; text-decoration: none; display: inline-block;">
            <i class="fas fa-trash"></i> Reset Analytics
        </a>
    </div>
</div>

<style>
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}
<style>

// Handle Settings Update
if ($isLoggedIn && isset($_POST['update_settings'])) {
    $newConfig = $config;

    // Update color settings
    $newConfig['colors']['primary'] = $_POST['color_primary_text'];
    $newConfig['colors']['secondary'] = $_POST['color_secondary_text'];
    $newConfig['colors']['glow_color'] = $_POST['color_glow_text'];
    $newConfig['colors']['card_background'] = $_POST['color_card_bg'];
    $newConfig['colors']['background_overlay'] = $_POST['color_bg_overlay'];
    
    // Update profile settings
    $newConfig['profile']['name'] = $_POST['name'];
    $newConfig['profile']['tagline'] = $_POST['tagline'];
    $newConfig['profile']['description'] = $_POST['description'];
    $newConfig['profile']['background_type'] = $_POST['background_type'];
    
    // Update skills
    $skills = array_filter(array_map('trim', explode(',', $_POST['skills'])));
    $newConfig['skills'] = $skills;
    
    // Update predefined social links
    $newConfig['social']['discord'] = $_POST['discord'];
    $newConfig['social']['youtube'] = $_POST['youtube'];
    $newConfig['social']['instagram'] = $_POST['instagram'];
    $newConfig['social']['shop'] = $_POST['shop'];
    
    // Update music settings
    $newConfig['music']['enabled'] = isset($_POST['music_enabled']) ? true : false;
    $newConfig['music']['custom_url'] = $_POST['music_custom_url'];
    $newConfig['music']['volume'] = floatval($_POST['music_volume']);
    $newConfig['music']['icon'] = $_POST['music_icon'];
    
    // Update meta
    $newConfig['meta']['title'] = $_POST['title'];
    $newConfig['meta']['description'] = $_POST['meta_description'];
    $newConfig['meta']['footer'] = $_POST['footer'];
    
    // Save using the saveConfig function
    require_once 'config.php';
    saveConfig($newConfig);
    
    $success = "Settings updated successfully!";
    $config = $newConfig;
}

// Handle Custom Social Link Addition
if ($isLoggedIn && isset($_POST['add_custom_link'])) {
    $newConfig = $config;
    
    if (!isset($newConfig['custom_links'])) {
        $newConfig['custom_links'] = [];
    }
    
    $newLink = [
        'name' => $_POST['link_name'],
        'url' => $_POST['link_url'],
        'icon' => $_POST['link_icon'],
        'color' => $_POST['link_color']
    ];
    
    $newConfig['custom_links'][] = $newLink;
    
    // Save using the saveConfig function
    require_once 'config.php';
    saveConfig($newConfig);
    
    $success = "Custom link added successfully!";
    $config = $newConfig;
}

// Handle Custom Link Deletion
if ($isLoggedIn && isset($_GET['delete_link'])) {
    $newConfig = $config;
    $linkIndex = intval($_GET['delete_link']);
    
    if (isset($newConfig['custom_links'][$linkIndex])) {
        array_splice($newConfig['custom_links'], $linkIndex, 1);
        
        // Save using the saveConfig function
        require_once 'config.php';
        saveConfig($newConfig);
        
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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            background: rgba(40, 40, 40, 0.95);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .admin-container {
            max-width: 1000px;
            margin: 20px auto;
            background: rgba(40, 40, 40, 0.95);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        h1, h2 {
            color: #fff;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            color: #bbb;
            margin-bottom: 8px;
            font-weight: 600;
        }
        
        input[type="text"],
        input[type="password"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 12px;
            background: rgba(20, 20, 20, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            color: #fff;
            font-size: 14px;
        }
        
        textarea {
            resize: vertical;
            min-height: 80px;
        }
        
        input[type="file"] {
            display: none;
        }
        
        .file-upload-wrapper {
            position: relative;
            width: 100%;
        }
        
        .file-upload-btn {
            width: 100%;
            padding: 12px;
            background: rgba(20, 20, 20, 0.8);
            border: 2px dashed rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            color: #bbb;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .file-upload-btn:hover {
            border-color: #10b981;
            background: rgba(16, 185, 129, 0.1);
        }
        
        .file-upload-btn i {
            margin-right: 8px;
        }
        
        .progress-container {
            width: 100%;
            height: 30px;
            background: rgba(20, 20, 20, 0.8);
            border-radius: 15px;
            margin-top: 10px;
            overflow: hidden;
            display: none;
        }
        
        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #10b981, #059669);
            width: 0%;
            transition: width 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 12px;
        }
        
        .uploaded-files {
            margin-top: 15px;
            padding: 15px;
            background: rgba(20, 20, 20, 0.5);
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .file-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            margin-bottom: 8px;
            background: rgba(40, 40, 40, 0.7);
            border-radius: 6px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .file-info {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #bbb;
        }
        
        .file-info i {
            color: #10b981;
        }
        
        .file-actions {
            display: flex;
            gap: 10px;
        }
        
        .btn-delete {
            padding: 6px 12px;
            background: rgba(220, 53, 69, 0.8);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-size: 12px;
            transition: all 0.3s ease;
        }
        
        .btn-delete:hover {
            background: rgba(220, 53, 69, 1);
            transform: scale(1.05);
        }
        
        .btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #333, #555);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            background: linear-gradient(135deg, #444, #666);
            transform: translateY(-2px);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #10b981, #059669);
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #059669, #047857);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #dc3545, #c82333);
            margin-top: 10px;
        }
        
        .btn-danger:hover {
            background: linear-gradient(135deg, #c82333, #bd2130);
        }
        
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .alert-success {
            background: rgba(16, 185, 129, 0.2);
            border: 1px solid #10b981;
            color: #10b981;
        }
        
        .alert-error {
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid #dc3545;
            color: #dc3545;
        }
        
        .section {
            background: rgba(30, 30, 30, 0.6);
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .section h3 {
            color: #888;
            margin-bottom: 15px;
            font-size: 18px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 10px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .logout-btn {
            padding: 8px 20px;
            background: rgba(220, 53, 69, 0.8);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        
        .preview-link {
            padding: 8px 20px;
            background: rgba(16, 185, 129, 0.8);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-right: 10px;
        }
        
        .hint {
            color: #888;
            font-size: 12px;
            margin-top: 5px;
        }
        
        .custom-link-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px;
            background: rgba(20, 20, 20, 0.5);
            border-radius: 8px;
            margin-bottom: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .custom-link-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .custom-link-icon {
            font-size: 24px;
        }
        
        .icon-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(60px, 1fr));
            gap: 10px;
            margin-top: 10px;
            padding: 15px;
            background: rgba(20, 20, 20, 0.5);
            border-radius: 8px;
            max-height: 200px;
            overflow-y: auto;
        }
        
        .icon-option {
            padding: 10px;
            background: rgba(40, 40, 40, 0.8);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 6px;
            cursor: pointer;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .icon-option:hover {
            border-color: #10b981;
            background: rgba(16, 185, 129, 0.2);
        }
        
        .icon-option.selected {
            border-color: #10b981;
            background: rgba(16, 185, 129, 0.3);
        }
        
        .icon-option i {
            font-size: 24px;
            color: #fff;
        }
        
        @media (max-width: 600px) {
            .admin-container, .login-container {
                padding: 20px;
            }
            
            .header {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <?php if (!$isLoggedIn): ?>
        <!-- Login Form -->
        <div class="login-container">
            <h1><i class="fas fa-lock"></i> Admin Login</h1>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
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
                
                <button type="submit" name="login" class="btn">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
            
            <p class="hint" style="text-align: center; margin-top: 20px;">
                Default: admin / admin123
            </p>
        </div>
    <?php else: ?>
        <!-- Admin Panel -->
        <div class="admin-container">
            <div class="header">
                <h1><i class="fas fa-cog"></i> Portal Settings</h1>
                <div>
                    <a href="index.php" target="_blank" class="preview-link">
                        <i class="fas fa-eye"></i> Preview
                    </a>
                    <a href="?logout" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
            
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if (isset($_GET['deleted'])): ?>
                <div class="alert alert-success">File deleted successfully!</div>
            <?php endif; ?>
            
            <?php if (isset($_GET['link_deleted'])): ?>
                <div class="alert alert-success">Custom link deleted successfully!</div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <!-- Profile Section -->
                <div class="section">
                    <h3><i class="fas fa-user"></i> Profile Information</h3>
                    
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($config['profile']['name']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Tagline</label>
                        <input type="text" name="tagline" value="<?php echo htmlspecialchars($config['profile']['tagline']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" required><?php echo htmlspecialchars($config['profile']['description']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Background Type</label>
                        <select name="background_type" style="width: 100%; padding: 12px; background: rgba(20, 20, 20, 0.8); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 8px; color: #fff; font-size: 14px;">
                            <option value="video" <?php echo ($config['profile']['background_type'] ?? 'video') === 'video' ? 'selected' : ''; ?>>Video Background</option>
                            <option value="image" <?php echo ($config['profile']['background_type'] ?? 'video') === 'image' ? 'selected' : ''; ?>>Image Background</option>
                        </select>
                        <p class="hint">Choose whether to use a video or static image as background</p>
                    </div>
                </div>
                
                <!-- Skills Section -->
                <div class="section">
                    <h3><i class="fas fa-code"></i> Skills</h3>
                    
                    <div class="form-group">
                        <label>Skills (comma separated)</label>
                        <input type="text" name="skills" value="<?php echo htmlspecialchars(implode(', ', $config['skills'])); ?>" required>
                        <p class="hint">Example: PYTHON, NODE.JS, C#, JAVASCRIPT</p>
                    </div>
                </div>
                
                <!-- Social Links Section -->
                <div class="section">
                    <h3><i class="fas fa-share-alt"></i> Default Social Links</h3>
                    
                    <div class="form-group">
                        <label><i class="fab fa-discord"></i> Discord</label>
                        <input type="text" name="discord" value="<?php echo htmlspecialchars($config['social']['discord']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fab fa-youtube"></i> YouTube</label>
                        <input type="text" name="youtube" value="<?php echo htmlspecialchars($config['social']['youtube']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fab fa-instagram"></i> Instagram</label>
                        <input type="text" name="instagram" value="<?php echo htmlspecialchars($config['social']['instagram']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-shopping-cart"></i> Shop Link</label>
                        <input type="text" name="shop" value="<?php echo htmlspecialchars($config['social']['shop']); ?>" required>
                    </div>
                </div>
                
                <!-- Music Settings Section -->
                <div class="section">
                    <h3><i class="fas fa-music"></i> Music Settings</h3>
                    
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="music_enabled" <?php echo $config['music']['enabled'] ? 'checked' : ''; ?> style="width: auto; margin-right: 10px;">
                            Enable Background Music
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <label>Custom Music URL (Optional)</label>
                        <input type="text" name="music_custom_url" value="<?php echo htmlspecialchars($config['music']['custom_url']); ?>" placeholder="https://example.com/music.mp3">
                        <p class="hint">Leave empty to use uploaded file. Paste direct link to MP3 file.</p>
                    </div>
                    
                    <div class="form-group">
                        <label>Volume (0.0 - 1.0)</label>
                        <input type="number" name="music_volume" value="<?php echo $config['music']['volume']; ?>" min="0" max="1" step="0.1" required>
                        <p class="hint">0.5 is 50% volume, 1.0 is 100%</p>
                    </div>
                    
                    <div class="form-group">
                        <label>Music Icon URL (Optional)</label>
                        <input type="text" name="music_icon" value="<?php echo htmlspecialchars($config['music']['icon']); ?>" placeholder="default or custom URL">
                        <p class="hint">Use 'default' for Font Awesome icon or upload custom icon below</p>
                    </div>
                </div>
                
                <!-- Meta Section -->
                <div class="section">
                    <h3><i class="fas fa-info-circle"></i> Meta Information</h3>
                    
                    <div class="form-group">
                        <label>Page Title</label>
                        <input type="text" name="title" value="<?php echo htmlspecialchars($config['meta']['title']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Meta Description</label>
                        <input type="text" name="meta_description" value="<?php echo htmlspecialchars($config['meta']['description']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Footer Text</label>
                        <input type="text" name="footer" value="<?php echo htmlspecialchars($config['meta']['footer']); ?>" required>
                    </div>
                </div>
                
                <button type="submit" name="update_settings" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Settings
                </button>
            </form>
            
            <!-- Custom Social Links Section -->
            <div class="section" style="margin-top: 30px;">
                <h3><i class="fas fa-plus-circle"></i> Custom Social Links</h3>
                
                <?php if (isset($config['custom_links']) && !empty($config['custom_links'])): ?>
                    <div style="margin-bottom: 20px;">
                        <?php foreach ($config['custom_links'] as $index => $link): ?>
                            <div class="custom-link-item">
                                <div class="custom-link-info">
                                    <i class="<?php echo htmlspecialchars($link['icon']); ?> custom-link-icon" style="color: <?php echo htmlspecialchars($link['color']); ?>"></i>
                                    <div>
                                        <strong style="color: #fff;"><?php echo htmlspecialchars($link['name']); ?></strong>
                                        <br>
                                        <span style="color: #888; font-size: 12px;"><?php echo htmlspecialchars($link['url']); ?></span>
                                    </div>
                                </div>
                                <a href="?delete_link=<?php echo $index; ?>" class="btn-delete" onclick="return confirm('Delete this link?')">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" id="customLinkForm">
                    <div class="form-group">
                        <label>Link Name</label>
                        <input type="text" name="link_name" placeholder="e.g., GitHub, Twitter, TikTok" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Link URL</label>
                        <input type="text" name="link_url" placeholder="https://example.com" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Icon (Font Awesome class or custom URL)</label>
                        <input type="text" name="link_icon" id="selectedIcon" value="fab fa-link" required>
                        <p class="hint">Click an icon below or enter custom: fas fa-star, fab fa-github, etc.</p>
                        
                        <div class="icon-grid">
                            <div class="icon-option" onclick="selectIcon('fab fa-github')"><i class="fab fa-github"></i></div>
                            <div class="icon-option" onclick="selectIcon('fab fa-twitter')"><i class="fab fa-twitter"></i></div>
                            <div class="icon-option" onclick="selectIcon('fab fa-facebook')"><i class="fab fa-facebook"></i></div>
                            <div class="icon-option" onclick="selectIcon('fab fa-linkedin')"><i class="fab fa-linkedin"></i></div>
                            <div class="icon-option" onclick="selectIcon('fab fa-tiktok')"><i class="fab fa-tiktok"></i></div>
                            <div class="icon-option" onclick="selectIcon('fab fa-twitch')"><i class="fab fa-twitch"></i></div>
                            <div class="icon-option" onclick="selectIcon('fab fa-reddit')"><i class="fab fa-reddit"></i></div>
                            <div class="icon-option" onclick="selectIcon('fab fa-snapchat')"><i class="fab fa-snapchat"></i></div>
                            <div class="icon-option" onclick="selectIcon('fab fa-telegram')"><i class="fab fa-telegram"></i></div>
                            <div class="icon-option" onclick="selectIcon('fab fa-whatsapp')"><i class="fab fa-whatsapp"></i></div>
                            <div class="icon-option" onclick="selectIcon('fab fa-spotify')"><i class="fab fa-spotify"></i></div>
                            <div class="icon-option" onclick="selectIcon('fab fa-steam')"><i class="fab fa-steam"></i></div>
                            <div class="icon-option" onclick="selectIcon('fas fa-globe')"><i class="fas fa-globe"></i></div>
                            <div class="icon-option" onclick="selectIcon('fas fa-envelope')"><i class="fas fa-envelope"></i></div>
                            <div class="icon-option" onclick="selectIcon('fas fa-phone')"><i class="fas fa-phone"></i></div>
                            <div class="icon-option" onclick="selectIcon('fas fa-gamepad')"><i class="fas fa-gamepad"></i></div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Icon Color</label>
                        <input type="text" name="link_color" value="#10b981" placeholder="#10b981">
                        <p class="hint">Hex color code (e.g., #10b981, #FF0000, #5865F2)</p>
                    </div>
                    
                    <button type="submit" name="add_custom_link" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Custom Link
                    </button>
                </form>
            </div>
            
            <!-- File Upload & Management Section -->
            <div class="section" style="margin-top: 30px;">
                <h3><i class="fas fa-upload"></i> File Management</h3>
                
                <!-- Show Uploaded Files -->
                <div class="uploaded-files">
                    <h4 style="color: #888; margin-bottom: 15px;">Uploaded Files</h4>
                    
                    <?php
                    $files = [
                        'profile.jpg' => 'Profile Image',
                        'background.mp4' => 'Background Video',
                        'background.jpg' => 'Background Image',
                        'music.mp3' => 'Background Music',
                        'music-icon.png' => 'Music Icon'
                    ];
                    
                    foreach ($files as $filename => $label):
                        if (file_exists($filename)):
                            $filesize = filesize($filename);
                            $filesizeMB = number_format($filesize / 1048576, 2);
                    ?>
                        <div class="file-item">
                            <div class="file-info">
                                <i class="fas fa-file"></i>
                                <div>
                                    <strong style="color: #fff;"><?php echo $label; ?></strong>
                                    <br>
                                    <span style="font-size: 12px;"><?php echo $filename; ?> (<?php echo $filesizeMB; ?> MB)</span>
                                </div>
                            </div>
                            <div class="file-actions">
                                <a href="<?php echo $filename; ?>" target="_blank" class="btn-delete" style="background: rgba(16, 185, 129, 0.8);">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="?delete_file=<?php echo $filename; ?>" class="btn-delete" onclick="return confirm('Delete this file?')">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </div>
                        </div>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </div>
                
                <!-- Profile Image Upload -->
                <div style="margin-top: 25px;">
                    <h4 style="color: #888; margin-bottom: 10px;">Upload Profile Image</h4>
                    <div class="file-upload-wrapper">
                        <label for="profileImage" class="file-upload-btn">
                            <i class="fas fa-image"></i> Choose Profile Image (JPG/PNG)
                        </label>
                        <input type="file" id="profileImage" accept="image/*">
                        <div class="progress-container" id="profileProgress">
                            <div class="progress-bar" id="profileBar">0%</div>
                        </div>
                    </div>
                </div>
                
                <!-- Background Video Upload -->
                <div style="margin-top: 25px;">
                    <h4 style="color: #888; margin-bottom: 10px;">Upload Background Video</h4>
                    <div class="file-upload-wrapper">
                        <label for="backgroundVideo" class="file-upload-btn">
                            <i class="fas fa-video"></i> Choose Background Video (MP4)
                        </label>
                        <input type="file" id="backgroundVideo" accept="video/mp4">
                        <div class="progress-container" id="videoProgress">
                            <div class="progress-bar" id="videoBar">0%</div>
                        </div>
                    </div>
                    <p class="hint" style="margin-top: 8px;">Recommended: 1920x1080, under 50MB</p>
                </div>
                
                <!-- Background Image Upload -->
                <div style="margin-top: 25px;">
                    <h4 style="color: #888; margin-bottom: 10px;">Upload Background Image</h4>
                    <div class="file-upload-wrapper">
                        <label for="backgroundImage" class="file-upload-btn">
                            <i class="fas fa-image"></i> Choose Background Image (JPG/PNG)
                        </label>
                        <input type="file" id="backgroundImage" accept="image/*">
                        <div class="progress-container" id="imageProgress">
                            <div class="progress-bar" id="imageBar">0%</div>
                        </div>
                    </div>
                    <p class="hint" style="margin-top: 8px;">Recommended: 1920x1080 or higher resolution</p>
                </div>
                
                <!-- Music Upload -->
                <div style="margin-top: 25px;">
                    <h4 style="color: #888; margin-bottom: 10px;">Upload Background Music</h4>
                    <div class="file-upload-wrapper">
                        <label for="musicFile" class="file-upload-btn">
                            <i class="fas fa-music"></i> Choose Music File (MP3)
                        </label>
                        <input type="file" id="musicFile" accept="audio/mpeg">
                        <div class="progress-container" id="musicProgress">
                            <div class="progress-bar" id="musicBar">0%</div>
                        </div>
                    </div>
                </div>
                
                <!-- Music Icon Upload -->
                <div style="margin-top: 25px;">
                    <h4 style="color: #888; margin-bottom: 10px;">Upload Music Icon</h4>
                    <div class="file-upload-wrapper">
                        <label for="musicIcon" class="file-upload-btn">
                            <i class="fas fa-icons"></i> Choose Music Icon (PNG/JPG)
                        </label>
                        <input type="file" id="musicIcon" accept="image/*">
                        <div class="progress-container" id="iconProgress">
                            <div class="progress-bar" id="iconBar">0%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <script>
        // Icon selection
        function selectIcon(iconClass) {
            document.getElementById('selectedIcon').value = iconClass;
            
            // Visual feedback
            document.querySelectorAll('.icon-option').forEach(el => {
                el.classList.remove('selected');
            });
            event.currentTarget.classList.add('selected');
        }
        
        // File upload with progress
        function handleFileUpload(inputId, progressId, barId, uploadName) {
            const input = document.getElementById(inputId);
            const progress = document.getElementById(progressId);
            const bar = document.getElementById(barId);
            
            input.addEventListener('change', function() {
                const file = this.files[0];
                if (!file) return;
                
                const formData = new FormData();
                formData.append(uploadName, file);
                
                const xhr = new XMLHttpRequest();
                
                xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                        const percentComplete = (e.loaded / e.total) * 100;
                        progress.style.display = 'block';
                        bar.style.width = percentComplete + '%';
                        bar.textContent = Math.round(percentComplete) + '%';
                    }
                });
                
                xhr.addEventListener('load', function() {
                    if (xhr.status === 200) {
                        bar.textContent = 'Upload Complete!';
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        bar.textContent = 'Upload Failed!';
                        bar.style.background = 'linear-gradient(90deg, #dc3545, #c82333)';
                    }
                });
                
                xhr.addEventListener('error', function() {
                    bar.textContent = 'Upload Error!';
                    bar.style.background = 'linear-gradient(90deg, #dc3545, #c82333)';
                });
                
                xhr.open('POST', 'upload.php');
                xhr.send(formData);
            });
        }<!-- Color Customization Section -->
<div class="section">
    <h3><i class="fas fa-palette"></i> Color Customization</h3>
    
    <div style="margin-bottom: 25px;">
        <h4 style="color: #10b981; margin-bottom: 15px;">Portal Colors</h4>
        
        <div class="form-group">
            <label>Primary Color</label>
            <div style="display: flex; gap: 10px; align-items: center;">
                <input type="color" name="color_primary" id="color_primary" 
                       value="<?php echo htmlspecialchars($config['colors']['primary']); ?>" 
                       style="width: 60px; height: 40px; border: none; border-radius: 6px; cursor: pointer;">
                <input type="text" name="color_primary_text" 
                       value="<?php echo htmlspecialchars($config['colors']['primary']); ?>" 
                       placeholder="#10b981" 
                       oninput="document.getElementById('color_primary').value = this.value"
                       style="flex: 1;">
            </div>
            <p class="hint">Main brand color - buttons, links, highlights</p>
        </div>
        
        <div class="form-group">
            <label>Secondary Color</label>
            <div style="display: flex; gap: 10px; align-items: center;">
                <input type="color" name="color_secondary" id="color_secondary" 
                       value="<?php echo htmlspecialchars($config['colors']['secondary']); ?>" 
                       style="width: 60px; height: 40px; border: none; border-radius: 6px; cursor: pointer;">
                <input type="text" name="color_secondary_text" 
                       value="<?php echo htmlspecialchars($config['colors']['secondary']); ?>" 
                       placeholder="#888888"
                       oninput="document.getElementById('color_secondary').value = this.value"
                       style="flex: 1;">
            </div>
            <p class="hint">Secondary elements, borders, subtle text</p>
        </div>
        
        <div class="form-group">
            <label>Glow Color (Title Effect)</label>
            <div style="display: flex; gap: 10px; align-items: center;">
                <input type="color" name="color_glow" id="color_glow" 
                       value="<?php echo htmlspecialchars($config['colors']['glow_color']); ?>" 
                       style="width: 60px; height: 40px; border: none; border-radius: 6px; cursor: pointer;">
                <input type="text" name="color_glow_text" 
                       value="<?php echo htmlspecialchars($config['colors']['glow_color']); ?>" 
                       placeholder="#ffffff"
                       oninput="document.getElementById('color_glow').value = this.value"
                       style="flex: 1;">
            </div>
            <p class="hint">Color of the glowing effect on your name</p>
        </div>
        
        <div class="form-group">
            <label>Card Background (RGBA format)</label>
            <input type="text" name="color_card_bg" 
                   value="<?php echo htmlspecialchars($config['colors']['card_background']); ?>" 
                   placeholder="rgba(20, 20, 20, 0.95)">
            <p class="hint">Main container background - supports transparency (rgba)</p>
        </div>
        
        <div class="form-group">
            <label>Background Overlay (RGBA format)</label>
            <input type="text" name="color_bg_overlay" 
                   value="<?php echo htmlspecialchars($config['colors']['background_overlay']); ?>" 
                   placeholder="rgba(0, 0, 0, 0.85)">
            <p class="hint">Darkening over video/image background</p>
        </div>
    </div>
    
    <div style="margin-bottom: 25px;">
        <h4 style="color: #10b981; margin-bottom: 15px;">Quick Preset Themes</h4>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 10px;">
            <button type="button" onclick="applyTheme('cyber')" class="btn" style="background: linear-gradient(135deg, #FF00FF, #00FFFF); padding: 10px;">
                Cyberpunk
            </button>
            <button type="button" onclick="applyTheme('matrix')" class="btn" style="background: linear-gradient(135deg, #00FF00, #003300); padding: 10px;">
                Matrix
            </button>
            <button type="button" onclick="applyTheme('ocean')" class="btn" style="background: linear-gradient(135deg, #0099FF, #006699); padding: 10px;">
                Ocean
            </button>
            <button type="button" onclick="applyTheme('fire')" class="btn" style="background: linear-gradient(135deg, #FF4500, #FF8C00); padding: 10px;">
                Fire
            </button>
            <button type="button" onclick="applyTheme('purple')" class="btn" style="background: linear-gradient(135deg, #9D4EDD, #7209B7); padding: 10px;">
                Purple Haze
            </button>
            <button type="button" onclick="applyTheme('gold')" class="btn" style="background: linear-gradient(135deg, #FFD700, #FFA500); padding: 10px;">
                Gold Luxury
            </button>
        </div>
    </div>
</div>

<script>
// Theme presets
const themes = {
    cyber: {
        primary: '#FF00FF',
        secondary: '#00FFFF',
        glow: '#FF00FF'
    },
    matrix: {
        primary: '#00FF00',
        secondary: '#003300',
        glow: '#00FF00'
    },
    ocean: {
        primary: '#0099FF',
        secondary: '#006699',
        glow: '#00CCFF'
    },
    fire: {
        primary: '#FF4500',
        secondary: '#FF8C00',
        glow: '#FF0000'
    },
    purple: {
        primary: '#9D4EDD',
        secondary: '#7209B7',
        glow: '#C77DFF'
    },
    gold: {
        primary: '#FFD700',
        secondary: '#FFA500',
        glow: '#FFD700'
    }
};

function applyTheme(themeName) {
    const theme = themes[themeName];
    document.getElementById('color_primary').value = theme.primary;
    document.querySelector('input[name="color_primary_text"]').value = theme.primary;
    document.getElementById('color_secondary').value = theme.secondary;
    document.querySelector('input[name="color_secondary_text"]').value = theme.secondary;
    document.getElementById('color_glow').value = theme.glow;
    document.querySelector('input[name="color_glow_text"]').value = theme.glow;
    
    alert('Theme applied! Don\'t forget to Save Settings.');
}

// Sync color picker with text input
document.querySelectorAll('input[type="color"]').forEach(picker => {
    picker.addEventListener('input', function() {
        const textInput = this.parentElement.querySelector('input[type="text"]');
        if (textInput) {
            textInput.value = this.value;
        }
    });
});
</script>
        
        // Initialize file uploads
        handleFileUpload('profileImage', 'profileProgress', 'profileBar', 'profile_image');
        handleFileUpload('backgroundVideo', 'videoProgress', 'videoBar', 'background_video');
        handleFileUpload('backgroundImage', 'imageProgress', 'imageBar', 'background_image');
        handleFileUpload('musicFile', 'musicProgress', 'musicBar', 'music_file');
        handleFileUpload('musicIcon', 'iconProgress', 'iconBar', 'music_icon_file');
    </script>
</body>
</html>
