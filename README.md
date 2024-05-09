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

#### Authentication
- `POST /api/login`: User login
- `POST /api/register`: User registration
- `POST /api/password/email`: Request password reset
- `POST /api/password/reset`: Reset password
- `POST /api/logout`: Logout user
- `POST /api/logout-all-device`: Logout user from all devices

#### User Profile
- `GET /api/user`: Retrieve user profile
- `POST /api/user/change-password`: Change user password

#### Email Verification
- `POST /api/verify-email`: Send email verification notification
- `GET /api/verify-email/{token}/{email}`: Verify email

#### ToDo Management
_Faker request_
- `GET /api/faker/todos`: Fetch all fake ToDo items
- `GET /api/faker/todos/{id}`: Fetch a single fake ToDo item by ID
- `PUT /api/faker/todos/{id}`: Update a fake ToDo item
- `DELETE /api/faker/todos/{id}`: Delete a fake ToDo item


_Model request_
- `GET /api/user/todos`: Fetch all user ToDo items
- `GET /api/user/todos/{id}`: Fetch a single user ToDo item by ID
- `PUT /api/user/todos/{id}`: Update a user ToDo item
- `DELETE /api/user/todos/{id}`: Delete a user ToDo item

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
