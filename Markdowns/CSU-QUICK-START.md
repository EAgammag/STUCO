# CSU Student Conduct System - Quick Start Guide

## 🚀 What's Been Implemented

Your system now has a **complete, CSU Manual-compliant** student conduct management system with:

✅ **58 offense rules** from the CSU Student Manual (seeded in database)  
✅ **Progressive sanctions** (1st, 2nd, 3rd offense auto-calculation)  
✅ **Due process workflow** (timelines, deadlines, ex parte handling)  
✅ **Evidence management** (secure file storage)  
✅ **Audit trail system** (every action logged)  
✅ **Confidentiality controls** (access logging per CSU G.20)  
✅ **Staff incident reporting** (ready to use)  
✅ **Admin case management** (OSDW dashboard)  

---

## 📋 Immediate Next Steps

### 1. Test the Database (Verify Everything Works)

```bash
# Check offense rules were seeded
php artisan tinker
>>> App\Models\OffenseRule::count()
# Should return: 58

>>> App\Models\OffenseRule::where('code', 'OT-03')->first()->title
# Should return: "Uniform/ID Violation"

>>> App\Models\OffenseRule::where('code', 'OT-03')->first()->first_offense_sanction
# Should return: "Reprimand"
```

### 2. Create Test Users (If Not Already Created)

```bash
php artisan tinker

# Create a student
$student = App\Models\User::factory()->create([
    'name' => 'Juan Dela Cruz',
    'email' => 'juan.delacruz@csu.edu.ph',
    'student_id' => '2026-00001',
    'password' => bcrypt('password'),
]);
$student->assignRole('Student');

# Create a staff member (reporter)
$staff = App\Models\User::factory()->create([
    'name' => 'Prof. Maria Santos',
    'email' => 'maria.santos@csu.edu.ph',
    'password' => bcrypt('password'),
]);
$staff->assignRole('Faculty');

# Create an OSDW admin
$admin = App\Models\User::factory()->create([
    'name' => 'OSDW Director',
    'email' => 'osdw@csu.edu.ph',
    'password' => bcrypt('password'),
]);
$admin->assignRole('Admin');
```

### 3. Configure Routes

Add these to your `routes/web.php`:

```php
// Staff Portal Routes
Route::middleware(['auth', 'role:Faculty|Guard|Staff'])->prefix('staff')->group(function () {
    Route::get('/report-incident', function () {
        return view('staff.report-incident');
    })->name('staff.report-incident');
    
    Route::get('/quick-log', function () {
        return view('staff.quick-log');
    })->name('staff.quick-log');
    
    Route::get('/my-reports', function () {
        return view('staff.my-reports');
    })->name('staff.my-reports');
});

// Admin Portal Routes
Route::middleware(['auth', 'role:Admin|OSDW'])->prefix('admin')->group(function () {
    Route::get('/cases', function () {
        return view('admin.cases');
    })->name('admin.cases');
});
```

### 4. Create Basic Views

Create these view files:

**`resources/views/staff/report-incident.blade.php`**
```blade
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Report Incident
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @livewire('staff.report-incident')
        </div>
    </div>
</x-app-layout>
```

**`resources/views/admin/cases.blade.php`**
```blade
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Case Management Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @livewire('admin.case-management')
        </div>
    </div>
</x-app-layout>
```

### 5. Test Staff Incident Reporting

1. Log in as a staff member
2. Navigate to `/staff/report-incident`
3. Search for a student by Student ID
4. Select offense category (e.g., "Procedural")
5. Select specific offense (e.g., "Uniform/ID Violation")
6. Fill in incident details
7. Upload evidence (optional)
8. Submit report

**What Happens:**
- System calculates if this is 1st, 2nd, or 3rd offense for this student
- Auto-applies appropriate sanction (Reprimand → 5 days → Dismissal)
- Generates unique Case Tracking Number (e.g., `CSU-2026-03-0001`)
- Determines if Tribunal or Summary investigation is needed
- Sets answer deadline (5 class days from now)
- Logs all actions to audit trail

