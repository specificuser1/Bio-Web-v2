# SUBHAN DEV Portal - Installation Guide

## 📦 Files Included / شامل فائلیں

1. **index.php** - Main portal page (Video background ke sath)
2. **admin.php** - Admin panel (Settings change karne ke liye)
3. **config.php** - Configuration file (Sari settings yahan stored hain)
4. **README.md** - Ye file (Instructions)

---

## 🚀 Railway Pe Host Kaise Karein / How to Host on Railway

### Step 1: Files Upload Karein
1. Railway project banayein
2. **In files ko upload karein (IMPORTANT - Sab files):**
   - ✅ index.php
   - ✅ admin.php
   - ✅ upload.php
   - ✅ config.php
   - ✅ **.gitignore** (MUST UPLOAD - Settings protection ke liye!)
   - ✅ railway.json (Optional)
3. Media files (optional):
   - profile.jpg
   - background.mp4
   - music.mp3

**⚠️ IMPORTANT:** `.gitignore` file zaroor upload karein! Yeh file aap ki settings ko protect karti hai.

### Step 2: Settings
Railway automatically PHP detect kar lega aur run kar dega

### Step 3: Access & Test
- Main Portal: `https://your-app.railway.app/`
- Admin Panel: `https://your-app.railway.app/admin.php`

### Step 4: Verify Persistence
1. Admin panel mein kuch change karo
2. Save karo
3. Railway service restart karo
4. Check karo - settings wahi hongi! ✅

---

## 🎨 Video/Image Background Kaise Setup Karein / How to Setup Background

### Option 1: Video Background
1. Admin panel mein login karein
2. "Profile Information" section mein
3. **"Background Type"** → Select "Video Background"
4. Save Settings
5. "File Management" section mein jao
6. "Upload Background Video" se MP4 file upload karo

**Video Requirements:**
- Format: MP4
- Recommended Size: Under 50MB (fast loading ke liye)
- Resolution: 1920x1080 ya 1280x720
- Duration: 10-30 seconds (loop ho jayega automatically)

### Option 2: Image Background (NEW!)
1. Admin panel mein login karein
2. "Profile Information" section mein
3. **"Background Type"** → Select "Image Background"
4. Save Settings
5. "File Management" section mein jao
6. "Upload Background Image" se JPG/PNG file upload karo

**Image Requirements:**
- Format: JPG or PNG
- Recommended Size: Under 5MB
- Resolution: 1920x1080 or higher (Full HD recommended)
- Aspect Ratio: 16:9 best for widescreen

### Toggle Between Video/Image:
- Admin panel → Profile Information
- Change "Background Type" dropdown
- Save Settings
- Upload corresponding file

---

## 🎵 Music System Kaise Setup Karein / How to Setup Music

### Option 1: Local Music File Upload (Admin Panel)
1. Admin panel mein login karein
2. "Upload Files" section mein jayein
3. "Background Music (MP3)" select karein
4. Apna MP3 file upload karein
5. "Music Settings" mein "Enable Background Music" check karein

### Option 2: Custom URL Se Music (Online Link)
1. Admin panel → "Music Settings" section
2. "Custom Music URL" mein apna direct MP3 link paste karein
3. Example: `https://example.com/mymusic.mp3`
4. "Enable Background Music" check karein
5. Save Settings

### Music Player Features:
- 🎵 Auto-loop music
- 🔘 Click music icon to play/pause
- 🔊 Adjustable volume (Admin panel se)
- 🎨 Custom icon support (Upload apna icon)
- 📱 Mobile compatible

**Music File Requirements:**
- Format: MP3
- Size: Under 10MB recommended
- Quality: 128kbps - 320kbps

### Custom Music Icon Upload:
1. Admin panel → "Upload Files"
2. "Music Icon (PNG/JPG)" select karein
3. 50x50px ya 100x100px icon upload karein
4. Automatically apply ho jayega

---

## ➕ Custom Social Links Kaise Add Karein / How to Add Custom Links

