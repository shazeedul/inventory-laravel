// Initialize NProgress
NProgress.configure({ showSpinner: false });

// Function to start the loading progress bar
function startLoading() {
    NProgress.start();
}

// Function to stop the loading progress bar
function stopLoading() {
    NProgress.done();
}

// Axios request interceptor
// Uncomment the following code if you are using Axios

const axios = window.axios;

axios.interceptors.request.use(
    function (config) {
        startLoading();
        return config;
    },
    function (error) {
        return Promise.reject(error);
    }
);

axios.interceptors.response.use(
    function (response) {
        stopLoading();
        return response;
    },
    function (error) {
        stopLoading();
        return Promise.reject(error);
    }
);

// Ajax (jQuery) global event handlers
// Uncomment the following code if you are using jQuery for Ajax requests
$(document).ajaxStart(function () {
    startLoading();
});

$(document).ajaxStop(function () {
    stopLoading();
});

// You can add other global JavaScript code here

// Export the functions if needed
// export { startLoading, stopLoading }
