# Staff Portal Implementation - Technical Documentation

## Overview
The Authorized Staff Portal enables faculty, guards, and department heads to report student conduct incidents efficiently while maintaining strict data encapsulation. The system features a modern, reactive interface built with Livewire 3 for real-time interactions, including:

- **Quick Log Modal**: Asynchronous submission for minor infractions with auto-verification
- **Formal Charge System**: Full-page form with drag-and-drop uploads and certification requirement
- **Status Tracking**: Real-time updates with visual status indicators

Staff members can submit incident reports and track their status, but cannot access a student's complete disciplinary history (per CSU Student Manual Section G.20).

## Enhanced Features

### 1. Quick Log Modal (Livewire Component)
**Component**: `App\Livewire\Staff\QuickLog`
**View**: `resources/views/livewire/staff/quick-log.blade.php`

#### Engineering Features:
- **Asynchronous Submission**: Uses Livewire for reactive, no-reload submissions
- **Auto-Fetch Identity Verification**: Real-time student lookup via `wire:model.live.debounce.500ms`
- **Streamlined Validation**: Only requires 3 inputs (student_id, offense_id, description)
- **Bypass Upload Logic**: Quick logs skip file upload handlers for instant processing
- **Minor Offenses Only**: Automatically filters to show only minor gravity offenses

