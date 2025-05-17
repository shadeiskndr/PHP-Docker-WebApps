// Enhanced Movie List JavaScript
document.addEventListener("DOMContentLoaded", () => {
  initializeViewToggle();
  initializeSearch();
  initializeSorting();
  initializeClearFilters();
  initializeKeyboardShortcuts();
});

// View Toggle (Grid/List)
function initializeViewToggle() {
  const gridViewBtn = document.getElementById("gridViewBtn");
  const listViewBtn = document.getElementById("listViewBtn");
  const gridView = document.getElementById("gridView");
  const listView = document.getElementById("listView");

  if (!gridViewBtn || !listViewBtn) return;

  // Load saved preference
  const savedView = localStorage.getItem("movieListView") || "grid";
  if (savedView === "list") {
    switchToListView();
  }

  gridViewBtn.addEventListener("click", () => {
    switchToGridView();
    localStorage.setItem("movieListView", "grid");
  });

  listViewBtn.addEventListener("click", () => {
    switchToListView();
    localStorage.setItem("movieListView", "list");
  });

  function switchToGridView() {
    gridView.classList.remove("hidden");
    listView.classList.add("hidden");

    gridViewBtn.classList.remove("bg-gray-300", "text-gray-700");
    gridViewBtn.classList.add("bg-pink-600", "text-white");

    listViewBtn.classList.remove("bg-pink-600", "text-white");
    listViewBtn.classList.add("bg-gray-300", "text-gray-700");

    // Animate cards
    animateMovieItems();
  }

  function switchToListView() {
    gridView.classList.add("hidden");
    listView.classList.remove("hidden");

    listViewBtn.classList.remove("bg-gray-300", "text-gray-700");
    listViewBtn.classList.add("bg-pink-600", "text-white");

    gridViewBtn.classList.remove("bg-pink-600", "text-white");
    gridViewBtn.classList.add("bg-gray-300", "text-gray-700");

    // Animate table/cards
    animateMovieItems();
  }
}

// Real-time Search
function initializeSearch() {
  const searchInput = document.getElementById("quickSearch");
  const genreFilter = document.getElementById("genre");

  if (!searchInput) return;

  let searchTimeout;

  searchInput.addEventListener("input", (e) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
      performSearch(e.target.value);
    }, 300);
  });

  genreFilter.addEventListener("change", () => {
    performSearch(searchInput.value);
  });

  function performSearch(searchTerm) {
    const movieItems = document.querySelectorAll(".movie-item");
    const selectedGenre = genreFilter.value;
    let visibleCount = 0;

    movieItems.forEach((item) => {
      const movieName = item.dataset.movieName || "";
      const genre = item.dataset.genre || "";

      const matchesSearch = movieName
        .toLowerCase()
        .includes(searchTerm.toLowerCase());
      const matchesGenre =
        !selectedGenre ||
        genre ===
          document.querySelector(`option[value="${selectedGenre}"]`)
            ?.textContent;

      if (matchesSearch && matchesGenre) {
        item.style.display = "";
        item.classList.add("fade-in");
        visibleCount++;
      } else {
        item.style.display = "none";
        item.classList.remove("fade-in");
      }
    });

    updateResultsCount(visibleCount);

    if (visibleCount === 0) {
      showNoResults(searchTerm || selectedGenre);
    } else {
      hideNoResults();
    }
  }

  function updateResultsCount(count) {
    const countElement = document.querySelector("span[class*='bg-gray-100']");
    if (countElement) {
      countElement.innerHTML = `<i class="fas fa-hashtag mr-1"></i>${count} movie(s) found`;
    }
  }

  function showNoResults(searchTerm) {
    let noResultsDiv = document.getElementById("noResults");
    if (!noResultsDiv) {
      noResultsDiv = document.createElement("div");
      noResultsDiv.id = "noResults";
      noResultsDiv.className = "text-center py-12 col-span-full";
      noResultsDiv.innerHTML = `
        <div class="mx-auto h-16 w-16 text-gray-400 mb-4">
          <i class="fas fa-search text-4xl"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No movies found</h3>
        <p class="text-gray-500 dark:text-gray-400 mb-4">
          ${
            searchTerm
              ? `No movies match "${searchTerm}"`
              : "No movies match your filters"
          }
        </p>
        <button onclick="clearAllFilters()" class="px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition-colors duration-200">
          <i class="fas fa-eraser mr-2"></i>Clear Search
        </button>
      `;

      const gridView = document.getElementById("gridView");
      if (gridView) {
        gridView.appendChild(noResultsDiv);
      }
    }

    noResultsDiv.style.display = "block";
  }

  function hideNoResults() {
    const noResultsDiv = document.getElementById("noResults");
    if (noResultsDiv) {
      noResultsDiv.style.display = "none";
    }
  }
}

