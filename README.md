# Pokedex Inertia

## Overview
Pokedex Inertia is a web application built with Vue.js and PHP Laravel, providing a modern and responsive interface for exploring Pokémon data sourced from the [PokeAPI](https://pokeapi.co/). Key features include:

- Real-time updates of Pokémon data every Sunday at 12:00.
- Full responsiveness and modern design.
- Pagination, theme switch, dynamic error page, and notifications.
- Like/unlike Pokémon, search, and filter functionalities.

## Installation
Ensure you have [Git](https://git-scm.com/), [Composer](https://getcomposer.org/), and [Node.js](https://nodejs.org/) installed.

1. **Clone the repository:**
   ```bash
   git clone https://github.com/AntonisKazantzis/pokedex.git
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
- Laravel 10.10
- Laravel Pint 1.0
- Laravel Jetstream 4.2
- Inertia.js 0.6.8
- Inertia.js Vue3 1.0.0
- Tabler Icons-Vue 2.46.0
- Date-Fns 3.3.1
- Lodash 4.17.21

## Screenshots
![Pokemon Index Page](https://github.com/AntonisKazantzis/pokedex-inertia/blob/master/public/image/Index.png?raw=true)
![Light Theme Index Page](https://github.com/AntonisKazantzis/pokedex-inertia/blob/master/public/image/Light-Theme-Index.png?raw=true)
![Responsive Pokemon Index Page](https://github.com/AntonisKazantzis/pokedex-inertia/blob/master/public/image/Responsive-Index.png?raw=true)
![User Liked Pokemon Page](https://github.com/AntonisKazantzis/pokedex-inertia/blob/master/public/image/Liked.png?raw=true)
![Pokemon Show Page](https://github.com/AntonisKazantzis/pokedex-inertia/blob/master/public/image/Show.png?raw=true)
![Light Theme Show Page](https://github.com/AntonisKazantzis/pokedex-inertia/blob/master/public/image/Light-Theme-Show.png?raw=true)
![Responsive Pokemon Show Page](https://github.com/AntonisKazantzis/pokedex-inertia/blob/master/public/image/Responsive-Show-Top.png?raw=true)
![Responsive Pokemon Show Top Page](https://github.com/AntonisKazantzis/pokedex-inertia/blob/master/public/image/Responsive-Show-Top.png?raw=true)
![Responsive Pokemon Show Bottom Page](https://github.com/AntonisKazantzis/pokedex-inertia/blob/master/public/image/Responsive-Show-Bottom.png?raw=true)
![Login Page](https://github.com/AntonisKazantzis/pokedex-inertia/blob/master/public/image/Login.png?raw=true)
![Register Page](https://github.com/AntonisKazantzis/pokedex-inertia/blob/master/public/image/Register.png?raw=true)
![Footer](https://github.com/AntonisKazantzis/pokedex-inertia/blob/master/public/image/Footer.png?raw=true)
![Notification](https://github.com/AntonisKazantzis/pokedex-inertia/blob/master/public/image/Notification.png?raw=true)
![Dynamic Error Page](https://github.com/AntonisKazantzis/pokedex-inertia/blob/master/public/image/Dynamic-Error-Page.png?raw=true)

## Tags
#VueJS #Laravel #Tailwind #Jetstream #FullStackDevelopment #PokemonAPI #InertiaJS

## Developer
Antonis Kazantzis
