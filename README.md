## ToDo App

### Description
The ToDo app is a task management application that provides a REST API backend built with PHP/Laravel. Users can register, login, manage their tasks, and more through the API endpoints. Additionally, the app integrates with a JSON Faker API to fetch and manage fake ToDo items.

### Installation
1. Clone the repository: `git clone <repository-url>`
2. Navigate to the project directory: `cd todo-app`
3. Install dependencies: `composer install`
4. Configure environment variables:
    - Rename `.env.example` to `.env` 
    - Update the `.env` file with your database and mail configuration
5. Generate an application key: `php artisan key:generate`
6. Run migrations: `php artisan migrate`
7. Start the development server: `php artisan serve`

Certainly, here's a more professional version of the note you want to add to your README:

---

## Important Note: Email Verification Required

Before you can log in to the application, you must verify your email address. To facilitate this process, SMTP setup is required. For easy testing, a sample `.env` file is provided, but you must configure the MySQL database accordingly.

### Instructions:
1. **SMTP Setup**: Configure your SMTP settings to enable email verification.
2. **Database Configuration**: Adjust the MySQL database settings in the `.env` file to match your environment.

### Sample .env Configuration:
```plaintext
# Sample .env configuration for email verification and MySQL database setup
APP_NAME=YourAppName
...
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=your-smtp-port
MAIL_USERNAME=your-smtp-username
MAIL_PASSWORD=your-smtp-password
MAIL_ENCRYPTION=your-smtp-encryption
MAIL_FROM_ADDRESS=your-email-address
MAIL_FROM_NAME="${APP_NAME}"
...
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=your-db-port
DB_DATABASE=your-db-name
DB_USERNAME=your-db-username
DB_PASSWORD=your-db-password
...
```

### Note:
For the sake of easy testing, a working SMTP config in `.env` file is included. However, ensure that you configure the MySQL database according to your environment before proceeding with testing or deployment.

---

### API Endpoints
The following API endpoints are available:
Sure, here's a breakdown of all the endpoints and what they do:

### Authentication Endpoints

#### User Authentication
- `POST /api/register`: Registers a new user. `(ok)`
- `POST /api/login`: Logs in a user. `(ok)`
- `POST /api/logout`: Logs out the authenticated user. `(ok)`
- `POST /api/logout-all-device`: Logs out the authenticated user from all devices. `(ok)`

#### Email Verification
- `GET /api/verify-email/{token}/{email}`: Verifies email using token and email. `(ok)`

#### Password Reset
- `POST /api/password/email`: Requests password reset email. `(ok)`
- `POST /api/password/reset`: Resets user password. `(ok)`

### User Profile Endpoints
- `GET /api/user`: Retrieves user profile information. `(ok)`
- `POST /api/user/change-password`: Changes user password. `(ok)`


### ToDo Management Endpoints

#### Fake ToDo Items
- `GET /api/faker/todos`: Fetches all fake ToDo items. `(ok)`
- `GET /api/faker/todos/{id}`: Fetches a single fake ToDo item by ID. `(ok)`
- `PUT /api/faker/todos/{id}`: Updates a fake ToDo item by ID. `(ok)`
- `DELETE /api/faker/todos/{id}`: Deletes a fake ToDo item by ID. `(ok)`

#### User-Specific ToDo Items
- `POST /api/user/todos`: Fetches all user-specific ToDo items. `(ok)`
- `GET /api/user/todos`: Fetches all user-specific ToDo items. `(ok)`
- `GET /api/user/todos/{id}`: Fetches a single user-specific ToDo item by ID. `(ok)`
- `PUT /api/user/todos/{id}`: Updates a user-specific ToDo item by ID. `(ok)`
- `DELETE /api/user/todos/{id}`: Deletes a user-specific ToDo item by ID. `(ok)`


### API Endpoints Detail

#### Authentication

- `POST /api/register`
    - Request Data Type: JSON
      ```json
      {
        "name": "string",
        "email": "string",
        "password": "string",
        "password_confirmation": "string"
      }
      ```
    - Response Type: JSON
      ```json
      {
        "user": {
          "id": "integer",
          "name": "string",
          "uuid": "string",
          "email": "string",
          "created_at": "string",
          "updated_at": "string"
        },
        "access_token": "string"
      }
      ```

- `POST /api/login`
    - Request Data Type: JSON
      ```json
      {
        "email": "string",
        "password": "string"
      }
      ```
    - Response Type: JSON
      ```json
      {
        "user": {
          "id": "integer",
          "name": "string",
          "uuid": "string",
          "email": "string",
          "created_at": "string",
          "updated_at": "string"
        },
        "access_token": "string"
      }
      ```
      
#### User Profile
- `GET /api/user`
    - Response Type: JSON
      ```json
      {
          "id": "integer",
          "name": "string",
          "uuid": "string",
          "email": "string",
          "created_at": "string",
          "updated_at": "string"
        }
      ```

- `POST /api/user/change-password`
    - Request Data Type: JSON
      ```json
      {
        "current_password": "string",
        "password": "string",
        "password_confirmation": "string"
      }
      ```
    - Response Type: JSON
      ```json
      {
        "message": "string",
        "code": "integer"
      }
      ```

