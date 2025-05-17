document.addEventListener("DOMContentLoaded", () => {
  // Attach click handlers to all delete buttons
  document.querySelectorAll(".delete-movie-btn").forEach((button) => {
    button.addEventListener("click", (e) => {
      e.preventDefault();
      const id = button.dataset.movieId;
      const name = button.dataset.movieName;
      showDeleteModal(id, name);
    });
  });

  // Initialize modal
  createDeleteModal();
});

// Create the delete confirmation modal
function createDeleteModal() {
  const modal = document.createElement("div");
  modal.id = "deleteModal";
  modal.className = "fixed inset-0 z-50 hidden overflow-y-auto";
  modal.innerHTML = `
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
      <!-- Background overlay -->
      <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-80" id="modalOverlay"></div>
      
      <!-- Modal content -->
      <div class="relative inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white dark:bg-gray-800 rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6" id="modalContent">
        <!-- Header -->
        <div class="sm:flex sm:items-start">
          <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 dark:bg-red-900 rounded-full sm:mx-0 sm:h-10 sm:w-10">
            <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-lg"></i>
          </div>
          <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100" id="modalTitle">
              Delete Movie
            </h3>
            <div class="mt-2">
              <p class="text-sm text-gray-500 dark:text-gray-400" id="modalMessage">
                Are you sure you want to delete this movie? This action cannot be undone.
              </p>
            </div>
          </div>
        </div>
        
        <!-- Movie Details Card -->
        <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600" id="movieDetails">
          <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
              <div class="w-12 h-12 bg-gradient-to-br from-pink-500 to-purple-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-film text-white text-lg"></i>
              </div>
            </div>
            <div>
              <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100" id="movieName">Movie Name</h4>
              <p class="text-xs text-gray-500 dark:text-gray-400">This movie will be permanently deleted</p>
            </div>
          </div>
        </div>
        
        <!-- Actions -->
        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse space-y-2 sm:space-y-0 sm:space-x-reverse sm:space-x-3">
          <button type="button" 
                  class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-all duration-200 transform hover:scale-105" 
                  id="confirmDeleteBtn">
            <i class="fas fa-trash mr-2"></i>
            <span id="deleteButtonText">Delete Movie</span>
            <i class="fas fa-spinner fa-spin ml-2 hidden" id="deleteSpinner"></i>
          </button>
          <button type="button" 
                  class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm transition-all duration-200" 
                  id="cancelDeleteBtn">
            <i class="fas fa-times mr-2"></i>
            Cancel
          </button>
        </div>
      </div>
    </div>
  `;

  document.body.appendChild(modal);

  // Add event listeners
  document
    .getElementById("modalOverlay")
    .addEventListener("click", hideDeleteModal);
  document
    .getElementById("cancelDeleteBtn")
    .addEventListener("click", hideDeleteModal);
  document
    .getElementById("confirmDeleteBtn")
    .addEventListener("click", confirmDelete);

  // ESC key to close modal
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && !modal.classList.contains("hidden")) {
      hideDeleteModal();
    }
  });
}

// Show delete modal with movie details
function showDeleteModal(id, name) {
  const modal = document.getElementById("deleteModal");
  const movieNameEl = document.getElementById("movieName");
  const confirmBtn = document.getElementById("confirmDeleteBtn");

  // Set movie details
  movieNameEl.textContent = name;
  confirmBtn.dataset.movieId = id;
  confirmBtn.dataset.movieName = name;

  // Show modal with animation
  modal.classList.remove("hidden");

  // Animate in
  setTimeout(() => {
    const content = document.getElementById("modalContent");
    content.style.transform = "scale(1)";
    content.style.opacity = "1";
  }, 50);
}

// Hide delete modal
function hideDeleteModal() {
  const modal = document.getElementById("deleteModal");
  const content = document.getElementById("modalContent");

  // Animate out
  content.style.transform = "scale(0.95)";
  content.style.opacity = "0";

  setTimeout(() => {
    modal.classList.add("hidden");
    // Reset button state
    resetDeleteButton();
  }, 200);
}

// Confirm delete action
function confirmDelete() {
  const confirmBtn = document.getElementById("confirmDeleteBtn");
  const id = confirmBtn.dataset.movieId;
  const name = confirmBtn.dataset.movieName;

  // Show loading state
  const deleteText = document.getElementById("deleteButtonText");
  const deleteSpinner = document.getElementById("deleteSpinner");

  confirmBtn.disabled = true;
  deleteText.textContent = "Deleting...";
  deleteSpinner.classList.remove("hidden");

  // Perform delete
  deleteMovie(id, name);
}

// Reset delete button to initial state
function resetDeleteButton() {
  const confirmBtn = document.getElementById("confirmDeleteBtn");
  const deleteText = document.getElementById("deleteButtonText");
  const deleteSpinner = document.getElementById("deleteSpinner");

  confirmBtn.disabled = false;
  deleteText.textContent = "Delete Movie";
  deleteSpinner.classList.add("hidden");
}

