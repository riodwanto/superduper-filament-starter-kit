# Getting Started

Welcome to the SuperDuper Filament Starter Kit! This documentation will help you get started with setting up and using the starter kit for your next project.

## Introduction

SuperDuper Filament Starter Kit is a robust starting point for building admin panels and applications with Laravel and Filament PHP. It comes with pre-configured features and components to accelerate your development process.

## Key Features

### 🛠️ Core Features
- **User Management**: Complete user management system with roles and permissions
- **Content Management**: Blog, pages, and media management
- **Theming**: Customizable admin panel with light/dark mode
- **API Ready**: Built-in API endpoints with authentication

### 🚀 Development Tools
- **Modern Stack**: Laravel 10, Filament 3, Livewire 3, Alpine.js, Tailwind CSS
- **Developer Experience**: IDE helpers, debugging tools, and testing setup
- **Modular Architecture**: Easy to extend and customize

## Quick Start

1. **Create a new project**:
   ```bash
   composer create-project riodwanto/superduper-filament-starter-kit
   ```

2. **Run the setup wizard**:
   ```bash
   php bin/setup.php
   ```

3. **Access the admin panel**:
   - URL: `http://localhost:8000/admin`
   - Email: `superadmin@starter-kit.com`
   - Password: `superadmin`

## Project Structure

```
app/
├── Filament/           # Filament resources and pages
├── Http/               # Controllers, middleware, etc.
├── Models/             # Eloquent models
├── Policies/           # Authorization policies
resources/
├── css/                # Custom CSS
├── docs/               # Documentation files
├── js/                 # JavaScript files
├── views/              # Blade templates
config/                 # Configuration files
database/
├── migrations/         # Database migrations
├── seeders/            # Database seeders
tests/                  # Test files
```

## Documentation Guide

Our documentation is organized into several sections to help you find what you need:

1. [Installation](/docs/installation) - Complete setup instructions
2. [Configuration](/docs/configuration) - System configuration options
3. [Features](/docs/features) - Detailed feature documentation
4. [User Guide](/docs/user-guide) - How to use the admin panel
5. [Development](/docs/development) - Developer documentation
6. [API Reference](/docs/api) - API documentation
7. [Performance](/docs/performance) - Optimization guides

## Getting Help

If you encounter any issues or have questions:

1. Check the [FAQ](/docs/faq)
2. Search the [GitHub Issues](https://github.com/riodwanto/superduper-filament-starter-kit/issues)
3. Create a new issue if your question hasn't been answered

## Contributing

We welcome contributions! Please see our [Contributing Guide](https://github.com/riodwanto/superduper-filament-starter-kit/blob/main/CONTRIBUTING.md) for details.

## License

This project is open-sourced under the [MIT License](https://opensource.org/licenses/MIT).