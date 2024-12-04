// Use sessionStorage for user-specific cart
let cart = JSON.parse(sessionStorage.getItem('cart')) || [];
let total = cart.reduce((acc, item) => acc + item.price, 0);

// Scroll to sections on button click
document.getElementById("scrlbtn").addEventListener("click", () => {
    document.getElementById("allproducts").scrollIntoView({ behavior: 'smooth' });
});

document.getElementById("home").addEventListener("click", () => {
    document.getElementById("abtus").scrollIntoView({ behavior: 'smooth' });
});

document.getElementById("prod").addEventListener("click", () => {
    document.getElementById("allproducts").scrollIntoView({ behavior: 'smooth' });
});

// Cart button to toggle expand/collapse
const cartDiv = document.getElementById('cart');
const cartItemsDiv = document.getElementById('cartItems');

cartDiv.addEventListener('click', function (e) {
    // Only toggle when clicking the cart button (not cart content)
    if (e.target === cartDiv || e.target === cartDiv.firstElementChild) {
        this.classList.toggle('expanded');
        displayCart(); // Display cart items whenever cart is expanded
    }
});

// Add product to the cart
function addToCart(productName, productPrice) {
    cart.push({ name: productName, price: productPrice });
    total += productPrice;
    saveCart();
    displayCart();
}

// Remove items from the cart
function removeFromCart(index) {
    total -= cart[index].price;
    cart.splice(index, 1);
    saveCart();
    displayCart();
}

// Save cart to sessionStorage
function saveCart() {
    sessionStorage.setItem('cart', JSON.stringify(cart));
}

// Display cart items and add the checkout button
function displayCart() {
    const cartTotalSpan = document.getElementById('cartTotal');
    const productCounter = document.getElementById('productCounter');

    // Clear existing items
    cartItemsDiv.innerHTML = `
        <h2>Your Cart</h2>
        <div class="cart-total">
            Total: ₹<span id="cartTotal">${total.toFixed(2)}</span>
        </div>
    `;

    // Display each item
    cart.forEach((item, index) => {
        const cartItemDiv = document.createElement('div');
        cartItemDiv.classList.add('cart-item');
        cartItemDiv.innerHTML = `
            <span>${item.name} - ₹${item.price.toFixed(2)}</span>
            <button onclick="removeFromCart(${index})">Remove</button>
        `;
        cartItemsDiv.appendChild(cartItemDiv);
    });

    // Add the checkout button if there are items in the cart
    if (cart.length > 0) {
        const checkoutButton = document.createElement('button');
        checkoutButton.textContent = 'Checkout';
        checkoutButton.classList.add('checkout-btn');
        checkoutButton.addEventListener('click', () => {
            // Process the checkout
            processCheckout();
        });
        cartItemsDiv.appendChild(checkoutButton);
    } else {
        // If the cart is empty, display a message centered and at the bottom
        const emptyCartMessage = document.createElement('p');
        emptyCartMessage.textContent = 'Your cart is empty!';
        emptyCartMessage.classList.add('empty-cart-message');
        cartItemsDiv.appendChild(emptyCartMessage);
    }

    // Update the total
    cartTotalSpan.textContent = total.toFixed(2);

    // Update the product counter
    productCounter.textContent = cart.length;
}

// Checkout function to send order details to the server
function processCheckout() {
    if (cart.length > 0) {
        const orderDetails = {
            cart: cart,
            totalBill: total
        };

        // Send cart data to server for processing
        fetch('checkout.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(orderDetails)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Clear the cart and total after successful order
                cart = []; // Empty the cart
                total = 0; // Reset total
                saveCart(); // Save empty cart to sessionStorage
                displayCart(); // Update the cart display

                // Store the order details in sessionStorage for the invoice
                sessionStorage.setItem('invoice', JSON.stringify(orderDetails));

                // Redirect to the invoice page after successful order
                window.location.href = 'invoice.html';
            } else {
                alert('Failed to save the order. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while processing your order.');
        });
    } else {
        alert("Your cart is empty!");
    }
}

// Display cart on page load
displayCart();

window.addEventListener('scroll', function () {
    let section = document.getElementById('allproducts');
    let scrollPosition = window.scrollY; // Get current scroll position
    let windowHeight = window.innerHeight; // Get window height

    // Calculate opacity (0 when at the top, 1 when halfway down the page)
    let opacityValue = scrollPosition / (windowHeight / 2);

    // Ensure opacity doesn't go beyond 1
    if (opacityValue > 1) {
        opacityValue = 1;
    }

    section.style.opacity = opacityValue;
});
