# Project Structure

```
/ (root)
├── .htaccess
├── db_klinik.sql
├── index.js
├── index.php
├── package.json
├── config/
│   ├── config.php
│   └── database.php
├── controllers/
│   ├── AdminController.php
│   ├── AuthController.php
│   ├── HomeController.php
│   └── PatientController.php
├── includes/
│   ├── auth.php
│   └── functions.php
├── views/
│   ├── 404.php
│   ├── home.php
│   ├── admin/
│   │   ├── dashboard.php
│   │   ├── doctors/
│   │   │   └── list.php
│   │   └── patients/
│   │       ├── edit.php
│   │       ├── list.php
│   │       └── view.php
│   ├── auth/
│   │   ├── login.php
│   │   ├── register.php
│   │   └── reset-password.php
│   ├── layouts/
│   │   └── main.php
│   ├── patient/
│       ├── dashboard.php
│       ├── profile.php
│       ├── appointments/
│       │   ├── list.php
│       │   └── request.php
│       ├── billing/
│       │   └── list.php
│       └── medical-records/
│           ├── list.php
│           └── view.php
```
