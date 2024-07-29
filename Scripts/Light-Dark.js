// Calculate the current theme setting based on localStorage and system settings
function calculateSettingAsThemeString({ localStorageTheme, systemSettingDark }) {
  return localStorageTheme || (systemSettingDark.matches ? "dark" : "light");
}

// Update the button's text and aria-label based on the current theme
function updateButton({ buttonEl, isDark }) {
  const newCta = `url("Partials/Favicon/Light-Dark-Change.png")`;
  buttonEl.style.backgroundImage = newCta;
  buttonEl.setAttribute("aria-label", `Switch to ${isDark ? "Light" : "Dark"} Mode`);
}

// Update the theme setting on the HTML tag
function updateThemeOnHtmlEl({ theme }) {
  document.querySelector("html").setAttribute("data-theme", theme);
}

// On page load:

// Find the theme toggle button and get theme settings from localStorage and system
const button = document.querySelector("[data-theme-toggle]");
const systemSettingDark = window.matchMedia("(prefers-color-scheme: dark)");

// Determine the current theme setting
let currentThemeSetting = calculateSettingAsThemeString({
  localStorageTheme: localStorage.getItem("theme"),
  systemSettingDark,
});

// Update the button and HTML theme on page load
updateButton({ buttonEl: button, isDark: currentThemeSetting === "dark" });
updateThemeOnHtmlEl({ theme: currentThemeSetting });

// Add an event listener to toggle the theme with a 2-second delay
let canToggleTheme = true;
button.addEventListener("click", () => {
  if (!canToggleTheme) {
    return;
  }

  canToggleTheme = false;

  const newTheme = currentThemeSetting === "dark" ? "light" : "dark";

  // Delay the theme change logic here
  setTimeout(() => {
    localStorage.setItem("theme", newTheme);
    updateButton({ buttonEl: button, isDark: newTheme === "dark" });
    updateThemeOnHtmlEl({ theme: newTheme });

    currentThemeSetting = newTheme;
    canToggleTheme = true;
  }, 500);
});
