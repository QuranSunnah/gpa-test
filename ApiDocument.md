# GP Academy API Documentation

## Base URL
`/api`

## Authentication
The API uses OAuth2 with Passport for authentication. Protected routes require a Bearer token in the Authorization header.

### Authentication Endpoints

#### Register User
- **URL**: `POST /v1/auth/register`
- **Description**: Register a new user
- **Request Body**:
  ```json
  {
    "full_name": "string",
    "email": "string",
    "phone": "string (format: +8801XXXXXXXXX or 01XXXXXXXXX)",
    "password": "string (min: 6 chars)",
    "password_confirmation": "string",
    "gender": "integer (1-3)",
    "designation": "integer",
    "institute_id": "integer (required for students)"
  }
  ```
- **Response**: Registration info with OTP sent confirmation

#### Complete Registration
- **URL**: `POST /v1/auth/register/complete`
- **Description**: Complete user registration with OTP verification
- **Request Body**:
  ```json
  {
    "identity_type": "string (email|phone)",
    "identity": "string",
    "otp": "string (4 digits)"
  }
  ```
- **Response**: Registration completion status with auth token

#### Login
- **URL**: `POST /v1/auth/login`
- **Description**: User login with credentials
- **Request Body**:
  ```json
  {
    "email": "string",
    "password": "string",
    "provider": "string (optional: google|general)",
    "platform": "string (optional: web|ios|android)"
  }
  ```
- **Response**: User info with auth token

#### Logout
- **URL**: `POST /v1/auth/logout`
- **Description**: Logout user and invalidate token
- **Authentication**: Required
- **Response**: Logout confirmation message

#### Reset Password
- **URL**: `POST /v1/auth/password/reset`
- **Description**: Reset user password with OTP verification
- **Request Body**:
  ```json
  {
    "email": "string",
    "otp": "string (4 digits)",
    "password": "string (min: 8 chars)",
    "password_confirmation": "string"
  }
  ```

#### Send OTP
- **URL**: `POST /v1/otp/send`
- **Description**: Send OTP for verification
- **Request Body**:
  ```json
  {
    "email": "string"
  }
  ```

### Protected Endpoints

#### Student Profile
- **URL**: `GET /v1/students/me`
- **Description**: Get authenticated student's profile
- **Authentication**: Required
- **Response**: Student profile information

#### Update Profile
- **URL**: `PATCH /v1/students/me`
- **Description**: Update student profile
- **Authentication**: Required

#### Change Profile Photo
- **URL**: `POST /v1/students/me/change-photo`
- **Description**: Update student profile photo
- **Authentication**: Required

#### Change Password
- **URL**: `PATCH /v1/students/me/change-password`
- **Description**: Change user password
- **Authentication**: Required
- **Request Body**:
  ```json
  {
    "current": "string",
    "password": "string (min: 8 chars)",
    "password_confirmation": "string"
  }
  ```

### Course Management

#### List Courses
- **URL**: `GET /v1/courses`
- **Description**: Get list of available courses

#### Get Course Details
- **URL**: `GET /v1/courses/{slug}`
- **Description**: Get detailed information about a specific course

#### Get My Courses
- **URL**: `GET /v1/students/me/courses`
- **Description**: Get courses enrolled by authenticated student
- **Authentication**: Required

#### Enroll in Course
- **URL**: `POST /v1/courses/{slug}/enroll`
- **Description**: Enroll in a specific course
- **Authentication**: Required

#### Get Course Progress
- **URL**: `GET /v1/courses/{slug}/lesson_progress`
- **Description**: Get progress for a specific course
- **Authentication**: Required

#### Update Course Progress
- **URL**: `PATCH /v1/courses/{slug}/lesson_progress`
- **Description**: Update progress for a specific course
- **Authentication**: Required

### Lesson Management

#### Get Lesson Quiz
- **URL**: `GET /v1/lessons/{lessonId}/quiz`
- **Description**: Get quiz for a specific lesson
- **Authentication**: Required

#### Get Lesson Content
- **URL**: `GET /v1/lessons/{lessonId}/content`
- **Description**: Get content for a specific lesson
- **Authentication**: Required

#### Get Lesson Resource
- **URL**: `GET /v1/lessons/{lessonId}/resource`
- **Description**: Get resources for a specific lesson
- **Authentication**: Required

### Other Endpoints

#### Categories
- `GET /v1/category/list` - Get all categories
- `GET /v1/top-categories/list` - Get top categories
- `GET /v1/top-categories/report` - Get category reports
- `GET /v1/top-categories/courses` - Get courses in top categories

#### Content
- `GET /v1/sliders/{id}` - Get slider content
- `GET /v1/partners` - Get partners list
- `GET /v1/mentors` - Get mentors list
- `GET /v1/testimonials` - Get testimonials
- `GET /v1/news` - Get news list
- `GET /v1/news/{slug}` - Get specific news
- `GET /v1/events` - Get events list
- `GET /v1/events/{slug}` - Get specific event
- `GET /v1/galleries` - Get galleries
- `GET /v1/galleries/{slug}` - Get specific gallery
- `GET /v1/web-pages/{slug}` - Get web page content
- `GET /v1/settings` - Get application settings

#### Contact
- `POST /v1/contact-us` - Submit contact form

## Response Format
All API responses follow a standard format:
```json
{
  "data": {},
  "message": "string",
  "status": "integer"
}
```

## Error Handling
The API returns appropriate HTTP status codes:
- 200: Success
- 201: Created
- 400: Bad Request
- 401: Unauthorized
- 403: Forbidden
- 404: Not Found
- 422: Validation Error
- 500: Server Error

## Notes
- OTP validity duration and resend intervals are configured in the application
- All protected routes require valid Bearer token authentication
- File uploads should use multipart/form-data format
- Responses may be paginated for list endpoints