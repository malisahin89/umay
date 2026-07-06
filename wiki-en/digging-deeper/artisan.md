# Umay Console (CLI)

Umay Framework provides the `umay` command-line tool to speed up your daily tasks.

Open your terminal (Command Prompt / Bash), navigate to the project directory, and run the tool by typing `php umay`.

## Available Commands

To see the full list of system commands:
```bash
php umay help
```

### Developer Generators (Make Commands)

Umay automatically creates the necessary class templates for you. You don't have to worry about creating files manually and setting up namespaces.

```bash
# Create a new Model and Controller
php umay make:model Post
php umay make:controller PostController

# Create a Resource Controller (with all CRUD methods ready)
php umay make:controller ProductController --resource

# Create a new Middleware
php umay make:middleware CheckAgeMiddleware

# Create a new Migration file
php umay make:migration create_products_table

# Create a Seeder for the database
php umay make:seeder ProductsSeeder
```

### Database Commands

Used to run Migration and Seeder files in the database.

```bash
# Run all new migrations
php umay migrate

# Rollback migrations and delete tables
php umay migrate:rollback

# Run seeder classes and add sample data
php umay db:seed
```

### Maintenance and Others

```bash
# Lists routes in the terminal (Perfect for debugging)
php umay route:list

# Create a symlink from public/storage to storage/app/public (For web access to uploaded files)
php umay storage:link

# Clears the framework's cache folder
php umay cache:clear
```

> [!TIP]
> When `make` commands run, they use templates from the `stubs/` folder in the framework root. If you want to customize the standard production code of the files, you can edit the files inside `stubs/`.
