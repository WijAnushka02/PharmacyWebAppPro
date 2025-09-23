// Handle medication search
const searchBox = document.getElementById('med-search-input');
const searchResultsSection = document.getElementById('search-results-section');
const medicationName = document.getElementById('medication-name');
const medicationDescription = document.getElementById('medication-description');
const medicationPrice = document.getElementById('medication-price');
const addToCartBtn = document.getElementById('add-to-cart-btn');
const closeSearchResultsBtn = document.getElementById('close-search-results');
const cartCount = document.getElementById('cart-count');

if (searchBox) {
  searchBox.addEventListener('keydown', (event) => {
    if (event.key === 'Enter') {
      event.preventDefault(); 
      const query = searchBox.value.trim();
      if (query) {
        performSearch(query);
      }
    }
  });
}

if (closeSearchResultsBtn) {
  closeSearchResultsBtn.addEventListener('click', () => {
    searchResultsSection.classList.add('hidden');
  });
}

function performSearch(query) {
  fetch(`search.php?q=${encodeURIComponent(query)}`)
    .then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      return response.json();
    })
    .then(data => {
      if (data.error) {
        // Handle no results found or a connection error
        medicationName.textContent = "No results found.";
        medicationDescription.textContent = data.error;
        medicationPrice.textContent = "";
        addToCartBtn.style.display = 'none';
      } else {
        // Display medication details
        medicationName.textContent = data.name;
        medicationDescription.textContent = data.description;
        medicationPrice.textContent = `Unit Price: Rs.${parseFloat(data.price).toFixed(2)}`;
        addToCartBtn.style.display = 'inline-block';
        addToCartBtn.onclick = () => {
          let current = 0;
          if (cartCount) {
             current = parseInt(cartCount.textContent, 10);
             cartCount.textContent = current + 1;
          }
          alert(`${data.name} has been added to your cart!`);
        };
      }
      searchResultsSection.classList.remove('hidden');
    })
    .catch(error => {
      console.error('Error fetching search results:', error);
      medicationName.textContent = "Search Error";
      medicationDescription.textContent = "An error occurred while searching. Please try again later.";
      medicationPrice.textContent = "";
      addToCartBtn.style.display = 'none';
      searchResultsSection.classList.remove('hidden');
    });
}