// This script dynamically handles the shopping cart on the cart.html page.

document.addEventListener("DOMContentLoaded", () => {
    // Select all the relevant HTML elements from the cart page
    const cartItemsContainer = document.getElementById('cartItems');
    const totalEl = document.getElementById('total');
    const emptyCartEl = document.getElementById('emptyCart');
    const cartContentEl = document.getElementById('cartContent');

    // --- Core Functions ---

    // Fetches the cart data from the backend API
    function fetchCart() {
        // Correct path to cart.php from the cart folder
        fetch('../../medications/api/cart.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Check if the cart data is valid before using it
                if (data && data.cart) {
                    displayCart(data.cart);
                    updateTotal(data.total);
                } else {
                    console.error('Invalid data received from cart.php:', data);
                    displayCart([]); // Display an empty cart to prevent errors
                    updateTotal(0);
                }
            })
            .catch(error => {
                console.error('Error fetching cart:', error);
                // Gracefully handle the error by displaying an empty cart
                displayCart([]);
                updateTotal(0);
            });
    }

    // Displays the cart items on the page based on the fetched data
    function displayCart(items) {
        cartItemsContainer.innerHTML = ''; // Clear existing static items

        if (items.length === 0) {
            // Show the "empty cart" message if there are no items
            emptyCartEl.style.display = 'block';
            cartContentEl.style.display = 'none';
        } else {
            // Show the cart content and hide the empty message
            emptyCartEl.style.display = 'none';
            cartContentEl.style.display = 'flex';

            items.forEach(item => {
                const itemDiv = document.createElement('div');
                itemDiv.classList.add('order-summary-item');
                itemDiv.innerHTML = `
                    <span>${item.name} x ${item.quantity}</span>
                    <span>Rs. ${(item.price * item.quantity).toFixed(2)}</span>
                `;
                cartItemsContainer.appendChild(itemDiv);
            });
        }
    }

    // Updates the total displayed in the summary section
    function updateTotal(subtotal) {
        totalEl.textContent = `Rs. ${parseFloat(subtotal).toFixed(2)}`;
    }

    // Initial fetch of cart data when the page loads
    fetchCart();
});