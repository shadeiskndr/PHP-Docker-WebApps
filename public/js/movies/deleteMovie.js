document.addEventListener("DOMContentLoaded", () => {
  // Attach click handlers to all delete buttons
  document.querySelectorAll(".delete-movie-btn").forEach((button) => {
    button.addEventListener("click", () => {
      const id = button.dataset.movieId;
      const name = button.dataset.movieName;
      deleteMovie(id, name);
    });
  });
});

function deleteMovie(id, name) {
  if (confirm(`Are you sure you want to delete the movie "${name}"?`)) {
    fetch("/crud/delete_movie.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `id=${id}`,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.message) {
          const successAlert = document.createElement("div");
          successAlert.className =
            "fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50";
          successAlert.textContent = data.message;
          document.body.appendChild(successAlert);

          setTimeout(() => {
            successAlert.remove();
            window.location.reload();
          }, 2000);
        } else {
          throw new Error(data.error);
        }
      })
      .catch((error) => {
        const errorAlert = document.createElement("div");
        errorAlert.className =
          "fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded shadow-lg z-50";
        errorAlert.textContent = error.message;
        document.body.appendChild(errorAlert);

        setTimeout(() => errorAlert.remove(), 3000);
      });
  }
}
