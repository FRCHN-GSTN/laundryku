document.addEventListener('DOMContentLoaded', function() {
  // Tracking form handler
  var trackingForm = document.getElementById('tracking-form');
  if (trackingForm) {
    trackingForm.addEventListener('submit', function(e) {
      e.preventDefault();
      alert('Pesanan #LND-9824 terverifikasi: Cucian sedang dalam tahap Setrika Uap & Quality Control.');
    });
  }
});
