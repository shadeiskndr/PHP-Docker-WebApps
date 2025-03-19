/* ==========================================================================
   Media gallery helpers
   ========================================================================== */

/* --------------------------------------------------------------------------
   Lazy–loading for images
   -------------------------------------------------------------------------- */
document.addEventListener("DOMContentLoaded", () => {
  const lazyImages = Array.from(document.querySelectorAll(".lazy-load"));

  // Always add native lazy-loading hint
  lazyImages.forEach((img) => (img.loading = "lazy"));

  if ("IntersectionObserver" in window) {
    /* Modern browsers */
    const imgObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;

        const img = entry.target;
        loadImage(img);
        observer.unobserve(img);
      });
    });

    lazyImages.forEach((img) => imgObserver.observe(img));
  } else {
    /* Fallback – just load everything immediately */
    lazyImages.forEach(loadImage);
  }

  function loadImage(img) {
    if (img.dataset.src) img.src = img.dataset.src;
    img.classList.remove("lazy-load");
  }
});

/* --------------------------------------------------------------------------
   Video play overlay
   HTML structure (example):
   <div class="video-wrapper">
     <video ...></video>
     <button class="video-play-btn" onclick="playVideo(this)">▶</button>
   </div>
   -------------------------------------------------------------------------- */
function playVideo(el) {
  const wrapper = el.closest(".video-wrapper");
  const video = wrapper
    ? wrapper.querySelector("video")
    : el.previousElementSibling;

  if (!video) return;

  if (video.paused) {
    video.load();
    video.play();
    el.style.display = "none";

    // Show overlay again when video ends / is paused manually
    const showOverlay = () => (el.style.display = "");
    video.addEventListener("ended", showOverlay, { once: true });
    video.addEventListener("pause", showOverlay, { once: true });
  }
}

/* --------------------------------------------------------------------------
   Full-screen viewer modal for images / videos
   -------------------------------------------------------------------------- */
function viewFile(url, mimeType) {
  if (!mimeType) mimeType = "";

  if (!mimeType.startsWith("image/") && !mimeType.startsWith("video/")) {
    window.open(url, "_blank");
    return;
  }

  // Build overlay
  const overlay = document.createElement("div");
  overlay.className =
    "fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75 p-4";

  // Build inner container
  const container = document.createElement("div");
  container.className =
    "relative max-w-[95vw] max-h-[95vh] w-full h-full flex items-center justify-center";

  // Content
  if (mimeType.startsWith("image/")) {
    const img = document.createElement("img");
    img.src = url;
    img.className = "max-w-full max-h-full object-contain block";
    img.style.maxWidth = "100%";
    img.style.maxHeight = "100%";
    container.appendChild(img);
  } else {
    const video = document.createElement("video");
    video.controls = true;
    video.autoplay = true;
    video.className = "max-w-full max-h-full object-contain";
    const source = document.createElement("source");
    source.src = url;
    source.type = mimeType;
    video.appendChild(source);
    container.appendChild(video);
  }

  overlay.appendChild(container);
  document.body.appendChild(overlay);

  // Prevent background scroll
  const previousOverflow = document.body.style.overflow;
  document.body.style.overflow = "hidden";

  function closeModal() {
    overlay.remove();
    document.body.style.overflow = previousOverflow;
  }

  overlay.addEventListener("click", (e) => {
    // Close modal if clicking anywhere except the media content
    const mediaElement = container.querySelector("img, video");
    if (!mediaElement || !mediaElement.contains(e.target)) {
      closeModal();
    }
  });

  // Also close on Escape key
  const handleEscape = (e) => {
    if (e.key === "Escape") {
      closeModal();
      document.removeEventListener("keydown", handleEscape);
    }
  };
  document.addEventListener("keydown", handleEscape);
}

/* --------------------------------------------------------------------------
   Clipboard util
   -------------------------------------------------------------------------- */
function toAbsoluteUrl(path) {
  try {
    // path may already be absolute → the URL constructor keeps it intact
    return new URL(path, window.location.origin).href;
  } catch {
    return path; // fallback – should not happen
  }
}

function showToast(message, type = "success") {
  // Create toast element
  const toast = document.createElement("div");
  toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white shadow-lg z-50 ${
    type === "success" ? "bg-green-600" : "bg-red-600"
  }`;
  toast.textContent = message;

  document.body.appendChild(toast);

  setTimeout(() => {
    if (toast && toast.parentNode) {
      toast.remove();
    }
  }, 3000);
}

function copyToClipboard(text) {
  const url = toAbsoluteUrl(text);

  if (navigator.clipboard && navigator.clipboard.writeText) {
    navigator.clipboard
      .writeText(url)
      .then(() => {
        showToast("URL copied to clipboard!");
      })
      .catch((err) => {
        console.error("Clipboard API failed:", err);
        fallbackCopy(url);
      });
  } else {
    fallbackCopy(url);
  }

  function fallbackCopy(str) {
    try {
      const ta = document.createElement("textarea");
      ta.value = str;
      ta.style.position = "fixed";
      ta.style.opacity = "0";
      ta.style.left = "-9999px";
      document.body.appendChild(ta);
      ta.select();
      ta.setSelectionRange(0, 99999);
      const success = document.execCommand("copy");
      ta.remove();
      showToast("URL copied to clipboard!");
    } catch (err) {
      console.error("Fallback copy failed:", err);
      showToast("Failed to copy URL", "error");
    }
  }
}

/* --------------------------------------------------------------------------
   Event-delegation for clicks (CSP-safe, no inline handlers)
   -------------------------------------------------------------------------- */
document.addEventListener("click", (ev) => {
  // Copy URL
  const copyBtn = ev.target.closest(".copy-url-btn");
  if (copyBtn) {
    ev.preventDefault();
    copyToClipboard(copyBtn.dataset.url);
    return;
  }

  // Full-screen preview (image / video)
  const preview = ev.target.closest(".media-preview");
  if (preview) {
    ev.preventDefault();
    viewFile(preview.dataset.url, preview.dataset.mime);
    return;
  }

  // Play video overlay
  const playBtn = ev.target.closest(".video-play-btn");
  if (playBtn) {
    ev.preventDefault();
    playVideo(playBtn);
  }
});
