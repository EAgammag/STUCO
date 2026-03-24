# Test Accounts

This document lists all available test accounts for the Student Conduct Management System. These accounts are automatically created when you run the database seeders.

## Running Database Seeders

To create these test accounts, run:

```bash
php artisan migrate:fresh --seed
```

---

## Available Test Accounts

All test accounts use the default password: **`password`**

### Administrator Account

- **Email:** `admin@example.com`
- **Password:** `password`
- **Role:** administrator
- **Permissions:** All permissions (full system access)
- **Use Case:** Testing administrative functions, user management, report generation, and full system features

### Staff Account

- **Email:** `staff@example.com`
- **Password:** `password`
- **Role:** staff
- **Permissions:**
  - View incidents
  - Create incidents
  - Edit incidents
  - View reports
  - Assign discipline
- **Use Case:** Testing staff workflows for documenting and managing student violations

### Student Accounts

#### Student 1 - John Doe

- **Email:** `student@example.com`
- **Password:** `password`
- **Role:** student
- **Permissions:**
  - View own incidents
  - Appeal incidents
- **Use Case:** Primary student account for testing student-facing features

#### Student 2 - Jane Smith

- **Email:** `student2@example.com`
- **Password:** `password`
- **Role:** student
- **Permissions:**
  - View own incidents
  - Appeal incidents
- **Use Case:** Secondary student account for testing multiple student scenarios

#### Student 3 - Mike Johnson

- **Email:** `student3@example.com`
- **Password:** `password`
- **Role:** student
- **Permissions:**
  - View own incidents
  - Appeal incidents
- **Use Case:** Additional student account for comprehensive testing

#### Student 4 - Temp Student (Temporary)

- **Email:** `tempstudent@example.com`
- **Password:** `password`
- **Role:** student
- **Permissions:**
  - View own incidents
  - Appeal incidents
- **Use Case:** Temporary student account for quick testing

---

## Role Permissions Summary

### student Role
Students have the most restricted access, limited to:
- Viewing their own violation records
- Submitting appeals for violations
- Accessing their personal conduct history

### staff Role
Staff members can:
- View all violation records
- Create new violation reports
- Edit existing violation records
- View system reports
- Assign disciplinary actions to students

### administrator Role
Administrators have complete system access including:
- All staff permissions
- User management (create, edit, delete users)
- Generate comprehensive reports
- Delete violation records
- Manage system-wide settings
- Assign and modify user roles

---

## Security Notes

⚠️ **Important:** These are test accounts for development and testing purposes only. 

- **Never use these credentials in production environments**
- Change all default passwords before deploying to production
- Implement proper password policies in production
- Review the [SECURITY-AUTHENTICATION.md](SECURITY-AUTHENTICATION.md) document for security best practices

---

## Creating Additional Test Data

To create a complete test dataset with offense rules and violation records, you can also run:

```bash
php artisan db:seed --class=StudentConductSeeder
```

This will create:
- 3 additional staff members
- 5 additional students with violation records
- 8 offense rules (academic, behavioral, procedural, safety, technology)
- Multiple violation records with various statuses

---

*Last Updated: March 1, 2026*
