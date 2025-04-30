# GP Academy Database Design Documentation

## Overview
GP Academy's database is designed to support a comprehensive e-learning platform with features including user management, course management, quiz systems, and certification functionalities.

## Core Tables

### Users
```sql
CREATE TABLE users (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    gp_id CHAR(255) NULL,
    first_name CHAR(255),
    last_name CHAR(255) NULL,
    email CHAR(150) UNIQUE,
    phone CHAR(50) UNIQUE NULL,
    password CHAR(60) NULL,
    gender TINYINT NULL COMMENT '1=Male, 2=Female, 3=Others',
    fathers_name CHAR(255) NULL,
    mothers_name CHAR(255) NULL,
    blood_group TINYINT NULL COMMENT '1=A+, 2=A-, 3=B+, 4=B-, 5=O+, 6=O-, 7=AB+, 8=AB-',
    dob DATE NULL,
    religion CHAR(50) NULL,
    images JSON NULL,
    address CHAR(255) NULL,
    nationality CHAR(255) NULL,
    academic_status TINYINT NULL COMMENT '1=Graduated, 2=Post Graduated, 3=1-4th year university student, 4=Others',
    institute_id INT NULL,
    institute_name CHAR(255) NULL,
    designation TINYINT NULL COMMENT '1=Student, 2=Service Holder, 3=Self Employed, 4=Others',
    about_yourself LONGTEXT NULL,
    biography LONGTEXT NULL,
    last_login TIMESTAMP NULL,
    settings JSON NULL,
    last_otp CHAR(60) NULL,
    otp_created_at TIMESTAMP NULL,
    is_verified TINYINT DEFAULT 0 COMMENT '0=No, 1=Yes',
    verified_by TINYINT NULL COMMENT '1=email, 2=phone, 3=google',
    status TINYINT DEFAULT 1 COMMENT '0=Inactive, 1=Active, 2=Profile pending',
    total_enrollments INT DEFAULT 0,
    total_course_completions INT DEFAULT 0,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL
)
```

### Courses
```sql
CREATE TABLE courses (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    instructor_id INT,
    category_id INT,
    certificate_template_id INT,
    title CHAR(255),
    slug CHAR(255) UNIQUE,
    type TINYINT DEFAULT 1 COMMENT '1=regular, 2=masterclass',
    payment_type TINYINT DEFAULT 1 COMMENT '1=Free, 2=Paid, 3=Premium, 4=Request',
    short_description TEXT NULL,
    full_description TEXT NULL,
    duration INT DEFAULT 0 COMMENT 'in seconds',
    outcomes JSON NULL,
    requirements JSON NULL,
    live_class JSON NULL,
    faq JSON NULL,
    language TINYINT DEFAULT 1 COMMENT '1=English, 2=Bangla',
    price FLOAT NULL,
    discount FLOAT NULL,
    level TINYINT DEFAULT 1 COMMENT '1=Beginner, 2=Intermediate, 3=Advanced',
    pass_marks INT,
    is_certification_final_exam_required BOOLEAN DEFAULT 0,
    media_info JSON NULL,
    others JSON NULL,
    is_top BOOLEAN DEFAULT 0,
    total_lessons INT DEFAULT 0,
    total_enrollments INT DEFAULT 0,
    total_course_completions INT DEFAULT 0,
    status TINYINT DEFAULT 1 COMMENT '1=Active, 0=Inactive',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL
)
```

### Categories
```sql
CREATE TABLE categories (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name CHAR(255),
    slug CHAR(255) UNIQUE NULL,
    description TEXT NULL,
    parent INT DEFAULT 0 COMMENT 'category_id',
    image CHAR(255) NULL,
    order INT DEFAULT 0,
    is_top BOOLEAN DEFAULT 1,
    is_highlighted BOOLEAN DEFAULT 0,
    others JSON NULL,
    status TINYINT DEFAULT 1 COMMENT '1=Active, 0=Inactive',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL
)
```

### Sections
```sql
CREATE TABLE sections (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    title CHAR(255),
    course_id INT,
    trainer_id INT NULL,
    order INT DEFAULT 0,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL
)
```

### Lessons
```sql
CREATE TABLE lessons (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    title CHAR(255),
    section_id INT,
    course_id INT,
    contentable_type TINYINT DEFAULT 1 COMMENT '1=Lesson, 2=Quiz, 3=Resource',
    contentable_id INT NULL,
    duration INT DEFAULT 0,
    media_info JSON NULL,
    order INT DEFAULT 0,
    summary TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
)
```

### Enrollments
```sql
CREATE TABLE enrolls (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT,
    course_id INT,
    type TINYINT DEFAULT 1 COMMENT '1=regular, 2=bulk',
    lesson_progress JSON,
    is_passed BOOLEAN DEFAULT 0,
    total_marks DECIMAL(5,2) DEFAULT 0.00 COMMENT 'Max value is 100.00',
    status INT DEFAULT 1 COMMENT '1=active, 0=inactive',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    UNIQUE KEY unique_enrollment (course_id, user_id)
)
```

## Quiz System Tables

### Questions
```sql
CREATE TABLE questions (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    title CHAR(255),
    category_id INT,
    type TINYINT COMMENT '1=text, 2=single, 3=multiple',
    options JSON NULL,
    answers CHAR(255),
    feedbacks JSON NULL,
    time_limit INT NULL,
    status TINYINT DEFAULT 1 COMMENT '1=Active, 0=Inactive',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL
)
```

