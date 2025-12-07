// Add smooth scrolling to all anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});

// Optional: Add a delay for carousel auto sliding
document.querySelectorAll('.carousel').forEach(carousel => {
    new bootstrap.Carousel(carousel, {
        interval: 5000
    });
});

const scrollToTopBtn = document.getElementById('scrollToTopBtn');

window.addEventListener('scroll', () => {
    if (window.scrollY > 200) {
        scrollToTopBtn.style.display = 'block';
    } else {
        scrollToTopBtn.style.display = 'none';
    }
});

scrollToTopBtn.addEventListener('click', () => {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
});

// Active link detection for sections
const sections = document.querySelectorAll("section, header");
const navLinks = document.querySelectorAll(".nav-link");

window.addEventListener("scroll", () => {
    let current = "home"; // Default to "home" section
    sections.forEach(section => {
        const sectionTop = section.offsetTop - 100; // Offset for navbar height
        if (window.scrollY >= sectionTop) {
            current = section.getAttribute("id") || "home";
        }
    });

    navLinks.forEach(link => {
        link.classList.remove("active"); // Remove active class from all links
        if (link.getAttribute("href") === `#${current}`) {
            link.classList.add("active"); // Add active class to current link
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    // Select all the Add to Cart buttons
    const addToCartButtons = document.querySelectorAll('.add-to-cart');

    // Loop through each button and add a click event listener
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function () {
            const gameId = this.getAttribute('data-game-id'); // Get the game ID from data attribute
            
            // Create a new XMLHttpRequest to send the AJAX request
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'add_to_cart.php?game_id=' + gameId, true);

            // On successful request, show a success message
            xhr.onload = function () {
                if (xhr.status === 200) {
                    // Show a custom alert or success message
                    const successMessage = document.createElement('div');
                    successMessage.classList.add('alert', 'alert-success', 'fixed-top', 'w-100', 'text-center');
                    successMessage.textContent = 'Game added to cart successfully!';
                    
                    document.body.appendChild(successMessage);

                    // Remove the alert after 3 seconds
                    setTimeout(() => {
                        successMessage.remove();
                    }, 3000);

                    // Optionally, you can update the cart icon or cart count here
                    // Example: Update the cart count (assuming you have a cart count element with id 'cart-count')
                    // let cartCount = document.getElementById('cart-count');
                    // cartCount.textContent = parseInt(cartCount.textContent) + 1;
                }
            };

            // Send the AJAX request
            xhr.send();
        });
    });
});

