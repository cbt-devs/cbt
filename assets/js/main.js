document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.load-content').forEach((link) => {
    link.addEventListener('click', function (e) {
      e.preventDefault();

      const page = this.getAttribute('data-page');
      const contentDiv = document.querySelector('.contents');

      loader.init(); // optional, your loader

      fetch(page)
        .then((response) => {
          if (!response.ok) {
            // Handle 404 or server error
            throw new Error(`HTTP error! Status: ${response.status}`);
          }
          return response.text();
        })
        .then((data) => {
          contentDiv.innerHTML = data;
          JsLoadingOverlay.hide();
        })
        .catch((error) => {
          console.error('Error loading content:', error);

          contentDiv.innerHTML = `
    <div style="
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      height: 100vh;
      text-align: center;
      padding: 2rem;
      background-color: #cbe3ff;
    ">
      <img src="assets/img/404.jpg" alt="404 Not Found" style="
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
      " />
    </div>
  `;

          JsLoadingOverlay.hide();
        });
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