---

## 🎯 Key Features & How They Work

### Progressive Sanctions Engine

The system automatically queries the student's history:

```php
// Example: Student gets caught without uniform 3 times
1st offense: "Reprimand" 
2nd offense: "5 class days suspension" 
3rd offense: "Dismissal"
```

**Code Implementation:**
```php
$offenseCount = ViolationRecord::where('student_id', $studentId)
    ->where('offense_id', $offenseId)
    ->count() + 1;

$appliedSanction = $offenseRule->getSanctionForOffenseCount($offenseCount);
```

### Case Tracking System

Every case gets a unique tracking number:

**Format:** `CSU-{YEAR}-{MONTH}-{SEQUENTIAL}`

**Examples:**
- `CSU-2026-03-0001` (1st case in March 2026)
- `CSU-2026-03-0042` (42nd case in March 2026)

### Due Process Automation

When a charge is filed:

| Timeline Event | Calculation | CSU Manual Reference |
|----------------|-------------|---------------------|
| **Answer Deadline** | Charge Date + 5 class days | Section G.6 |
| **Hearing Date** | Within 1 week after answer | Section G.7 |
| **Decision Deadline** | Final submission + 15 class days | Section G.13 |
| **Appeal Deadline** | Decision date + 10 class days | Section G.15 |

**Class Days:** Excludes weekends and university holidays (see `CSUDateHelper`)

### Confidential Record Access

Every time someone views a case:

```php
$violationRecord->logAccess(auth()->id(), 'OSDW Review');
```

**Stored Data:**
- User ID who accessed
- Timestamp
- Purpose (e.g., "OSDW Review", "SDT Investigation")
- IP address

Complies with CSU Section G.20 (confidentiality requirement)

---

## 📊 Database Schema Summary

### Main Tables

**offense_rules** (58 pre-seeded records)
- `code` - Offense code (e.g., "OT-03")
- `title` - Offense name
- `category` - Academic, Behavioral, Technology, etc.
- `first_offense_sanction` - Sanction for 1st offense
- `second_offense_sanction` - Sanction for 2nd offense
- `third_offense_sanction` - Sanction for 3rd offense
- `requires_tribunal` - TRUE/FALSE

**violation_records** (the "official entry book")
- `case_tracking_number` - Unique case ID
- `student_id` - Who violated
- `offense_id` - What offense
- `offense_count` - 1, 2, or 3
- `applied_sanction` - Auto-calculated sanction
- `reported_by` - Staff who reported
- `investigation_type` - Tribunal / Summary / Dean Direct
- Timeline fields (answer_deadline, hearing_date, decision_deadline, etc.)
- Documentation fields (student_answer, committee_report, final_decision)
- Confidentiality fields (access_log, sdt_members)

**case_evidence**
- Evidence files (stored in `storage/app/private/case-evidence/`)
- File metadata (name, size, type, mime)
- Who uploaded, when uploaded

**case_workflow_logs** (audit trail)
- Every action logged (Case Created, Evidence Uploaded, Hearing Scheduled, Decision Rendered, etc.)
- Actor ID, IP address, timestamp
- Immutable audit trail

---

## 🔒 Security & Compliance

### File Storage
Evidence files are stored in **private storage** (not publicly accessible):
```
storage/app/private/case-evidence/
```

**Access Control:** Only authenticated users with proper permissions can download evidence

### Access Logging
Every case view is logged:
```php
// Automatic logging in Admin/CaseManagement component
$case->logAccess(Auth::id(), 'OSDW Case Review');
```

### Role-Based Access
- **Students:** Cannot see other students' cases
- **Staff:** Only see cases they reported
- **OSDW/Admin:** See all cases
- **SDT Members:** See only cases assigned to them

---

## 🛠️ Helper Functions & Services

### CSUDateHelper Class

Located at `app/Helpers/CSUDateHelper.php`

