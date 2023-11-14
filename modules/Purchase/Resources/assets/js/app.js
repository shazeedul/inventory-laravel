document.addEventListener("DOMContentLoaded", function () {
    $("#supplier_id").select2({
        tags: true,
    });

    $(".product_id").select2({
        tags: true,
    });

    // addRow
    $(document).on("click", "#addRow", function () {
        var count = parseInt($("#rowCount").val()) + 1;
        var html = "";
        html += "<tr>";
        html += `<td><input type="hidden" name="purchase_details_id[]" /><select name="product_id[]" class="form-control product_id" id="product_id_${count}"></select></td>`;
        html += `<td><input type="number" name="quantity[]" class="form-control form-number-input" id="quantity_${count}" onchange="calculateTotalPrice(${count})" onkeyup="calculateTotalPrice(${count})" value="0.00"></td>`;
        html += `<td><input type="number" name="unit_price[]" class="form-control form-number-input" id="unit_price_${count}" onchange="calculateTotalPrice(${count})" onkeyup="calculateTotalPrice(${count})" value="0.00"></td>`;
        html += `<td><input type="text" name="description[]" class="form-control" id="description_${count}"></td>`;
        html += `<td><input type="number" name="total[]" class="form-control" id="total_${count}" readonly value="0.00"></td>`;
        html += `<td><button type="button" class="btn btn-danger removeRow"><i class="fa fa-trash"></i></button></td>`;
        html += `</tr>`;
        $("#purchaseItem").append(html);

        // add $product_id_ count option
        var option = "";
        option += `<option value="">Select Product</option>`;
        products.forEach(function (product) {
            option += `<option value="${product.id}">${product.name}</option>`;
        });
        $(`#product_id_${count}`).html(option);

        $("#rowCount").val(count);

        $(".product_id").select2({
            tags: true,
        });
    });

    // removeRow
    $(document).on("click", ".removeRow", function () {
        // check this closest tr is not last
        if ($("#purchaseItem tr").length == 1) {
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

// get purchase total price
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
