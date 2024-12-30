// Check if dark mode is enabled on page load
document.addEventListener('DOMContentLoaded', () => {
    // Check localStorage and system preference
    const isDarkMode = localStorage.getItem('darkMode') === 'enabled' || 
        (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches);
    
    // Apply dark mode if needed
    if (isDarkMode) {
        document.documentElement.classList.add('dark');
    }

    // Set up toggle button functionality
    const darkModeToggle = document.getElementById('darkModeToggle');
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', () => {
            document.documentElement.classList.toggle('dark');
            const isDark = document.documentElement.classList.contains('dark');
            localStorage.setItem('darkMode', isDark ? 'enabled' : 'disabled');
        });
    }
});