### Admin Panel Se:
1. Admin panel kholo
2. "Custom Social Links" section mein jao (scroll down)
3. Form fill karo:
   - **Link Name**: GitHub, Twitter, TikTok, etc.
   - **Link URL**: `https://github.com/your-username`
   - **Icon**: Click karo icon grid se ya manually enter karo
   - **Color**: Hex color code (#FF0000, #5865F2, etc.)
4. "Add Custom Link" button pe click karo

### Available Icons:
- GitHub, Twitter, Facebook, LinkedIn
- TikTok, Twitch, Reddit, Snapchat
- Telegram, WhatsApp, Spotify, Steam
- Globe, Email, Phone, Gamepad
- **16+ pre-loaded icons!**

### Custom Icon Class:
FontAwesome classes use kar sakte ho:
```
fab fa-github
fab fa-twitter
fas fa-star
fas fa-heart
```

### Delete Custom Link:
- Admin panel → Custom Social Links
- "Delete" button pe click karo link ke sath

---

## 📤 File Upload System Features

### Upload Progress Bar:
- Real-time upload progress dikhta hai
- Percentage display (0% → 100%)
- Green progress bar
- "Upload Complete!" message

### File Management:
- **View All Files**: Uploaded files list with size
- **View Button**: File preview (image/video)
- **Delete Button**: Remove unwanted files
- **File Size Display**: MB mein file size

### Supported Files:
1. **Profile Image** - JPG/PNG
2. **Background Video** - MP4
3. **Background Image** - JPG/PNG (NEW!)
4. **Background Music** - MP3
5. **Music Icon** - PNG/JPG

---

## 🔐 Admin Panel Kaise Use Karein / How to Use Admin Panel

### Login:
- URL: `https://your-site.com/admin.php`
- Default Username: `admin`
- Default Password: `admin123`

### Features:
✅ **Profile Information** - Name, tagline, description change karein
✅ **Skills** - Apne skills add/remove karein (comma se separate)
✅ **Social Links** - Discord, YouTube, Instagram, Shop links update karein
✅ **Meta Info** - Page title, description, footer text edit karein
✅ **File Upload** - Profile image aur background video upload karein

---

## 🎯 Quick Customization / Jaldi Changes

### Social Media Links Update:
Admin panel mein ja kar directly change kar sakte ho:
- Discord: `https://discord.gg/YOUR_INVITE`
- YouTube: `https://youtube.com/@YOUR_CHANNEL`
- Instagram: `https://instagram.com/YOUR_USERNAME`
- Shop: `https://your-shop-link.com`

### Skills Update:
Skills ko comma se separate karke likho:
```
PYTHON, NODE.JS, C#, JAVASCRIPT, DISCORD BOTS, PHP
```

---

## 🔒 Security Tips / Security Settings

### Password Change Karein (IMPORTANT):
1. Admin panel mein login karein
2. Manually `config.php` file edit karein
3. Line 29 pe jayein:
```php
'password' => password_hash('admin123', PASSWORD_DEFAULT)
```
4. `admin123` ko apne new password se replace karein
5. Save karein

### Username Change:
`config.php` mein line 28:
```php
'username' => 'admin',
```
Change to:
```php
'username' => 'your_new_username',
```

---

## 🎨 Colors Customization

Portal ka color scheme **Black & Gray** hai with video background.

Agar colors change karne hain:
1. `index.php` kholen
2. `<style>` section mein jayein
3. Background colors (`rgba(20, 20, 20, 0.95)`) change karein

---

## 📁 File Structure

```
your-project/
├── index.php          (Main portal)
├── admin.php          (Admin panel)
├── upload.php         (File upload handler)
├── config.php         (Settings loader)
├── config_data.php    (YOUR SETTINGS - Auto-created, PERSISTENT!)
├── .gitignore         (Protects your settings)
├── railway.json       (Railway configuration)
├── profile.jpg        (Your profile image)
├── background.mp4     (Background video - optional)
├── background.jpg     (Background image - optional)
├── music.mp3          (Background music - optional)
├── music-icon.png     (Custom music icon - optional)
└── README.md          (This file)
```

**🔒 IMPORTANT:** `config_data.php` is auto-created and stores ALL your settings. This file is protected by `.gitignore` and persists across server restarts!

**📝 Note:** You can use either `background.mp4` (video) OR `background.jpg` (image), not both at the same time. Choose in admin panel!

---

## 🔐 Settings Persistence (Server Restart Ke Baad Bhi Safe)

### Your Settings Are SAFE! ✅

All settings you change in admin panel are saved to `config_data.php`:
- ✅ Profile information
- ✅ Skills
- ✅ Social links (default + custom)
- ✅ Music settings
- ✅ Meta information
- ✅ All changes!

### How It Works:
1. First time: Default settings from `config.php`
2. You make changes in admin panel
3. Settings saved to `config_data.php` (auto-created)
4. Server restart → Settings load from `config_data.php`
5. **Your changes are still there!** 🎉

### Protected Files (.gitignore):
- config_data.php (your settings)
- profile.jpg (your images)
- background.mp4 (your video)
- music.mp3 (your music)

**Result:** Server restart ke baad bhi sab settings wahi rahegi! 🔒

For detailed information, see `PERSISTENCE_GUIDE.md`

---

## ⚡ Features

✨ **Video/Image Background** - Choose between video or static image background
🎵 **Background Music System** - Auto-play music with custom controls
🎨 **Dark Theme** - Black & Gray professional design with glowing title
✨ **Particle Effects** - Animated particles around glowing text
📱 **Mobile Responsive** - Works perfectly on all devices
🔗 **Real Social Icons** - Discord, YouTube, Instagram, Shop
➕ **Custom Social Links** - Add unlimited custom links with icons
⚙️ **Easy Admin Panel** - Change everything without coding
📤 **Upload Progress Bar** - Real-time file upload progress
🗑️ **File Management** - View and delete uploaded files
🚀 **No Database** - File-based configuration (fast & simple)
🎧 **Custom Music Icon** - Upload your own music player icon
🎨 **Icon Library** - 16+ pre-loaded social icons to choose from

---

## 🆘 Troubleshooting / Common Issues

### Video Nahi Chal Raha?
- Check file name: `background.mp4`
- Check file format: MP4
- Check file size: Under 50MB recommended
- Browser cache clear karein

### Music Nahi Chal Raha?
- Music player icon pe click karein (bottom right)
- Check "Enable Background Music" admin panel mein
- Browser autoplay settings check karein
- MP3 file properly uploaded hai ya nahi check karein
- Console errors check karein (F12 browser mein)

### Admin Panel Access Nahi Ho Raha?
- URL check karein: `/admin.php`
- Credentials: admin / admin123
- Cookies enable karein browser mein

### Changes Save Nahi Ho Rahe?
- File permissions check karein (777 for config.php)
- Browser cache clear karein
- Admin panel se logout/login karein

---

## 💡 Tips

1. **Profile Image**: Square image use karein (500x500px best hai)
2. **Video Background**: Short loop video best rehti hai (15-20 seconds)
3. **Background Music**: 2-3 minute loop track perfect hai
4. **Music Icon**: Transparent PNG (50x50px) best results deta hai
5. **Regular Backups**: `config.php` ka backup rakhein
6. **Password Security**: Strong password use karein production mein
7. **Glow Effect**: Title automatically glow karega with particles

---

## 📞 Support

Agar koi issue ho to:
1. README dobara parhein
2. File permissions check karein
3. Browser console errors check karein

---

**Created by:** SUBHAN DEV | 蘇班_سبحان !
**Version:** 1.0
**Last Updated:** 2026

---

## 🎉 Enjoy Your New Portal!

Ab ap apna professional bio portal Railway pe host kar sakte ho with video background!
Admin panel se easily sab kuch change kar sakte ho bina coding ke!

Good Luck! 🚀
