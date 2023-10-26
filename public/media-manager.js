var mediaManagerSelector = null;
var mediaManagerCallback = null;
var mediaManagerOfflineData = [
    {
        name: "test-1",
        path: "portfolio-gallery/TUHbcGYULP7lzWg9pWLgwdsqIkuyRVjSUqRaZQEp.jpg",
    },
    {
        name: "test-2",
        path: "portfolio-gallery/P9ZqPPqeDYTaqwMeFU7OL9yezZIQmMbREYbSwzI0.jpg",
    },
    {
        name: "test-3",
        path: "portfolio-gallery/ciJ1foRpBi9QmhxqeAPN9y1UpF8YavIhMmuEVdpK.jpg",
    },
    {
        name: "test-4",
        path: "portfolio-gallery/CEm0WgUVOP6uEwperX98Zsi13u537MexIoUgHx07.jpg",
    },
    {
        name: "test-5",
        path: "portfolio-gallery/bZTfeJFjEmgP0PtxM6il2NK8FAZ6FLusRZzXNU6K.png",
    },
    {
        name: "test-6",
        path: "portfolio-gallery/XkwjdWAIoSZwagoKvctVmcIKynjA1PPfE6IJ0LQn.png",
    },
    {
        name: "test-7",
        path: "portfolio-gallery/8Y1qnDxyN4po9Msrq7qp7DCCAuqTOSV8BwJrdtDn.png",
    },
    {
        name: "test-8",
        path: "portfolio-gallery/dnz3rhr8STQX15BSSKo7nCXEzBVdxpAbpvD7pKH9.png",
    },
    {
        name: "test-9",
        path: "portfolio-gallery/UkfMPDjPJGDx1JdjnJbRCMQgoujiQbviCIqnfGCM.jpg",
    },
    {
        name: "test-10",
        path: "portfolio-gallery/FJEHw4weOuAZm3ZAHYaZFGpQYvNeuUYwK3qaZuef.jpg",
    },
];
var mediaManagerModalTitle = "Media Library";
var mediaManagerModalCloseBtnTitle = "Close";

function mediaModelDesign() {
    var modalHTML = `
                        <div class="modal fade modal-sm" id="media-manager" data-bs-keyboard="false" tabindex="-1"
                            data-bs-backdrop="static" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class=" row py-1 px-2">
                                        <div class="col-md-7">
                                            <h5 class="modal-title">${mediaManagerModalTitle}</h5>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="float-right">
                                                <input type="text" name="" id="" class="form-control"
                                                    oninput="mediaManagerSearch()" placeholder="Search item">
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="modal-body">
                                        <div class="row" id="media-manager-container">
                                            <!-- Media items will be displayed here -->
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                                ${mediaManagerModalCloseBtnTitle}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>`;
    // push to body tag
    $("body").append(modalHTML);
}

function mediaManagerOpen(callback = null, e) {
    mediaManagerSelector = event.target;
    mediaManagerCallback = callback;
    // Show the modal
    $("#media-manager").modal("show");
    // Pass the callback to mediaManagerGetFile
    mediaManagerGetFile();
}

function mediaManagerGetFile(search = null) {
    if (mediaManagerOfflineData) {
        mediaManagerSyncMedia(mediaManagerOfflineData);
        return;
    }
    $.ajax({
        url: "/get-media",
        type: "GET",
        data: {
            search: search,
        },
        success: function (response) {
            // Pass the callback to mediaManagerSyncMedia
            mediaManagerSyncMedia(response.data);
        },
    });
}

function mediaManagerSyncMedia(collection) {
    const container = document.querySelector("#media-manager-container");
    container.innerHTML = "";

    collection.forEach((item) => {
        const divElement = document.createElement("div");
        divElement.classList.add("col-md-3");
        divElement.classList.add("media-manager-image-box");

        const anchorElement = document.createElement("a");
        anchorElement.href = "javascript:void(0)";
        anchorElement.setAttribute(
            "onclick",
            `mediaManagerSelectImage("${item.path}")`
        );

        const imgElement = document.createElement("img");
        imgElement.src = item.path;
        imgElement.alt = item.name;
        const imgTitle = document.createElement("p");
        imgTitle.classList.add("my-1");
        imgTitle.classList.add("text-center");
        imgTitle.classList.add("text-muted");
        imgTitle.innerHTML = item.name;

        anchorElement.appendChild(imgElement);
        anchorElement.appendChild(imgTitle);
        divElement.appendChild(anchorElement);
        container.appendChild(divElement);
    });
}

function mediaManagerSelectImage(path) {
    $("#media-manager").modal("hide");

    // Only execute the provided callback function when an image is selected
    if (mediaManagerCallback && typeof mediaManagerCallback === "function") {
        mediaManagerCallback(path, mediaManagerSelector);
    }
}

function mediaManagerSearch() {
    const search = event.target.value;
    // mediaManagerGetFile(search);

    if (mediaManagerOfflineData) {
        const filteredData = mediaManagerOfflineData.filter((item) =>
            item.name.includes(search)
        );
        mediaManagerSyncMedia(filteredData);
        return;
    }
    mediaManagerGetFile(search);
}

function testCallback(path, selector) {
    $(selector).attr("id", path);
}
