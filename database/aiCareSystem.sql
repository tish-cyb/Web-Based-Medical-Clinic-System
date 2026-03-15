-- ============================================================
--  aicaresystem.sql
--  AiCare System – Complete Database Schema
--  Compatible with: MySQL 8.0+ / MariaDB 10.4+
--
--  PORTALS
--  ├── Student Portal    → role: 'student'
--  ├── Employee Portal   → role: 'employee'
--  ├── Medical Portal    → role: 'medical_staff'
--  └── Admin             → role: 'admin'
--
--  PATIENT TYPES
--  Every clinical record (appointments, consultations, exams,
--  lab requests, clearances, certificates, consent) carries
--  either a student_id OR an employee_id — never both.
--  This is enforced by a CHECK constraint on each table and
--  the patient_type ENUM column for fast filtering.
-- ============================================================

CREATE DATABASE IF NOT EXISTS aicaresystem
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE aicaresystem;

-- ============================================================
-- 1. USERS
--    Single authentication table for ALL portal roles.
--    After login, the role column determines which portal
--    loads and which profile table the session maps to:
--      'student'       → students table  (Student Portal)
--      'employee'      → employees table (Employee Portal)
--      'medical_staff' → medical_staff   (Medical Portal)
--      'admin'         → medical_staff   (Medical Portal + admin)
-- ============================================================
CREATE TABLE users (
    user_id        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email          VARCHAR(191) NOT NULL UNIQUE,

    -- ⚠️  DEV/DEMO ONLY — drop this column before going to production.
    --     Stores the plain-text password so developers can read test
    --     credentials at a glance.  Never expose it to the frontend or
    --     include it in production SELECT queries.
    password_plain VARCHAR(100) NULL
                   COMMENT 'DEV ONLY: plain-text reference; drop in production',

    -- The ONLY field used for login verification in production.
    -- Populate with PHP: password_hash($plain, PASSWORD_BCRYPT)
    -- Verify  with PHP: password_verify($input, $hash)
    -- VARCHAR(255) future-proofs for stronger algorithms (Argon2id etc.)
    -- without requiring a schema change.
    password_hash  VARCHAR(255) NOT NULL
                   COMMENT 'bcrypt hash — the only field used for authentication',

    role           ENUM('student','employee','medical_staff','admin')
                   NOT NULL DEFAULT 'student',
    is_active      TINYINT(1)  NOT NULL DEFAULT 1,
    last_login     DATETIME,
    created_at     DATETIME    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at     DATETIME    NOT NULL DEFAULT CURRENT_TIMESTAMP
                   ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_users_role (role)
) ENGINE=InnoDB;

-- ============================================================
-- 2. STUDENTS
--    Profile for users with role = 'student'.
--    Powers the Student Portal:
--      book_appointment.php, medical_history.php,
--      certificates.php, profile.php
-- ============================================================
CREATE TABLE students (
    student_id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id                  INT UNSIGNED NOT NULL UNIQUE,
    student_number           VARCHAR(30)  NOT NULL UNIQUE
                             COMMENT 'e.g. 2023-12345-MN-0',
    last_name                VARCHAR(100) NOT NULL,
    first_name               VARCHAR(100) NOT NULL,
    middle_name              VARCHAR(100),
    sex                      ENUM('Male','Female','Prefer not to say'),
    date_of_birth            DATE,
    civil_status             ENUM('Single','Married','Widowed','Separated'),
    contact_number           VARCHAR(20),
    email                    VARCHAR(191),
    address                  TEXT,
    campus                   VARCHAR(100),
    college                  VARCHAR(100),
    program                  VARCHAR(100) COMMENT 'e.g. BSIT',
    year_level               ENUM('1st Year','2nd Year','3rd Year',
                                  '4th Year','5th Year','Graduate'),
    school_year              VARCHAR(20)  COMMENT 'e.g. 2025-2026',
    emergency_contact_name   VARCHAR(200),
    emergency_contact_number VARCHAR(20),
    photo_path               VARCHAR(255),
    created_at               DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at               DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
                             ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_student_user FOREIGN KEY (user_id)
        REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_student_number  (student_number),
    INDEX idx_student_college (college)
) ENGINE=InnoDB;

-- ============================================================
-- 3. EMPLOYEES  (Faculty, Admin Staff, Non-teaching Personnel)
--    Profile for users with role = 'employee'.
--    Powers the Employee Portal — identical pages to the
--    Student Portal but scoped to employee records:
--      book_appointment.php, medical_history.php,
--      certificates.php, profile.php  (employee versions)
--    Also appears in Medical Portal → patient_records.php
--    Faculty/Staff tab and consultations.php employee forms.
-- ============================================================
CREATE TABLE employees (
    employee_id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id                  INT UNSIGNED NOT NULL UNIQUE,
    employee_number          VARCHAR(30)  NOT NULL UNIQUE
                             COMMENT 'e.g. EMP-00123',
    last_name                VARCHAR(100) NOT NULL,
    first_name               VARCHAR(100) NOT NULL,
    middle_name              VARCHAR(100),
    sex                      ENUM('Male','Female','Prefer not to say'),
    date_of_birth            DATE,
    civil_status             ENUM('Single','Married','Widowed','Separated'),
    contact_number           VARCHAR(20),
    email                    VARCHAR(191),
    address                  TEXT,
    campus                   VARCHAR(100),
    department               VARCHAR(100)
                             COMMENT 'e.g. CCS, HR Office, Maintenance',
    position_designation     VARCHAR(150)
                             COMMENT 'e.g. Associate Professor I, Admin Aide',
    employment_type          ENUM('Permanent','Temporary','Part-time / Contractual'),
    emergency_contact_name   VARCHAR(200),
    emergency_contact_number VARCHAR(20),
    photo_path               VARCHAR(255),
    is_active                TINYINT(1) NOT NULL DEFAULT 1,
    created_at               DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at               DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
                             ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_employee_user FOREIGN KEY (user_id)
        REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_emp_number (employee_number),
    INDEX idx_emp_dept   (department)
) ENGINE=InnoDB;

