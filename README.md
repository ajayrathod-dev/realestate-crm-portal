# Property Management & Real Estate CRM

An all-in-one PHP & MySQL based **Real Estate & Property Management CRM** designed to streamline property listings, lead management, staff work assignments, field visit tracking with GPS and photo verification, and agent onboarding.

---

## 🌟 Key Features

### 1. 🏢 Admin Dashboard
- **Comprehensive Analytics:** Overview of active properties, staff assignments, inquiries, and incoming/outgoing records.
- **Property Catalog Management:** Add, edit, review, and approve properties across various categories:
  - Residential & Commercial spaces
  - Agricultural lands & plots
  - Multi-photo upload and gallery management
  - Geolocation coordinates & Google Maps linking
- **Staff Operations & Task Delegation:**
  - Assign specific jobs/tasks to staff members with expected completion dates.
  - Track status updates and task remarks.
- **Field Visit Verification:**
  - Review live field visits submitted by field staff and agents.
  - Verification includes selfie capture and real-time GPS coordinates (Latitude/Longitude).
- **Lead & Inquiry Tracking:**
  - Record customer requirements (Purchase vs. Rent, budget, location preferences, deposit).
  - Quick filtering and property matching.
- **Office Registry (Aavak / Javak):**
  - Manage Inward (*Aavak*) and Outward (*Javak*) registers and correspondence.
- **Billing & Receipts:**
  - Generate, track, and print customer payment receipts.
- **Agent Approvals:**
  - Review and approve or reject newly registered agents.

---

### 2. 👥 Staff Portal (`/staff_admin`)
- Secure login for company staff and field associates.
- Add client inquiries and new property submissions.
- Perform and record on-site field visits with live selfie camera capture and GPS location tags.
- Update assigned work progress.

---

### 3. 🤝 Agent Portal (`/agent_panel`)
- **Passwordless Mobile OTP Login:** Seamless agent onboarding using mobile phone verification.
- **Agent Approval Workflow:** New agents complete registration and wait for admin approval before full portal access.
- **Property Submissions:** Agents can list new properties directly into the portal for admin review.
- **Field Logging:** Record visits, client meetings, and feedback with GPS and selfie logging.

---

## 🛠️ Tech Stack

- **Backend:** PHP (Native / Procedural)
- **Database:** MySQL / MariaDB
- **Frontend:** Bootstrap, HTML5, CSS3, JavaScript, jQuery
- **Icons & Assets:** FontAwesome, Material Icons
- **APIs & Features:** HTML5 Geolocation API, HTML5 Media/Camera API, Google Maps Integration

---

## 📂 Project Structure

```text
├── agent_panel/               # Agent portal (OTP auth, property listing, visits)
│   ├── assets/                # CSS, JS, vendor plugins, icons
│   ├── login.php              # Agent OTP login
│   ├── otp.php                # OTP verification handler
│   └── ...
├── assets/                    # Shared stylesheets, JS plugins, images
├── database/                  # Database migration files
│   └── property_schema.sql    # Complete database schema and seed data
├── images/                    # Property uploads and media storage
├── staff_admin/               # Staff portal
│   ├── login.php              # Staff login
│   └── ...
├── add_pro.php                # Property creation page
├── all_properties.php         # Property listing and search
├── connection.php             # Database connection configuration
├── connection.example.php     # Example configuration file
├── dashboard.php              # Main admin dashboard
├── field_visit.php            # Field visit logging with GPS & camera
├── login.php                  # Admin login portal
├── receipt.php                # Receipt management and generator
├── work_assign.php            # Work assignment and tracking
├── .gitignore                 # Git ignore rules for logs, temp files
└── README.md                  # Project documentation
```

---

## 🚀 Installation & Local Setup

### Prerequisites
- [XAMPP](https://www.apachefriends.org/) (with PHP 7.4+ or PHP 8.x and MySQL / MariaDB)
- Web browser (Chrome, Edge, Firefox)

### Step 1: Clone or Copy the Repository
Place the project directory inside your local server root:
```bash
# For XAMPP on Windows:
C:\xampp\htdocs\property
```

### Step 2: Database Setup
1. Start **Apache** and **MySQL** from the XAMPP Control Panel.
2. Open [phpMyAdmin](http://localhost/phpmyadmin) in your browser.
3. Create a new database named `property`.
4. Click on the **Import** tab.
5. Select and import the file located at:
   ```text
   database/property_schema.sql
   ```
6. Alternatively, import via MySQL CLI:
   ```bash
   mysql -u root -p property < database/property_schema.sql
   ```

### Step 3: Configure Database Connection
Open `connection.php` (and if needed `agent_panel/connection.php` & `staff_admin/connection.php`) and configure your database credentials:
```php
$DB_SERVER = "localhost";
$DB_NAME   = "property";
$username  = "root";     // Your MySQL username
$password  = "";         // Your MySQL password (default is empty in XAMPP)
```

### Step 4: Access the Application
- **Admin Panel:** [http://localhost/property/login.php](http://localhost/property/login.php)
  - **Username:** `admin`
  - **Password:** `1234`
- **Staff Panel:** [http://localhost/property/staff_admin/login.php](http://localhost/property/staff_admin/login.php)
- **Agent Panel:** [http://localhost/property/agent_panel/login.php](http://localhost/property/agent_panel/login.php)

---

## 🔒 Security Best Practices for Production

1. Update default admin credentials upon deployment.
2. Store database credentials in a secure environment file or outside the web root.
3. Ensure HTTPS is enforced, especially for Geolocation and Camera features.

---

## 📄 License
This project is open-source and available under the [MIT License](LICENSE).
