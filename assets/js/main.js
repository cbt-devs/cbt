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
          loader.init();
          contentDiv.innerHTML = data; // Update content
        })
        .catch((error) => console.error('Error loading content:', error));
    });
  });
});

var loader = {
  init: function () {
    JsLoadingOverlay.show({
      overlayBackgroundColor: '#141414',
      overlayOpacity: 0.6,
      spinnerIcon: 'square-loader',
      spinnerColor: '#0D6EFD',
      spinnerSize: '2x',
      overlayIDName: 'overlay',
      spinnerIDName: 'spinner',
      offsetX: 0,
      offsetY: 0,
      containerID: null,
      lockScroll: false,
      overlayZIndex: 9998,
      spinnerZIndex: 9999,
    });
  },
};
