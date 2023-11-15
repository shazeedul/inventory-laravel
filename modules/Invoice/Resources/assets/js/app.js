document.addEventListener("DOMContentLoaded", function () {
    $("#customer_id").select2({
        tags: true,
    });
    $(".product_id").select2({
        tags: true,
    });

    // removeRow
    $(document).on("click", ".removeRow", function () {
        // check this closest tr is not last
        if ($("#invoiceItem tr").length == 1) {
            toastr.error("You can not remove last row!");
            return false;
        }
        $(this).closest("tr").remove();
    });
});

// calculate total price
function calculateTotalPrice(count) {
    var quantity = $(`#quantity_${count}`).val();
    var unit_price = $(`#unit_price_${count}`).val();
    var total_price = quantity * unit_price;
    $(`#total_${count}`).val(total_price);
    getPurchaseTotalPrice();
}

// get invoice total price
function getPurchaseTotalPrice() {
    var total = 0;
    $("#grandTotal").val(0);
    $('input[name^="total"]').each(function () {
        var value = parseFloat($(this).val());
        if (!isNaN(value)) {
            total += value;
        }
    });
    $("#grandTotal").val(total);
}

// get product data by products json data
function get_product() {

}