# NextLvl Games - E-Commerce Website

## Description

NextLvl Games is a comprehensive e-commerce platform designed for buying and selling digital games. Built with PHP and MySQL, it offers a seamless shopping experience with features like user authentication, game browsing, shopping cart, secure checkout, and order management. The website caters to gamers looking for the latest releases, classics, and special offers.

## Features

- **User Authentication**: Secure sign-up and login system.
- **Game Catalog**: Browse a wide range of games categorized into featured, recommended, latest, and special offers.
- **Search Functionality**: Easily search for games by title or keywords.
- **Game Details**: Detailed pages with game information, reviews, ratings, and system requirements.
- **Shopping Cart**: Add games to cart, view cart contents, and manage quantities.
- **Checkout System**: Secure checkout with multiple payment options including GCash, Credit Card, PayPal, Bank Transfer, GrabPay, Maya, and PayMaya. Includes tax calculation (12%).
- **Order Management**: Place orders, view order confirmations, and track status.
- **Newsletter Subscription**: Subscribe to newsletters for updates and promotions.
- **Responsive Design**: Mobile-friendly interface using Bootstrap.
- **Animations**: Smooth animations with AOS (Animate On Scroll) library.

## Technologies Used

- **Backend**: PHP
- **Database**: MySQL
- **Frontend**: HTML, CSS, JavaScript
- **UI Framework**: Bootstrap 5.3.0
- **Animations**: AOS (Animate On Scroll)
- **Icons**: Bootstrap Icons
- **Fonts**: Google Fonts (Poppins)

## Prerequisites

Before running this project, ensure you have the following installed:

- PHP 7.0 or higher
- MySQL 5.7 or higher
- Apache server (recommended via XAMPP for local development)
- Web browser (Chrome, Firefox, etc.)

## Installation

1. **Clone the Repository**:
   ```
   git clone https://github.com/yourusername/nextlvl-games.git
   cd nextlvl-games
   ```

2. **Set Up Local Server**:
   - Download and install XAMPP from [https://www.apachefriends.org/](https://www.apachefriends.org/).
   - Start Apache and MySQL modules in XAMPP control panel.

3. **Database Setup**:
   - Open phpMyAdmin (usually at http://localhost/phpmyadmin).
   - Create a new database named `nextlvl_games`.
   - Import the database schema and sample data (if provided in the repository).

4. **Configure Database Connection**:
   - Update `php/db_config.php` with your database credentials if necessary (default is localhost, root, no password).

5. **Place Files in Server Directory**:
   - Copy the project files to `C:\xampp\htdocs\nextlvl-games` (Windows) or `/opt/lampp/htdocs/nextlvl-games` (Linux/Mac).

6. **Run the Application**:
   - Open your browser and navigate to `http://localhost/nextlvl-games/php/index.php`.

## Database Schema

The application uses the following main tables:

- `games`: Stores game information (title, description, price, image, etc.)
- `users`: User accounts for authentication
- `orders`: Order records
- `order_items`: Items in each order
- `reviews`: User reviews for games
- `special_offers`: Special promotional offers

## Usage

1. **Home Page**: View featured, latest, recommended games, and special offers.
2. **Sign Up/Login**: Create an account or log in to access personalized features.
3. **Browse Games**: Use the Games dropdown or search bar to find games.
4. **Game Details**: Click "Learn More" to view detailed information and reviews.
5. **Add to Cart**: Add games to your cart from the catalog or details page.
6. **Checkout**: Proceed to checkout, fill in details, select payment method, and place order.
7. **Order Confirmation**: View order summary after successful purchase.

## File Structure

```
nextlvl-games/
├── css/
│   ├── styles.css
│   ├── games.css
│   ├── cart.css
│   ├── checkout-system.css
│   ├── featured.css
│   ├── game_details.css
│   ├── navbar.css
│   ├── order_confirmation.css
│   └── signup.css
├── js/
│   ├── script.js
│   ├── checkout-system.js
│   ├── game_details.js
│   ├── games.js
│   ├── login.js
│   └── signup.js
├── nextlvl_images/
│   ├── nextlvl-logo.jpg
│   └── [game images...]
├── php/
│   ├── index.php
│   ├── games.php
│   ├── game_details.php
│   ├── cart.php
│   ├── checkout.php
│   ├── order_confirmation.php
│   ├── login.php
│   ├── signup.php
│   ├── signup_process.php
│   ├── login_process.php
│   ├── logout.php
│   ├── search.php
│   ├── add_to_cart.php
│   ├── remove_from_cart.php
│   ├── buy_now.php
│   ├── featured.php
│   ├── recommended.php
│   ├── latest.php
│   ├── special-offers.php
│   ├── subscribe.php
│   └── db_config.php
└── README.md
```

## Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository.
2. Create a new branch for your feature (`git checkout -b feature/AmazingFeature`).
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`).
4. Push to the branch (`git push origin feature/AmazingFeature`).
5. Open a Pull Request.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Contact

- **Email**: cecb2023-3381-92168@bicol-u.edu.ph
- **Phone**: +63 956 225 3362
- **Address**: Tabaco City
- **Social Media**:
  - Facebook: [NextLvl Games](https://www.facebook.com/charls.barquin)
  - Instagram: [@NextLvl](https://www.instagram.com/chrls.barquin_/)

---

*Built with passion for gamers by the NextLvl Team.*
