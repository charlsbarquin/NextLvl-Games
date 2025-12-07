// JavaScript for toggling the reviews section visibility
document.getElementById("reviewToggle").addEventListener("click", function() {
    var reviewsContent = document.getElementById("reviewsContent");
    if (reviewsContent.style.display === "none") {
        reviewsContent.style.display = "block";
        this.textContent = "Hide Reviews"; // Change button text
    } else {
        reviewsContent.style.display = "none";
        this.textContent = "View Reviews"; // Change button text back
    }
});

document.addEventListener('DOMContentLoaded', () => {
    // Cart handling
    const cart = JSON.parse(localStorage.getItem('cart')) || []; // Retrieve cart from localStorage
    updateCartCount();

    // Select "Add to Cart" buttons
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', (event) => {
            const card = event.target.closest('.card'); // Get the parent card element
            const title = card.querySelector('.game-title').textContent; // Get game title
            const price = card.querySelector('.price').textContent; // Get game price
            const gameId = card.querySelector('.game-id').value; // Get game ID (add as hidden input in PHP)

            // Check if the item is already in the cart
            const itemExists = cart.some(item => item.title === title);
            if (itemExists) {
                alert(`${title} is already in your cart!`);
                return;
            }

            // Add the item to the cart
            cart.push({ title, price, gameId, quantity: 1 });
            localStorage.setItem('cart', JSON.stringify(cart)); // Save cart to localStorage

            alert(`${title} has been added to your cart!`);
            updateCartCount(); // Update cart count in navbar
        });
    });

    // Function to update cart count in the Navbar (displayed next to the cart icon)
    function updateCartCount() {
        const cartCount = cart.reduce((total, item) => total + item.quantity, 0);
        const cartBadge = document.querySelector('.cart-count');
        if (cartBadge) {
            cartBadge.textContent = cartCount;
            if (cartCount > 0) {
                cartBadge.style.display = 'inline-block'; // Show cart count if items are added
            } else {
                cartBadge.style.display = 'none'; // Hide cart count if no items
            }
        }
    }

    // Show cart contents in console (for debugging)
    document.querySelector('#show-cart').addEventListener('click', () => {
        console.log('Cart:', cart);
    });
});