**Key Methods:**

```php
use App\Helpers\CSUDateHelper;

// Add 5 class days to today
$answerDeadline = CSUDateHelper::addClassDays(now(), 5);

// Calculate class days between two dates
$daysRemaining = CSUDateHelper::classDaysBetween(now(), $deadline);

// Check if deadline passed
if (CSUDateHelper::isDeadlinePassed($case->answer_deadline)) {
    // Proceed ex parte (CSU Section G.9)
}

// Get countdown message
$countdown = CSUDateHelper::getDeadlineCountdown($case->decision_deadline);
// Returns: "3 class days remaining" or "Overdue by 2 class days"
```

**Configure University Holidays:**
```php
CSUDateHelper::setUniversityHolidays([
    '2026-04-03', // Maundy Thursday
    '2026-04-04', // Good Friday
    '2026-12-25', // Christmas
    // Add more holidays as needed
]);
```

---

## 📨 Notifications (To Be Implemented)

**Recommended Notifications:**

1. **To Student:** When charge is filed (includes case number, offense, answer deadline)
2. **To Parent/Guardian:** When sanction is applied (CSU G.21.2)
3. **To SDT Members:** When assigned to a case
4. **To OSDW:** When answer deadline passes without response (ex parte flag)
5. **To Student:** When hearing is scheduled
6. **To All Parties:** When decision is rendered

**Laravel Command:**
```bash
php artisan make:notification CaseChargeFiledNotification
```

---

## 🧪 Testing Checklist

- [ ] Create a test student account
- [ ] Create a test staff account
- [ ] Log in as staff and report an incident
- [ ] Verify case tracking number is generated
- [ ] Check that offense count is 1
- [ ] Report same student for same offense again
- [ ] Verify offense count is now 2
- [ ] Check that sanction changed to 2nd offense sanction
- [ ] Log in as admin and view case list
- [ ] Verify access log recorded your view
- [ ] Assign SDT to a tribunal case
- [ ] Verify workflow log recorded the assignment

---

## 📖 CSU Manual Sections Implemented

| Section | Description | Implementation |
|---------|-------------|----------------|
| **G.1** | SDT Composition (5 members) | `sdt_members` JSON field |
| **G.4** | Official Entry Book | `case_tracking_number` |
| **G.6** | Answer Deadline (5 days) | `answer_deadline` auto-calculated |
| **G.7** | Hearing Schedule | `hearing_scheduled_date` |
| **G.9** | Ex Parte Proceedings | `isAnswerOverdue()` method |
| **G.11** | Committee Report | `committee_report` field |
| **G.13** | Decision Timeline (15 days) | `decision_deadline` |
| **G.15** | Appeal Process (10 days) | `appeal_deadline` |
| **G.20** | Confidentiality | Access logging + private storage |
| **G.21.2** | Parent Notification | `parent_notified` tracking |
| **G.22** | Summary Investigation | `investigation_type` enum |

---

## 🎓 Example Workflow

### Scenario: Guard Reports Uniform Violation

1. **Guard logs in** → Goes to "Quick Log"
2. **Searches student** by ID: `2026-00123`
3. **Selects offense:** "Uniform/ID Violation" (OT-03)
4. **Submits report** with note: "Student not wearing ID at gate"
5. **System generates:** Case `CSU-2026-03-0001`
6. **System checks history:** This is student's 1st uniform offense
7. **System applies:** "Reprimand" (1st offense sanction)
8. **Investigation type:** "Summary" (no tribunal needed)
9. **Notification sent** to student's email
10. **OSDW receives** pending case for review

### One Month Later: Same Student, Same Offense

1. **Guard reports** same student for uniform violation
2. **System generates:** Case `CSU-2026-04-0015`
3. **System detects:** This is student's **2nd** uniform offense
4. **System applies:** "5 class days suspension" (2nd offense sanction)
5. **Investigation type:** "Summary"
6. **Dean approves** suspension directly
7. **Parent notified** per CSU G.21.2
8. **Student serves** suspension

