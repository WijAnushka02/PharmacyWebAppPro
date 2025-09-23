// Function to fetch and display data
function fetchOrders(query = '') {
    const tableBody = document.getElementById('orders-table-body');
    
    // Use a simple fetch request to call the PHP script with the search query
    fetch('get_orders.php?query=' + encodeURIComponent(query))
        .then(response => response.text())
        .then(html => {
            tableBody.innerHTML = html;
        })
        .catch(error => console.error('Error fetching data:', error));
}

// Event listener for the search bar
document.getElementById('search').addEventListener('keyup', function(e) {
    fetchOrders(e.target.value);
});

// Initial fetch to load all orders when the page loads
document.addEventListener('DOMContentLoaded', () => {
    fetchOrders();
});