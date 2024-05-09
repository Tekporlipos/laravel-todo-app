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

### API Endpoints
The following API endpoints are available:
Sure, here's a breakdown of all the endpoints and what they do:

### Authentication Endpoints

#### User Authentication
- `POST /api/register`: Registers a new user.
- `POST /api/login`: Logs in a user.
- `POST /api/logout`: Logs out the authenticated user.
- `POST /api/logout-all-device`: Logs out the authenticated user from all devices.

#### Email Verification
- `POST /api/verify-email`: Sends email verification notification.
- `GET /api/verify-email/{token}/{email}`: Verifies email using token and email.

#### Password Reset
- `POST /api/password/email`: Requests password reset email.
- `POST /api/password/reset`: Resets user password.

### User Profile Endpoints
- `GET /api/user`: Retrieves user profile information.
- `POST /api/user/change-password`: Changes user password.

### ToDo Management Endpoints

#### Fake ToDo Items
- `GET /api/faker/todos`: Fetches all fake ToDo items.
- `GET /api/faker/todos/{id}`: Fetches a single fake ToDo item by ID.
- `PUT /api/faker/todos/{id}`: Updates a fake ToDo item by ID.
- `DELETE /api/faker/todos/{id}`: Deletes a fake ToDo item by ID.

#### User-Specific ToDo Items
- `GET /api/user/todos`: Fetches all user-specific ToDo items.
- `GET /api/user/todos/{id}`: Fetches a single user-specific ToDo item by ID.
- `PUT /api/user/todos/{id}`: Updates a user-specific ToDo item by ID.
- `DELETE /api/user/todos/{id}`: Deletes a user-specific ToDo item by ID.


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
- `POST /api/verify-email`
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

- `GET /api/verify-email/{token}/{email}`
    - Response Type: JSON
      ```json
      {
        "message": "string"
      }
      ```

#### ToDo Management (Faker request)
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
