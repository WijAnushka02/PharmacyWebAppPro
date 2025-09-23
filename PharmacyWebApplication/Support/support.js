// Accordion toggle
document.querySelectorAll(".accordion-header").forEach(btn => {
  btn.addEventListener("click", () => {
    btn.classList.toggle("active");
    const panel = btn.nextElementSibling;
    panel.style.display = panel.style.display === "block" ? "none" : "block";
  });
});

// FAQ search
const searchInput = document.getElementById("faq-search");
const questions = document.querySelectorAll(".accordion-header");

searchInput.addEventListener("input", () => {
  const value = searchInput.value.toLowerCase();
  questions.forEach(q => {
    const content = q.textContent.toLowerCase();
    q.style.display = content.includes(value) ? "" : "none";
    q.nextElementSibling.style.display = "none";
  });
});

// Contact form submission (optional AJAX)
document.getElementById("contact-form").addEventListener("submit", e => {
  // You can implement AJAX here if needed
  // e.preventDefault();
});
