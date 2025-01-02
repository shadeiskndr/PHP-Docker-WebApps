document.addEventListener('DOMContentLoaded', () => {
    const isDarkMode = localStorage.getItem('darkMode') === 'enabled' || 
        (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches);
    
    if (isDarkMode) {
        document.documentElement.classList.add('dark');
    }

    // Handle both desktop and mobile toggles
    const darkModeToggles = ['darkModeToggle', 'mobileDarkModeToggle'];
    
    // Handle dark mode toggle with event delegation
    document.addEventListener('click', (event) => {
        if (event.target.closest('#darkModeToggle') || event.target.closest('#mobileDarkModeToggle')) {
            document.documentElement.classList.toggle('dark');
            const isDark = document.documentElement.classList.contains('dark');
            localStorage.setItem('darkMode', isDark ? 'enabled' : 'disabled');
        }
    });
});
