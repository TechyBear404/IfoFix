# 📝 IfoFix

IfoFix is a web application developed as part of a school project. It allows school administrators to assign and manage student interventions in the school's cybercafé.

## 🎯 Purpose

The main goal of this application is to track the interventions carried out by students and to allow teachers to assign students to specific tickets.

## 🛠️ Main Features

-   Create tickets and interventions.
-   Manage tickets, students, and hardware.

## 🧰 Technologies Used

-   **Frontend**: Vue.js, Tailwind CSS, Schadcn-vue, FontAwesome
-   **Backend**: Laravel 11, Sqlite

## ⚙️ Installation

### 🔧 Prerequisites

-   PHP 8.2 or higher
-   Composer
-   Node.js and NPM

### 📦 Installation Steps

1. Clone this repository:

    ```bash
    git clone https://github.com/TechyBear404/IfoFix.git
    ```

2. Install PHP dependencies:

    ```bash
    cd ifofix
    composer install
    ```

3. Install Node.js dependencies:

    ```bash
    npm install
    ```

4. Copy the .env.example file to create your .env file:

    ```bash
    cp .env.example .env
    ```

5. Configure your `.env` file with your **database** and **Admin Login** settings.

6. Generate the Laravel application key and the symbolic link:

    ```bash
    php artisan key:generate
    php artisan storage:link
    ```

7. Run the migrations to set up the database tables:

    ```bash
    php artisan migrate --seed
    ```

    > 💡 If you want fake data, go to `database/seeders/DatabaseSeeder.php` and uncomment all factories.

8. Start backend and frontend development server:

    ```bash
    npm run dev:all
    ```

9. Access the app with the provided URL.

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](./LICENSE) file for details.

## 👤 Author

-   [Hadrien Janssens](https://github.com/Hadrien-Janssens)
-   [Techybear](https://github.com/TechyBear404)

## Previews

 <p>Tickets listing with create ticket modal deployed :</p>
 <img src="https://github.com/TechyBear404/IfoFix/blob/main/public/images/screenshot/tickets_view_with_create_modal.png?raw=true" alt="tickets listing" width="300" />
<p>Ticket : </p>
<img src="https://github.com/TechyBear404/IfoFix/blob/main/public/images/screenshot/ticket_view.png?raw=true" alt="ticket's view" width="300" />
<p>Intervention :</p>
<img src="https://github.com/TechyBear404/IfoFix/blob/main/public/images/screenshot/intervention_view.png?raw=true" alt="intervention's view" width="300" />
