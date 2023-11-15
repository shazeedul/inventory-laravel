// init tooltip
$(document).ready(function () {
    ("use strict"); // Start of use strict

    // init tooltip
    $("[data-bs-toggle=tooltip]").tooltip();
    // init validation
    // $(".needs-validation").validate();
    axiosModelInit();
    $(".needs-validation").valid();

    $(document).on(
        "click focus",
        ".form-number-input:not(.focussed)",
        function () {
            var $inp = $(this),
                formNumberInpOldVal = $inp.val();

            $inp.select()
                .addClass("focussed")
                .unbind("blur.numberInputTriggerReset");

            $inp.on("blur.numberInputTriggerReset", function (event2) {
                if (!$inp.val()) {
                    $inp.val(formNumberInpOldVal || 0).trigger("change");
                }
                $inp.removeClass("focussed");
            });
        }
    );
});

/**
 * Show axios errors to toastr
 * @param {*} error
 */
function showAxiosErrors(error) {
    if (
        typeof error.response !== "undefined" &&
        typeof error.response.data !== "undefined"
    ) {
        if (typeof error.response.data.errors !== "undefined") {
            $.each(error.response.data.errors, function (key, value) {
                toastr.error(value);
            });
        } else {
            if (typeof error.response.data.message !== "undefined")
                toastr.error(error.response.data.message);
            if (typeof error.response.data.errors.message !== "undefined")
                toastr.error(error.response.data.errors.message);
            if (typeof error.response.data.data !== "undefined")
                toastr.error(error.response.data.data);
        }
    } else {
        toastr.error("Something went wrong");
    }
}

function copySummernoteContentToClipboard(editorId, type = "text") {
    // Get the HTML content of the Summernote editor
    var content = $(editorId)
        .summernote("code")
        .replace(/<\/?[^>]+(>|$)/g, "");
    console.log(content);
    // Create a new textarea element to copy the content to clipboard
    var $temp = $("<textarea>");
    $("body").append($temp);

    // Set the textarea value to the HTML content
    $temp.val(content).select();

    // Copy the content to clipboard using the Clipboard API
    navigator.clipboard
        .writeText(content)
        .then(function () {
            console.log("Content copied to clipboard");
        })
        .catch(function (error) {
            console.error("Failed to copy content: ", error);
        })
        .finally(function () {
            // Remove the temporary textarea element
            $temp.remove();
        });
}

/**
 * create a preloader for ajax request
 */
function ajaxProcess() {
    // soft  show preloader
    $("#preloader").show();
    $("#preloader").removeClass("hide");
}

/**
 * hide preloader
 *
 * */
function ajaxComplete() {
    setTimeout(function () {
        $("#preloader").addClass("hide");
        $("#preloader").hide();
    }, 300);
}

/**
 * submit form with ajax
 * @param object e
 * @param function callback default null
 * @param object validationOptions default null
 *
 */

function submitFormAxios(e, callback = null, validationOptions = null) {
    // prevent default form submit
    e.preventDefault();
    // create a custom event
    const submitFormAxiosCall = new Event("submitFormAxiosCall");
    // Fire the event
    window.dispatchEvent(submitFormAxiosCall);
    // get the form element
    var form = $(e.target);
    // check if form is valid
    if (form.valid(validationOptions)) {
        // disiable submit button
        form.find("button[type=submit]").attr("disabled", true);
        // Now you can access form attributes and serialize data
        var action = form.attr("action");
        var method = form.attr("method");
        var enctype = form.attr("enctype");
        var data = new FormData(form[0]);
        // now call axios using action, method, data
        axios({
            method: method,
            url: action,
            data: data,
            headers: {
                "Content-Type": enctype,
            },
        })
            .then(function (response) {
                // enable submit button
                form.find("button[type=submit]").attr("disabled", false);
                // create a custom event
                const submitFormAxiosSuccess = new Event(
                    "submitFormAxiosSuccess"
                );
                // Fire the event
                window.dispatchEvent(submitFormAxiosSuccess);
                // check if message is set
                if (typeof response.data.message !== "undefined") {
                    toastr.success(response.data.message);
                }
                // check if redirect is set
                if (typeof response.data.redirect !== "undefined") {
                    window.location.href = response.data.redirect;
                }
                // check if reload is set
                if (typeof response.data.reload !== "undefined") {
                    window.location.reload();
                }
                var table = $("#page-axios-data").data("table-id");
                if (table !== null) {
                    try {
                        $(table).DataTable().ajax.reload(null, false);
                    } catch (e) {
                        console.log(e);
                    }
                }
                // check if callback is set and call callback function
                if (callback !== null) {
                    callback(response);
                } else {
                    // close modal
                    $("#ajaxModal").modal("hide");
                }
            })
            .catch(function (error) {
                // enable submit button
                form.find("button[type=submit]").attr("disabled", false);
                // show toastr error
                showAxiosErrors(error);
                // create a custom event
                const submitFormAxiosError = new Event("submitFormAxiosError");
                // Fire the event
                window.dispatchEvent(submitFormAxiosError);
            });
    }
}

function axiosModelInit() {
    var modal = `<div class="modal fade " id="ajaxModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-fullscreen-sm-down">
                        <div class="modal-content"></div>
                    </div>
                </div>`;

    // push to body
    $("body").append(modal);
}

/**
 * Showing axios modal
 * @param string url
 * @param string method default GET
 * @param object data default null
 * @param function callback default null
 * @param string modalSize default modal-xl
 * @returns void
 *
 */
function axiosModal(
    url,
    method = "GET",
    data = null,
    callback = null,
    modalSize = "modal-xl"
) {
    const axiosModalCall = new Event("axiosModalCall");
    // Fire the event
    window.dispatchEvent(axiosModalCall);
    // check methos is function
    if (typeof method === "function") {
        callback = method;
        method = "GET";
    } else if (typeof method === "object") {
        data = method;
        method = "GET";
    } else if (typeof data === "function") {
        callback = data;
        data = null;
    }

    axios({
        method: method,
        url: url,
        data: data,
    })
        .then(function (response) {
            // find modal
            var ajaxModal = $("#ajaxModal");
            // set modal size and remove other classes
            ajaxModal
                .find(".modal-dialog")
                .removeClass()
                .addClass("modal-dialog")
                .addClass("modal-fullscreen-sm-down");
            ajaxModal.find(".modal-dialog").addClass(modalSize);
            // find modal body
            var modalContent = ajaxModal.find(".modal-content");
            // empty modal body
            modalContent.empty();
            // set modal body content
            modalContent.html(response.data);
            // create a custom event
            const axiosModalSuccess = new Event("axiosModalSuccess");
            // Fire the event
            window.dispatchEvent(axiosModalSuccess);
            // show modal
            $("#ajaxModal").modal("show");
            if (callback !== null && typeof callback === "function") {
                callback(response);
            }
        })
        .catch(function (error) {
            // show toastr error
            showAxiosErrors(error);
            $("#ajaxModal").modal("hide");
            // create a custom event
            const axiosModalError = new Event("axiosModalError");
            // Fire the event
            window.dispatchEvent(axiosModalError);
        });
}

/**
 * Reload Page
 *
 */
function reloadPage() {
    window.location.reload();
}