---

## 💡 Tips for Deployment

### Production Checklist
- [ ] Set up daily database backups
- [ ] Configure email notifications (Mailgun/AWS SES)
- [ ] Set up queue worker for file uploads: `php artisan queue:work`
- [ ] Configure university holidays in CSUDateHelper
- [ ] Set up role permissions properly
- [ ] Test evidence file downloads work correctly
- [ ] Verify private storage is truly private (not web-accessible)
- [ ] Set up monitoring for overdue cases
- [ ] Create admin dashboard with statistics

### Performance Optimization
- Index `case_tracking_number` for fast lookups
- Cache offense rules (they rarely change)
- Use eager loading for relationships in lists
- Queue large evidence file processing

---

## 🔗 Related Files

**Documentation:**
- `Markdowns/CSU-IMPLEMENTATION-GUIDE.md` - Full implementation details
- `Markdowns/CSU-STUDENT-MANUAL.md` - Original student manual text

**Key Code Files:**
- `app/Models/OffenseRule.php` - Offense rules model
- `app/Models/ViolationRecord.php` - Case records model
- `app/Models/CaseEvidence.php` - Evidence files model
- `app/Models/CaseWorkflowLog.php` - Audit trail model
- `app/Helpers/CSUDateHelper.php` - Date/deadline calculations
- `app/Livewire/Staff/ReportIncident.php` - Staff reporting component
- `app/Livewire/Admin/CaseManagement.php` - Admin dashboard component

**Database:**
- `database/seeders/CSUOffenseRulesCompleteSeeder.php` - 58 pre-loaded offense rules
- `database/migrations/2026_03_01_135603_enhance_offense_rules_table_for_progressive_sanctions.php`
- `database/migrations/2026_03_01_135610_enhance_violation_records_for_csu_workflow.php`
- `database/migrations/2026_03_01_135617_create_case_evidence_table.php`
- `database/migrations/2026_03_01_135617_create_case_workflow_logs_table.php`

---

## ❓ FAQ

**Q: How do I add a new offense rule?**

```bash
php artisan tinker
>>> App\Models\OffenseRule::create([
    'code' => 'CUSTOM-01',
    'title' => 'My Custom Offense',
    'description' => 'Description here',
    'category' => 'Behavioral',
    'severity_level' => 'Minor',
    'first_offense_sanction' => 'Reprimand',
    'second_offense_sanction' => '5 class days suspension',
    'third_offense_sanction' => 'Dismissal',
    'requires_tribunal' => false,
    'is_active' => true,
]);
```

**Q: How do I manually log a case access?**

```php
$case = ViolationRecord::find(1);
$case->logAccess(auth()->id(), 'Manual review by registrar');
```

**Q: How do I check a student's violation history?**

```php
$student = User::find($studentId);
$violations = ViolationRecord::where('student_id', $studentId)
    ->with('offenseRule')
    ->get();
```

**Q: How do I reset the offense count for a student?**

This should NEVER be done programmatically. The offense count is auto-calculated based on actual records. To "reset," you would need to change the status of resolved cases, which should only be done through official university procedures with proper documentation.

---

## ✅ System Status

Current Status: **✅ READY FOR TESTING**

**Completed:**
- ✅ Database schema
- ✅ All models and relationships
- ✅ 58 offense rules seeded
- ✅ Progressive sanctions engine
- ✅ Staff incident reporting component
- ✅ Admin case management component
- ✅ Confidentiality & audit trail
- ✅ Due process workflow fields
- ✅ Evidence file management

**Pending:**
- ⏳ Blade views (HTML/CSS)
- ⏳ Email notifications
- ⏳ SDT workspace interface
- ⏳ Student answer submission portal
- ⏳ Summary investigation workflow
- ⏳ Reports & analytics dashboard

---

**For Support:** Refer to CSU Student Manual or Laravel Boost documentation in `AGENTS.md`
