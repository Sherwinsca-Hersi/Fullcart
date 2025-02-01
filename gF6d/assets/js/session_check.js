// document.addEventListener('DOMContentLoaded', function() {
//     console.log('logged in...');
//     function isSessionActive() {
//         return localStorage.getItem('sessionActive') === 'true';
//     }
//     window.addEventListener('popstate', function(event) {
//         if (!isSessionActive()) {
//             window.location.href = 'index.php';
//         }
//     });
// });

//default imgs-Failed Loading Images

var defaultImageSrc = '../defaultimgs/nullimg.png';

function checkAndReplaceBrokenImages(img) {
    // Create a new Image object to test loading the source
    const testImage = new Image();
    testImage.src = img.src;

    // Handle failure to load by replacing with the default image
    testImage.onload = () => {
        // console.log(`Image loaded successfully: ${img.src}`);
    };
    testImage.onerror = () => {
        // console.log(`Image load failed: ${img.src}, replacing with default.`);
        img.src = defaultImageSrc;
    };
}

// Apply the check to each image with the "watch-image" class
document.querySelectorAll("img").forEach(img => {
    checkAndReplaceBrokenImages(img);
});