// Enhanced delete function with better error handling
function deleteMovie(id, name) {
  fetch("/crud/MovieList/delete_movie.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `id=${encodeURIComponent(id)}`,
  })
    .then(async (response) => {
      const data = await response.json();

      if (!response.ok) {
        throw new Error(data.error || `HTTP error! status: ${response.status}`);
      }

      return data;
    })
    .then((data) => {
      if (data.message) {
        // Hide modal first
        hideDeleteModal();

        // Show success notification
        showToast(data.message, "success");

        // Remove the movie row/card with animation
        removeMovieElement(id);

        // Update movie count if exists
        updateMovieCount();
      } else {
        throw new Error(data.error || "Unknown error occurred");
      }
    })
    .catch((error) => {
      console.error("Delete error:", error);

      // Reset button state
      resetDeleteButton();

      // Show error notification
      showToast(`Error: ${error.message}`, "error");
    });
}

// Remove movie element with animation
function removeMovieElement(movieId) {
  // Find the movie row or card
  const movieRow = document
    .querySelector(`[data-movie-id="${movieId}"]`)
    ?.closest("tr");
  const movieCard = document
    .querySelector(`[data-movie-id="${movieId}"]`)
    ?.closest(".bg-gray-50");

  const elementToRemove = movieRow || movieCard;

  if (elementToRemove) {
    // Add fade out animation
    elementToRemove.style.transition = "all 0.3s ease-out";
    elementToRemove.style.opacity = "0";
    elementToRemove.style.transform = "translateX(-100%)";

    // Remove element after animation
    setTimeout(() => {
      elementToRemove.remove();

      // Check if no movies left
      checkIfNoMoviesLeft();
    }, 300);
  }
}

// Update movie count display
function updateMovieCount() {
  const countElement = document.querySelector(
    "p[class*='mb-4'][class*='text-gray-600']"
  );
  if (countElement && countElement.textContent.includes("Found")) {
    const currentCount = parseInt(countElement.textContent.match(/\d+/)[0]) - 1;
    countElement.textContent = `Found ${currentCount} movie(s).`;
  }
}

// Check if no movies are left and show empty state
function checkIfNoMoviesLeft() {
  const movieRows = document.querySelectorAll("tbody tr");
  const movieCards = document.querySelectorAll(".md\\:hidden .bg-gray-50");

  if (movieRows.length === 0 && movieCards.length === 0) {
    showEmptyState();
  }
}

// Show empty state when no movies are found
function showEmptyState() {
  const tableContainer = document.querySelector(".hidden.md\\:block");
  const cardsContainer = document.querySelector(".md\\:hidden");

  const emptyStateHTML = `
    <div class="text-center py-12">
      <div class="mx-auto h-24 w-24 text-gray-400 mb-4">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-full h-full">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2h4a1 1 0 110 2h-1v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6H3a1 1 0 110-2h4zM6 6v12h12V6H6zm8-2V2H10v2h8z"/>
        </svg>
      </div>
      <h3 class="text-xl font-medium text-gray-900 dark:text-gray-100 mb-2">No movies found</h3>
      <p class="text-gray-500 dark:text-gray-400 mb-6">All movies have been deleted or none match your search criteria.</p>
      <a href="/movies/add" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-pink-600 to-purple-600 text-white font-semibold rounded-lg shadow-md hover:scale-105 transition-transform duration-300">
        <i class="fas fa-plus mr-2"></i>
        Add New Movie
      </a>
    </div>
  `;

  if (tableContainer) {
    tableContainer.innerHTML = emptyStateHTML;
  }
  if (cardsContainer) {
    cardsContainer.innerHTML = emptyStateHTML;
  }
}

// Enhanced toast notification system
function showToast(message, type = "success") {
  // Remove existing toasts
  const existingToasts = document.querySelectorAll(".toast-notification");
  existingToasts.forEach((toast) => toast.remove());

  const toast = document.createElement("div");
  toast.className =
    "toast-notification fixed top-4 right-4 z-50 p-4 rounded-lg shadow-xl transform transition-all duration-300 translate-x-full max-w-sm";

  const colors = {
    success: "bg-green-500 text-white border-green-600",
    error: "bg-red-500 text-white border-red-600",
    warning: "bg-yellow-500 text-black border-yellow-600",
    info: "bg-blue-500 text-white border-blue-600",
  };

  const icons = {
    success: "fas fa-check-circle",
    error: "fas fa-exclamation-circle",
    warning: "fas fa-exclamation-triangle",
    info: "fas fa-info-circle",
  };

  toast.className += ` ${colors[type]} border-l-4`;
  toast.innerHTML = `
    <div class="flex items-start space-x-3">
      <div class="flex-shrink-0">
        <i class="${icons[type]} text-lg mt-0.5"></i>
      </div>
      <div class="flex-1 min-w-0">
        <p class="text-sm font-medium leading-5">${message}</p>
      </div>
      <div class="flex-shrink-0">
        <button onclick="this.closest('.toast-notification').remove()" class="text-lg hover:opacity-75 transition-opacity duration-200">
          <i class="fas fa-times"></i>
        </button>
      </div>
    </div>
  `;

  document.body.appendChild(toast);

  // Animate in
  setTimeout(() => {
    toast.classList.remove("translate-x-full");
  }, 100);

  // Auto remove after 5 seconds
  setTimeout(() => {
    if (document.body.contains(toast)) {
      toast.classList.add("translate-x-full");
      setTimeout(() => {
        if (document.body.contains(toast)) {
          toast.remove();
        }
      }, 300);
    }
  }, 5000);
}
