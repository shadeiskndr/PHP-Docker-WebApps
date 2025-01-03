document.addEventListener('DOMContentLoaded', function() {
    const accordionButtons = document.querySelectorAll('.accordion-btn');
    
    accordionButtons.forEach(button => {
        button.addEventListener('click', () => {
            const content = button.nextElementSibling;
            const icon = button.querySelector('.fa-chevron-down');
            
            // Toggle content
            content.classList.toggle('hidden');
            
            // Rotate icon
            icon.style.transform = content.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
        });
    });
});