#### Email Verification
- `GET /api/verify-email/{token}/{email}`
    - Response Type: JSON
      ```json
      {
        "message": "string"
      }
      ```


### Password Reset

#### Request Password Reset Email
- `POST /api/password/email`
    - Request Data Type: JSON
      ```json
      {
        "email": "string"
      }
      ```
    - Response Type: JSON
      ```json
      {
        "message": "string"
      }
      ```

#### Reset User Password
- `POST /api/password/reset`
    - Request Data Type: JSON
      ```json
      {
        "token": "string",
        "email": "string",
        "password": "string",
        "password_confirmation": "string"
      }
      ```
    - Response Type: JSON
      ```json
      {
        "message": "string"
      }
      ```


#### ToDo Management (Faker request)

- `POST /api/faker/todos`
    - Request Data Type: JSON
      ```json
      {
       "title": "string",
       "completed": "boolean"
      }
      ```
    - Response Type: JSON Array of User-Specific ToDo objects
      ```json
      [
        {
          "id": "integer",
          "user_id": "integer",
          "title": "string",
          "completed": "boolean"
        }
      ]
      ```
      
- `GET /api/faker/todos`
    - Response Type: JSON Array of ToDo objects
      ```json
      [
        {
          "userId": "integer",
          "id": "integer",
          "title": "string",
          "completed": "boolean"
        }
      ]
      ```

- `GET /api/faker/todos/{id}`
    - Response Type: JSON
      ```json
      {
        "userId": "integer",
        "id": "integer",
        "title": "string",
        "completed": "boolean"
      }
      ```

- `PUT /api/faker/todos/{id}`
    - Request Data Type: JSON
      ```json
      {
        "title": "string",
        "completed": "boolean"
      }
      ```
    - Response Type: JSON
      ```json
      {
        "userId": "integer",
        "id": "integer",
        "title": "string",
        "completed": "boolean"
      }
      ```

- `DELETE /api/faker/todos/{id}`
    - Response Type: JSON
      ```json
      {
        "message": "string"
      }
      ```

#### ToDo Management (_Model request_)

- `POST /api/user/todos`
    - Request Data Type: JSON
      ```json
      {
       "title": "string",
       "completed": "boolean"
      }
      ```
    - Response Type: JSON Array of User-Specific ToDo objects
      ```json
      [
        {
          "id": "integer",
          "user_id": "integer",
          "title": "string",
          "completed": "boolean",
          "created_at": "string",
          "updated_at": "string"
        }
      ]
      ```

- `GET /api/user/todos`
    - Response Type: JSON Array of ToDo objects
      ```json
      [
        {
          "id": "integer",
          "user_id": "integer",
          "title": "string",
          "completed": "boolean",
          "created_at": "string",
          "updated_at": "string"
        }
      ]
      ```

- `GET /api/user/todos/{id}`
    - Response Type: JSON
      ```json
      {
        "id": "integer",
        "user_id": "integer",
        "title": "string",
        "completed": "boolean",
        "created_at": "string",
        "updated_at": "string"
      }
      ```

- `PUT /api/user/todos/{id}`
    - Request Data Type: JSON
      ```json
      {
        "title": "string",
        "completed": "boolean"
      }
      ```
    - Response Type: JSON
      ```json
      {
        "id": "integer",
        "user_id": "integer",
        "title": "string",
        "completed": "boolean",
        "created_at": "string",
        "updated_at": "string"
      }
      ```

- `DELETE /api/user/todos/{id}`
    - Response Type: JSON
      ```json
      {
        "message": "string"
      }
      ```


These endpoints allow users to fetch all fake ToDo items and all user-specific ToDo items respectively. The response for both endpoints is a JSON array containing the respective ToDo objects.





These data types and response types should help clarify the structure of the API endpoints and the expected data.

_These endpoints cover user authentication, email verification, password reset, user profile management, and ToDo 
management functionalities. 
Each endpoint serves a specific purpose in managing user accounts and tasks within the application.******~~_


### Usage
- **Authentication**: Use the authentication endpoints to register, login, reset password, and manage user sessions.
- **User Profile**: Access the user profile endpoints to retrieve and update user information.
- **Email Verification**: Verify user email addresses using the email verification endpoints.
- **ToDo Management**: Perform CRUD operations on ToDo items, including both fake ToDo items and user-specific ToDo items.

### Environment Variables
Ensure to configure the following environment variables in your `.env` file:

```plaintext
# Example .env configuration
APP_NAME=ToDo
APP_ENV=local
...
```

Refer to the provided `.env.example` file for a complete list of environment variables and their descriptions.

### Testing
Unit tests and PHP tests are provided to ensure the functionality and robustness of the API endpoints. Run tests using the following command:

```bash
php artisan test
```

### Additional Information
For more details on how to use the application, interact with the API endpoints, and configure environment variables, please refer to the documentation provided in the codebase. This includes detailed explanations of each endpoint, usage examples, and instructions for configuring the environment.