#### Design Features:
- **Modal Interface**: Clean floating modal with Platinum (#f3f3f3) background
- **Auto-Focus**: Student ID field receives focus on modal open
- **Identity Verification Display**:
  - ✓ Green badge when student found: "E.G. - BSIT"
  - ✗ Red badge when student not found
  - Loading spinner during verification
- **Action Buttons**: Black Cherry (#590004) submit, subdued cancel
- **Loading States**: Disabled button with spinner during submission

#### Technical Implementation:
```php
// Component listens for event from parent
#[On('open-quick-log-modal')]
public function openModal(): void

// Auto-verification on student ID input
public function updatedStudentId(): void {
    $student = User::where('student_id', $this->studentId)
        ->role('student')->first();
    // Generate initials and program
}

// Streamlined submission
public function submit(): void {
    // Creates report without evidence
    // Dispatches IncidentReported event
    // Refreshes dashboard
}
```

### 2. Formal Charge Enhancements

#### Drag-and-Drop File Upload:
**Alpine.js Integration** for visual feedback:
```blade
<div x-data="{ 
    fileName: '', 
    dragging: false,
    handleFiles(files) {
        this.fileName = files[0].name;
    }
}">
```

**Features**:
- **Visual States**: Border changes to Inferno (#a50104) when dragging
- **File Name Display**: Shows selected file name in green
- **Click or Drag**: Supports both interaction methods
- **Highlight on Drag**: Background turns to `bg-red-50` during drag

#### Certification Requirement:
**Mandatory Checkbox** with psychological friction:
```html
<input type="checkbox" name="certification" required>
I certify that this report is accurate and filed in good faith.
```

**Design**:
- Red border (#a50104) box with light red background
- Bold certification statement
- Warning about false reports
- Required field validation

**Purpose**: Ensures staff review entries before escalating major offenses

### 3. Data Table & Empty States

#### Status Mapping with Colors:
| Status | Color | Hex | Badge Style |
|--------|-------|-----|-------------|
| Submitted | Blue | #3B82F6 | bg-blue-100 text-blue-800 |
| Under Review by OSDW | Black Cherry | #590004 | bg-[#590004] text-[#f3f3f3] |
| Resolved | Green | #10B981 | bg-green-100 text-green-800 |
| Dismissed | Gray | #6B7280 | bg-gray-100 text-gray-700 |

#### Empty State Design:
- Centered folder icon (16×16, gray-300)
- "No incident reports submitted yet" in muted gray
- Prominent "+ New Report" button
- Platinum background for visual comfort

#### Populated Table Features:
- **Hover Effects**: Subtle gray-50 background on row hover
- **Monospaced Fonts**: Tracking numbers and Student IDs use `font-mono`
- **Clickable Tracking Numbers**: Link to detail view with hover effect
- **Conditional Rendering**: Shows appropriate UI based on data presence

## Architecture

### Database Schema

#### `incident_reports` Table
```php
- id (primary key)
- tracking_number (unique) - Format: INC-YYYY-XXXXX
- reporter_id (foreign key → users)
- student_id (foreign key → users)
- offense_id (foreign key → offense_rules)
- report_type (enum: 'Quick Log', 'Formal Charge')
- description (text)
- evidence_path (nullable string)
- status (enum: 'Submitted', 'Under Review by OSDW', 'Resolved', 'Dismissed')
- timestamps
```

#### `offense_rules` Table
```php
- id (primary key)
- code (unique) - e.g., "AC-001", "DC-015"
- title (string)
- description (text)
- category (enum: 'Academic', 'Behavioral', 'Procedural', 'Safety', 'Technology')
- severity_level (enum: 'Minor', 'Moderate', 'Major', 'Severe')
- gravity (enum: 'minor', 'major', 'other') - Aligns with CSU Manual classification
- standard_sanction (string)
- first_offense_sanction (nullable string)
- second_offense_sanction (nullable string)
- third_offense_sanction (nullable string)
- legal_reference (nullable string)
- requires_tribunal (boolean)
- is_active (boolean)
- timestamps
```

**Note**: The `gravity` column was added to align with CSU Student Manual terminology, which explicitly separates "Minor Offenses" and "Major Offenses". This enables efficient filtering in the Quick Log modal (which only shows minor offenses) and supports the manual's two-tier classification system.

#### `users` Table (Enhanced)
```php
- id (primary key)
- student_id (string, nullable, unique) - Student ID number
- name (string)
- email (string, unique)
- program (string, nullable) - e.g., "BSIT", "BSCS"
- email_verified_at (timestamp, nullable)
- password (string)
- remember_token (string)
- timestamps
```

**Note**: The `student_id` and `program` fields were added via migration `2026_03_01_143154_add_student_fields_to_users_table.php` to support identity verification in the Quick Log modal.

### Livewire Components

#### QuickLog Component
**Namespace**: `App\Livewire\Staff\QuickLog`
**Location**: `app/Livewire/Staff/QuickLog.php`

**Properties**:
- `$showModal` - Controls modal visibility
- `$studentId` - Student identifier (validated, live-updates)
- `$offenseId` - Selected offense (validated)
- `$description` - Incident description (validated, min:10)
- `$studentInitials` - Auto-generated from student name
- `$studentProgram` - Student's program (e.g., BSIT)
- `$studentVerified` - Boolean flag for verification status
- `$minorOffenses` - Collection of minor gravity offenses

**Lifecycle Methods**:
- `mount()` - Loads minor offenses on component initialization, ordered by code
- `updatedStudentId()` - Triggered on student ID input change, performs auto-verification
- `openModal()` - Opens modal and resets form state (listens to `open-quick-log-modal` event)
- `closeModal()` - Closes modal and clears errors
- `submit()` - Creates incident report and dispatches events

**Events Dispatched**:
- `incident-logged` - After successful submission (includes tracking number)
- `refresh-dashboard` - Triggers parent component to reload

**Attributes Used**:
- `#[On('open-quick-log-modal')]` - Listens for modal open event
- `#[Validate('...')]` - Laravel validation rules on properties

### Models

#### IncidentReport Model
**Location:** `app/Models/IncidentReport.php`

**Relationships:**
- `reporter()` - BelongsTo User (the staff member who filed the report)
- `student()` - BelongsTo User (the student involved)
- `offense()` - BelongsTo OffenseRule

**Fillable Fields:**
- tracking_number
- reporter_id
- student_id
- offense_id
- report_type
- description
- evidence_path
- status

### Factory

#### IncidentReportFactory
**Location:** `database/factories/IncidentReportFactory.php`

**Factory States:**
- `quickLog()` - Creates a Quick Log report without evidence
- `formalCharge()` - Creates a Formal Charge with evidence
- `submitted()` - Sets status to 'Submitted'
- `underReview()` - Sets status to 'Under Review by OSDW'
- `resolved()` - Sets status to 'Resolved'
- `dismissed()` - Sets status to 'Dismissed'

### Routes

**Prefix:** `/staff`  
**Middleware:** `auth`, `role:staff|administrator`  
**Route Name Prefix:** `staff.`

| Method | URI | Action | Middleware | Description |
|--------|-----|--------|------------|-------------|
| GET | /staff/dashboard | StaffIncidentController@index | - | Staff dashboard with incident reporting hub |
| GET | /staff/report/create | StaffIncidentController@create | - | Show incident report form |
| POST | /staff/report | StaffIncidentController@store | - | Store new incident report |
| GET | /staff/report/{report} | StaffIncidentController@show | can:view,report | View specific report details |
| GET | /staff/my-reports | StaffIncidentController@myReports | - | List all reports by staff member |

### Controller

#### StaffIncidentController
**Location:** `app/Http/Controllers/StaffIncidentController.php`

**Methods:**

##### `index()` - Staff Dashboard
- Displays recent reports submitted by authenticated staff
- Calculates dashboard metrics (total, submitted, under review, resolved)
- **Returns:** `staff.dashboard` view

##### `create()` - Report Form
- Loads all students and offense rules for dropdowns
- **Returns:** `staff.report-create` view

##### `store()` - Submit Report
**Validation Rules:**
- `student_id`: required, exists in users table
- `offense_id`: required, exists in offense_rules table
- `report_type`: required, must be 'Quick Log' or 'Formal Charge'
- `description`: required, string, minimum 10 characters
- `evidence`: nullable, file, mimes:jpg,png,pdf, max:5120 KB

**Process:**
1. Validates input
2. Securely uploads evidence to `confidential_evidence` directory (outside public)
3. Generates unique tracking number (format: INC-YYYY-XXXXX)
4. Creates incident report
5. Dispatches `IncidentReported` event for OSDW notification
6. Redirects to dashboard with success message

##### `show()` - View Report Details
- Authorizes via policy (staff can only view their own reports)
- Eager loads relationships
- **Returns:** `staff.report-show` view

##### `myReports()` - List All Reports
- Displays paginated list of all reports by authenticated staff
- **Returns:** `staff.my-reports` view

### Policy

#### IncidentReportPolicy
**Location:** `app/Policies/IncidentReportPolicy.php`

**Authorization Rules:**

| Action | Rule |
|--------|------|
| `viewAny` | Staff or Administrator |
| `view` | Reporter (owner) or Administrator |
| `create` | Staff or Administrator |
| `update` | Administrator only |
| `delete` | Administrator only |
| `restore` | Administrator only |
| `forceDelete` | Administrator only |

**Critical Security:**
- Staff members can ONLY view reports they submitted
- Prevents unauthorized access to other staff members' reports
- Enforces read-only access after submission (only admins can update)

### Event

#### IncidentReported Event
**Location:** `app/Events/IncidentReported.php`

**Purpose:** Triggered when a staff member submits an incident report

**Properties:**
- `$report` (IncidentReport) - The newly created report

**Broadcast Channel:** `admin-notifications` (private channel)

**Usage:**
```php
Event::dispatch(new IncidentReported($report));
```

**Future Enhancement:** Create a listener to send email/notification to OSDW administrators

## Views

### Layout Component

#### staff-app-layout.blade.php
**Location:** `resources/views/components/staff-app-layout.blade.php`

**Features:**
- Black Cherry (#590004) sidebar - distinct from student interface
- Navigation: Incident Hub, My Submitted Reports
- User profile display with logout
- Academic year display in header

### Staff Dashboard (Enhanced with Livewire)
**Location:** `resources/views/staff/dashboard.blade.php`

**Features:**
1. **Success Message Alert** - Green banner with tracking number after submission
2. **Action Cards:**
   - **Quick Log Button** - Opens Livewire modal via `Livewire.dispatch('open-quick-log-modal')`
   - **Formal Charge Link** - Navigates to full-page form
3. **Metrics Dashboard:**
   - Total Reports (gray badge)
   - Submitted Count (blue badge) 
   - Under Review Count (yellow badge)
   - Resolved Count (green badge)
4. **Recent Reports Table:**
   - Date Filed with time
   - Tracking Number (clickable, font-mono)
   - Student ID (privacy-preserving, font-mono)
   - Offense name
   - Status badges with conditional colors
   - Privacy disclaimer footer
5. **Livewire Integration:**
   - Quick Log component embedded: `@livewire('staff.quick-log')`
   - Event listeners for `incident-logged` and `refresh-dashboard`

### Quick Log Modal (Livewire)
**Component:** `livewire/staff/quick-log.blade.php`

**Structure:**
- **Backdrop**: Semi-transparent black overlay (can close modal)
- **Modal Card**: Platinum background, rounded-2xl, max-w-2xl
- **Header**: Title, description, close button (X)
- **Form Fields:**
  1. **Student ID Input**:
     - Auto-focused on open: `x-init="$nextTick(() => $el.focus())"`
     - Live validation: `wire:model.live.debounce.500ms`
     - Identity verification display (green/red badge)
     - Loading spinner during verification
  2. **Offense Dropdown**: Minor offenses only
  3. **Description Textarea**: Minimum 10 characters
- **Action Buttons**:
   - Cancel (gray button)
   - Submit Log (Black Cherry, with loading state)
- **Alpine.js Integration**: Modal visibility controlled by `x-show` and `x-data`
- **Transitions**: Smooth fade-in/fade-out with scale effect

### Report Creation Form (Enhanced)
**Location:** `resources/views/staff/report-create.blade.php`

**Features:**
- Dynamic header based on `request('type')` parameter
- Student selection dropdown
- Offense type selection dropdown
- Incident description textarea (min 10 chars)
- **Enhanced File Upload (Formal Charges Only)**:
  - Alpine.js drag-and-drop handler
  - Visual feedback on drag: border-[#a50104], bg-red-50
  - File name display after selection
  - Click or drag interaction
  - Required validation for formal charges
- **Certification Checkbox (Formal Charges Only)**:
  - Red-bordered box with warning background
  - Required field
  - Bold certification statement
  - Good faith clause
- Privacy & confidentiality notice
- Dynamic submit button text based on report type
- Cancel and Submit buttons

### My Reports List
**Location:** `resources/views/staff/my-reports.blade.php`

**Features:**
- Paginated table of all reports by staff member
- Columns: Date, Tracking #, Student, Offense, Type, Status, Actions
- Status badges with color coding
- "View Details" link for each report
- Empty state with call-to-action

### Report Details
**Location:** `resources/views/staff/report-show.blade.php`

**Sections:**
1. **Header:**
   - Back navigation
   - Status badge
   - Tracking number
   - Report type badge
2. **Student Information Card:**
   - Name
   - Student ID
   - Email
3. **Offense Details Card:**
   - Offense name
   - Section number
   - Gravity level
4. **Incident Description**
5. **Evidence Attachment** (if exists)
6. **Reporter Information**
7. **Privacy Notice** - Explains limited access to resolution details

## Color Palette

### Staff Portal Colors
| Color Name | Hex Code | Usage |
|------------|----------|-------|
| Black Cherry | #590004 | Sidebar background, primary buttons |
| Mahogany | #250001 | Active nav states, text headers |
| Inferno | #a50104 | Formal charge actions, hover states |
| Platinum | #f3f3f3 | Main background, card backgrounds |

### Status Colors
| Status | Color |
|--------|-------|
| Submitted | Blue (#3B82F6) |
| Under Review | Black Cherry (#590004) |
| Resolved | Green (#10B981) |
| Dismissed | Gray (#6B7280) |

## Security Features

### Data Encapsulation
1. **Staff Isolation:**
   - Staff can only view their own submitted reports
   - No access to other staff members' reports
   - No access to student's complete disciplinary history

2. **Evidence Security:**
   - Files stored in `storage/app/confidential_evidence`
   - Outside public directory
   - Only accessible through authenticated routes
   - Displayed as "View (Admin Only)" button for staff

3. **Authorization:**
   - Policy enforcement on all routes
   - Middleware protection (`auth`, `role:staff|administrator`)
   - Route model binding with authorization gates

4. **Privacy Compliance:**
   - Student names hidden in tables (only Student ID shown)
   - Resolution details not accessible to staff
   - Explicit privacy notices on dashboard and detail views
   - Compliant with CSU Student Manual Section G.20

### Input Validation
- All inputs sanitized and validated
- File upload restrictions (type, size)
- CSRF protection on all forms
- SQL injection prevention via Eloquent ORM

## File Upload Security

### Evidence Upload
**Storage Path:** `storage/app/confidential_evidence/`

**Configuration:**
- Disk: `local` (not public)
- Accepted formats: JPG, PNG, PDF
- Max size: 5120 KB (5 MB)
- Generated filename: UUID-based to prevent enumeration

**Access Control:**
- Files not web-accessible
- Future: Create download route with authorization
- Admin-only viewing through secure endpoint

## Testing Considerations

### Unit Tests
- IncidentReportFactory generates valid reports
- Model relationships function correctly
- Event dispatches properly

### Feature Tests
1. **Staff Dashboard:**
   - Authenticated staff can access dashboard
   - Students cannot access staff dashboard
   - Dashboard displays correct metrics

2. **Report Creation:**
   - Valid reports are created successfully
   - Validation errors prevent invalid submissions
   - Evidence uploads work correctly
   - Tracking numbers are unique

3. **Report Viewing:**
   - Staff can view their own reports
   - Staff cannot view other staff members' reports
   - Administrators can view all reports

4. **Authorization:**
   - Policy prevents unauthorized access
   - Route middleware enforces role restrictions

### Example Test Commands
```bash
# Run all tests
php artisan test --compact

# Test specific feature
php artisan test --compact --filter=StaffIncident

# With coverage
php artisan test --coverage
```

## Usage Examples

### Creating Test Data
```php
// Create a staff user
$staff = User::factory()->create();
$staff->assignRole('staff');

// Create an incident report
$report = IncidentReport::factory()
    ->formalCharge()
    ->submitted()
    ->create([
        'reporter_id' => $staff->id
    ]);

// Create multiple reports
$reports = IncidentReport::factory()
    ->count(10)
    ->quickLog()
    ->create();
```

### Querying Reports
```php
// Get all reports by a staff member
$reports = IncidentReport::where('reporter_id', Auth::id())
    ->with(['student', 'offense'])
    ->orderBy('created_at', 'desc')
    ->get();

// Get reports by status
$pending = IncidentReport::where('status', 'Submitted')->get();
$underReview = IncidentReport::where('status', 'Under Review by OSDW')->get();
```

## Future Enhancements

### Phase 2 Features
1. **Notifications:**
   - Email notification to OSDW when report is submitted
   - Status update notifications to staff reporter
   - Implement IncidentReported event listener

2. **Evidence Management:**
   - Secure download route for administrators
   - Image preview for JPG/PNG files
   - PDF viewer integration

3. **Reporting & Analytics:**
   - Export reports to CSV/PDF
   - Statistical dashboard for staff
   - Offense type frequency charts

4. **Search & Filter:**
   - Search by tracking number
   - Filter by status, date range, offense type
   - Advanced filtering options

5. **Batch Operations:**
   - Bulk status updates for administrators
   - Mass notifications

## Migration & Deployment

### Migration Command
```bash
php artisan migrate
```

### Rollback
```bash
php artisan migrate:rollback
```

### Seeding (if applicable)
```bash
# Create test incident reports
php artisan db:seed --class=IncidentReportSeeder
```

## API Documentation (Future)

### Endpoints
- `POST /api/v1/staff/reports` - Submit report via API
- `GET /api/v1/staff/reports` - List reports
- `GET /api/v1/staff/reports/{id}` - Get report details

### Authentication
- Sanctum token-based authentication
- Role-based access control

## Compliance & Regulations

### CSU Student Manual References
- **Section G.4:** Incident reports are allegations/charges, not proven violations
- **Section G.20:** Staff members cannot access student's complete disciplinary history
- **Sections A, F, I:** Evidence requirements for specific offense types

### Data Privacy
- FERPA compliance
- Limited data exposure principle
- Audit trail for access logs (future enhancement)

## Troubleshooting

### Common Issues

**Issue:** Staff member cannot access dashboard  
**Solution:** Verify user has `staff` or `administrator` role assigned

**Issue:** Cannot view report details  
**Solution:** Ensure user is the report creator or has admin role

**Issue:** File upload fails  
**Solution:** 
- Check `storage/app/confidential_evidence` directory exists and is writable
- Verify file size is under 5MB
- Confirm file type is JPG, PNG, or PDF

**Issue:** Tracking number not unique  
**Solution:** Tracking numbers use `Str::random(5)` - collision rate is extremely low but add retry logic if needed

## Conclusion

The Staff Portal implementation provides a secure, efficient, and privacy-compliant system for incident reporting. The architecture enforces strict data encapsulation while maintaining usability for frontline staff members.

All components follow Laravel best practices, CSU Student Manual requirements, and enterprise-grade security standards.
