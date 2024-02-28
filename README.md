<p align="center">
  <img src="https://i.postimg.cc/4djrcJXx/logo.png" alt="Starter kit logo" width="200"/>
</p>

## Introduction

A starting point to create your next Filament 3 💡 app. With pre-installed plugins, pre-configured, and custom page. So you don't start all over again.

## Table of Contents

-   [Introduction](#introduction)
-   [Features](#features)
-   [Getting Started](#getting-started)
-   [Plugins](#plugins)
-   [License](#license)

## Features

-   🛡 [Filament Shield](#plugins-used) for managing role access
-   👨🏻‍🦱 customizable profile page from [Filament Breezy](#plugins-used)
-   🌌 Managable media with [Filament Spatie Media](#plugins-used)
-   🖼 Theme settings for changing panel color
-   💌 Setting mail on the fly in Mail settings
-   Etc..

## Getting Started

To start using this as your kickstart, follow these steps:

Create project with this composer command

```bash
composer create-project riodwanto/superduper-filament-starter-kit
```

Setup your environment

```bash
cd superduper-filament-starter-kit
cp .env.example .env
```

After create project, Run migration & seeder

```bash
php artisan migrate
php artisan db:seed
```

or

```bash
php artisan migrate:fresh --seed
```

Now you can access with `/admin` path, using:

```bash
email: superadmin@starter-kit.com
password: superadmin
```

## Plugins

These are [Filament Plugins](https://filamentphp.com/plugins) that used for this project.

| **Plugin**                                                                                          | **Author**                                          |
| :-------------------------------------------------------------------------------------------------- | :-------------------------------------------------- |
| [Filament Spatie Media Library](https://github.com/filamentphp/spatie-laravel-media-library-plugin) | [Filament Official](https://github.com/filamentphp) |
| [Filament Spatie Settings](https://github.com/filamentphp/spatie-laravel-settings-plugin)           | [Filament Official](https://github.com/filamentphp) |
| [Filament Spatie Tags](https://github.com/filamentphp/spatie-laravel-tags-plugin)                   | [Filament Official](https://github.com/filamentphp) |
| [Shield](https://github.com/bezhanSalleh/filament-shield)                                           | [bezhansalleh](https://github.com/bezhansalleh)     |
| [Exceptions](https://github.com/bezhansalleh/filament-exceptions)                                   | [bezhansalleh](https://github.com/bezhansalleh)     |
| [Breezy](https://github.com/jeffgreco13/filament-breezy)                                            | [jeffgreco13](https://github.com/jeffgreco13)       |
| [Logger](https://github.com/z3d0x/filament-logger)                                                  | [z3d0x](https://github.com/z3d0x)                   |

## License

Filament Starter is provided under the [MIT License](LICENSE.md).

If you discover a bug, please [open an issue](https://github.com/riodwanto/superduper-filament-starter-kit/issues).