// Sorting functionality
function initializeSorting() {
  const sortSelect = document.getElementById("sortBy");

  if (!sortSelect) return;

  sortSelect.addEventListener("change", (e) => {
    sortMovies(e.target.value);
  });

  function sortMovies(sortBy) {
    const gridView = document.getElementById("gridView");
    const listView = document.getElementById("listView");
    const currentView = !gridView.classList.contains("hidden")
      ? gridView
      : listView;

    const movieItems = Array.from(currentView.querySelectorAll(".movie-item"));

    movieItems.sort((a, b) => {
      let aValue, bValue;

      switch (sortBy) {
        case "name":
          aValue = a.dataset.movieName;
          bValue = b.dataset.movieName;
          return aValue.localeCompare(bValue);

        case "name_desc":
          aValue = a.dataset.movieName;
          bValue = b.dataset.movieName;
          return bValue.localeCompare(aValue);

        case "year":
          aValue = parseInt(a.dataset.year);
          bValue = parseInt(b.dataset.year);
          return aValue - bValue;

        case "year_desc":
          aValue = parseInt(a.dataset.year);
          bValue = parseInt(b.dataset.year);
          return bValue - aValue;

        case "rating":
          aValue = parseInt(a.dataset.rating);
          bValue = parseInt(b.dataset.rating);
          return aValue - bValue;

        case "rating_desc":
          aValue = parseInt(a.dataset.rating);
          bValue = parseInt(b.dataset.rating);
          return bValue - aValue;

        case "price":
          aValue = parseFloat(a.dataset.price);
          bValue = parseFloat(b.dataset.price);
          return aValue - bValue;

        case "price_desc":
          aValue = parseFloat(a.dataset.price);
          bValue = parseFloat(b.dataset.price);
          return bValue - aValue;

        default:
          return 0;
      }
    });

    // Re-append sorted items
    const container =
      currentView === gridView
        ? gridView
        : currentView.querySelector("tbody") || currentView;
    movieItems.forEach((item) => {
      container.appendChild(item);
    });

    // Animate the sorting
    animateMovieItems();

    showToast(`Movies sorted by ${getSortLabel(sortBy)}`, "info");
  }

  function getSortLabel(sortBy) {
    const labels = {
      name: "Name (A-Z)",
      name_desc: "Name (Z-A)",
      year: "Year (Oldest First)",
      year_desc: "Year (Newest First)",
      rating: "Rating (Low to High)",
      rating_desc: "Rating (High to Low)",
      price: "Price (Low to High)",
      price_desc: "Price (High to Low)",
    };
    return labels[sortBy] || sortBy;
  }
}

// Clear Filters
function initializeClearFilters() {
  const clearBtn = document.getElementById("clearFilters");

  if (clearBtn) {
    clearBtn.addEventListener("click", clearAllFilters);
  }
}

// Global function for clearing filters
function clearAllFilters() {
  const searchInput = document.getElementById("quickSearch");
  const genreFilter = document.getElementById("genre");
  const sortSelect = document.getElementById("sortBy");

  if (searchInput) searchInput.value = "";
  if (genreFilter) genreFilter.value = "";
  if (sortSelect) sortSelect.value = "name";

  // Show all movies
  const movieItems = document.querySelectorAll(".movie-item");
  movieItems.forEach((item) => {
    item.style.display = "";
    item.classList.add("fade-in");
  });

  // Hide no results message
  const noResultsDiv = document.getElementById("noResults");
  if (noResultsDiv) {
    noResultsDiv.style.display = "none";
  }

  // Update count
  const countElement = document.querySelector("span[class*='bg-gray-100']");
  if (countElement) {
    countElement.innerHTML = `<i class="fas fa-hashtag mr-1"></i>${movieItems.length} movie(s) found`;
  }

  showToast("Filters cleared", "info");
}

// Animation helpers
function animateMovieItems() {
  const movieItems = document.querySelectorAll(
    ".movie-item:not([style*='display: none'])"
  );

  movieItems.forEach((item, index) => {
    item.style.opacity = "0";
    item.style.transform = "translateY(20px)";

    setTimeout(() => {
      item.style.transition = "all 0.3s ease-out";
      item.style.opacity = "1";
      item.style.transform = "translateY(0)";
    }, index * 50);
  });
}

// Keyboard shortcuts
function initializeKeyboardShortcuts() {
  document.addEventListener("keydown", (e) => {
    // Ctrl/Cmd + F to focus search
    if ((e.ctrlKey || e.metaKey) && e.key === "f") {
      e.preventDefault();
      const searchInput = document.getElementById("quickSearch");
      if (searchInput) {
        searchInput.focus();
        searchInput.select();
      }
    }

    // Ctrl/Cmd + G to toggle grid view
    if ((e.ctrlKey || e.metaKey) && e.key === "g") {
      e.preventDefault();
      const gridViewBtn = document.getElementById("gridViewBtn");
      if (gridViewBtn) {
        gridViewBtn.click();
      }
    }

    // Ctrl/Cmd + L to toggle list view
    if ((e.ctrlKey || e.metaKey) && e.key === "l") {
      e.preventDefault();
      const listViewBtn = document.getElementById("listViewBtn");
      if (listViewBtn) {
        listViewBtn.click();
      }
    }

    // Escape to clear search
    if (e.key === "Escape") {
      const searchInput = document.getElementById("quickSearch");
      if (searchInput && searchInput === document.activeElement) {
        searchInput.blur();
      }
      clearAllFilters();
    }
  });
}

// Enhanced toast notification (reuse from movieForm.js if available)
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

  // Auto remove after 3 seconds for info/success toasts
  if (type === "info" || type === "success") {
    setTimeout(() => {
      if (document.body.contains(toast)) {
        toast.classList.add("translate-x-full");
        setTimeout(() => {
          if (document.body.contains(toast)) {
            toast.remove();
          }
        }, 300);
      }
    }, 3000);
  }
}

// Add CSS for fade-in animation
const style = document.createElement("style");
style.textContent = `
  .fade-in {
    animation: fadeIn 0.3s ease-out;
  }
  
  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  
  .line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
  
  .movie-card:hover {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
  }
  
  @media (prefers-reduced-motion: reduce) {
    .fade-in,
    .movie-card {
      animation: none !important;
      transition: none !important;
    }
  }
`;
document.head.appendChild(style);
