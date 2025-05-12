<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>CMS Admin Layout</title>
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
      justify-content: space-between;
      height: 50px;
      border: 1px solid #000;
      padding: 0 10px;
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
      flex-wrap: wrap;
    }
    .link-item {
      display: flex;
      align-items: center;
      gap: 5px;
    }
    input, button {
      margin: 5px;
    }
  </style>
</head>
<body>
  <header>
    <h2>http://host:port/cms/admin</h2>
  </header>

  <div class="container">
    <div class="logo">
      <img id="logoImage" src="default-logo.jpg" alt="Logo" style="height: 50px;">
      <button onclick="editLogo()">Edit Logo</button>
    </div>

    <img src="https://i.ytimg.com/vi/hNBDtBfv6wE/maxresdefault.jpg" />

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
      <img src="https://m.media-amazon.com/images/S/pv-target-images/ba6bf7241aadcaf2bb253c845ae10f1eb0252c8bf212dcb0b457dc3f7cb135ef.jpg" alt="Article Image" />
    </div>

    <div class="article">
      <div>
        <h3>Hey You</h3>
        <p>Yes you</p>
      </div>
      <img src="https://m.media-amazon.com/images/M/MV5BNjgxMTQ0OTAwOF5BMl5BanBnXkFtZTgwODIwNTU1MjE@._V1_.jpg" alt="Article Image" />
    </div>
  </div>

  <footer>
    <p id="footerNote">Your company's name</p>
    <button onclick="editFooterNote()">Edit Footer Note</button>

    <p>&copy; 2024, Company's name, All rights reserved.</p>

    <div class="footer-links" id="footerLinks"></div>

    <div style="margin-top: 20px;">
      <input type="text" id="linkText" placeholder="Link Text" />
      <input type="url" id="linkHref" placeholder="Link URL" />
      <button onclick="addOrUpdateLink()">Add / Save</button>
    </div>
  </footer>

  <script>
    let editingIndex = -1;

    function loadData() {
      const logo = localStorage.getItem("logo");
      if (logo) document.getElementById("logoImage").src = logo;

      const footer = localStorage.getItem("footerNote");
      if (footer) document.getElementById("footerNote").innerText = footer;

      const links = JSON.parse(localStorage.getItem("socialLinks")) || [];
      const container = document.getElementById("footerLinks");
      container.innerHTML = "";

      links.forEach((link, index) => {
        const span = document.createElement("span");
        span.className = "link-item";
        span.innerHTML = `
          <a href="${link.href}" target="_blank">${link.text}</a>
          <button onclick="editLink(${index})">✏️</button>
          <button onclick="deleteLink(${index})">🗑️</button>
        `;
        container.appendChild(span);
      });
    }

    function editLogo() {
      const newLogoUrl = prompt("Enter the new logo image URL:");

      if (newLogoUrl) {
        // Store the new image URL in localStorage
        localStorage.setItem("logo", newLogoUrl);

        // Update the logo image inside the container
        const logoImage = document.getElementById("logoImage");

        // Check if the logo image element exists
        if (logoImage) {
          logoImage.src = newLogoUrl;
        }
      }
    }

    function editFooterNote() {
      const newNote = prompt("Enter new footer note:");
      if (newNote) {
        localStorage.setItem("footerNote", newNote);
        document.getElementById("footerNote").innerText = newNote;
      }
    }

    function addOrUpdateLink() {
      const text = document.getElementById("linkText").value.trim();
      const href = document.getElementById("linkHref").value.trim();
      if (!text || !href) return alert("Both fields required!");

      const links = JSON.parse(localStorage.getItem("socialLinks")) || [];

      if (editingIndex >= 0) {
        links[editingIndex] = { text, href };
        editingIndex = -1;
      } else {
        links.push({ text, href });
      }

      localStorage.setItem("socialLinks", JSON.stringify(links));
      document.getElementById("linkText").value = "";
      document.getElementById("linkHref").value = "";
      loadData();
    }

    function editLink(index) {
      const links = JSON.parse(localStorage.getItem("socialLinks")) || [];
      const link = links[index];
      document.getElementById("linkText").value = link.text;
      document.getElementById("linkHref").value = link.href;
      editingIndex = index;
    }

    function deleteLink(index) {
      if (!confirm("Are you sure you want to delete this link?")) return;

      const links = JSON.parse(localStorage.getItem("socialLinks")) || [];
      links.splice(index, 1);
      localStorage.setItem("socialLinks", JSON.stringify(links));
      loadData();
    }

    window.onload = loadData;
  </script>
</body>
</html>
