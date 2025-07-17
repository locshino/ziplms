document.addEventListener("load", () => {
    const themeToggle = document.getElementById("theme-toggle");
    const htmlElement = document.documentElement;

    // Function to apply the theme
    const applyTheme = (theme) => {
        htmlElement.setAttribute("data-theme", theme);
        localStorage.setItem("theme", theme);
    };

    // Set initial toggle state based on current theme
    const currentTheme = localStorage.getItem("theme") || "light";
    if (currentTheme === "dark") {
        themeToggle.checked = true;
    }
    applyTheme(currentTheme);

    // Listen for toggle changes
    themeToggle.addEventListener("change", () => {
        const newTheme = themeToggle.checked ? "dark" : "light";
        applyTheme(newTheme);
    });
});

// Apply theme from localStorage to prevent FOUC (Flash of Unstyled Content)
const theme = localStorage.getItem("theme") || "light";
document.documentElement.setAttribute("data-theme", theme);
