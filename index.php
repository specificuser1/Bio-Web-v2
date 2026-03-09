<?php
// Load configuration
$config = include 'config.php';

// Track visitor
require_once 'analytics.php';
trackVisitor();

// Extract colors for easy use
$colors = $config['colors'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars($config['meta']['description']); ?>">
    <title><?php echo htmlspecialchars($config['meta']['title']); ?></title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Google Fonts - Distinctive Typography -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            /* Dynamic Colors from Config */
            --primary: <?php echo $colors['primary']; ?>;
            --secondary: <?php echo $colors['secondary']; ?>;
            --accent: <?php echo $colors['accent']; ?>;
            --bg-overlay: <?php echo $colors['background_overlay']; ?>;
            --card-bg: <?php echo $colors['card_background']; ?>;
            --text-primary: <?php echo $colors['text_primary']; ?>;
            --text-secondary: <?php echo $colors['text_secondary']; ?>;
            --glow: <?php echo $colors['glow_color']; ?>;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Space Mono', monospace;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
            background: #000;
        }
        
        /* Background System */
        .background-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            overflow: hidden;
        }
        
        .background-container video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .background-container .image-background {
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        
        .background-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--bg-overlay);
            z-index: -1;
        }
        
        /* Particle Canvas */
        #particles-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            pointer-events: none;
        }
        
        /* Main Container - Advanced Design */
        .container {
            max-width: 700px;
            width: 100%;
            background: var(--card-bg);
            border-radius: 30px;
            padding: 60px 50px;
            box-shadow: 
                0 30px 80px rgba(0, 0, 0, 0.9),
                0 0 0 1px rgba(255, 255, 255, 0.05),
                inset 0 1px 0 0 rgba(255, 255, 255, 0.1);
            text-align: center;
            animation: containerEntrance 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            z-index: 10;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        @keyframes containerEntrance {
            from {
                opacity: 0;
                transform: translateY(40px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        /* Decorative Corner Elements */
        .container::before,
        .container::after {
            content: '';
            position: absolute;
            width: 40px;
            height: 40px;
            border: 2px solid var(--primary);
            opacity: 0.5;
        }
        
        .container::before {
            top: 20px;
            left: 20px;
            border-right: none;
            border-bottom: none;
        }
        
        .container::after {
            bottom: 20px;
            right: 20px;
            border-left: none;
            border-top: none;
        }
        
        /* Profile Section */
        .profile-section {
            margin-bottom: 40px;
            position: relative;
        }
        
        .profile-img-wrapper {
            position: relative;
            width: 180px;
            height: 180px;
            margin: 0 auto 30px;
        }
        
        .profile-img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 4px solid var(--primary);
            object-fit: cover;
            box-shadow: 
                0 20px 40px rgba(0, 0, 0, 0.5),
                0 0 40px var(--primary);
            animation: profilePulse 3s ease-in-out infinite;
            position: relative;
            z-index: 2;
        }
        
        @keyframes profilePulse {
            0%, 100% {
                box-shadow: 
                    0 20px 40px rgba(0, 0, 0, 0.5),
                    0 0 40px var(--primary);
            }
            50% {
                box-shadow: 
                    0 20px 40px rgba(0, 0, 0, 0.5),
                    0 0 60px var(--primary),
                    0 0 80px var(--primary);
            }
        }
        
        /* Rotating Ring Behind Profile */
        .profile-ring {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 200px;
            height: 200px;
            border: 2px solid transparent;
            border-top-color: var(--primary);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            animation: ringRotate 4s linear infinite;
            opacity: 0.6;
        }
        
        @keyframes ringRotate {
            from {
                transform: translate(-50%, -50%) rotate(0deg);
            }
            to {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }
        
        /* Title with Advanced Glow */
        h1 {
            font-family: 'Orbitron', sans-serif;
            color: var(--text-primary);
            font-size: 3em;
            font-weight: 900;
            margin-bottom: 15px;
            text-shadow: 
                0 0 20px var(--glow),
                0 0 40px var(--glow),
                0 0 60px var(--glow),
                0 0 80px var(--glow),
                0 5px 20px rgba(0, 0, 0, 0.8);
            animation: titleGlow 3s ease-in-out infinite alternate;
            letter-spacing: 2px;
            position: relative;
            display: inline-block;
        }
        
        @keyframes titleGlow {
            from {
                text-shadow: 
                    0 0 20px var(--glow),
                    0 0 40px var(--glow),
                    0 0 60px var(--glow),
                    0 5px 20px rgba(0, 0, 0, 0.8);
            }
            to {
                text-shadow: 
                    0 0 30px var(--glow),
                    0 0 50px var(--glow),
                    0 0 70px var(--glow),
                    0 0 90px var(--glow),
                    0 0 110px var(--glow),
                    0 5px 20px rgba(0, 0, 0, 0.8);
            }
        }
        
        .tagline {
            color: var(--primary);
            font-size: 1.2em;
            font-weight: 700;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 3px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        }
        
        .description {
            color: var(--text-secondary);
            font-size: 1.1em;
            line-height: 1.8;
            margin-bottom: 35px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }
        
        /* Skills with Glassmorphism */
        .skills {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            justify-content: center;
            margin-bottom: 45px;
        }
        
        .skill-tag {
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0.1), 
                rgba(255, 255, 255, 0.05));
            backdrop-filter: blur(10px);
            color: var(--text-primary);
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 0.85em;
            font-weight: 700;
            border: 1px solid rgba(255, 255, 255, 0.2);
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
        }
        
        .skill-tag::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, 
                transparent, 
                rgba(255, 255, 255, 0.2), 
                transparent);
            transition: left 0.5s;
        }
        
        .skill-tag:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            border-color: var(--primary);
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0.15), 
                rgba(255, 255, 255, 0.1));
        }
        
        .skill-tag:hover::before {
            left: 100%;
        }
        
        /* Social Links - Grid Layout */
        .social-links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            margin-top: 35px;
        }
        
        .social-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 18px 25px;
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0.08), 
                rgba(255, 255, 255, 0.04));
            backdrop-filter: blur(10px);
            border: 1.5px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            text-decoration: none;
            color: var(--text-primary);
            font-weight: 700;
            font-size: 1em;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
        }
        
        .social-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--primary);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            z-index: -1;
        }
        
        .social-link:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.6);
            border-color: var(--primary);
        }
        
        .social-link:hover::before {
            transform: scaleX(1);
        }
        
        .social-link i {
            font-size: 1.6em;
            transition: transform 0.3s;
        }
        
        .social-link:hover i {
            transform: scale(1.2) rotate(5deg);
        }
        
        /* Specific Link Colors */
        .discord:hover { --primary: #5865F2; }
        .youtube:hover { --primary: #FF0000; }
        .instagram:hover { --primary: #E1306C; }
        .shop:hover { --primary: #10b981; }
        
        .discord i { color: #5865F2; }
        .youtube i { color: #FF0000; }
        .instagram i { color: #E1306C; }
        .shop i { color: #10b981; }
        
        /* Custom Link Styling */
        .custom-link:hover {
            --primary: var(--hover-color);
        }
        
        /* Footer */
        footer {
            margin-top: 50px;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--secondary);
            font-size: 0.9em;
        }
        
        /* Music Player - Floating */
        .music-player {
            position: fixed;
            bottom: 40px;
            right: 40px;
            z-index: 1000;
        }
        
        .music-button {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: 3px solid rgba(255, 255, 255, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 
                0 10px 30px rgba(0, 0, 0, 0.6),
                0 0 40px var(--primary);
            animation: musicPulse 2s ease-in-out infinite;
        }
        
        @keyframes musicPulse {
            0%, 100% {
                box-shadow: 
                    0 10px 30px rgba(0, 0, 0, 0.6),
                    0 0 40px var(--primary);
            }
            50% {
                box-shadow: 
                    0 10px 30px rgba(0, 0, 0, 0.6),
                    0 0 60px var(--primary),
                    0 0 80px var(--primary);
            }
        }
        
        .music-button:hover {
            transform: scale(1.15);
            box-shadow: 
                0 15px 40px rgba(0, 0, 0, 0.7),
                0 0 70px var(--primary);
        }
        
        .music-button img {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }
        
        .music-button i {
            font-size: 32px;
            color: #fff;
        }
        
        .music-button.playing {
            animation: musicRotate 3s linear infinite, musicPulse 2s ease-in-out infinite;
        }
        
        @keyframes musicRotate {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 40px 30px;
                border-radius: 20px;
            }
            
            h1 {
                font-size: 2.2em;
            }
            
            .tagline {
                font-size: 1em;
            }
            
            .profile-img-wrapper {
                width: 140px;
                height: 140px;
            }
            
            .social-links {
                grid-template-columns: 1fr;
            }
            
            .music-player {
                bottom: 20px;
                right: 20px;
            }
            
            .music-button {
                width: 60px;
                height: 60px;
            }
            
            .music-button i {
                font-size: 26px;
            }
        }
    </style>
</head>
<body>
    <!-- Background System -->
    <div class="background-container">
        <?php if ($config['profile']['background_type'] === 'video'): ?>
            <video autoplay muted loop playsinline>
                <source src="<?php echo htmlspecialchars($config['profile']['background_video']); ?>" type="video/mp4">
            </video>
        <?php else: ?>
            <div class="image-background" style="background-image: url('<?php echo htmlspecialchars($config['profile']['background_image']); ?>');"></div>
        <?php endif; ?>
    </div>
    <div class="background-overlay"></div>
    
    <!-- Particle Canvas -->
    <canvas id="particles-canvas"></canvas>
    
    <!-- Music Player -->
    <?php if ($config['music']['enabled']): ?>
    <div class="music-player">
        <div class="music-button" id="musicToggle">
            <?php if ($config['music']['icon'] !== 'default' && !empty($config['music']['icon'])): ?>
                <img src="<?php echo htmlspecialchars($config['music']['icon']); ?>" alt="Music">
            <?php else: ?>
                <i class="fas fa-music"></i>
            <?php endif; ?>
        </div>
    </div>
    
    <audio id="backgroundMusic" loop>
        <?php 
        $audioSrc = !empty($config['music']['custom_url']) ? 
                    $config['music']['custom_url'] : 
                    $config['music']['audio_file'];
        ?>
        <source src="<?php echo htmlspecialchars($audioSrc); ?>" type="audio/mpeg">
    </audio>
    <?php endif; ?>
    
    <!-- Main Container -->
    <div class="container">
        <div class="profile-section">
            <div class="profile-img-wrapper">
                <div class="profile-ring"></div>
                <img src="<?php echo htmlspecialchars($config['profile']['image']); ?>" 
                     alt="<?php echo htmlspecialchars($config['profile']['name']); ?>" 
                     class="profile-img">
            </div>
            
            <h1><?php echo htmlspecialchars($config['profile']['name']); ?></h1>
            <p class="tagline"><?php echo htmlspecialchars($config['profile']['tagline']); ?></p>
            <p class="description">
                <?php echo htmlspecialchars($config['profile']['description']); ?>
            </p>
        </div>
        
        <div class="skills">
            <?php foreach($config['skills'] as $skill): ?>
                <span class="skill-tag"><?php echo htmlspecialchars($skill); ?></span>
            <?php endforeach; ?>
        </div>
        
        <div class="social-links">
            <a href="<?php echo htmlspecialchars($config['social']['discord']); ?>" 
               class="social-link discord" target="_blank">
                <i class="fab fa-discord"></i>
                <span>Discord</span>
            </a>
            
            <a href="<?php echo htmlspecialchars($config['social']['youtube']); ?>" 
               class="social-link youtube" target="_blank">
                <i class="fab fa-youtube"></i>
                <span>YouTube</span>
            </a>
            
            <a href="<?php echo htmlspecialchars($config['social']['instagram']); ?>" 
               class="social-link instagram" target="_blank">
                <i class="fab fa-instagram"></i>
                <span>Instagram</span>
            </a>
            
            <a href="<?php echo htmlspecialchars($config['social']['shop']); ?>" 
               class="social-link shop" target="_blank">
                <i class="fas fa-shopping-cart"></i>
                <span>Shop</span>
            </a>
            
            <?php if (isset($config['custom_links']) && !empty($config['custom_links'])): ?>
                <?php foreach ($config['custom_links'] as $link): ?>
                    <a href="<?php echo htmlspecialchars($link['url']); ?>" 
                       class="social-link custom-link" 
                       target="_blank" 
                       style="--hover-color: <?php echo htmlspecialchars($link['color']); ?>">
                        <i class="<?php echo htmlspecialchars($link['icon']); ?>" 
                           style="color: <?php echo htmlspecialchars($link['color']); ?>"></i>
                        <span><?php echo htmlspecialchars($link['name']); ?></span>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <footer>
            <p><?php echo $config['meta']['footer']; ?></p>
        </footer>
    </div>
    
    <script>
        // Advanced Particle System
        const canvas = document.getElementById('particles-canvas');
        const ctx = canvas.getContext('2d');
        
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        
        window.addEventListener('resize', () => {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        });
        
        class Particle {
            constructor() {
                this.reset();
            }
            
            reset() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.size = Math.random() * 4 + 1;
                this.speedX = (Math.random() - 0.5) * 2;
                this.speedY = (Math.random() - 0.5) * 2;
                this.opacity = Math.random() * 0.6 + 0.2;
                this.twinkle = Math.random() * Math.PI * 2;
            }
            
            update() {
                this.x += this.speedX;
                this.y += this.speedY;
                this.twinkle += 0.05;
                
                if (this.x > canvas.width || this.x < 0 || 
                    this.y > canvas.height || this.y < 0) {
                    this.reset();
                }
            }
            
            draw() {
                const alpha = this.opacity * (Math.sin(this.twinkle) * 0.5 + 0.5);
                ctx.fillStyle = `rgba(255, 255, 255, ${alpha})`;
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fill();
                
                ctx.shadowBlur = 15;
                ctx.shadowColor = 'rgba(255, 255, 255, 0.8)';
            }
        }
        
        const particles = [];
        for (let i = 0; i < 120; i++) {
            particles.push(new Particle());
        }
        
        function animateParticles() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            particles.forEach(particle => {
                particle.update();
                particle.draw();
            });
            
            requestAnimationFrame(animateParticles);
        }
        
        animateParticles();
        
        // Music Player
        <?php if ($config['music']['enabled']): ?>
        const music = document.getElementById('backgroundMusic');
        const musicToggle = document.getElementById('musicToggle');
        let isPlaying = false;
        
        music.volume = <?php echo $config['music']['volume']; ?>;
        
        musicToggle.addEventListener('click', function() {
            if (isPlaying) {
                music.pause();
                musicToggle.classList.remove('playing');
                isPlaying = false;
            } else {
                music.play().catch(e => console.log('Play prevented:', e));
                musicToggle.classList.add('playing');
                isPlaying = true;
            }
        });
        
        // Auto-play on page load
        window.addEventListener('load', function() {
            music.play().then(() => {
                musicToggle.classList.add('playing');
                isPlaying = true;
            }).catch(() => {
                const tryAutoPlay = () => {
                    music.play().then(() => {
                        musicToggle.classList.add('playing');
                        isPlaying = true;
                    }).catch(() => {});
                    
                    document.removeEventListener('click', tryAutoPlay);
                    document.removeEventListener('touchstart', tryAutoPlay);
                    document.removeEventListener('scroll', tryAutoPlay);
                };
                
                document.addEventListener('click', tryAutoPlay, { once: true });
                document.addEventListener('touchstart', tryAutoPlay, { once: true });
                document.addEventListener('scroll', tryAutoPlay, { once: true });
            });
        });
        <?php endif; ?>
    </script>
</body>
</html>
