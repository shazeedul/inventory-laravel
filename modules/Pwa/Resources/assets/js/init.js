if ("serviceWorker" in navigator) {
    // get service worker url from meta tag
    var serviceWorkerUrl = document
        .querySelector('meta[name="service-worker-url"]')
        .getAttribute("content");
    navigator.serviceWorker.register(serviceWorkerUrl).then(
        function (registration) {
            // Registration was successful
            console.log(
                "Laravel PWA: ServiceWorker registration successful with scope: ",
                registration.scope
            );
        },
        function (err) {
            // registration failed :(
            console.log(
                "Laravel PWA: ServiceWorker registration failed: ",
                err
            );
        }
    );
}
