<?php
// index.php
require_once 'config.php'; // Adjust path if config.php is outside web root

// Fetch footer links
$links_result = $conn->query('SELECT name, href FROM links');
$footer_links = [];
while ($row = $links_result->fetch_assoc()) {
    $footer_links[] = $row;
}

// Fetch sections
$sections_result = $conn->query('SELECT title, description, image_url FROM sections');
$sections = [];
while ($row = $sections_result->fetch_assoc()) {
    $sections[] = $row;
}

$conn->close();

// Load footer text from JSON
$footerText = json_decode(file_get_contents('data/footer.json'), true) ?? ['text' => 'Default footer text'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS Layout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .container {
            width: 80%;
            margin: auto;
        }
        header {
            text-align: center;
            padding: 10px;
            background: #f4f4f4;
        }
        nav {
            display: flex;
            justify-content: center;
            gap: 20px;
            padding: 10px;
        }
        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 50px;
            border: 1px solid #000;
            margin-bottom: 10px;
        }
        .article {
            display: flex;
            justify-content: space-between;
            border: 1px solid #000;
            padding: 15px;
            margin-bottom: 10px;
        }
        .article img {
            width: 150px;
            height: 100px;
            background: #ccc;
        }
        footer {
            background: #f4f4f4;
            padding: 15px;
            text-align: center;
        }
        .footer-links {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <header>
        <h2>http://localhost/cms</h2>
    </header>
    
    <div class="container">
        <div class="logo">
            <img id="logoImage" src="default-logo.jpg" alt="Logo" style="height: 50px;">
        </div>

        <img src="https://i.ytimg.com/vi/hNBDtBfv6wE/maxresdefault.jpg" alt="Header Image">
        
        <nav>
            <a href="#">Home</a>
            <a href="#">About</a>
            <a href="#">Blog</a>
        </nav>
        
        <?php foreach ($sections as $section): ?>
            <div class="article">
                <div>
                    <h3><?= htmlspecialchars($section['title']) ?></h3>
                    <p><?= htmlspecialchars($section['description']) ?></p>
                </div>
                <img src="<?= htmlspecialchars($section['image_url']) ?>" alt="Article Image">
            </div>
        <?php endforeach; ?>
    </div>
    
    <footer>
        <p><?= htmlspecialchars($footerText['text']) ?></p>
        <p>© 2024, Your Company, All rights reserved.</p>
        <div class="footer-links">
            <?php foreach ($footer_links as $link): ?>
                <a href="<?= htmlspecialchars($link['href']) ?>"><?= htmlspecialchars($link['name']) ?></a>
            <?php endforeach; ?>
        </div>
    </footer>

    <script>
        // Load logo from localStorage if available
        const logo = localStorage.getItem("logo");
        if (logo) document.getElementById("logoImage").src = logo;
    </script>
</body>
</html>