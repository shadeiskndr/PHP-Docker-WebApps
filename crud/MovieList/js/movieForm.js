// Enhanced Movie Form JavaScript
document.addEventListener("DOMContentLoaded", () => {
  initializeStarRating();
  initializeFormValidation();
  initializeFormProgress();
  initializeTooltips();
});

// Star Rating System
function initializeStarRating() {
  const starBtns = document.querySelectorAll(".star-btn");
  const ratingInput = document.getElementById("rating");
  const ratingText = document.getElementById("rating-text");

  let currentRating = parseInt(ratingInput.value) || 0;

  // Set initial state
  updateStars(currentRating);

  starBtns.forEach((btn, index) => {
    const rating = index + 1;

    btn.addEventListener("mouseenter", () => {
      updateStars(rating, true);
    });

    btn.addEventListener("mouseleave", () => {
      updateStars(currentRating);
    });

    btn.addEventListener("click", () => {
      currentRating = rating;
      ratingInput.value = rating;
      updateStars(rating);
      updateRatingText(rating);

      // Add success animation
      btn.style.transform = "scale(1.2)";
      setTimeout(() => {
        btn.style.transform = "scale(1)";
      }, 150);
    });
  });

  function updateStars(rating, isHover = false) {
    starBtns.forEach((btn, index) => {
      const star = btn.querySelector("i");
      if (index < rating) {
        star.className = "fas fa-star";
        btn.className = `star-btn text-2xl text-yellow-400 ${
          isHover ? "scale-110" : ""
        } transition-all duration-200 focus:outline-none`;
      } else {
        star.className = "fas fa-star";
        btn.className = `star-btn text-2xl text-gray-300 hover:text-yellow-400 ${
          isHover ? "scale-105" : ""
        } transition-all duration-200 focus:outline-none`;
      }
    });
  }

  function updateRatingText(rating) {
    const ratingTexts = {
      1: "Poor",
      2: "Fair",
      3: "Good",
      4: "Very Good",
      5: "Excellent",
    };
    ratingText.textContent = `${rating} Star${rating > 1 ? "s" : ""} - ${
      ratingTexts[rating]
    }`;
    ratingText.className =
      "ml-2 text-sm text-yellow-600 dark:text-yellow-400 font-medium";
  }
}

// Form Validation
function initializeFormValidation() {
  const form = document.getElementById("movieForm");
  const inputs = form.querySelectorAll("input, select");

  inputs.forEach((input) => {
    input.addEventListener("blur", () => validateField(input));
    input.addEventListener("input", () => {
      if (input.classList.contains("border-red-500")) {
        validateField(input);
      }
    });
  });

  form.addEventListener("submit", (e) => {
    let isValid = true;
    inputs.forEach((input) => {
      if (input.hasAttribute("required") && !validateField(input)) {
        isValid = false;
      }
    });

    if (!isValid) {
      e.preventDefault();
      showToast("Please fix the errors before submitting", "error");
      return;
    }

    // Show loading state
    const submitBtn = document.getElementById("submitBtn");
    const submitText = document.getElementById("submitBtnText");
    const submitSpinner = document.getElementById("submitSpinner");

    submitBtn.disabled = true;
    submitText.textContent = "Adding Movie...";
    submitSpinner.classList.remove("hidden");
  });

  function validateField(field) {
    const value = field.value.trim();
    let isValid = true;
    let errorMessage = "";

    // Clear previous validation styles
    field.classList.remove("border-red-500", "border-green-500");

    // Check required fields
    if (field.hasAttribute("required") && !value) {
      isValid = false;
      errorMessage = "This field is required";
    }

    // Specific validations
    switch (field.name) {
      case "year":
        const year = parseInt(value);
        const currentYear = new Date().getFullYear();
        if (value && (year < 1900 || year > currentYear + 5)) {
          isValid = false;
          errorMessage = `Year must be between 1900 and ${currentYear + 5}`;
        }
        break;

      case "ticket_price":
        const price = parseFloat(value);
        if (value && (price <= 0 || isNaN(price))) {
          isValid = false;
          errorMessage = "Price must be a positive number";
        }
        break;

      case "name":
        if (value && value.length < 2) {
          isValid = false;
          errorMessage = "Movie name must be at least 2 characters";
        }
        break;
    }

    // Apply validation styles
    if (!isValid) {
      field.classList.add("border-red-500");
      showFieldError(field, errorMessage);
    } else if (value) {
      field.classList.add("border-green-500");
      hideFieldError(field);

      // Show success check
      const checkIcon = field.parentElement.querySelector(
        `#${field.name}-check`
      );
      if (checkIcon) {
        checkIcon.classList.remove("hidden");
      }
    }

    return isValid;
  }

  function showFieldError(field, message) {
    // Remove existing error message
    hideFieldError(field);

    const errorDiv = document.createElement("div");
    errorDiv.className = "text-red-500 text-xs mt-1 error-message";
    errorDiv.textContent = message;
    field.parentElement.appendChild(errorDiv);

    // Hide success check
    const checkIcon = field.parentElement.querySelector(`#${field.name}-check`);
    if (checkIcon) {
      checkIcon.classList.add("hidden");
    }
  }

  function hideFieldError(field) {
    const errorMsg = field.parentElement.querySelector(".error-message");
    if (errorMsg) {
      errorMsg.remove();
    }
  }
}