-- ============================================================
-- 4. MEDICAL STAFF  (Nurses, Physicians, Clinic Admins)
--    Profile for users with role = 'medical_staff' or 'admin'.
--    Powers the Medical Portal:
--      medical_dashboard.php, roster.php, appointments.php,
--      patient_records.php, consultations.php, profile.php
-- ============================================================
CREATE TABLE medical_staff (
    staff_id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id           INT UNSIGNED NOT NULL UNIQUE,
    full_name         VARCHAR(200) NOT NULL,
    position          VARCHAR(100)
                      COMMENT 'e.g. Registered Nurse, Physician',
    license_number    VARCHAR(50),
    department        VARCHAR(100) DEFAULT 'AiCare Clinic',
    contact_number    VARCHAR(20),
    email             VARCHAR(191),
    photo_path        VARCHAR(255),
    created_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
                      ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_staff_user FOREIGN KEY (user_id)
        REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- 5. APPOINTMENTS
--    Booked from Student Portal and Employee Portal via
--    book_appointment.php.  Managed by medical staff via
--    appointments.php.
--
--    patient_type is a convenience ENUM that caches which
--    portal/patient the row belongs to and drives fast WHERE
--    clauses.  The CHECK constraint keeps it consistent with
--    the nullable FKs.
-- ============================================================
CREATE TABLE appointments (
    appointment_id   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id       INT UNSIGNED
                     COMMENT 'FK → students; NULL when patient_type = employee',
    employee_id      INT UNSIGNED
                     COMMENT 'FK → employees; NULL when patient_type = student',
    staff_id         INT UNSIGNED
                     COMMENT 'FK → medical_staff; assigned after booking',
    patient_type     ENUM('student','employee') NOT NULL,
    service_category ENUM(
                       'General Consultation',
                       'First Aid / Injury Care',
                       'Medical Clearance',
                       'Follow-Up Checkup',
                       'Health Counseling'
                     ) NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    reason_for_visit TEXT NOT NULL,
    status           ENUM(
                       'Pending','Confirmed','Waiting',
                       'In Progress','Completed','Cancelled','No Show'
                     ) NOT NULL DEFAULT 'Pending',
    consent_given    TINYINT(1) NOT NULL DEFAULT 0,
    notes            TEXT,
    created_at       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
                     ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_appt_student  FOREIGN KEY (student_id)
        REFERENCES students(student_id)    ON DELETE CASCADE  ON UPDATE CASCADE,
    CONSTRAINT fk_appt_employee FOREIGN KEY (employee_id)
        REFERENCES employees(employee_id)  ON DELETE CASCADE  ON UPDATE CASCADE,
    CONSTRAINT fk_appt_staff    FOREIGN KEY (staff_id)
        REFERENCES medical_staff(staff_id) ON DELETE SET NULL ON UPDATE CASCADE,

    CONSTRAINT chk_appt_patient CHECK (
        (patient_type = 'student'  AND student_id  IS NOT NULL AND employee_id IS NULL) OR
        (patient_type = 'employee' AND employee_id IS NOT NULL AND student_id  IS NULL)
    ),
    INDEX idx_appt_date         (appointment_date),
    INDEX idx_appt_status       (status),
    INDEX idx_appt_student      (student_id),
    INDEX idx_appt_employee     (employee_id),
    INDEX idx_appt_patient_type (patient_type)
) ENGINE=InnoDB;

-- ============================================================
-- 6. CONSULTATION RECORDS  (General Consultation Form)
--    Created by medical staff via consultations.php.
--    Linked to an appointment when the visit was pre-booked;
--    appointment_id is NULL for walk-in consultations.
-- ============================================================
CREATE TABLE consultation_records (
    record_id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    appointment_id     INT UNSIGNED
                       COMMENT 'FK → appointments; NULL for walk-ins',
    student_id         INT UNSIGNED
                       COMMENT 'FK → students; NULL when patient_type = employee',
    employee_id        INT UNSIGNED
                       COMMENT 'FK → employees; NULL when patient_type = student',
    staff_id           INT UNSIGNED NOT NULL,
    patient_type       ENUM('student','employee') NOT NULL,
    consultation_date  DATE NOT NULL,
    consultation_time  TIME,
    consultation_type  ENUM(
                         'Walk-in','Appointment','Follow-up',
                         'Emergency','Teleconsult'
                       ) NOT NULL DEFAULT 'Walk-in',
    college_department VARCHAR(100),
    -- Vitals
    blood_pressure     VARCHAR(20)    COMMENT 'e.g. 120/80 mmHg',
    temperature_c      DECIMAL(4,1)   COMMENT 'degrees Celsius',
    heart_rate_bpm     SMALLINT UNSIGNED,
    respiratory_rate   SMALLINT UNSIGNED,
    height_cm          DECIMAL(5,1),
    weight_kg          DECIMAL(5,1),
    bmi                DECIMAL(4,1),
    -- Clinical
    chief_complaint    TEXT NOT NULL,
    pe_findings        TEXT           COMMENT 'Physical Examination Findings',
    diagnosis          TEXT NOT NULL,
    treatment          TEXT NOT NULL,
    medications_given  TEXT,
    follow_up_date     DATE,
    days_of_rest       TINYINT UNSIGNED,
    additional_notes   TEXT,
    created_at         DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at         DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
                       ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_consult_appt     FOREIGN KEY (appointment_id)
        REFERENCES appointments(appointment_id)   ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_consult_student  FOREIGN KEY (student_id)
        REFERENCES students(student_id)           ON DELETE CASCADE  ON UPDATE CASCADE,
    CONSTRAINT fk_consult_employee FOREIGN KEY (employee_id)
        REFERENCES employees(employee_id)         ON DELETE CASCADE  ON UPDATE CASCADE,
    CONSTRAINT fk_consult_staff    FOREIGN KEY (staff_id)
        REFERENCES medical_staff(staff_id)        ON DELETE RESTRICT ON UPDATE CASCADE,

    CONSTRAINT chk_consult_patient CHECK (
        (patient_type = 'student'  AND student_id  IS NOT NULL AND employee_id IS NULL) OR
        (patient_type = 'employee' AND employee_id IS NOT NULL AND student_id  IS NULL)
    ),
    INDEX idx_consult_student      (student_id),
    INDEX idx_consult_employee     (employee_id),
    INDEX idx_consult_date         (consultation_date),
    INDEX idx_consult_patient_type (patient_type)
) ENGINE=InnoDB;

-- ============================================================
-- 7. HEALTH EXAMINATION RECORDS
--    Full annual PE form used for students (enrollment) and
--    employees (annual fitness check / return to work).
-- ============================================================
CREATE TABLE health_exam_records (
    exam_id                   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id                INT UNSIGNED
                              COMMENT 'FK → students; NULL when patient_type = employee',
    employee_id               INT UNSIGNED
                              COMMENT 'FK → employees; NULL when patient_type = student',
    staff_id                  INT UNSIGNED NOT NULL,
    patient_type              ENUM('student','employee') NOT NULL,
    exam_date                 DATE NOT NULL,
    school_year_semester      VARCHAR(50),
    -- Vitals
    blood_pressure            VARCHAR(20),
    temperature_c             DECIMAL(4,1),
    heart_rate_bpm            SMALLINT UNSIGNED,
    respiratory_rate          SMALLINT UNSIGNED,
    height_cm                 DECIMAL(5,1),
    weight_kg                 DECIMAL(5,1),
    bmi                       DECIMAL(4,1),
    -- Personal & Social History
    general_condition         VARCHAR(100),
    childhood_illness         TEXT,
    other_childhood           TEXT,
    operations_surgeries      TEXT,
    previous_hospitalizations TEXT,
    allergies                 TEXT,
    current_medications       TEXT,
    traveled_abroad           TINYINT(1) DEFAULT 0,
    alcohol_drinking          ENUM('Non-drinker','Social Drinker','Moderate','Heavy'),
    cigarette_smoking         ENUM('Non-smoker','Former','Current - Light','Current - Heavy'),
    last_menstruation         DATE,
    -- Family History
    family_history_notes      TEXT,
    -- Physical Findings & Clinical Impression
    physical_findings         TEXT,
    working_impression        TEXT,
    remarks                   TEXT,
    fit_status                ENUM('Fit','Fit with Conditions','Unfit'),
    for_workup                TEXT,
    follow_up_date            DATE,
    created_at                DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at                DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
                              ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_exam_student  FOREIGN KEY (student_id)
        REFERENCES students(student_id)    ON DELETE CASCADE  ON UPDATE CASCADE,
    CONSTRAINT fk_exam_employee FOREIGN KEY (employee_id)
        REFERENCES employees(employee_id)  ON DELETE CASCADE  ON UPDATE CASCADE,
    CONSTRAINT fk_exam_staff    FOREIGN KEY (staff_id)
        REFERENCES medical_staff(staff_id) ON DELETE RESTRICT ON UPDATE CASCADE,

    CONSTRAINT chk_exam_patient CHECK (
        (patient_type = 'student'  AND student_id  IS NOT NULL AND employee_id IS NULL) OR
        (patient_type = 'employee' AND employee_id IS NOT NULL AND student_id  IS NULL)
    ),
    INDEX idx_exam_student      (student_id),
    INDEX idx_exam_employee     (employee_id),
    INDEX idx_exam_date         (exam_date),
    INDEX idx_exam_patient_type (patient_type)
) ENGINE=InnoDB;

-- ============================================================
-- 8. LABORATORY REQUESTS
--    Issued from the Lab Exam Request form in consultations.php.
--    17 individual boolean columns for fast reporting.
-- ============================================================
CREATE TABLE lab_requests (
    lab_id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id        INT UNSIGNED
                      COMMENT 'FK → students; NULL when patient_type = employee',
    employee_id       INT UNSIGNED
                      COMMENT 'FK → employees; NULL when patient_type = student',
    staff_id          INT UNSIGNED NOT NULL,
    consultation_id   INT UNSIGNED
                      COMMENT 'FK → consultation_records; NULL if issued independently',
    patient_type      ENUM('student','employee') NOT NULL,
    request_date      DATE NOT NULL,
    -- Radiology
    chest_xray_pa     TINYINT(1) NOT NULL DEFAULT 0,
    chest_xray_aplat  TINYINT(1) NOT NULL DEFAULT 0,
    ecg               TINYINT(1) NOT NULL DEFAULT 0,
    -- Urinalysis / Stool
    urinalysis        TINYINT(1) NOT NULL DEFAULT 0,
    fecalysis         TINYINT(1) NOT NULL DEFAULT 0,
    drug_test         TINYINT(1) NOT NULL DEFAULT 0,
    -- Hematology & Chemistry
    cbc               TINYINT(1) NOT NULL DEFAULT 0,
    fbs               TINYINT(1) NOT NULL DEFAULT 0,
    bun               TINYINT(1) NOT NULL DEFAULT 0,
    creatinine        TINYINT(1) NOT NULL DEFAULT 0,
    total_cholesterol TINYINT(1) NOT NULL DEFAULT 0,
    triglycerides     TINYINT(1) NOT NULL DEFAULT 0,
    hdl               TINYINT(1) NOT NULL DEFAULT 0,
    ldl               TINYINT(1) NOT NULL DEFAULT 0,
    uric_acid         TINYINT(1) NOT NULL DEFAULT 0,
    sgpt              TINYINT(1) NOT NULL DEFAULT 0,
    hepatitis_b       TINYINT(1) NOT NULL DEFAULT 0,
    -- Free-text for unlisted tests
    other_tests       TEXT,
    physician_notes   TEXT,
    status            ENUM('Requested','Released','Completed')
                      NOT NULL DEFAULT 'Requested',
    created_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_lab_student   FOREIGN KEY (student_id)
        REFERENCES students(student_id)           ON DELETE CASCADE  ON UPDATE CASCADE,
    CONSTRAINT fk_lab_employee  FOREIGN KEY (employee_id)
        REFERENCES employees(employee_id)         ON DELETE CASCADE  ON UPDATE CASCADE,
    CONSTRAINT fk_lab_staff     FOREIGN KEY (staff_id)
        REFERENCES medical_staff(staff_id)        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_lab_consult   FOREIGN KEY (consultation_id)
        REFERENCES consultation_records(record_id) ON DELETE SET NULL ON UPDATE CASCADE,

    CONSTRAINT chk_lab_patient CHECK (
        (patient_type = 'student'  AND student_id  IS NOT NULL AND employee_id IS NULL) OR
        (patient_type = 'employee' AND employee_id IS NOT NULL AND student_id  IS NULL)
    ),
    INDEX idx_lab_student      (student_id),
    INDEX idx_lab_employee     (employee_id),
    INDEX idx_lab_date         (request_date),
    INDEX idx_lab_patient_type (patient_type)
) ENGINE=InnoDB;

-- ============================================================
-- 9. MEDICAL CLEARANCES
--    Students: enrollment, OJT, scholarship.
--    Employees: return to work, annual fitness.
--    Shown in both portals under Certificates (certificates.php).
-- ============================================================
CREATE TABLE medical_clearances (
    clearance_id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id           INT UNSIGNED
                         COMMENT 'FK → students; NULL when patient_type = employee',
    employee_id          INT UNSIGNED
                         COMMENT 'FK → employees; NULL when patient_type = student',
    staff_id             INT UNSIGNED NOT NULL,
    exam_id              INT UNSIGNED
                         COMMENT 'FK → health_exam_records; linked PE if applicable',
    patient_type         ENUM('student','employee') NOT NULL,
    issue_date           DATE NOT NULL,
    valid_until          DATE,
    purpose_of_clearance VARCHAR(200)
                         COMMENT 'e.g. Enrollment, OJT, Return to Work',
    fit_status           ENUM('Physically Fit','Fit with Conditions','Unfit') NOT NULL,
    conditions_noted     TEXT,
    school_year          VARCHAR(20),
    physician_name       VARCHAR(200),
    license_number       VARCHAR(50),
    remarks              TEXT,
    created_at           DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_clear_student  FOREIGN KEY (student_id)
        REFERENCES students(student_id)         ON DELETE CASCADE  ON UPDATE CASCADE,
    CONSTRAINT fk_clear_employee FOREIGN KEY (employee_id)
        REFERENCES employees(employee_id)       ON DELETE CASCADE  ON UPDATE CASCADE,
    CONSTRAINT fk_clear_staff    FOREIGN KEY (staff_id)
        REFERENCES medical_staff(staff_id)      ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_clear_exam     FOREIGN KEY (exam_id)
        REFERENCES health_exam_records(exam_id) ON DELETE SET NULL ON UPDATE CASCADE,

    CONSTRAINT chk_clear_patient CHECK (
        (patient_type = 'student'  AND student_id  IS NOT NULL AND employee_id IS NULL) OR
        (patient_type = 'employee' AND employee_id IS NOT NULL AND student_id  IS NULL)
    ),
    INDEX idx_clear_student      (student_id),
    INDEX idx_clear_employee     (employee_id),
    INDEX idx_clear_patient_type (patient_type)
) ENGINE=InnoDB;

-- ============================================================
-- 10. MEDICAL CERTIFICATES
--     Generated from the Medical Certificate form in
--     consultations.php; shown in both portals (certificates.php).
-- ============================================================
CREATE TABLE medical_certificates (
    certificate_id   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id       INT UNSIGNED
                     COMMENT 'FK → students; NULL when patient_type = employee',
    employee_id      INT UNSIGNED
                     COMMENT 'FK → employees; NULL when patient_type = student',
    staff_id         INT UNSIGNED NOT NULL,
    consultation_id  INT UNSIGNED
                     COMMENT 'FK → consultation_records; NULL if issued standalone',
    patient_type     ENUM('student','employee') NOT NULL,
    certificate_type ENUM(
                       'Sick Leave',
                       'Fitness to Study',
                       'Fitness to Work',
                       'Medical Clearance',
                       'General Purpose'
                     ) NOT NULL,
    issue_date       DATE NOT NULL,
    valid_until      DATE,
    purpose          TEXT,
    diagnosis        TEXT,
    days_of_rest     TINYINT UNSIGNED,
    physician_name   VARCHAR(200),
    license_number   VARCHAR(50),
    remarks          TEXT,
    status           ENUM('Active','Expired','Revoked') NOT NULL DEFAULT 'Active',
    created_at       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_cert_student  FOREIGN KEY (student_id)
        REFERENCES students(student_id)           ON DELETE CASCADE  ON UPDATE CASCADE,
    CONSTRAINT fk_cert_employee FOREIGN KEY (employee_id)
        REFERENCES employees(employee_id)         ON DELETE CASCADE  ON UPDATE CASCADE,
    CONSTRAINT fk_cert_staff    FOREIGN KEY (staff_id)
        REFERENCES medical_staff(staff_id)        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_cert_consult  FOREIGN KEY (consultation_id)
        REFERENCES consultation_records(record_id) ON DELETE SET NULL ON UPDATE CASCADE,

    CONSTRAINT chk_cert_patient CHECK (
        (patient_type = 'student'  AND student_id  IS NOT NULL AND employee_id IS NULL) OR
        (patient_type = 'employee' AND employee_id IS NOT NULL AND student_id  IS NULL)
    ),
    INDEX idx_cert_student      (student_id),
    INDEX idx_cert_employee     (employee_id),
    INDEX idx_cert_status       (status),
    INDEX idx_cert_patient_type (patient_type)
) ENGINE=InnoDB;

-- ============================================================
-- 11. CONSENT RECORDS  (Data Privacy Act 2012 compliance)
--     Collected at first booking or visit for both portals.
--     staff_id is NULL when the patient self-submitted online.
-- ============================================================
CREATE TABLE consent_records (
    consent_id    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id    INT UNSIGNED
                  COMMENT 'FK → students; NULL when patient_type = employee',
    employee_id   INT UNSIGNED
                  COMMENT 'FK → employees; NULL when patient_type = student',
    staff_id      INT UNSIGNED
                  COMMENT 'FK → medical_staff; NULL if self-submitted online',
    patient_type  ENUM('student','employee') NOT NULL,
    date_signed   DATE NOT NULL,
    consent_given TINYINT(1) NOT NULL DEFAULT 1,
    purpose_notes TEXT,
    created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_consent_student  FOREIGN KEY (student_id)
        REFERENCES students(student_id)    ON DELETE CASCADE  ON UPDATE CASCADE,
    CONSTRAINT fk_consent_employee FOREIGN KEY (employee_id)
        REFERENCES employees(employee_id)  ON DELETE CASCADE  ON UPDATE CASCADE,
    CONSTRAINT fk_consent_staff    FOREIGN KEY (staff_id)
        REFERENCES medical_staff(staff_id) ON DELETE SET NULL ON UPDATE CASCADE,

    CONSTRAINT chk_consent_patient CHECK (
        (patient_type = 'student'  AND student_id  IS NOT NULL AND employee_id IS NULL) OR
        (patient_type = 'employee' AND employee_id IS NOT NULL AND student_id  IS NULL)
    ),
    INDEX idx_consent_student      (student_id),
    INDEX idx_consent_employee     (employee_id),
    INDEX idx_consent_patient_type (patient_type)
) ENGINE=InnoDB;


-- ============================================================
-- ============================================================
--  V I E W S
-- ============================================================
-- ============================================================

-- ============================================================
-- V1. STUDENT MEDICAL HISTORY
--     Used by: Student Portal → medical_history.php
--     PHP query: WHERE student_id = :logged_in_student_id
-- ============================================================
CREATE OR REPLACE VIEW vw_student_medical_history AS
    SELECT cr.student_id,
           cr.consultation_date    AS record_date,
           'Consultation'          AS record_type,
           cr.consultation_type    AS sub_type,
           cr.diagnosis            AS diagnosis_purpose,
           cr.follow_up_date,
           cr.record_id            AS source_id,
           'consultation_records'  AS source_table
    FROM consultation_records cr
    WHERE cr.patient_type = 'student'
    UNION ALL
    SELECT he.student_id, he.exam_date,
           'Health Exam', he.fit_status, he.working_impression,
           he.follow_up_date, he.exam_id, 'health_exam_records'
    FROM health_exam_records he WHERE he.patient_type = 'student'
    UNION ALL
    SELECT lr.student_id, lr.request_date,
           'Laboratory Request', lr.status, lr.other_tests,
           NULL, lr.lab_id, 'lab_requests'
    FROM lab_requests lr WHERE lr.patient_type = 'student'
    UNION ALL
    SELECT mc.student_id, mc.issue_date,
           'Medical Clearance', mc.fit_status, mc.purpose_of_clearance,
           mc.valid_until, mc.clearance_id, 'medical_clearances'
    FROM medical_clearances mc WHERE mc.patient_type = 'student'
    UNION ALL
    SELECT cert.student_id, cert.issue_date,
           'Medical Certificate', cert.certificate_type, cert.purpose,
           cert.valid_until, cert.certificate_id, 'medical_certificates'
    FROM medical_certificates cert WHERE cert.patient_type = 'student';

-- ============================================================
-- V2. EMPLOYEE MEDICAL HISTORY
--     Used by: Employee Portal → medical_history.php
--     PHP query: WHERE employee_id = :logged_in_employee_id
-- ============================================================
CREATE OR REPLACE VIEW vw_employee_medical_history AS
    SELECT cr.employee_id,
           cr.consultation_date    AS record_date,
           'Consultation'          AS record_type,
           cr.consultation_type    AS sub_type,
           cr.diagnosis            AS diagnosis_purpose,
           cr.follow_up_date,
           cr.record_id            AS source_id,
           'consultation_records'  AS source_table
    FROM consultation_records cr WHERE cr.patient_type = 'employee'
    UNION ALL
    SELECT he.employee_id, he.exam_date,
           'Health Exam', he.fit_status, he.working_impression,
           he.follow_up_date, he.exam_id, 'health_exam_records'
    FROM health_exam_records he WHERE he.patient_type = 'employee'
    UNION ALL
    SELECT lr.employee_id, lr.request_date,
           'Laboratory Request', lr.status, lr.other_tests,
           NULL, lr.lab_id, 'lab_requests'
    FROM lab_requests lr WHERE lr.patient_type = 'employee'
    UNION ALL
    SELECT mc.employee_id, mc.issue_date,
           'Medical Clearance', mc.fit_status, mc.purpose_of_clearance,
           mc.valid_until, mc.clearance_id, 'medical_clearances'
    FROM medical_clearances mc WHERE mc.patient_type = 'employee'
    UNION ALL
    SELECT cert.employee_id, cert.issue_date,
           'Medical Certificate', cert.certificate_type, cert.purpose,
           cert.valid_until, cert.certificate_id, 'medical_certificates'
    FROM medical_certificates cert WHERE cert.patient_type = 'employee';

-- ============================================================
-- V3. STUDENT CERTIFICATES
--     Used by: Student Portal → certificates.php
--     Returns both clearances and certificates for a student.
--     PHP query: WHERE patient_id = :logged_in_student_id
-- ============================================================
CREATE OR REPLACE VIEW vw_student_certificates AS
    SELECT mc.clearance_id        AS cert_id,
           mc.student_id          AS patient_id,
           'Medical Clearance'    AS certificate_type,
           mc.issue_date,
           mc.valid_until,
           mc.purpose_of_clearance AS purpose,
           mc.fit_status          AS status_detail,
           mc.physician_name,
           mc.license_number,
           'medical_clearances'   AS source_table
    FROM medical_clearances mc WHERE mc.patient_type = 'student'
    UNION ALL
    SELECT cert.certificate_id, cert.student_id,
           cert.certificate_type,
           cert.issue_date, cert.valid_until, cert.purpose,
           cert.status,
           cert.physician_name, cert.license_number,
           'medical_certificates'
    FROM medical_certificates cert WHERE cert.patient_type = 'student';

-- ============================================================
-- V4. EMPLOYEE CERTIFICATES
--     Used by: Employee Portal → certificates.php
-- ============================================================
CREATE OR REPLACE VIEW vw_employee_certificates AS
    SELECT mc.clearance_id        AS cert_id,
           mc.employee_id         AS patient_id,
           'Medical Clearance'    AS certificate_type,
           mc.issue_date,
           mc.valid_until,
           mc.purpose_of_clearance AS purpose,
           mc.fit_status          AS status_detail,
           mc.physician_name,
           mc.license_number,
           'medical_clearances'   AS source_table
    FROM medical_clearances mc WHERE mc.patient_type = 'employee'
    UNION ALL
    SELECT cert.certificate_id, cert.employee_id,
           cert.certificate_type,
           cert.issue_date, cert.valid_until, cert.purpose,
           cert.status,
           cert.physician_name, cert.license_number,
           'medical_certificates'
    FROM medical_certificates cert WHERE cert.patient_type = 'employee';

-- ============================================================
-- V5. DASHBOARD STATS
--     Used by: Medical Portal → medical_dashboard.php
-- ============================================================
CREATE OR REPLACE VIEW vw_dashboard_stats AS
SELECT
    (SELECT COUNT(*) FROM appointments
     WHERE status IN ('Pending','Confirmed','Waiting'))           AS pending_appointments,
    (SELECT COUNT(*) FROM appointments
     WHERE MONTH(appointment_date) = MONTH(CURDATE())
       AND YEAR(appointment_date)  = YEAR(CURDATE()))             AS monthly_appointments,
    (SELECT COUNT(*) FROM appointments
     WHERE YEAR(appointment_date)  = YEAR(CURDATE()))             AS annual_appointments,
    (SELECT COUNT(*) FROM students)                               AS total_students,
    (SELECT COUNT(*) FROM employees WHERE is_active = 1)          AS total_employees;

-- ============================================================
-- V6. TODAY'S SCHEDULE
--     Used by: Medical Portal → medical_dashboard.php table
--     Shows student AND employee appointments for today.
-- ============================================================
CREATE OR REPLACE VIEW vw_todays_schedule AS
SELECT
    a.appointment_id,
    TIME_FORMAT(a.appointment_time, '%h:%i %p')   AS appt_time,
    CASE
        WHEN a.patient_type = 'student'
            THEN CONCAT(s.last_name, ', ', s.first_name)
        ELSE CONCAT(e.last_name, ', ', e.first_name)
    END                                            AS patient_name,
    CASE
        WHEN a.patient_type = 'student' THEN s.student_number
        ELSE e.employee_number
    END                                            AS id_number,
    a.patient_type,
    a.service_category                             AS purpose,
    a.status
FROM appointments a
LEFT JOIN students  s ON s.student_id  = a.student_id
LEFT JOIN employees e ON e.employee_id = a.employee_id
WHERE a.appointment_date = CURDATE()
  AND a.status NOT IN ('Cancelled','No Show')
ORDER BY a.appointment_time;

-- ============================================================
-- V7. ROSTER  (Student list)
--     Used by: Medical Portal → roster.php
-- ============================================================
CREATE OR REPLACE VIEW vw_roster AS
SELECT
    s.student_id,
    s.student_number                                      AS id_number,
    CONCAT(s.last_name, ', ', s.first_name,
           IFNULL(CONCAT(' ', LEFT(s.middle_name,1),'.'), '')) AS full_name,
    s.program,
    s.year_level,
    s.sex,
    s.college,
    (SELECT MAX(cr.consultation_date)
     FROM consultation_records cr
     WHERE cr.student_id = s.student_id)                  AS last_visit
FROM students s;

-- ============================================================
-- V8. PATIENT RECORDS  (two-tab layout in patient_records.php)
--     Used by: Medical Portal → patient_records.php
--     Tab filter in PHP: WHERE patient_type = 'student'
--                     or WHERE patient_type = 'employee'
-- ============================================================
CREATE OR REPLACE VIEW vw_patient_records AS
    -- Student: Consultations
    SELECT cr.record_id, cr.consultation_date AS record_date,
           CONCAT(s.last_name,', ',s.first_name) AS patient_name,
           s.student_number AS id_number, s.college AS dept_college,
           'Consultation' AS form_type,
           cr.diagnosis AS diag_purpose, cr.follow_up_date, 'student' AS patient_type
    FROM consultation_records cr
    JOIN students s ON s.student_id = cr.student_id
    WHERE cr.patient_type = 'student'
    UNION ALL
    -- Student: Health Exams
    SELECT he.exam_id, he.exam_date,
           CONCAT(s.last_name,', ',s.first_name),
           s.student_number, s.college,
           'Health Exam', he.working_impression, he.follow_up_date, 'student'
    FROM health_exam_records he
    JOIN students s ON s.student_id = he.student_id
    WHERE he.patient_type = 'student'
    UNION ALL
    -- Student: Clearances
    SELECT mc.clearance_id, mc.issue_date,
           CONCAT(s.last_name,', ',s.first_name),
           s.student_number, s.college,
           'Medical Clearance', mc.purpose_of_clearance, mc.valid_until, 'student'
    FROM medical_clearances mc
    JOIN students s ON s.student_id = mc.student_id
    WHERE mc.patient_type = 'student'
    UNION ALL
    -- Employee: Consultations
    SELECT cr.record_id, cr.consultation_date,
           CONCAT(e.last_name,', ',e.first_name),
           e.employee_number, e.department,
           'Consultation', cr.diagnosis, cr.follow_up_date, 'employee'
    FROM consultation_records cr
    JOIN employees e ON e.employee_id = cr.employee_id
    WHERE cr.patient_type = 'employee'
    UNION ALL
    -- Employee: Health Exams
    SELECT he.exam_id, he.exam_date,
           CONCAT(e.last_name,', ',e.first_name),
           e.employee_number, e.department,
           'Health Exam', he.working_impression, he.follow_up_date, 'employee'
    FROM health_exam_records he
    JOIN employees e ON e.employee_id = he.employee_id
    WHERE he.patient_type = 'employee'
    UNION ALL
    -- Employee: Clearances
    SELECT mc.clearance_id, mc.issue_date,
           CONCAT(e.last_name,', ',e.first_name),
           e.employee_number, e.department,
           'Medical Clearance', mc.purpose_of_clearance, mc.valid_until, 'employee'
    FROM medical_clearances mc
    JOIN employees e ON e.employee_id = mc.employee_id
    WHERE mc.patient_type = 'employee';


-- ============================================================
-- ============================================================
--  S A M P L E   D A T A
--  Mirrors all hard-coded demo data across every PHP file.
--  NOTE: Replace $2y$12$demo... hashes with real bcrypt hashes
--        before deploying to production.
-- ============================================================
-- ============================================================

-- ── Users ──────────────────────────────────────────────────
-- password_plain : human-readable reference (DEV ONLY)
-- password_hash  : bcrypt hash used by PHP password_verify()
INSERT INTO users (email, password_plain, password_hash, role) VALUES
  -- Medical staff
  ('maria.garcia@pup.edu.ph', 'Nurse@2025!', '$2y$12$rIslIhDuVeZ3dotnUAAihubYO8egak/EzHMUwBA9FDQZ4a/ssxW12', 'medical_staff'),
  -- Students  (default password: Student@2025!)
  ('juan.delacruz@iskolar.pup.edu.ph', 'Student@2025!', '$2y$12$RXGPwk7x/2nsfbBdgN5nUeevJ5LoZY0AJEjhCvqVjtmaJFmRQc9RK', 'student'),
  ('maria.santos@iskolar.pup.edu.ph', 'Student@2025!', '$2y$12$NsdGB7Y80zYtp7lgn5JZiOjL6vowNhNEt/WNdgiS0icsFcUXafnXm', 'student'),
  ('john.garcia@iskolar.pup.edu.ph', 'Student@2025!', '$2y$12$8HmONBttfdzt7.B8gqOmtuOpwwLZjbdwsrwhZVheTy.AHDK6RkR.e', 'student'),
  ('grace.cruz@iskolar.pup.edu.ph', 'Student@2025!', '$2y$12$Gfk6Mh5LbUkx.tjqJlj.7uG7vSODR5s4nt/uhlWvmZC2tstJRc6Li', 'student'),
  -- Employees (default password: Employee@2025!)
  ('roberto.cruz@pup.edu.ph', 'Employee@2025!', '$2y$12$V6Rp8b4WErj7cI6F2dPv0OCzPBF003RTIYhovnBRGoqm57DH6XHAW', 'employee'),
  ('grace.villanueva@pup.edu.ph', 'Employee@2025!', '$2y$12$Dei/94oXeiWBjOdGoWgOEODkoTyltdohBNqTVllON2puWYD4fhauS', 'employee'),
  ('edwin.santos@pup.edu.ph', 'Employee@2025!', '$2y$12$RgOJGsX5M5V4Mas0isv16.9S3joM.u3hjyBNxzUVUyVX.17vw40aG', 'employee'),
  -- Admin (default password: Admin@2025!)
  ('admin@aicare.pup.edu.ph', 'Admin@2025!', '$2y$12$OhVDC8bDoLQxw1J1WWb51elyGA4rmG.bSkNvxdE2ooVQw5eln4j2y', 'admin');

-- ── Medical Staff ───────────────────────────────────────────
-- user_id 9 = admin@aicare.pup.edu.ph (added after 8 existing users)
INSERT INTO medical_staff
  (user_id, full_name, position, license_number, department, contact_number, email)
VALUES
  (1, 'Maria Garcia, RN', 'Registered Nurse', 'RN-2019-00123',
   'AiCare Clinic', '+63 917 123 4567', 'maria.garcia@pup.edu.ph'),
  (9, 'System Administrator', 'Administrator', NULL,
   'AiCare Clinic', NULL, 'admin@aicare.pup.edu.ph');

-- ── Students ────────────────────────────────────────────────
INSERT INTO students
  (user_id, student_number, last_name, first_name,
   sex, program, year_level, college, contact_number, email)
VALUES
  (2, '2023-12345-MN-0', 'Dela Cruz', 'Juan',
   'Male',   'BSCS', '2nd Year', 'CCS', '09171111111',
   'juan.delacruz@iskolar.pup.edu.ph'),
  (3, '2023-56789-MN-0', 'Santos',   'Maria',
   'Female', 'BSIT', '1st Year', 'CCS', '09172222222',
   'maria.santos@iskolar.pup.edu.ph'),
  (4, '2023-76543-MN-0', 'Garcia',   'John',
   'Male',   'BSA',  '3rd Year', 'CAF', '09173333333',
   'john.garcia@iskolar.pup.edu.ph'),
  (5, '2023-11123-MN-0', 'Cruz',     'Grace',
   'Female', 'BSED', '2nd Year', 'COE', '09174444444',
   'grace.cruz@iskolar.pup.edu.ph');

-- ── Employees ───────────────────────────────────────────────
INSERT INTO employees
  (user_id, employee_number, last_name, first_name, sex,
   department, position_designation, employment_type,
   email, contact_number)
VALUES
  (6, 'EMP-00123', 'Cruz',       'Roberto', 'Male',
   'CCS',        'Associate Professor I',    'Permanent',
   'roberto.cruz@pup.edu.ph',     '09181111111'),
  (7, 'EMP-00456', 'Villanueva', 'Grace',   'Female',
   'HR Office',  'Human Resource Officer I', 'Permanent',
   'grace.villanueva@pup.edu.ph', '09182222222'),
  (8, 'EMP-00789', 'Santos',     'Edwin',   'Male',
   'Maintenance','General Services Aide',    'Permanent',
   'edwin.santos@pup.edu.ph',     '09183333333');

-- ── Appointments: today's schedule (students) ───────────────
INSERT INTO appointments
  (student_id, employee_id, staff_id, patient_type,
   service_category, appointment_date, appointment_time,
   reason_for_visit, status, consent_given)
VALUES
  (1, NULL, 1, 'student', 'Medical Clearance',
   CURDATE(), '09:00:00',
   'Enrollment clearance requirement.',        'Confirmed', 1),
  (2, NULL, 1, 'student', 'General Consultation',
   CURDATE(), '10:00:00',
   'Persistent headache and mild fever.',      'Waiting',   1),
  (3, NULL, 1, 'student', 'General Consultation',
   CURDATE(), '10:30:00',
   'Stomach ache since yesterday.',            'Waiting',   1),
  (4, NULL, 1, 'student', 'General Consultation',
   CURDATE(), '11:30:00',
   'Cough and colds, difficulty breathing.',   'Waiting',   1);

-- ── Student consultation records ────────────────────────────
INSERT INTO consultation_records
  (student_id, employee_id, staff_id, patient_type,
   consultation_date, consultation_time, consultation_type,
   college_department, blood_pressure, temperature_c,
   heart_rate_bpm, respiratory_rate, height_cm, weight_kg, bmi,
   chief_complaint, pe_findings, diagnosis,
   treatment, medications_given, follow_up_date, additional_notes)
VALUES
  (1, NULL, 1, 'student',
   '2025-04-22', '10:15:00', 'Walk-in', 'CCS',
   '110/70', 37.2, 82, 18, 170.0, 62.0, 21.5,
   'Mild headache and fatigue',
   'Alert, no visible distress; pupils equal and reactive',
   'Tension Headache',
   'Rest, oral hydration, analgesic as needed',
   'Paracetamol 500mg – 1 tab q6h PRN', NULL,
   'Advised to reduce screen time and get adequate sleep'),

  (1, NULL, 1, 'student',
   '2025-02-10', '09:00:00', 'Walk-in', 'CCS',
   '112/72', 36.8, 78, 17, 170.0, 61.5, 21.3,
   'Cough and colds for 3 days',
   'Throat slightly erythematous; no tonsillar enlargement',
   'Acute Upper Respiratory Tract Infection (URTI)',
   'Symptomatic management; adequate rest and fluids',
   'Paracetamol 500mg PRN; Cetirizine 10mg OD', '2025-02-17',
   'Return if symptoms worsen or persist beyond 1 week');

-- ── Employee consultation records ───────────────────────────
INSERT INTO consultation_records
  (student_id, employee_id, staff_id, patient_type,
   consultation_date, consultation_time, consultation_type,
   college_department, blood_pressure, temperature_c,
   heart_rate_bpm, respiratory_rate, height_cm, weight_kg,
   chief_complaint, pe_findings, diagnosis,
   treatment, medications_given, follow_up_date, additional_notes)
VALUES
  (NULL, 2, 1, 'employee',
   '2025-04-10', '10:00:00', 'Follow-up', 'HR Office',
   '140/90', 36.6, 78, 17, 160.0, 65.0,
   'Elevated BP, mild headache',
   'BP elevated on repeat; no focal neurologic deficits',
   'Hypertension — monitoring',
   'Continue antihypertensive; advise low-salt diet and regular exercise',
   'Amlodipine 5mg OD', '2025-04-24',
   'Monitor BP daily; refer to internist if uncontrolled');

-- ── Employee health exam ─────────────────────────────────────
INSERT INTO health_exam_records
  (student_id, employee_id, staff_id, patient_type, exam_date,
   blood_pressure, temperature_c, heart_rate_bpm, respiratory_rate,
   height_cm, weight_kg, bmi, general_condition,
   allergies, current_medications, physical_findings,
   working_impression, fit_status, follow_up_date, remarks)
VALUES
  (NULL, 1, 1, 'employee', '2025-05-18',
   '130/85', 36.7, 74, 16, 172.0, 78.0, 26.4,
   'Ambulatory, conscious, coherent',
   'None known', 'None',
   'Cardiovascular: regular rate and rhythm; Lungs: clear to auscultation',
   'Fit for work; mild hypertensive tendency noted',
   'Fit with Conditions', '2025-11-18',
   'Annual PE completed; referred to cardiology for monitoring');

-- ── Employee clearance ───────────────────────────────────────
INSERT INTO medical_clearances
  (student_id, employee_id, staff_id, patient_type,
   issue_date, valid_until, purpose_of_clearance,
   fit_status, physician_name, license_number, remarks)
VALUES
  (NULL, 3, 1, 'employee',
   '2025-03-05', '2025-09-05', 'Return to work after sick leave',
   'Physically Fit', 'FELICITAS A. BERMUDEZ, M.D.', '0115224',
   'Fully recovered from URTI; cleared for full duties');

-- ── Student clearance ────────────────────────────────────────
INSERT INTO medical_clearances
  (student_id, employee_id, staff_id, patient_type,
   issue_date, valid_until, purpose_of_clearance,
   fit_status, school_year, physician_name, license_number, remarks)
VALUES
  (1, NULL, 1, 'student',
   '2025-01-10', '2025-12-31', 'Enrollment',
   'Physically Fit', '2024-2025',
   'FELICITAS A. BERMUDEZ, M.D.', '0115224',
   'No contraindication to full academic participation');

-- ── Student medical certificate ──────────────────────────────
INSERT INTO medical_certificates
  (student_id, employee_id, staff_id, patient_type,
   certificate_type, issue_date, valid_until,
   purpose, diagnosis, days_of_rest,
   physician_name, license_number, status)
VALUES
  (1, NULL, 1, 'student',
   'Sick Leave', '2025-02-10', '2025-02-17',
   'Excuse from classes due to illness',
   'Acute Upper Respiratory Tract Infection (URTI)', 5,
   'FELICITAS A. BERMUDEZ, M.D.', '0115224', 'Active');

-- ── Consent records ──────────────────────────────────────────
INSERT INTO consent_records
  (student_id, employee_id, staff_id, patient_type,
   date_signed, consent_given, purpose_notes)
VALUES
  (1, NULL, 1, 'student', '2025-01-10', 1,
   'Consent collected during enrollment medical clearance'),
  (2, NULL, 1, 'student', CURDATE(), 1,
   'Consent collected at appointment booking'),
  (3, NULL, 1, 'student', CURDATE(), 1,
   'Consent collected at appointment booking'),
  (4, NULL, 1, 'student', CURDATE(), 1,
   'Consent collected at appointment booking'),
  (NULL, 2, 1, 'employee', '2025-04-10', 1,
   'Consent collected at follow-up consultation');

-- ============================================================
-- END OF SCHEMA
-- ============================================================