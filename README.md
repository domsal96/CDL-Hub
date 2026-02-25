# CDL Hub

A web application for Call of Duty League fans to track teams, players, and matches. Built with PHP, MySQL, and JavaScript.

## Features

- Browse all CDL teams and players with stats
- Search for teams and players by name
- View match schedules and results
- User registration, login, logout, and password change
- Favourite and unfavourite teams and players with AJAX (no page reload)
- Personal profile page displaying your saved favourites
- Security hardening including CSRF protection, brute force rate limiting, and session management

## Tech Stack

- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP
- **Database:** MySQL
- **Server:** Apache (XAMPP for local development)
- **Hosting:** InfinityFree

## Pages

| Page | Description |
|------|-------------|
| `index.php` | Home page with top players and featured match |
| `teams.php` | Browse and search all CDL teams |
| `players.php` | Browse and search all CDL players |
| `matches.php` | View match schedule and results |
| `profile.php` | Personal dashboard with favourite teams and players |
| `login.php` | User login |
| `register.php` | User registration |
| `change_password.php` | Change account password |

## Database Tables

- `users` — registered user accounts
- `teams` — CDL team records
- `players` — CDL player records
- `matches` — match schedule and results
- `team_favorites` — user saved team favourites
- `player_favorites` — user saved player favourites

## Security Features

- Passwords hashed using `password_hash()` with `PASSWORD_DEFAULT`
- CSRF tokens on all forms and AJAX requests
- Brute force protection — 5 minute lockout after 5 failed login attempts
- Session regeneration on login to prevent session fixation
- Secure session cookie settings (`httponly`, `samesite`)
- PHP error output suppressed in production
- All database queries use prepared statements to prevent SQL injection

## Author

Dominic Salvador  [GitHub Profile](https://github.com/domsal96)
