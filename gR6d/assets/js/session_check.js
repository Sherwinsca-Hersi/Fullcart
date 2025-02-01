document.addEventListener('DOMContentLoaded', function() {
    console.log('logged in...');
    function isSessionActive() {
        return localStorage.getItem('sessionActive') === 'true';
    }
    window.addEventListener('popstate', function(event) {
        if (!isSessionActive()) {
            window.location.href = 'index.php';
        }
    });
});


var defaultImageSrc = '../defaultimgs/nullimg.png';

function checkAndReplaceBrokenImages(img) {
    const testImage = new Image();
    testImage.src = img.src;

    testImage.onload = () => {
    };
    testImage.onerror = () => {
        img.src = defaultImageSrc;
    };
}

document.querySelectorAll("img").forEach(img => {
    checkAndReplaceBrokenImages(img);
});
