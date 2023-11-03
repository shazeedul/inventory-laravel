window.addEventListener("axiosModalSuccess", function (e) {
    $("#supplier_id").select2({
        dropdownParent: $("#ajaxModal"),
        tags: true,
        placeholder: "Select a supplier"
    });
    $("#category_id").select2({
        dropdownParent: $("#ajaxModal"),
        tags: true,
        placeholder: "Select a category"
    });
    $("#unit_id").select2({
        dropdownParent: $("#ajaxModal"),
        tags: true,
        placeholder: "Select a unit"
    });
});