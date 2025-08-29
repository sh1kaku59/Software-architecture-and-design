const textTransition = document.getElementById('text-transition');
const phrases = [
    'Book Your Tickets',
    'Explore Destinations',
    'Enjoy Your Journey',
    'Safe and Convenient Travel',
    'Best Prices Guaranteed',
    '24/7 Customer Support',
    'Discover Amazing Deals',
    'Effortless Booking Process',
    'Travel with Confidence'
];
let currentIndex = 0;

// Function to automatically cycle through text phrases
function cycleText() {
    currentIndex = (currentIndex + 1) % phrases.length;
    textTransition.style.opacity = 0;
    setTimeout(() => {
        textTransition.innerHTML = `<span>${phrases[currentIndex]}</span>`;
        textTransition.style.opacity = 1;
        setTimeout(cycleText, 2000); // Change text every 2 seconds (2000 milliseconds)
    }, 500); // Wait for the transition duration before updating the text
}

// Initialize with the first text phrase and start cycling
textTransition.innerHTML = `<span>${phrases[currentIndex]}</span>`;
setTimeout(cycleText, 2000); // Start the cycling after 2 seconds (2000 milliseconds)
