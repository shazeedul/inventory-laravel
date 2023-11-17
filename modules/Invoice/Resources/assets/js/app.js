document.addEventListener("DOMContentLoaded", function () {
    $("#customer_id").select2({
        tags: true,
    });
    $(".product_id").select2({
        tags: true,
    });
    var existingProductIds = [];

    // addRow
    $(document).on("click", "#addRow", function () {
        var count = parseInt($("#rowCount").val()) + 1;
        var html = "";
        // Check for unique product IDs in existing rows
        $(".product_id").each(function () {
            var productId = $(this).val();
            if (productId) {
                existingProductIds.push(productId);
            }
        });
        
        html += "<tr>";
        html += `<td><select name="product_id[]" class="form-control product_id" id="product_id_${count}" onchange="getProduct(${count})"></select></td>`;
        html += `<td><input type="text" class="form-control form-number-input" id="category_${count}" readonly /></td>`;
        html += `<td><input type="text" class="form-control form-number-input" id="unit_${count}" readonly /></td>`;
        html += `<td><input type="number" class="form-control form-number-input" id="stock_${count}" readonly /></td>`;
        html += `<td><input type="number" name="quantity[]" class="form-control form-number-input" id="quantity_${count}" onchange="calculateTotalPrice(${count})" onkeyup="calculateTotalPrice(${count})" value="0.00"></td>`;
        html += `<td><input type="number" name="unit_price[]" class="form-control form-number-input" id="unit_price_${count}" onchange="calculateTotalPrice(${count})" onkeyup="calculateTotalPrice(${count})" value="0.00"></td>`;
        html += `<td><input type="number" name="total[]" class="form-control" id="total_${count}" readonly value="0.00"></td>`;
        html += `<td><button type="button" class="btn btn-danger removeRow"><i class="fa fa-trash"></i></button></td>`;
        html += `</tr>`;

        // Append the new row
        $("#invoiceItem").append(html);

        // Populate options for the newly added dropdown, excluding existing product IDs
        populateProductOptions(count, existingProductIds);

        // Update the row count
        $("#rowCount").val(count);

        // Initialize select2 for the newly added dropdown
        $(`#product_id_${count}`).select2({
            tags: true,
        });
    });

    // Remove Row
    $(document).on("click", ".removeRow", function () {
        // Check if this is not the last row
        if ($("#invoiceItem tr").length > 1) {
            var removedProductId = $(this).closest("tr").find('.product_id').val();

            // Remove the product ID from existingProductIds array
            existingProductIds = existingProductIds.filter(id => id !== removedProductId);

            // Remove the row
            $(this).closest("tr").remove();
        } else {
            toastr.error("You cannot remove the last row!");
        }
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

function getProduct(count) {
    // get product id by call this getProduct function
    var product_id = $(`#product_id_${count}`).val();
    // get product by product id
    var product = products.find((product) => product.id == product_id);
    // category, unit, stock
    $(`#category_${count}`).val(product.category.name);
    $(`#unit_${count}`).val(product.unit.name);
    $(`#stock_${count}`).val(product.quantity);
    if (product.quantity == 0 || product.quantity < 1 || product.quantity == 0.00) {
        $(`#quantity_${count}`).attr('readonly', 'readonly');
        $(`#unit_price_${count}`).attr('readonly', 'readonly');
    }
}

function populateProductOptions(count, excludeProductIds) {
    var option = `<option value="">Select Product</option>`;
    products.forEach(function (product) {
        // Exclude products with IDs present in existing rows
        if (!excludeProductIds.includes(product.id.toString())) {
            option += `<option value="${product.id}">${product.name} -- ${product.category.name} (${product.unit.name})</option>`;
        }
    });
    $(`#product_id_${count}`).html(option);
}