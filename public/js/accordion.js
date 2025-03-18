document.addEventListener("DOMContentLoaded", function () {
  const accordionButtons = document.querySelectorAll(".accordion-btn");

  // Function to get accordion state from localStorage
  function getAccordionState() {
    const state = localStorage.getItem("accordionState");
    return state ? JSON.parse(state) : {};
  }

  // Function to save accordion state to localStorage
  function saveAccordionState(accordionId, isOpen) {
    const state = getAccordionState();
    state[accordionId] = isOpen;
    localStorage.setItem("accordionState", JSON.stringify(state));
  }

  // Function to get accordion ID from button
  function getAccordionId(button) {
    const text = button.querySelector("span").textContent.trim();
    return text.toLowerCase().replace(/\s+/g, "-");
  }

  // Initialize accordions based on saved state or current URL
  function initializeAccordions() {
    const savedState = getAccordionState();
    const currentPath = window.location.pathname;

    accordionButtons.forEach((button) => {
      const content = button.nextElementSibling;
      const icon = button.querySelector(".fa-chevron-down");
      const accordionId = getAccordionId(button);

      let shouldOpen = false;

      // Check if state is saved in localStorage
      if (savedState.hasOwnProperty(accordionId)) {
        shouldOpen = savedState[accordionId];
      } else {
        // Auto-open based on current URL
        const links = content.querySelectorAll("a");
        shouldOpen = Array.from(links).some((link) => {
          const linkPath = new URL(link.href).pathname;
          return currentPath === linkPath || currentPath.startsWith(linkPath);
        });
      }

      if (shouldOpen) {
        content.classList.remove("hidden");
        icon.style.transform = "rotate(180deg)";
        saveAccordionState(accordionId, true);
      }
    });
  }

  // Add click event listeners
  accordionButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const content = button.nextElementSibling;
      const icon = button.querySelector(".fa-chevron-down");
      const accordionId = getAccordionId(button);

      // Toggle content
      const isOpening = content.classList.contains("hidden");
      content.classList.toggle("hidden");

      // Rotate icon
      icon.style.transform = isOpening ? "rotate(180deg)" : "rotate(0deg)";

      // Save state
      saveAccordionState(accordionId, isOpening);
    });
  });

  // Initialize on page load
  initializeAccordions();
});
