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
        <h2>http://host:port/cms</h2>
    </header>
    
    <div class="container">
        <div class="logo">LOGO</div>
        <img src="https://i.ytimg.com/vi/hNBDtBfv6wE/maxresdefault.jpg">
        <nav>
            <a href="#">Home</a>
            <a href="#">About</a>
            <a href="#">Blog</a>
        </nav>
        
        <div class="article">
            <div>
                <h3>HI THERE</h3>
                <p>wASSUP</p>
            </div>
            <img src="https://m.media-amazon.com/images/S/pv-target-images/ba6bf7241aadcaf2bb253c845ae10f1eb0252c8bf212dcb0b457dc3f7cb135ef.jpg" alt="Article Image">
        </div>
        
        <div class="article">
            <div>
                <h3>Hey You</h3>
                <p>Yes you</p>
            </div>
            <img src="https://m.media-amazon.com/images/M/MV5BNjgxMTQ0OTAwOF5BMl5BanBnXkFtZTgwODIwNTU1MjE@._V1_.jpg" alt="Article Image">
        </div>
    </div>
    
    <footer>
        <p>Your company's name</p>
        <p>&copy; 2024, Company's name, All rights reserved.</p>
        <div class="footer-links">
            <a href="#">Home</a>
            <a href="#">About</a>
            <a href="#">Blog</a>
            <a href="#">Facebook</a>
            <a href="#">LinkedIn</a>
            <a href="#">GitHub</a>
        </div>
    </footer>
</body>
</html>
