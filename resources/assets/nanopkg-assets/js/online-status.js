function isOnline() {
    return window.navigator.onLine;
}
let isInitialLoad = true;

function togglePopup() {
    // Get the no-internet-popup and user-online-popup elements
    const noInternetPopup = document.getElementById("noInternetPopup");
    const userOnlinePopup = document.getElementById("userOnlinePopup");

    if (!isOnline()) {
        // Add a class to the no-internet-popup element to show it
        noInternetPopup.classList.add("show");
        isInitialLoad = false;
    } else {
        // Hide the no-internet-popup
        noInternetPopup.classList.remove("show");

        if (!isInitialLoad) {
            // Show the user-online-popup for 3 seconds
            userOnlinePopup.classList.add("show");
            setTimeout(function () {
                userOnlinePopup.classList.remove("show");
            }, 3000);
        } else {
            isInitialLoad = false;
        }
    }
}

// Check for the internet connection status and toggle the popup initially.
togglePopup();

// Listen for online/offline events to update the popup.
window.addEventListener("online", togglePopup);
window.addEventListener("offline", togglePopup);
