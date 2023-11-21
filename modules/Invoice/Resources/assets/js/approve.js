document.addEventListener("DOMContentLoaded", function () {
    $(`#approve`).on("click", function (e) {
        e.preventDefault();
        var url = $(this).closest("form").attr("action");
        $.ajax({
            type: "post",
            url,
            dataType: "json",
            data: {
                _token: $('meta[name="csrf-token"]').attr("content"),
                invoice: $(`#invoice_id`).val(),
            },
            success: function (res) {
                if (res.success) {
                    window.location = res.responseJSON.redirect_url;
                }
            },
            error: function (err) {
                // toastr message
                toastr.error(err.responseJSON.message);
            },
        });
    });
});
