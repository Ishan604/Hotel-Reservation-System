// Simple form validation for reservation form (example)

const reservationForm = document.getElementById('reservationForm');

reservationForm.addEventListener('submit', (e) => {
  const checkIn = reservationForm.checkIn.value;
  const checkOut = reservationForm.checkOut.value;
  if (checkIn >= checkOut) {
    alert('Check-Out date must be after Check-In date.');
    e.preventDefault();
  }
});

// JavaScript for navbar scroll effect
window.addEventListener('scroll', function() {
  const navbar = document.querySelector('.navbar');
  if (window.scrollY > 0) {
    navbar.classList.add('scrolled');
  } else {
    navbar.classList.remove('scrolled');
  }
});