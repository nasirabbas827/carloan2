# carloan2_final  

A web‑based car loan management system built with PHP. It provides separate interfaces for **Admin**, **Dealer**, and **Bank Manager** to manage users, cars, and loan charges.

---

## Overview  

`carloan2_final` is a multi‑role application that allows:

- **Admins** to manage users and car listings.  
- **Dealers** to add, edit, and view cars they sell.  
- **Bank Managers** to view dealer cars and set loan charges.  

All roles share a common navigation bar and use a MySQL database (`kainat_carloan.sql`) for persistence.

---

## Features  

| Role | Capabilities |
|------|--------------|
| **Admin** | • View, edit, and delete users<br>• View, edit, and delete car records<br>• Secure logout |
| **Dealer** | • Add new cars<br>• Edit own car listings<br>• View all cars<br>• Secure logout |
| **Bank Manager** | • View dealer cars<br>• Retrieve and update car loan charges<br>• Secure logout |
| **Common** | • Responsive UI (CSS)<br>• Centralized navigation (`navbar.php`)<br>• Configurable database connection |

---

## Tech Stack  

| Layer | Technology |
|-------|------------|
| Backend | PHP 7.4+ |
| Database | MySQL |
| Styling | CSS (custom stylesheet) |
| Server | Apache / Nginx (any LAMP stack) |

---

## Installation  

1. **Clone the repository**  

   ```bash
   git clone https://github.com/yourusername/carloan2_final.git
   cd carloan2_final
   ```

2. **Create the database**  

   ```sql
   -- In MySQL client or phpMyAdmin
   SOURCE Database/kainat_carloan.sql;
   ```

3. **Configure database connection**  

   Edit the `config.php` files (root, `admin/`, `dealer/`, `manager/`) and replace the placeholder values with your own credentials:

   ```php
   // Example config.php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'kainat_carloan');
   define('DB_USER', 'YOUR_DB_USERNAME');
   define('DB_PASS', 'YOUR_DB_PASSWORD');
   ```

4. **Set up a virtual host (optional)**  

   ```apacheconf
   DocumentRoot "/path/to/carloan2_final"
   <Directory "/path/to/carloan2_final">
       AllowOverride All
       Require all granted
   </Directory>
   ```

5. **Start the server**  

   ```bash
   # Using built‑in PHP server for quick testing
   php -S localhost:8000
   ```

6. **Access the application**  

   Open a browser and navigate to `http://localhost:8000/`.

---

## Usage  

| Entry Point | Description |
|-------------|-------------|
| `index.php` | Landing page with links to login. |
| `login.php` | Common login page – redirects to the appropriate dashboard based on role. |
| `admin/admin_dashboard.php` | Admin home – manage users and cars. |
| `dealer/car_dealer_dashboard.php` | Dealer home – add/edit/view cars. |
| `manager/bank_manager_dashboard.php` | Manager home – view dealer cars and set loan charges. |
| `adminlogin.php` | Direct admin login (if separate). |
| `logout.php` (role‑specific) | Ends the session and redirects to login. |

**Typical workflow**

1. Log in with the credentials created in the seed data (`kainat_carloan.sql`).  
2. Use the navigation bar to access the required module.