// Form Progress Indicator
function initializeFormProgress() {
  const inputs = document.querySelectorAll(
    "#movieForm input, #movieForm select"
  );
  const progressBar = document.querySelector(".bg-gradient-to-r");

  inputs.forEach((input) => {
    input.addEventListener("input", updateProgress);
    input.addEventListener("change", updateProgress);
  });

  function updateProgress() {
    const requiredInputs = Array.from(inputs).filter((input) =>
      input.hasAttribute("required")
    );
    const filledInputs = requiredInputs.filter(
      (input) => input.value.trim() !== ""
    );
    const progress = (filledInputs.length / requiredInputs.length) * 100;

    progressBar.style.width = `${progress}%`;

    // Change color based on progress
    if (progress < 33) {
      progressBar.className =
        "bg-gradient-to-r from-red-500 to-red-600 h-2 rounded-full transition-all duration-300";
    } else if (progress < 66) {
      progressBar.className =
        "bg-gradient-to-r from-yellow-500 to-orange-500 h-2 rounded-full transition-all duration-300";
    } else if (progress < 100) {
      progressBar.className =
        "bg-gradient-to-r from-blue-500 to-indigo-500 h-2 rounded-full transition-all duration-300";
    } else {
      progressBar.className =
        "bg-gradient-to-r from-green-500 to-emerald-500 h-2 rounded-full transition-all duration-300";
    }
  }

  // Initial progress calculation
  updateProgress();
}

// Enhanced Toast Notifications
function showToast(message, type = "success") {
  // Remove existing toasts
  const existingToasts = document.querySelectorAll(".toast-notification");
  existingToasts.forEach((toast) => toast.remove());

  const toast = document.createElement("div");
  toast.className = `toast-notification fixed top-4 right-4 z-50 p-4 rounded-lg shadow-xl transform transition-all duration-300 translate-x-full`;

  const colors = {
    success: "bg-green-500 text-white",
    error: "bg-red-500 text-white",
    warning: "bg-yellow-500 text-black",
    info: "bg-blue-500 text-white",
  };

  const icons = {
    success: "fas fa-check-circle",
    error: "fas fa-exclamation-circle",
    warning: "fas fa-exclamation-triangle",
    info: "fas fa-info-circle",
  };

  toast.className += ` ${colors[type]}`;
  toast.innerHTML = `
    <div class="flex items-center space-x-3">
      <i class="${icons[type]} text-lg"></i>
      <span class="font-medium">${message}</span>
      <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-lg hover:opacity-75">
        <i class="fas fa-times"></i>
      </button>
    </div>
  `;

  document.body.appendChild(toast);

  // Animate in
  setTimeout(() => {
    toast.classList.remove("translate-x-full");
  }, 100);

  // Auto remove after 5 seconds
  setTimeout(() => {
    toast.classList.add("translate-x-full");
    setTimeout(() => toast.remove(), 300);
  }, 5000);
}

// Initialize Tooltips
function initializeTooltips() {
  const elements = document.querySelectorAll("[data-tooltip]");

  elements.forEach((element) => {
    element.addEventListener("mouseenter", showTooltip);
    element.addEventListener("mouseleave", hideTooltip);
  });

  function showTooltip(e) {
    const tooltipText = e.target.dataset.tooltip;
    const tooltip = document.createElement("div");
    tooltip.className =
      "absolute z-50 px-3 py-2 text-sm text-white bg-gray-900 rounded-lg shadow-lg tooltip";
    tooltip.textContent = tooltipText;

    document.body.appendChild(tooltip);

    const rect = e.target.getBoundingClientRect();
    tooltip.style.left = `${
      rect.left + rect.width / 2 - tooltip.offsetWidth / 2
    }px`;
    tooltip.style.top = `${rect.top - tooltip.offsetHeight - 8}px`;
  }

  function hideTooltip() {
    const tooltip = document.querySelector(".tooltip");
    if (tooltip) {
      tooltip.remove();
    }
  }
}

// Auto-save functionality (optional)
function initializeAutoSave() {
  const form = document.getElementById("movieForm");
  const inputs = form.querySelectorAll("input, select");

  inputs.forEach((input) => {
    input.addEventListener(
      "input",
      debounce(() => {
        saveFormData();
      }, 1000)
    );
  });

  function saveFormData() {
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);
    localStorage.setItem("movieFormDraft", JSON.stringify(data));

    showToast("Draft saved", "info");
  }

  function loadFormData() {
    const savedData = localStorage.getItem("movieFormDraft");
    if (savedData) {
      const data = JSON.parse(savedData);
      Object.keys(data).forEach((key) => {
        const input = form.querySelector(`[name="${key}"]`);
        if (input && data[key]) {
          input.value = data[key];
        }
      });
    }
  }

  // Load saved data on page load
  loadFormData();
}

// Utility function for debouncing
function debounce(func, wait) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}
