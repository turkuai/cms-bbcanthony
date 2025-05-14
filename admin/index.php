<?php
// admin.php
// Note: Footer note is still stored in a JSON file or localStorage as per original code
$footerText = json_decode(file_get_contents('../data/footer.json'), true) ?? ['text' => 'Default footer text'];
?>

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
            align-items: center;
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
        .section-form {
            margin: 20px 0;
            padding: 10px;
            border: 1px solid #ccc;
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

        <img src="https://i.ytimg.com/vi/hNBDtBfv6wE/maxresdefault.jpg" alt="Header Image" />

        <nav>
            <a href="#">Home</a>
            <a href="#">About</a>
            <a href="#">Blog</a>
        </nav>

        <div class="section-form">
            <h3>Add/Edit Section</h3>
            <input type="hidden" id="sectionId" />
            <input type="text" id="sectionTitle" placeholder="Section Title" />
            <textarea id="sectionDescription" placeholder="Section Description"></textarea>
            <input type="url" id="sectionImageUrl" placeholder="Image URL" />
            <button onclick="addOrUpdateSection()">Add / Save Section</button>
        </div>

        <div id="sectionsContainer"></div>
    </div>

    <footer>
        <p id="footerNote"><?= htmlspecialchars($footerText['text']) ?></p>
        <button onclick="editFooterNote()">Edit Footer Note</button>

        <p>© 2024, Company's name, All rights reserved.</p>

        <div class="footer-links" id="footerLinks"></div>

        <div style="margin-top: 20px;">
            <input type="text" id="linkText" placeholder="Link Text" />
            <input type="url" id="linkHref" placeholder="Link URL" />
            <button onclick="addOrUpdateLink()">Add / Save Link</button>
        </div>
    </footer>

    <script>
        let editingLinkId = -1;
        let editingSectionId = -1;

        async function loadData() {
            // Load logo and footer note from localStorage
            const logo = localStorage.getItem("logo");
            if (logo) document.getElementById("logoImage").src = logo;

            const footer = localStorage.getItem("footerNote");
            if (footer) document.getElementById("footerNote").innerText = footer;

            // Fetch links from server
            try {
                const linkResponse = await fetch('../api/links.php');
                const links = await linkResponse.json();
                const linkContainer = document.getElementById("footerLinks");
                linkContainer.innerHTML = "";
                links.forEach(link => {
                    const span = document.createElement("span");
                    span.className = "link-item";
                    span.innerHTML = `
                        <a href="${link.href}" target="_blank">${link.name}</a>
                        <button onclick="editLink(${link.id}, '${link.name}', '${link.href}')">✏️</button>
                        <button onclick="deleteLink(${link.id})">🗑️</button>
                    `;
                    linkContainer.appendChild(span);
                });
            } catch (error) {
                console.error('Error fetching links:', error);
            }

            // Fetch sections from server
            try {
                const sectionResponse = await fetch('../api/sections.php');
                const sections = await sectionResponse.json();
                const sectionContainer = document.getElementById("sectionsContainer");
                sectionContainer.innerHTML = "";
                sections.forEach(section => {
                    const div = document.createElement("div");
                    div.className = "article";
                    div.innerHTML = `
                        <div>
                            <h3>${section.title}</h3>
                            <p>${section.description}</p>
                        </div>
                        <img src="${section.image_url}" alt="Article Image" />
                        <div>
                            <button onclick="editSection(${section.id}, '${section.title}', '${section.description}', '${section.image_url}')">✏️</button>
                            <button onclick="deleteSection(${section.id})">🗑️</button>
                        </div>
                    `;
                    sectionContainer.appendChild(div);
                });
            } catch (error) {
                console.error('Error fetching sections:', error);
            }
        }

        function editLogo() {
            const newLogoUrl = prompt("Enter the new logo image URL:");
            if (newLogoUrl) {
                localStorage.setItem("logo", newLogoUrl);
                document.getElementById("logoImage").src = newLogoUrl;
            }
        }

        function editFooterNote() {
            const newNote = prompt("Enter new footer note:");
            if (newNote) {
                localStorage.setItem("footerNote", newNote);
                document.getElementById("footerNote").innerText = newNote;
                // Optionally, update footer.json on server if required
            }
        }

        async function addOrUpdateLink() {
        const name = document.getElementById("linkText").value.trim();
        const href = document.getElementById("linkHref").value.trim();
        if (!name || !href) return alert("Both fields required!");

        const method = editingLinkId >= 0 ? 'PUT' : 'POST';
        const body = editingLinkId >= 0 ? { id: editingLinkId, name, href } : { name, href };

        try {
            const response = await fetch('../api/links.php', {
                method,
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(body)
            });
            if (response.ok) {
                document.getElementById("linkText").value = "";
                document.getElementById("linkHref").value = "";
                editingLinkId = -1;
                loadData();
            } else {
                const error = await response.json();
                alert(error.error || 'Failed to save link');
            }
        } catch (error) {
            console.error('Error saving link:', error);
            alert('Failed to save link');
        }
    }

        function editLink(id, name, href) {
            document.getElementById("linkText").value = name;
            document.getElementById("linkHref").value = href;
            editingLinkId = id;
        }

        async function deleteLink(id) {
            if (!confirm("Are you sure you want to delete this link?")) return;

            try {
                const response = await fetch('../api/links.php', {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                });
                if (response.ok) {
                    loadData();
                } else {
                    const error = await response.json();
                    alert(error.error || 'Failed to delete link');
                }
            } catch (error) {
                console.error('Error deleting link:', error);
                alert('Failed to delete link');
            }
        }

        async function addOrUpdateSection() {
            const title = document.getElementById("sectionTitle").value.trim();
            const description = document.getElementById("sectionDescription").value.trim();
            const image_url = document.getElementById("sectionImageUrl").value.trim();
            if (!title || !description || !image_url) return alert("All fields required!");

            const method = editingSectionId >= 0 ? 'PUT' : 'POST';
            const body = editingSectionId >= 0 ? { id: editingSectionId, title, description, image_url } : { title, description, image_url };

            try {
                const response = await fetch('../api/sections.php', {
                    method,
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(body)
                });
                if (response.ok) {
                    document.getElementById("sectionId").value = "";
                    document.getElementById("sectionTitle").value = "";
                    document.getElementById("sectionDescription").value = "";
                    document.getElementById("sectionImageUrl").value = "";
                    editingSectionId = -1;
                    loadData();
                } else {
                    const error = await response.json();
                    alert(error.error || 'Failed to save section');
                }
            } catch (error) {
                console.error('Error saving section:', error);
                alert('Failed to save section');
            }
        }

        function editSection(id, title, description, image_url) {
            document.getElementById("sectionId").value = id;
            document.getElementById("sectionTitle").value = title;
            document.getElementById("sectionDescription").value = description;
            document.getElementById("sectionImageUrl").value = image_url;
            editingSectionId = id;
        }

        async function deleteSection(id) {
            if (!confirm("Are you sure you want to delete this section?")) return;

            try {
                const response = await fetch('../api/sections.php', {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                });
                if (response.ok) {
                    loadData();
                } else {
                    const error = await response.json();
                    alert(error.error || 'Failed to delete section');
                }
            } catch (error) {
                console.error('Error deleting section:', error);
                alert('Failed to delete section');
            }
        }

        window.onload = loadData;
    </script>
</body>
</html>