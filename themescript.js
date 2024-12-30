
document.addEventListener('DOMContentLoaded', function () {
    // Check if there's a saved theme in localStorage
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        applyTheme(savedTheme);
        document.getElementById(savedTheme).checked = true;
    }

    // Listen for changes in theme selection
    const radios = document.querySelectorAll('input[name="theme"]');
    radios.forEach(radio => {
        radio.addEventListener('change', () => {
            if (radio.checked) {
                applyTheme(radio.id);
                localStorage.setItem('theme', radio.id);
            }
        });
    });

    function applyTheme(theme) {
        if (theme === 'black') {
            document.documentElement.style.setProperty('--primary-color', '#1b1b1b'); // Example Red Color
            document.documentElement.style.setProperty('--secondary-color', '#fff'); // White
        } else if (theme === 'green') {
            document.documentElement.style.setProperty('--primary-color', '#522a61'); // Example Green Color
            document.documentElement.style.setProperty('--secondary-color', '#fff'); // White
        } else if (theme === 'blue') {
            document.documentElement.style.setProperty('--primary-color', '#8E2157'); // Example Blue Color
            document.documentElement.style.setProperty('--secondary-color', '#fff'); // White
        }
    }
});