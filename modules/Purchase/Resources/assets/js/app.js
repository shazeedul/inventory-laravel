window.addEventListener("axiosModalSuccess", function (e) {
    $("#supplier_id").select2({
        dropdownParent: $("#ajaxModal"),
        tags: true,
        placeholder: "Select a supplier"
    });
});