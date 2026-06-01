# Digital Library & Book Management System

A dynamic, full-stack web application designed for cataloging literary inventories, featuring an object-oriented architecture, secure user management, and seamless image asset handling. 

## 🚀 Features

- **User Authentication System:** Secure registration and session-managed login/logout flows (`Authenticate.php`).
- **Full CRUD Capabilities:** Authenticated users can dynamically add, view, and interact with the book collection directory.
- **Robust Asset Pipeline:** Automated handling for custom book covers and image data persistence.
- **Modern PHP Standards:** Fully integrated with Composer for streamlined backend dependency management and PSR-compliant autoloading.

## 🛠️ Tech Stack

- **Backend:** PHP 
- **Database:** MySQL
- **Dependency Management:** Composer
- **Frontend:** HTML5, CSS3

## 📁 Project Structure

```text
├── Book_Covers/      # Uploaded image assets for cataloged inventory
├── classes/          # OOP Class architecture (Auth, DB connection, CRUD operations)
├── layout/           # Reusable user interface components (Navigation, Header, Footer)
├── resources/css/    # Specialized stylesheet modules (Auth, Navigation, Typography)
├── vendor/           # Composer-managed local dependencies (Autoloader)
└── views/            # MVC-style client-facing presentation interfaces
