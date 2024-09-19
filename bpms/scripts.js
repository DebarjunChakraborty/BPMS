document.addEventListener('DOMContentLoaded', () => {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const cartModal = document.getElementById('cart-modal');
    const cartItemsContainer = document.getElementById('cart-items');
    const viewCartButton = document.getElementById('view-cart');
    const clearCartButton = document.getElementById('clear-cart');
    const buyNowButton = document.getElementById('buy-now');
    const closeButton = document.querySelector('.close');
    const buyNowModal = document.getElementById('buy-now-modal');
    const buyNowForm = document.getElementById('buy-now-form');

    // Function to update cart display
    function updateCart() {
        cartItemsContainer.innerHTML = '';
        let total = 0;

        if (cart.length === 0) {
            cartItemsContainer.innerHTML = '<p>Your cart is empty.</p>';
            return;
        }

        cart.forEach(item => {
            total += parseFloat(item.price);

            const cartItem = document.createElement('div');
            cartItem.className = 'cart-item';
            cartItem.innerHTML = `
                <p>${item.name} - $${item.price}</p>
                <button class="remove-from-cart" data-id="${item.id}">Remove</button>
            `;
            cartItemsContainer.appendChild(cartItem);
        });

        const totalElement = document.createElement('p');
        totalElement.innerHTML = `<strong>Total: $${total.toFixed(2)}</strong>`;
        cartItemsContainer.appendChild(totalElement);
    }

    // Add product to cart
    function addToCart(id, name, price) {
        const existingItem = cart.find(item => item.id === id);
        if (existingItem) {
            return; // Prevent duplicate items
        }
        cart.push({ id, name, price });
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCart();
    }

    // Remove product from cart
    function removeFromCart(id) {
        const index = cart.findIndex(item => item.id === id);
        if (index > -1) {
            cart.splice(index, 1);
            localStorage.setItem('cart', JSON.stringify(cart));
            updateCart();
        }
    }

    // Toggle cart modal visibility
    function toggleCartModal() {
        cartModal.style.display = cartModal.style.display === 'block' ? 'none' : 'block';
    }

    // Handle "Add to Cart" button click
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const price = button.getAttribute('data-price');
            addToCart(id, name, price);
        });
    });

    // Handle "Remove from Cart" button click
    cartItemsContainer.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-from-cart')) {
            const id = e.target.getAttribute('data-id');
            removeFromCart(id);
        }
    });

    // Handle "View Cart" button click
    viewCartButton.addEventListener('click', () => {
        toggleCartModal();
        updateCart();
    });

    // Handle "Clear Cart" button click
    clearCartButton.addEventListener('click', () => {
        localStorage.removeItem('cart');
        cart.length = 0; // Clear cart array
        updateCart();
    });

    // Handle "Buy Now" button click
    buyNowButton.addEventListener('click', () => {
        if (cart.length === 0) {
            alert('Your cart is empty.');
            return;
        }
        buyNowModal.style.display = 'block';
    });

    // Handle form submission for "Buy Now"
    buyNowForm.addEventListener('submit', (e) => {
        e.preventDefault();

        const name = document.getElementById('name').value;
        const address = document.getElementById('address').value;

        // Send cart data and user info to the server
        fetch('process_purchase.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                name: name,
                address: address,
                cart: cart
            }),
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok.');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Purchase successful!');
                localStorage.removeItem('cart');
                cart.length = 0; // Clear cart array
                updateCart();
                buyNowModal.style.display = 'none';
            } else {
                alert('An error occurred while processing your purchase.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while processing your purchase.');
        });
    });

    // Close modal when 'x' is clicked
    closeButton.addEventListener('click', () => {
        cartModal.style.display = 'none';
        buyNowModal.style.display = 'none';
    });

    // Close modal when clicking outside of it
    window.addEventListener('click', (e) => {
        if (e.target === cartModal || e.target === buyNowModal) {
            cartModal.style.display = 'none';
            buyNowModal.style.display = 'none';
        }
    });

    // Update cart on page load
    updateCart();
});
