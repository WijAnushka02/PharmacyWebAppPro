// Filter & Sort functionality
const categorySelect = document.getElementById("filter-category");
const sortSelect = document.getElementById("sort-by");
const offersGrid = document.getElementById("offersGrid");
const cards = Array.from(offersGrid.getElementsByClassName("offer-card"));

function renderCards(list) {
  offersGrid.innerHTML = "";
  list.forEach(card => offersGrid.appendChild(card));
}

function applyFilterSort() {
  const category = categorySelect.value;
  const sortBy = sortSelect.value;

  let filtered = cards.filter(card =>
    category === "all" || card.dataset.category === category
  );

  filtered.sort((a, b) => {
    const priceA = parseFloat(a.dataset.price);
    const priceB = parseFloat(b.dataset.price);
    if (sortBy === "low-high") return priceA - priceB;
    if (sortBy === "high-low") return priceB - priceA;
    return 0; // newest = keep original order
  });

  renderCards(filtered);
}

categorySelect.addEventListener("change", applyFilterSort);
sortSelect.addEventListener("change", applyFilterSort);

function toggleDropdown(event) {
            event.preventDefault();
            const dropdownContent = event.target.nextElementSibling;
            
            // Close all other dropdowns
            document.querySelectorAll('.dropdown-content.active').forEach(openDropdown => {
                if (openDropdown !== dropdownContent) {
                    openDropdown.classList.remove('active');
                }
            });

            // Toggle the 'active' class on the clicked dropdown
            dropdownContent.classList.toggle('active');
        }

        // Close dropdowns if the user clicks outside
        window.onclick = function(event) {
            if (!event.target.matches('.dropdown a')) {
                const dropdowns = document.querySelectorAll('.dropdown-content');
                dropdowns.forEach(dropdown => {
                    if (dropdown.classList.contains('active')) {
                        dropdown.classList.remove('active');
                    }
                });
            }
        }