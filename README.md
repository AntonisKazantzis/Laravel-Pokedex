# Laravel Pokedex

## Overview
Laravel Pokedex is a web application built with Vue.js and PHP Laravel, providing a modern and responsive interface for exploring Pokémon data sourced from the [PokeAPI](https://pokeapi.co/). Key features include:

- Schedule
- Pokemon Page
- Fully Responsive
- Modern Design
- Theme Switcher
- Custom Error Page
- Notifications
- Admin Panel
- Filters
- Pagination

## Installation
Ensure you have [Git](https://git-scm.com/), [Composer](https://getcomposer.org/), and [Node.js](https://nodejs.org/) installed.

1. **Clone the repository:**
   ```bash
   git clone https://github.com/AntonisKazantzis/Pokedex-Inertia.git
   ```

2. **Install dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **Set up environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Migrate database and seed initial data:**
   ```bash
   php artisan migrate:fresh --seed
   php artisan app:fetch-pokemon-data
   ```

5. **Compile assets and start server:**
   ```bash
   npm run dev
   php artisan serve
   ```

6. **Access the application:**
   - URL: [http://127.0.0.1:8000/login](http://127.0.0.1:8000/login)
   - Credentials: 
     - Email: test@test.com 
     - Password: password

## Dependencies
- Laravel: 10.10
- Laravel Pint: 1.0
- Laravel Jetstream: 4.2
- Inertia.js: 0.6.8
- Inertia.js Vue3: 1.0.0
- Tabler Icons-Vue: 2.46.0
- Date-Fns: 3.3.1
- Lodash: 4.17.21

## Tags
#VueJS #Laravel #Tailwind #Jetstream #FullStackDevelopment #PokemonAPI #InertiaJS

## Developer
Antonis Kazantzis
