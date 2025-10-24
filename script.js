// script.js - validasi realtime, UX lebih baik, dan konfirmasi sebelum submit
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('bookingForm');
  if (!form) return;

  // Elemen
  const inputs = {
    name: form.querySelector('#name'),
    email: form.querySelector('#email'),
    from: form.querySelector('#from'),
    to: form.querySelector('#to'),
    date: form.querySelector('#date'),
    passengers: form.querySelector('#passengers'),
    class: form.querySelector('#class')
  };
  const errors = {
    name: document.getElementById('err-name'),
    email: document.getElementById('err-email'),
    from: document.getElementById('err-from'),
    to: document.getElementById('err-to'),
    date: document.getElementById('err-date'),
    passengers: document.getElementById('err-passengers'),
    class: document.getElementById('err-class')
  };
  const previewBtn = document.getElementById('previewBtn');
  const status = document.getElementById('status');

  // Modal
  const modal = document.getElementById('confirmModal');
  const confirmBody = document.getElementById('confirmBody');
  const confirmSubmit = document.getElementById('confirmSubmit');
  const confirmCancel = document.getElementById('confirmCancel');

  // Validasi helper
  function setError(field, message) {
    errors[field].textContent = message || '';
    if (message) {
      errors[field].classList.add('visible');
    } else {
      errors[field].classList.remove('visible');
    }
    updateSubmitState();
  }

  function validateName() {
    const v = inputs.name.value.trim();
    if (!v) return setError('name', 'Nama harus diisi.');
    if (v.length < 2) return setError('name', 'Nama terlalu pendek.');
    setError('name', '');
  }

  function validateEmail() {
    const v = inputs.email.value.trim();
    if (!v) return setError('email', 'Email harus diisi.');
    const re = /^\S+@\S+\.\S+$/;
    if (!re.test(v)) return setError('email', 'Format email tidak valid.');
    setError('email', '');
  }

  function validateFrom() {
    const v = inputs.from.value.trim();
    if (!v) return setError('from', 'Asal harus diisi.');
    setError('from', '');
  }

  function validateTo() {
    const v = inputs.to.value.trim();
    if (!v) return setError('to', 'Tujuan harus diisi.');
    if (v === inputs.from.value.trim()) return setError('to', 'Tujuan tidak boleh sama dengan asal.');
    setError('to', '');
  }

  function validateDate() {
    const v = inputs.date.value;
    if (!v) return setError('date', 'Tanggal harus dipilih.');
    const selected = new Date(v);
    const today = new Date();
    today.setHours(0,0,0,0);
    if (selected < today) return setError('date', 'Tanggal tidak boleh di masa lalu.');
    setError('date', '');
  }

  function validatePassengers() {
    const v = Number(inputs.passengers.value);
    if (!Number.isInteger(v) || v < 1) return setError('passengers', 'Jumlah penumpang minimal 1.');
    if (v > 20) return setError('passengers', 'Maksimal demo: 20 penumpang.');
    setError('passengers', '');
  }

  function validateClass() {
    const v = inputs.class.value;
    if (!v) return setError('class', 'Pilih kelas.');
    setError('class', '');
  }

  // Attach events
  inputs.name.addEventListener('input', validateName);
  inputs.email.addEventListener('input', validateEmail);
  inputs.from.addEventListener('input', validateFrom);
  inputs.to.addEventListener('input', validateTo);
  inputs.date.addEventListener('change', validateDate);
  inputs.passengers.addEventListener('input', validatePassengers);
  inputs.class.addEventListener('change', validateClass);

  // Enable/disable preview button
  function allValid() {
    // run validators to ensure errors objects up-to-date
    validateName(); validateEmail(); validateFrom(); validateTo(); validateDate(); validatePassengers(); validateClass();
    return Object.values(errors).every(el => el.textContent === '');
  }

  function updateSubmitState() {
    previewBtn.disabled = !allValid();
  }

  // Show confirmation modal with a summary
  function showConfirm() {
    confirmBody.innerHTML = '';
    const frag = document.createDocumentFragment();
    const fields = [
      ['Nama', inputs.name.value.trim()],
      ['Email', inputs.email.value.trim()],
      ['Dari', inputs.from.value.trim()],
      ['Ke', inputs.to.value.trim()],
      ['Tanggal', inputs.date.value],
      ['Penumpang', inputs.passengers.value],
      ['Kelas', inputs.class.value]
    ];
    fields.forEach(([label, value]) => {
      const p = document.createElement('p');
      p.innerHTML = '<strong>' + label + ':</strong> ' + (value || '-');
      frag.appendChild(p);
    });
    confirmBody.appendChild(frag);
    openModal();
  }

  function openModal() {
    modal.setAttribute('aria-hidden', 'false');
    // set focus to first button
    confirmSubmit.focus();
    // trap focus simple: listen for focusout if needed (omitted for simplicity)
  }

  function closeModal() {
    modal.setAttribute('aria-hidden', 'true');
    previewBtn.focus();
  }

  previewBtn.addEventListener('click', function () {
    status.textContent = '';
    showConfirm();
  });

  confirmCancel.addEventListener('click', function (ev) {
    ev.preventDefault();
    closeModal();
  });

  // On confirm, submit the form (normal POST)
  confirmSubmit.addEventListener('click', function (ev) {
    ev.preventDefault();
    closeModal();
    // show user feedback (disabled button)
    previewBtn.textContent = 'Mengirim...';
    previewBtn.disabled = true;
    // small delay to show feedback then submit
    setTimeout(() => {
      form.submit();
    }, 350);
  });

  // Reset handler: clear errors and status
  form.addEventListener('reset', function () {
    setTimeout(() => {
      Object.keys(errors).forEach(k => setError(k, ''));
      status.textContent = '';
      previewBtn.textContent = 'Pesan Sekarang';
      updateSubmitState();
    }, 0);
  });

  // initial validation state
  updateSubmitState();

  // Accessibility: close modal with Esc
  document.addEventListener('keydown', function (ev) {
    if (ev.key === 'Escape' && modal.getAttribute('aria-hidden') === 'false') {
      closeModal();
    }
  });
});