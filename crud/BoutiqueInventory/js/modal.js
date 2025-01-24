document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('addCategoryModal');
    const addButton = document.querySelector('button[name="add"]');
    const closeButton = document.getElementById('closeModal');
    const form = document.getElementById('addCategoryForm');

    // Show modal
    addButton.addEventListener('click', (e) => {
        e.preventDefault();
        modal.classList.remove('hidden');
    });

    // Hide modal
    const hideModal = () => {
        modal.classList.add('hidden');
        form.reset(); // Reset form on close
    };

    closeButton.addEventListener('click', hideModal);

    // Close modal when clicking outside
    modal.addEventListener('click', (e) => {
        if (e.target === modal) hideModal();
    });

    // Handle form submission
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = {
            categoryName: document.getElementById('categoryName').value.trim(),
            productName: document.getElementById('productName').value.trim()
        };
        
        try {
            const response = await fetch('/crud/BoutiqueInventory/add_category.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            });
            
            const result = await response.json();
            
            if (response.ok) {
                hideModal();
                // Show success message
                const successAlert = document.createElement('div');
                successAlert.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded shadow-lg';
                successAlert.textContent = result.message;
                document.body.appendChild(successAlert);
                
                setTimeout(() => {
                    successAlert.remove();
                    window.location.reload();
                }, 2000);
            } else {
                throw new Error(result.error);
            }
        } catch (error) {
            console.error('Error:', error);
            // Show error message
            const errorAlert = document.createElement('div');
            errorAlert.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded shadow-lg';
            errorAlert.textContent = error.message;
            document.body.appendChild(errorAlert);
            
            setTimeout(() => errorAlert.remove(), 3000);
        }
    });
});