### Quizzes
```sql
CREATE TABLE quizzes (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    title CHAR(255),
    course_id INT,
    category_id INT,
    type TINYINT COMMENT '1=random, 2=manual',
    exam_type TINYINT DEFAULT 1 COMMENT '1=quiz, 2=mock, 3=final',
    question_ids JSON NULL,
    total_question INT NULL,
    each_qmark DECIMAL(10,2) NULL,
    pass_marks_percentage INT NULL,
    quiz_time INT DEFAULT 0,
    attempt_time INT DEFAULT 0,
    penalty_time INT DEFAULT 0,
    instructions TEXT NULL,
    status TINYINT DEFAULT 1 COMMENT '1=active, 0=inactive',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL
)
```

## Certificate System Tables

### Certificates
```sql
CREATE TABLE certificates (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    uuid CHAR(36) UNIQUE,
    course_id INT,
    user_id BIGINT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    UNIQUE KEY unique_certificate (course_id, user_id)
)
```

### Certificate Templates
```sql
CREATE TABLE certificate_templates (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    title CHAR(255),
    certificate_layout_id INT,
    settings JSON,
    status TINYINT DEFAULT 1 COMMENT '0=Inactive, 1=Active',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL
)
```

### Certificate Layouts
```sql
CREATE TABLE certificate_layouts (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    title CHAR(255),
    path CHAR(255),
    height INT DEFAULT 0,
    width INT DEFAULT 0,
    status TINYINT DEFAULT 1 COMMENT '0=Inactive, 1=Active',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL
)
```

## Content Management Tables

### Events
```sql
CREATE TABLE events (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    uuid CHAR(36) UNIQUE,
    title CHAR(255),
    slug CHAR(255) UNIQUE,
    description TEXT,
    banner CHAR(255),
    gallery_id INT NULL,
    button_title CHAR(255) NULL,
    button_url CHAR(255) NULL,
    date TIMESTAMP,
    is_highlighted TINYINT DEFAULT 0,
    status TINYINT DEFAULT 1 COMMENT '0=Inactive, 1=Active',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL
)
```

### News
```sql
CREATE TABLE news (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    title CHAR(255),
    slug CHAR(255) UNIQUE,
    description TEXT,
    image CHAR(255),
    is_highlighted TINYINT DEFAULT 0,
    status TINYINT DEFAULT 1 COMMENT '0=Inactive, 1=Active',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL
)
```

### Web Pages
```sql
CREATE TABLE web_pages (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    title CHAR(255),
    slug CHAR(255),
    status INT DEFAULT 1 COMMENT '1=active, 0=inactive',
    lang INT DEFAULT 1 COMMENT '1=en, 2=bn',
    components JSON NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    UNIQUE KEY slug_lang_unique (slug, lang)
)
```

## Other Supporting Tables

### Institutes
```sql
CREATE TABLE institutes (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    division_id INT,
    name CHAR(255),
    code CHAR(255) NULL,
    address CHAR(255) NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
)
```

### Settings
```sql
CREATE TABLE settings (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    website_settings JSON NULL,
    system_settings JSON NULL,
    media JSON NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
)
```

### Dashboard Reports
```sql
CREATE TABLE dashboard_reports (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    total_enrollments INT,
    total_completions INT,
    total_students INT,
    gender TINYINT DEFAULT 1 COMMENT '1=Male, 2=Female, 3=Others',
    date DATE,
    UNIQUE KEY date_gender_unique (date, gender)
)
```

## Relationships

1. Users
   - Has many Enrollments
   - Has many Certificates
   - Belongs to Institute

2. Courses
   - Belongs to Category
   - Belongs to Instructor
   - Has many Sections
   - Has many Lessons (through Sections)
   - Has many Enrollments
   - Has one Certificate Template
   - Has many Quizzes

3. Categories
   - Has many Courses
   - Has many sub-categories (self-referential)

4. Sections
   - Belongs to Course
   - Has many Lessons
   - Belongs to Trainer (optional)

5. Lessons
   - Belongs to Section
   - Belongs to Course
   - Polymorphic relation with Quiz/Resource

6. Quizzes
   - Belongs to Course
   - Belongs to Category
   - Has many Questions (through question_ids)

7. Certificates
   - Belongs to User
   - Belongs to Course

## Key Features

1. **Soft Deletes**: Most tables implement soft delete functionality (deleted_at column)
2. **Timestamps**: All tables maintain created_at and updated_at timestamps
3. **JSON Storage**: Flexible schema using JSON columns for dynamic data
4. **Unique Constraints**: Implemented on critical columns to maintain data integrity
5. **Status Management**: Most tables include status columns for active/inactive states

## Indexing Strategy

1. **Primary Keys**: All tables use auto-incrementing bigint IDs
2. **Foreign Keys**: Indexing on relationship columns (user_id, course_id, etc.)
3. **Unique Indexes**: On slug fields and composite keys
4. **Polymorphic Indexes**: On contentable_type and contentable_id

## Data Types Standardization

1. **Text Fields**:
   - CHAR(255): For fixed-length strings
   - TEXT: For variable-length content
   - LONGTEXT: For very large content

2. **Numeric Fields**:
   - BIGINT: For IDs and large numbers
   - INT: For regular numeric values
   - TINYINT: For small range numbers and boolean flags
   - DECIMAL: For precise decimal values (marks, prices)

3. **JSON Fields**: For flexible, schema-less data storage
4. **Timestamp Fields**: For all datetime values
5. **Boolean Fields**: Using TINYINT(1)

## Notes

1. The database uses UTF8MB4 character set for full Unicode support
2. All timestamp fields allow NULL values unless specified
3. The schema supports multi-language content (especially in web_pages)
4. Implements OAuth2 authentication system with necessary tables
5. Includes comprehensive logging and monitoring capabilities