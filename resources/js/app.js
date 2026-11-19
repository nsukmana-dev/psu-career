

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Builds and submits a real (non-AJAX) POST form on the fly — used by admin
// modals so per-item actions (approve/reject/etc.) stay plain server round-trips.
window.postTo = function (url, fields) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = url;
    form.style.display = 'none';

    const csrf = document.createElement('input');
    csrf.name = '_token';
    csrf.value = document.querySelector('meta[name="csrf-token"]').content;
    form.appendChild(csrf);

    Object.entries(fields || {}).forEach(([key, value]) => {
        const input = document.createElement('input');
        input.name = key;
        input.value = value;
        form.appendChild(input);
    });

    document.body.appendChild(form);
    form.submit();
};

Alpine.start();
