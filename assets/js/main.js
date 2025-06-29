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

var validate = {
  requiredfields: function (fields) {
    let isValid = true;
    console.log(fields);

    fields.forEach(({ element, message }) => {
      let elements = Array.isArray(element) ? element : [element]; // allow single or multiple

      const anyFilled = elements.some((el) => {
        const value = el.value.trim();
        return el.tagName === 'SELECT'
          ? value !== '' && value !== '0'
          : value !== '';
      });

      if (!anyFilled) {
        this.showToolTip(elements[0], message); // show tooltip on the first one
        isValid = false;
      }
    });

    return isValid;
  },
  showToolTip: function (el, message) {
    el.setAttribute('data-bs-toggle', 'tooltip');
    el.setAttribute('data-bs-placement', 'top');
    el.setAttribute('data-bs-html', 'true');
    el.setAttribute(
      'title',
      `
      <div style='text-align: left; font-size: 0.9rem; padding-left: 10px;'>
          <strong>Missing Field:</strong><br>${message}
      </div>`
    );

    const tooltip = new bootstrap.Tooltip(el);
    tooltip.show();

    setTimeout(() => {
      tooltip.dispose();
      el.removeAttribute('data-bs-toggle');
      el.removeAttribute('title');
    }, 3000);
  },
};
