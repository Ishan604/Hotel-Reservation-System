const bookingForm = document.getElementById('bookingForm');

bookingForm.addEventListener('submit', function (e) {
  const checkIn = bookingForm.checkIn.value;
  const checkOut = bookingForm.checkOut.value;

  if (checkIn >= checkOut) {
    alert('Check-Out date must be after Check-In date.');
    e.preventDefault();
  } else {
    alert('Your reservation has been submitted successfully!');
  }
});

const roomCards = document.querySelector('.room-cards');
const prevBtn = document.querySelector('.prev-btn');
const nextBtn = document.querySelector('.next-btn');

let currentIndex = 0;
const slideWidth = 320; // Room card width + gap
const totalCards = roomCards.children.length;
const maxIndex = totalCards - 3; // Show only 3 slides at a time

// Move the slide to the left when clicking the "prev" button
prevBtn.addEventListener('click', () => {
  currentIndex = Math.max(currentIndex - 1, 0);
  roomCards.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
});

// Move the slide to the right when clicking the "next" button
nextBtn.addEventListener('click', () => {
  currentIndex = Math.min(currentIndex + 1, maxIndex);
  roomCards.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
});


