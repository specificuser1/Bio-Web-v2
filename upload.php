<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$uploadDir = './';
$response = ['success' => false, 'message' => ''];

// Handle Profile Image Upload
if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
    $fileName = 'profile.jpg';
    $filePath = $uploadDir . $fileName;
    
    if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $filePath)) {
        $response['success'] = true;
        $response['message'] = 'Profile image uploaded successfully!';
    } else {
        $response['message'] = 'Failed to upload profile image.';
    }
}

// Handle Background Video Upload
elseif (isset($_FILES['background_video']) && $_FILES['background_video']['error'] === UPLOAD_ERR_OK) {
    $fileName = 'background.mp4';
    $filePath = $uploadDir . $fileName;
    
    if (move_uploaded_file($_FILES['background_video']['tmp_name'], $filePath)) {
        $response['success'] = true;
        $response['message'] = 'Background video uploaded successfully!';
    } else {
        $response['message'] = 'Failed to upload background video.';
    }
}

// Handle Background Image Upload
elseif (isset($_FILES['background_image']) && $_FILES['background_image']['error'] === UPLOAD_ERR_OK) {
    $fileName = 'background.jpg';
    $filePath = $uploadDir . $fileName;
    
    if (move_uploaded_file($_FILES['background_image']['tmp_name'], $filePath)) {
        $response['success'] = true;
        $response['message'] = 'Background image uploaded successfully!';
    } else {
        $response['message'] = 'Failed to upload background image.';
    }
}

// Handle Music File Upload
elseif (isset($_FILES['music_file']) && $_FILES['music_file']['error'] === UPLOAD_ERR_OK) {
    $fileName = 'music.mp3';
    $filePath = $uploadDir . $fileName;
    
    if (move_uploaded_file($_FILES['music_file']['tmp_name'], $filePath)) {
        $response['success'] = true;
        $response['message'] = 'Music file uploaded successfully!';
    } else {
        $response['message'] = 'Failed to upload music file.';
    }
}

// Handle Music Icon Upload
elseif (isset($_FILES['music_icon_file']) && $_FILES['music_icon_file']['error'] === UPLOAD_ERR_OK) {
    $fileName = 'music-icon.png';
    $filePath = $uploadDir . $fileName;
    
    if (move_uploaded_file($_FILES['music_icon_file']['tmp_name'], $filePath)) {
        // Update config to use this icon
        $config = include 'config.php';
        $config['music']['icon'] = 'music-icon.png';
        
        require_once 'config.php';
        saveConfig($config);
        
        $response['success'] = true;
        $response['message'] = 'Music icon uploaded successfully!';
    } else {
        $response['message'] = 'Failed to upload music icon.';
    }
}

else {
    $response['message'] = 'No file uploaded or upload error.';
}

header('Content-Type: application/json');
echo json_encode($response);
?>
