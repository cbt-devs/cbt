document.addEventListener('DOMContentLoaded', function () {
  // Select all links with the 'load-content' class
  document.querySelectorAll('.load-content').forEach((link) => {
    link.addEventListener('click', function (e) {
      e.preventDefault(); // Prevent page reload

      const page = this.getAttribute('data-page'); // Get the target page
      const contentDiv = document.querySelector('.contents');

      // Load content using fetch
      fetch(page)
        .then((response) => response.text())
        .then((data) => {
          contentDiv.innerHTML = data; // Update content
        })
        .catch((error) => console.error('Error loading content:', error));
    });
  });
});
