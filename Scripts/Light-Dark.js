// Theme Toggle Functionality
const themeToggle = document.querySelector("[data-theme-toggle]");
const htmlElement = document.documentElement;

function toggleTheme() {
  const currentTheme = htmlElement.getAttribute("data-theme");
  const newTheme = currentTheme === "dark" ? "light" : "dark";
  htmlElement.setAttribute("data-theme", newTheme);
  localStorage.setItem("theme", newTheme);
}

// Load saved theme
const savedTheme = localStorage.getItem("theme") || "dark";
htmlElement.setAttribute("data-theme", savedTheme);

themeToggle.addEventListener("click", toggleTheme);

// Form interaction
const dataInput = document.getElementById("data");
const agreementBlock = document.getElementById("agreementBlock");

dataInput.addEventListener("input", function () {
  if (this.value.trim() !== "") {
    agreementBlock.style.display = "block";
  } else {
    agreementBlock.style.display = "none";
  }
});

// Link redirect functionality (placeholder)
function delayRedirect() {
  const overlay = document.getElementById("textField");
  overlay.style.display = "flex";

  setTimeout(() => {
    // In the actual implementation, this would redirect to the PHP-generated link
    const link = document
      .getElementById("linkToPage")
      .getAttribute("data-link");
    // window.location.href = link;
    overlay.style.display = "none"; // For demo purposes
  }, 2000);
}
document
  .getElementById("linkToPage")
  .addEventListener("click", function (event) {
    event.preventDefault();
    delayRedirect();
  });
// Initial setup for the agreement block
if (dataInput.value.trim() !== "") {
  agreementBlock.style.display = "block";
} else {
  agreementBlock.style.display = "none";
}
// End of Scripts/Light-Dark.js
