# Custom Forms Web Application

A web application for creating customizable forms (quizzes, tests, questionnaires, polls) and collecting responses.  
Built with your choice of technology stack:
- **.NET (C#):** Blazor or MVC
- **PHP (8.2+):** Symfony 7+
- **JavaScript/TypeScript:** React + Express

---

## Table of Contents

1. [Features](#features)  
2. [Tech Stack](#tech-stack)  
3. [Project Structure](#project-structure)  
4. [Installation](#installation)  
5. [Deployment](#deployment)  
6. [Usage](#usage)  
7. [Configuration](#configuration)  
8. [Database Setup](#database-setup)  
9. [Search and Indexing](#search-and-indexing)  
10. [Contributing](#contributing)  
11. [License](#license)  

---

## Features

- **User Management:** Registration, login, password management  
- **Roles & Permissions:** Admin and regular user roles  
- **Template Builder:** Create, edit, reorder questions with drag-and-drop  
- **Form Filling:** Public and restricted templates, answer submission  
- **Results & Analytics:** View individual responses, aggregated stats  
- **Search:** Full-text search across templates and comments  
- **Comments & Likes:** Real-time comment updates, one like per user  
- **Media & Tags:** Image uploads (cloud storage), tag autocomplete, tag cloud  
- **Internationalization:** UI in English + one additional language  
- **Themes:** Light and dark mode  
- **Responsive Design:** Mobile-friendly layouts  

---

## Tech Stack

- **Backend:**  
  - .NET 8 / ASP.NET Core (Blazor or MVC) _or_  
  - PHP 8.2 / Symfony 7 _or_  
  - Node.js / Express  
- **Frontend:**  
  - Blazor / Razor Views _or_  
  - Twig (Symfony) _or_  
  - React (JavaScript/TypeScript)  
- **Database:** MySQL / PostgreSQL / SQL Server (choose)  
- **ORM:** Entity Framework Core / Doctrine ORM / Sequelize / TypeORM  
- **Search Engine:** Database full-text search or external library  
- **CSS Framework:** Bootstrap (or alternative)  
- **Cloud Storage:** AWS S3 / Azure Blob / Cloudinary for images  

---

## Project Structure

```
├── src/                # Application source code
│   ├── Controllers/    # MVC controllers / API routes
│   ├── Entities/       # Database models / Entities
│   ├── Services/       # Business logic and services
│   ├── Views/          # Razor / Twig / React components
│   └── ...
├── public/             # Static assets (CSS, JS, images)
├── config/             # Configuration files (env, services)
├── migrations/         # Database migration scripts
├── templates/          # Twig or HTML templates
├── tests/              # Automated tests
├── README.md           # Project documentation
└── ...
```

---

## Installation

1. **Clone the repository**  
   ```bash
   git clone https://github.com/yourusername/your-repo.git
   cd your-repo
   ```

2. **Install dependencies**  
   - **.NET:** `dotnet restore`  
   - **PHP/Symfony:** `composer install`  
   - **Node.js:** `npm install`  

3. **Configure environment**  
   - Copy `.env.example` to `.env` and set database and storage credentials.  

4. **Run database migrations**  
   ```bash
   # .NET
   dotnet ef database update

   # Symfony
   php bin/console doctrine:migrations:migrate

   # Node.js (TypeORM)
   npm run typeorm migration:run
   ```

---

## Deployment

- Ensure the application always has a live deployment (start with a “Hello, world!” page).
- Upload code to your hosting/platform.  
- Configure web root to `public/` or `www/` directory.  
- Set environment variables for database and cloud storage.  
- Run migrations on production database.  
- Use a process manager (e.g., `systemd`, `pm2`, IIS) to keep the app running.

---

## Usage

1. Register a new user or login.  
2. Create templates under “My Templates” tab.  
3. Add questions, configure access, and save.  
4. Share link with other users to fill forms.  
5. View results and analytics on the template page.  
6. Admin can manage all users, templates, and forms.

---

## Configuration

- `.env` or `appsettings.json` holds sensitive settings.  
- UI language and theme preferences are saved per user.

---

## Database Setup

- Use a relational database.  
- Define tables for `users`, `roles`, `templates`, `questions`, `forms`, `answers`, `comments`, `likes`, `tags`, etc.  
- Add full-text indexes on template title, description, question text, and comments.

---

## Search and Indexing

- Use native database full-text search (e.g., MySQL `FULLTEXT`, PostgreSQL `GIN`)  
- Or integrate an external engine (e.g., Elasticsearch, Meilisearch).

---

## Contributing

1. Fork the repo.  
2. Create a feature branch.  
3. Write code and tests.  
4. Submit a pull request.

---

## License

This project is licensed under the MIT License.  
