window.addEventListener("axiosModalSuccess", function (e) {
    $("#group").select2({
        dropdownParent: $("#ajaxModal"),
        tags: true,
    });
});
