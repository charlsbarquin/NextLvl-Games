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

document.addEventListener('DOMContentLoaded', () => {
    const cart = [];

    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', (event) => {
            const card = event.target.closest('.card');
            const title = card.querySelector('.card-title').textContent;

            // Add the item to the cart array
            cart.push({ title });
            console.log(`Added to cart: ${title}`);

            // Display a notification or update cart UI
            alert(`${title} has been added to your cart!`);
        });
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const cart = []; // Array to store cart items

    // Select all "Add to Cart" buttons
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', (event) => {
            const card = event.target.closest('.card'); // Get the parent card element
            const title = card.querySelector('.card-title').textContent; // Get game title
            const description = card.querySelector('.card-text').textContent; // Get game description

            // Check if the item is already in the cart
            const itemExists = cart.some(item => item.title === title);
            if (itemExists) {
                alert(`${title} is already in your cart!`);
                return;
            }

            // Add the item to the cart
            cart.push({ title, description });
            console.log(cart); // Log the updated cart for debugging

            // Display a confirmation message
            alert(`${title} has been added to your cart!`);
        });
    });

    // Optionally, create a function to display cart items in the console or on the page
    const displayCart = () => {
        console.log('Cart Contents:', cart);
    };

    // Example: Add a button to show cart contents in the console
    document.querySelector('body').insertAdjacentHTML(
        'beforeend',
        `<button id="show-cart" class="btn btn-info" style="position: fixed; bottom: 20px; right: 20px;">Show Cart</button>`
    );

    document.getElementById('show-cart').addEventListener('click', displayCart);
});
