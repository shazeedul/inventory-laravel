$(function () {
    "use strict";
    $("#jstree").on("click", function (event) {
        $("#addCoaFrom").html("");
        $("#editCoaFrom").html("");
        $("#deleteCoaFrom").html("");
        var accountHeadId = event.target.id;

        if (accountHeadId != "") {
            var coaId = $("#" + accountHeadId)
                .closest("li")
                .attr("data-id");
            addCartOfAccount(coaId);
        }
    });
});

var baseUrl = window.location.protocol + "//" + window.location.host;

function addCartOfAccount(coaID) {
    "use strict";
    loader();
    $.ajax({
        type: "GET",
        url: baseUrl + "/admin/account/coa/show/" + coaID,
        async: false,
        success: function (data) {
            if (data.data.head_level == 1 && data.data.parent_id == 0) {
                var addForm = addNewCoaFrom(data.data);
                $("#addCoaFrom").html(addForm);
                $("#editCoaFrom").html("");
                $("#deleteCoaFrom").html("");
            }
            if (
                data.data.head_level == 1 ||
                data.data.head_level == 2 ||
                data.data.head_level == 3 ||
                data.data.head_level == 4
            ) {
                checkUpdate(data.data.id);
            }
            // head_level higher then 4 toastr message show
            if (data.data.head_level > 4) {
                toastr.error(
                    "Invalid Account Head Level.The Account Head Level is Higher Then 5"
                );
            }
        },
    });
    loader("hide");
}

function checkUpdate(id) {
    let url = baseUrl + "/admin/account/coa/edit/" + id;
    $.ajax({
        type: "GET",
        url: url,
        async: false,
        success: function (data) {
            $("#addCoaFrom").html("");
            $("#editCoaFrom").html("");
            $("#deleteCoaFrom").html("");
            let fromUpdate = loadUpdateFrom(data.data);
            $("#editCoaFrom").html(fromUpdate);
        },
    });
}

function loadUpdateFrom(UpdateCoaData) {
    var currentHeadLabel = UpdateCoaData.chartOfAccount.head_level;
    var note_no =
        UpdateCoaData.chartOfAccount.note_no == null
            ? ""
            : UpdateCoaData.chartOfAccount.note_no;

    var updateFrom = "<div class='row g-4'>";
    updateFrom += "<div class='col-md-12'>";
    // row start
    updateFrom += "<div class='row'>";
    updateFrom +=
        "<label for='currentHeadLabel' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'>" +
        localize("Head Label") +
        "<i class='text-danger'>*</i></label>";
    updateFrom += "<div class='col-sm-8 col-md-12 col-xl-8'>";
    updateFrom +=
        "<input type='text' readonly class='form-control' id='currentHeadLabel' name='currentHeadLabel' value ='" +
        currentHeadLabel +
        "'>";
    updateFrom += "</div>";
    updateFrom += "</div>";
    // row end

    // row start
    updateFrom += "<div class='row'>";
    updateFrom +=
        "<label for='code' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'>" +
        localize("Ledger Code") +
        "<i class='text-danger'>*</i></label>";
    updateFrom += "<div class='col-sm-8 col-md-12 col-xl-8'>";
    updateFrom +=
        "<input type='text' class='form-control' id='code' name='code' value = '" +
        UpdateCoaData.chartOfAccount.code +
        "' readonly>";
    updateFrom += "</div>";
    updateFrom += "</div>";
    // row end

    // row start
    updateFrom += "<div class='row'>";
    updateFrom +=
        "<label for='name' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'>" +
        localize("Ledger Name") +
        "<i class='text-danger'>*</i></label>";
    updateFrom += "<div class='col-sm-8 col-md-12 col-xl-8'>";
    updateFrom +=
        "<input type='text' class='form-control' id='name' name='name' value = '" +
        UpdateCoaData.chartOfAccount.name +
        "' required>";
    updateFrom += "</div>";
    updateFrom += "</div>";
    // row end

    //note start
    if (currentHeadLabel == 3 || currentHeadLabel == 4) {
        updateFrom += "<div class='row'>";
        updateFrom +=
            "<label for='note_no' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'>" +
            localize("Note No") +
            "</label>";
        updateFrom += "<div class='col-sm-8 col-md-12 col-xl-8'>";
        updateFrom +=
            "<input type='text' class='form-control' id='note_no' name='note_no' value = '" +
            note_no +
            "'>";
        updateFrom += "</div>";
        updateFrom += "</div>";
    }
    //note end

    // Parent Head Name
    updateFrom += "</div>";
    updateFrom += "</div>";

    updateFrom += "<div class='col-md-12 mt-3'>";
    updateFrom += "<div class='row'>";
    updateFrom += "<div class='col-md-4'>" + localize("Parent Name") + "</div>";
    updateFrom += "<div class='col-md-8'>";

    updateFrom += "<select id='parent_id'  class='form-select' disabled>";
    $.each(UpdateCoaData.dropDownAccounts, function (key, value) {
        if (value.id == UpdateCoaData.chartOfAccount.parent_id) {
            updateFrom +=
                "<option value = '" +
                value.id +
                "'selected>" +
                value.name +
                "</option>";
        } else {
            updateFrom +=
                "<option value = '" +
                value.id +
                "'>" +
                value.name +
                "</option>";
        }
    });
    updateFrom += "</select>";
    updateFrom +=
        "<input type='hidden' name='parent_id' value='" +
        UpdateCoaData.chartOfAccount.parent_id +
        "' />";
    updateFrom += "</div>";
    updateFrom += "</div>";
    updateFrom += "</div>";

    // Parent Head Name End

    if (UpdateCoaData.chartOfAccount.head_level != 1) {
        // radio button start
        updateFrom += "<div class='col-md-12 mt-3'>";
        updateFrom += "<div class='row mt-2'>";
        updateFrom +=
            "<label for='status' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold required'>" +
            localize("Status") +
            "</label>";
        updateFrom += "<div class='col-sm-8 col-md-12 col-xl-8 mt-2'>";

        updateFrom +=
            "<input class='form-check-input m-1' type='radio' name='is_active' value='1' id='is_active' checked >";
        updateFrom +=
            "<label class='form-check-label' for='is_active'>" +
            localize("active") +
            "</label>";
        updateFrom +=
            "<input class='form-check-input m-1' type='radio' name='is_active' value='0' id='inactive' >";
        updateFrom +=
            "<label class='form-check-label' for='inactive'>" +
            localize("Disable") +
            "</label>";

        updateFrom += "</div>";
        updateFrom += "</div>";
        updateFrom += "</div>";
        // radio button End
    }

    updateFrom +=
        "<input type='hidden' name='id' value=" +
        UpdateCoaData.chartOfAccount.id +
        " id='head_level'>";
    updateFrom +=
        "<input type='hidden' name='account_type_id' value=" +
        UpdateCoaData.chartOfAccount.account_type_id +
        " id='account_type_id'>";

    if (
        Number(UpdateCoaData.chartOfAccount.head_level) == 3 &&
        Number(UpdateCoaData.chartOfAccount.account_type_id) == 1
    ) {
        //Asset radio button start
        updateFrom += "<div class='col-md-12 mt-3'>";
        updateFrom += "<div class='row mt-2'>";
        updateFrom +=
            "<label for='asset_type' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'>" +
            localize("Type") +
            "</label>";
        updateFrom += "<div class='col-sm-8 col-md-12 col-xl-8 mt-2'>";

        if (UpdateCoaData.chartOfAccount.is_stock == 1) {
            updateFrom +=
                "<input class='form-check-input m-1' type='radio' name='asset_type' value='is_stock' checked id='is_stock' >";
        } else {
            updateFrom +=
                "<input class='form-check-input m-1' type='radio' name='asset_type' value='is_stock' id='is_stock'>";
        }

        updateFrom +=
            "<label class='form-check-label' for='is_stock'>" +
            localize("Is Stock") +
            "</label>";

        if (UpdateCoaData.chartOfAccount.is_fixed_asset_schedule == 1) {
            updateFrom +=
                "<input class='form-check-input m-1' type='radio' name='asset_type' value='is_fixed_asset' checked id='is_fixed_asset' >";
        } else {
            updateFrom +=
                "<input class='form-check-input m-1' type='radio' name='asset_type' value='is_fixed_asset' id='is_fixed_asset'>";
        }

        updateFrom +=
            "<label class='form-check-label' for='is_fixed_asset'>" +
            localize("Is Fixed Asset") +
            "</label>";

        updateFrom += "</div>";
        updateFrom += "</div>";
        updateFrom += "</div>";
        //Asset radio button End
    }

    if (
        Number(UpdateCoaData.chartOfAccount.head_level) == 3 &&
        (Number(UpdateCoaData.chartOfAccount.account_type_id) == 2 ||
            Number(UpdateCoaData.chartOfAccount.account_type_id) == 5)
    ) {
        // for liability & equity
        updateFrom += "<div class='col-md-12 mt-3'>";
        updateFrom += "<div class='row mt-2'>";
        updateFrom +=
            "<label for='asset_type' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'>" +
            localize("Type") +
            "</label>";
        updateFrom += "<div class='col-sm-8 col-md-12 col-xl-8 mt-2'>";

        if (UpdateCoaData.chartOfAccount.is_fixed_asset_schedule == 1) {
            updateFrom +=
                "<input class='form-check-input m-1' type='checkbox' name='asset_type' value='is_fixed_asset' checked  id='is_fixed_asset'>";
        } else {
            updateFrom +=
                "<input class='form-check-input m-1' type='checkbox' name='asset_type' value='is_fixed_asset' id='is_fixed_asset'>";
        }

        updateFrom +=
            "<label class='form-check-label' for='is_fixed_asset'>" +
            localize("Is Fixed Asset") +
            "</label>";

        updateFrom += "</div>";
        updateFrom += "</div>";
        updateFrom += "</div>";
        //Asset radio button End
    }

    if (Number(UpdateCoaData.chartOfAccount.head_level) == 4) {
        // Currency type add
        // updateFrom += "<div class='col-md-12 mt-3'>";
        // updateFrom += "<div class='row mt-2'>";
        // updateFrom +=
        //     "<div class='col-md-4'>" + localize("Currency") + "</div>";
        // updateFrom += "<div class='col-md-8'>";
        // updateFrom +=
        //     "<select id='currency_id' name='currency_id' class='form-select'>";
        // var editELcurrency = $("#currency").val();
        // var editELcurrencyData = JSON.parse(editELcurrency);
        // $.each(editELcurrencyData, function (key, editELvalue) {
        //     if (UpdateCoaData.chartOfAccount.currency_id == editELvalue.id) {
        //         updateFrom +=
        //             "<option value = '" +
        //             editELvalue.id +
        //             "' selected>" +
        //             editELvalue.title +
        //             "</option>";
        //     } else {
        //         updateFrom +=
        //             "<option value = '" +
        //             editELvalue.id +
        //             "'>" +
        //             editELvalue.title +
        //             "</option>";
        //     }
        // });
        // updateFrom += "</select>";
        // updateFrom += "</div>";
        // updateFrom += "</div>";
        // updateFrom += "</div>";
        // Currency type end
    }
    if (
        Number(UpdateCoaData.chartOfAccount.head_level) == 4 &&
        Number(UpdateCoaData.chartOfAccount.account_type_id) == 1
    ) {
        //Asset radio button start
        updateFrom += "<div class='col-md-12 mt-3'>";
        updateFrom += "<div class='row mt-2'>";

        updateFrom +=
            "<label for='asset_type' class='col-form-label col-sm-4 col-md-4 col-xl-4 fw-semibold'>" +
            localize("Type") +
            "</label>";
        updateFrom += "<div class='col-sm-8 col-md-8 col-xl-8 mt-2'>";

        if (UpdateCoaData.chartOfAccount.is_stock == 1) {
            updateFrom +=
                "<input class='form-check-input  m-1' type='radio' name='asset_type' value='is_stock' id = 'editAsset_is_stock' checked >";
        } else {
            updateFrom +=
                "<input class='form-check-input  m-1' type='radio' name='asset_type' value='is_stock' id = 'editAsset_is_stock' >";
        }
        updateFrom +=
            "<label class='form-check-label' for='editAsset_is_stock'>" +
            localize("Is Stock") +
            "</label>";

        if (UpdateCoaData.chartOfAccount.is_fixed_asset_schedule == 1) {
            updateFrom +=
                "<input class='form-check-input  m-1' type='radio' name='asset_type' value='is_fixed_asset' id = 'editAsset_is_fixed_asset' checked >";
        } else {
            updateFrom +=
                "<input class='form-check-input  m-1' type='radio' name='asset_type' value='is_fixed_asset' id = 'editAsset_is_fixed_asset'  >";
        }
        updateFrom +=
            "<label class='form-check-label' for='editAsset_is_fixed_asset'>" +
            localize("Is Fixed Asset") +
            "</label>";

        if (UpdateCoaData.chartOfAccount.is_subtype == 1) {
            updateFrom +=
                "<input class='form-check-input m-1' type='radio' name='asset_type' value='is_subtype'  id = 'editAsset_is_subtype' checked >";
        } else {
            updateFrom +=
                "<input class='form-check-input m-1' type='radio' name='asset_type' value='is_subtype'  id = 'editAsset_is_subtype' >";
        }
        updateFrom +=
            "<label class='form-check-label' for='editAsset_is_subtype'>" +
            localize("Is Sub Type") +
            "</label>";

        if (UpdateCoaData.chartOfAccount.is_cash_nature == 1) {
            updateFrom +=
                "<input class='form-check-input mr-3 m-1' type='radio' name='asset_type' value='is_cash' id = 'editAsset_is_cash' checked >";
        } else {
            updateFrom +=
                "<input class='form-check-input mr-3 m-1' type='radio' name='asset_type' value='is_cash' id = 'editAsset_is_cash' >";
        }
        updateFrom +=
            "<label class='form-check-label' for='editAsset_is_cash'>" +
            localize("Is Cash Nature") +
            "</label>";

        if (UpdateCoaData.chartOfAccount.is_bank_nature == 1) {
            updateFrom +=
                "<input class='form-check-input m-1' type='radio' name='asset_type' value='is_bank'  id = 'editAsset_is_bank' checked>";
        } else {
            updateFrom +=
                "<input class='form-check-input m-1' type='radio' name='asset_type' value='is_bank'  id = 'editAsset_is_bank' >";
        }
        updateFrom +=
            "<label class='form-check-label' for='editAsset_is_bank'>" +
            localize("Is Bank Nature") +
            "</label>";

        updateFrom += "</div>";
        updateFrom += "</div>";
        updateFrom += "</div>";

        //Asset radio button End
        if (
            UpdateCoaData.chartOfAccount.asset_code != null ||
            UpdateCoaData.chartOfAccount.depreciation_rate != null
        ) {
            updateFrom += "<div class='col-md-12 mt-3'>";
            updateFrom += "<div class='row mt-2' id='editFixedAssetField'>";
            //Fixed Asset Code start
            updateFrom += "<div class='row'>";
            updateFrom +=
                "<label for='asset_code' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'>" +
                localize("Fixed Asset Code") +
                "</label>";
            updateFrom += "<div class='col-sm-8 col-md-12 col-xl-8'>";
            if (UpdateCoaData.chartOfAccount.asset_code != null) {
                updateFrom +=
                    "<input type='text' class='form-control' id='asset_code' name='asset_code' value='" +
                    UpdateCoaData.chartOfAccount.asset_code +
                    "' >";
            } else {
                updateFrom +=
                    "<input type='text' class='form-control' id='asset_code' name='asset_code' >";
            }

            updateFrom += "</div>";
            updateFrom += "</div>";

            updateFrom += "<div class='row'>";
            updateFrom +=
                "<label for='depreciation_rate' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'>" +
                localize("Depreciation Rate") +
                " % </label>";
            updateFrom += "<div class='col-sm-8 col-md-12 col-xl-8'>";
            if (UpdateCoaData.chartOfAccount.depreciation_rate != null) {
                updateFrom +=
                    "<input type='text' class='form-control' id='depreciation_rate' name='depreciation_rate' value='" +
                    UpdateCoaData.chartOfAccount.depreciation_rate +
                    "' >";
            } else {
                updateFrom +=
                    "<input type='text' class='form-control' id='depreciation_rate' name='depreciation_rate' >";
            }

            updateFrom += "</div>";
            updateFrom += "</div>";

            //Fixed Asset Code End
            updateFrom += "</div>";
            updateFrom += "</div>";
        } else {
            updateFrom += "<div class='col-md-12 mt-3'>";
            updateFrom +=
                "<div class='row mt-2 d-none' id='editFixedAssetField'>";
            //Fixed Asset Code start
            updateFrom += "<div class='row'>";
            updateFrom +=
                "<label for='asset_code' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'>" +
                localize("Fixed Asset Code") +
                " </label>";
            updateFrom += "<div class='col-sm-8 col-md-12 col-xl-8'>";

            updateFrom +=
                "<input type='text' class='form-control' id='asset_code' name='asset_code' >";

            updateFrom += "</div>";
            updateFrom += "</div>";

            updateFrom += "<div class='row'>";
            updateFrom +=
                "<label for='depreciation_rate' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'>" +
                localize("Depreciation Rate") +
                " % </label>";
            updateFrom += "<div class='col-sm-8 col-md-12 col-xl-8'>";

            updateFrom +=
                "<input type='text' class='form-control' id='depreciation_rate' name='depreciation_rate' >";

            updateFrom += "</div>";
            updateFrom += "</div>";

            //Fixed Asset Code End
            updateFrom += "</div>";
            updateFrom += "</div>";
        }

        if (UpdateCoaData.chartOfAccount.account_sub_type_id != null) {
            updateFrom += "<div class='col-md-12 mt-3'>";
            updateFrom += "<div class='row mt-2 ' id='editSubtypeAssetField'>";
            // Account subtype Dropdown start
            updateFrom += "<div class='col-md-12 mt-3'>";
            updateFrom += "<div class='row'>";
            updateFrom +=
                "<div class='col-md-4'>" + localize("Sub Type") + "</div>";
            updateFrom += "<div class='col-md-8'>";

            updateFrom +=
                "<select id='account_sub_type_id' name='account_sub_type_id' class='form-select'>";
            var editSubTypeAcc = $("#accountSubType").val();
            var editAccSubType = JSON.parse(editSubTypeAcc);
            $.each(editAccSubType, function (key, editValue) {
                if (
                    UpdateCoaData.chartOfAccount.account_sub_type_id ==
                    editValue.id
                ) {
                    updateFrom +=
                        "<option value = '" +
                        editValue.id +
                        "' selected>" +
                        editValue.name +
                        "</option>";
                } else {
                    updateFrom +=
                        "<option value = '" +
                        editValue.id +
                        "'>" +
                        editValue.name +
                        "</option>";
                }
            });
            updateFrom += "</select>";
            updateFrom += "</div>";
            updateFrom += "</div>";
            updateFrom += "</div>";
            // Account subtype Dropdown end
            updateFrom += "</div>";
            updateFrom += "</div>";
        } else {
            updateFrom += "<div class='col-md-12 mt-3'>";
            updateFrom +=
                "<div class='row mt-2 d-none' id='editSubtypeAssetField'>";
            // Account subtype Dropdown start
            updateFrom += "<div class='col-md-12 mt-3'>";
            updateFrom += "<div class='row'>";
            updateFrom +=
                "<div class='col-md-4'>" + localize("Sub Type") + "</div>";
            updateFrom += "<div class='col-md-8'>";

            updateFrom +=
                "<select id='account_sub_type_id' name='account_sub_type_id' class='form-select'>";
            var editSubTypeAcc = $("#accountSubType").val();
            var editAccSubType = JSON.parse(editSubTypeAcc);
            $.each(editAccSubType, function (key, editValue) {
                updateFrom +=
                    "<option value = '" +
                    editValue.id +
                    "'>" +
                    editValue.name +
                    "</option>";
            });
            updateFrom += "</select>";
            updateFrom += "</div>";
            updateFrom += "</div>";
            updateFrom += "</div>";
            // Account subtype Dropdown end
            updateFrom += "</div>";
            updateFrom += "</div>";
        }
    }

    //for expense & income 4th level
    if (
        Number(UpdateCoaData.chartOfAccount.head_level) == 4 &&
        (Number(UpdateCoaData.chartOfAccount.account_type_id) == 3 ||
            Number(UpdateCoaData.chartOfAccount.account_type_id) == 4)
    ) {
        // Check box Sub Type
        updateFrom += "<div class='col-md-12 mt-3'>";
        updateFrom += "<div class='row mt-2'>";

        updateFrom +=
            "<label for='asset_type' class='col-form-label col-sm-4 col-md-4 col-xl-4 fw-semibold'>" +
            localize("Type") +
            "</label>";
        updateFrom += "<div class='col-sm-8 col-md-8 col-xl-8 mt-2'>";

        if (UpdateCoaData.chartOfAccount.is_subtype == 1) {
            updateFrom +=
                "<input class='form-check-input m-1' type='checkbox' name='asset_type' value='is_subtype'  id='edit_is_subtype' checked >";
        } else {
            updateFrom +=
                "<input class='form-check-input m-1' type='checkbox' name='asset_type' value='is_subtype'  id='edit_is_subtype' >";
        }
        updateFrom +=
            "<label class='form-check-label' for='asset_type'>" +
            localize("Is Sub Type") +
            "</label>";

        updateFrom += "</div>";
        updateFrom += "</div>";
        updateFrom += "</div>";
        // Check box Sub Type

        if (UpdateCoaData.chartOfAccount.account_sub_type_id != null) {
            updateFrom += "<div class='col-md-12 mt-3'>";
            updateFrom += "<div class='row mt-2 ' id='editSubtypeExpIncField'>";
            // Account subtype Dropdown start
            updateFrom += "<div class='col-md-12 mt-3'>";
            updateFrom += "<div class='row'>";
            updateFrom +=
                "<div class='col-md-4'>" + localize("Sub Type") + "</div>";
            updateFrom += "<div class='col-md-8'>";

            updateFrom +=
                "<select id='account_sub_type_id' name='account_sub_type_id' class='form-select'>";
            var editIEsubtypeAcc = $("#accountSubType").val();
            var editEIacSubtype = JSON.parse(editIEsubtypeAcc);
            $.each(editEIacSubtype, function (key, editIEvalue) {
                if (
                    UpdateCoaData.chartOfAccount.account_sub_type_id ==
                    editIEvalue.id
                ) {
                    updateFrom +=
                        "<option value = '" +
                        editIEvalue.id +
                        "' selected>" +
                        editIEvalue.name +
                        "</option>";
                } else {
                    updateFrom +=
                        "<option value = '" +
                        editIEvalue.id +
                        "'>" +
                        editIEvalue.name +
                        "</option>";
                }
            });
            updateFrom += "</select>";
            updateFrom += "</div>";
            updateFrom += "</div>";
            updateFrom += "</div>";
            // Account subtype Dropdown end
            updateFrom += "</div>";
            updateFrom += "</div>";
        } else {
            updateFrom += "<div class='col-md-12 mt-3'>";
            updateFrom +=
                "<div class='row mt-2 d-none' id='editSubtypeExpIncField'>";
            // Account subtype Dropdown start
            updateFrom += "<div class='col-md-12 mt-3'>";
            updateFrom += "<div class='row'>";
            updateFrom +=
                "<label class='col-md-4'>" + localize("Sub Type") + "</label>";
            updateFrom += "<div class='col-md-8'>";

            updateFrom +=
                "<select id='account_sub_type_id' name='account_sub_type_id' class='form-select'>";
            var editIEsubtypeAcc = $("#accountSubType").val();
            var editEIacSubtype = JSON.parse(editIEsubtypeAcc);
            $.each(editEIacSubtype, function (key, editIEvalue) {
                updateFrom +=
                    "<option value = '" +
                    editIEvalue.id +
                    "'>" +
                    editIEvalue.name +
                    "</option>";
            });
            updateFrom += "</select>";
            updateFrom += "</div>";
            updateFrom += "</div>";
            updateFrom += "</div>";
            // Account subtype Dropdown end
            updateFrom += "</div>";
            updateFrom += "</div>";
        }
    }

    if (
        Number(UpdateCoaData.chartOfAccount.head_level) == 4 &&
        (Number(UpdateCoaData.chartOfAccount.account_type_id) == 2 ||
            Number(UpdateCoaData.chartOfAccount.account_type_id) == 5)
    ) {
        //for liability and equity fifth level
        updateFrom += "<div class='col-md-12 mt-3'>";
        updateFrom += "<div class='row mt-2'>";

        updateFrom +=
            "<label for='asset_type' class='col-form-label col-sm-4 col-md-4 col-xl-4 fw-semibold'>" +
            localize("Type") +
            "</label>";
        updateFrom += "<div class='col-sm-8 col-md-8 col-xl-8 mt-2'>";

        if (UpdateCoaData.chartOfAccount.is_fixed_asset_schedule == 1) {
            updateFrom +=
                "<input class='form-check-input  m-1' type='radio' name='asset_type' value='is_fixed_asset' id='edit_is_fixed_asset' checked >";
        } else {
            updateFrom +=
                "<input class='form-check-input  m-1' type='radio' name='asset_type' value='is_fixed_asset' id='edit_is_fixed_asset'  >";
        }
        updateFrom +=
            "<label class='form-check-label' for='edit_is_fixed_asset'>Is Fixed Asset</label>";

        if (UpdateCoaData.chartOfAccount.is_subtype == 1) {
            updateFrom +=
                "<input class='form-check-input m-1' type='radio' name='asset_type' value='is_subtype'  id='editIsSubType' checked >";
        } else {
            updateFrom +=
                "<input class='form-check-input m-1' type='radio' name='asset_type' value='is_subtype'  id='editIsSubType' >";
        }
        updateFrom +=
            "<label class='form-check-label' for='editIsSubType'>" +
            localize("Is Sub Type") +
            "</label>";

        updateFrom += "</div>";
        updateFrom += "</div>";
        updateFrom += "</div>";

        //Asset radio button End

        if (UpdateCoaData.chartOfAccount.is_fixed_asset_schedule == 1) {
            updateFrom += "<div class='col-md-12 mt-3'>";
            updateFrom += "<div class='row mt-2' id='editFixedAssetLEField'>";
            //Fixed Asset Code start
            updateFrom += "<div class='row'>";
            updateFrom +=
                "<label for='depreciation_code' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'>" +
                localize("Depreciation Code") +
                "</label>";
            updateFrom += "<div class='col-sm-8 col-md-12 col-xl-8'>";
            if (UpdateCoaData.chartOfAccount.depreciation_code != null) {
                updateFrom +=
                    "<input type='text' class='form-control' id='depreciation_code' name='depreciation_code' value='" +
                    UpdateCoaData.chartOfAccount.depreciation_code +
                    "' >";
            } else {
                updateFrom +=
                    "<input type='text' class='form-control' id='depreciation_code' name='depreciation_code' >";
            }

            updateFrom += "</div>";
            updateFrom += "</div>";

            //Fixed Asset Code End
            updateFrom += "</div>";
            updateFrom += "</div>";
        } else {
            updateFrom += "<div class='col-md-12 mt-3'>";
            updateFrom +=
                "<div class='row mt-2 d-none' id='editFixedAssetLEField'>";
            //Fixed Asset Code start
            updateFrom += "<div class='row'>";
            updateFrom +=
                "<label for='depreciation_code' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'>" +
                localize("Depreciation Code") +
                "</label>";
            updateFrom += "<div class='col-sm-8 col-md-12 col-xl-8'>";
            updateFrom +=
                "<input type='text' class='form-control' id='depreciation_code' name='depreciation_code' >";
            updateFrom += "</div>";
            updateFrom += "</div>";

            //Fixed Asset Code End
            updateFrom += "</div>";
            updateFrom += "</div>";
        }

        if (UpdateCoaData.chartOfAccount.account_sub_type_id != null) {
            updateFrom += "<div class='col-md-12 mt-3'>";
            updateFrom += "<div class='row mt-2 ' id='editSubtypeLibEqField'>";
            // Account subtype Dropdown start
            updateFrom += "<div class='col-md-12 mt-3'>";
            updateFrom += "<div class='row'>";
            updateFrom +=
                "<div class='col-md-4'>" + localize("Sub Type") + "</div>";
            updateFrom += "<div class='col-md-8'>";

            updateFrom +=
                "<select id='account_sub_type_id' name='account_sub_type_id' class='form-select'>";
            var editELsubtypeAcc = $("#accountSubType").val();
            var editELacSubtype = JSON.parse(editELsubtypeAcc);
            $.each(editELacSubtype, function (key, editELvalue) {
                if (
                    UpdateCoaData.chartOfAccount.account_sub_type_id ==
                    editELvalue.id
                ) {
                    updateFrom +=
                        "<option value = '" +
                        editELvalue.id +
                        "' selected>" +
                        editELvalue.name +
                        "</option>";
                } else {
                    updateFrom +=
                        "<option value = '" +
                        editELvalue.id +
                        "'>" +
                        editELvalue.name +
                        "</option>";
                }
            });
            updateFrom += "</select>";
            updateFrom += "</div>";
            updateFrom += "</div>";
            updateFrom += "</div>";
            // Account subtype Dropdown end
            updateFrom += "</div>";
            updateFrom += "</div>";
        } else {
            updateFrom += "<div class='col-md-12 mt-3'>";
            updateFrom +=
                "<div class='row mt-2 d-none' id='editSubtypeLibEqField'>";
            // Account subtype Dropdown start
            updateFrom += "<div class='col-md-12 mt-3'>";
            updateFrom += "<div class='row'>";
            updateFrom +=
                "<div class='col-md-4'>" + localize("Sub Type") + "</div>";
            updateFrom += "<div class='col-md-8'>";

            updateFrom +=
                "<select id='account_sub_type_id' name='account_sub_type_id' class='form-select'>";
            var editELsubtypeAcc = $("#accountSubType").val();
            var editELacSubtype = JSON.parse(editELsubtypeAcc);
            $.each(editELacSubtype, function (key, editELvalue) {
                updateFrom +=
                    "<option value = '" +
                    editELvalue.id +
                    "'>" +
                    editELvalue.name +
                    "</option>";
            });
            updateFrom += "</select>";
            updateFrom += "</div>";
            updateFrom += "</div>";
            updateFrom += "</div>";
            // Account subtype Dropdown end
            updateFrom += "</div>";
            updateFrom += "</div>";
        }
    }

    var stringifiedObj = JSON.stringify(UpdateCoaData);
    //  button text
    updateFrom += "<div class='col-md-12'>";
    updateFrom += "<div class='row'>";
    if (Number(UpdateCoaData.chartOfAccount.head_level) == 4) {
        updateFrom +=
            "<div class='form-group mt-3'><button type='submit' class='btn btn-primary'>" +
            localize("Update") +
            "</button><a class='btn btn-danger mx-2' onclick='loadDeleteFrom(" +
            stringifiedObj +
            ")'>" +
            localize("Delete") +
            "</a></div>";
    } else {
        if (Number(UpdateCoaData.chartOfAccount.head_level) == 1) {
            updateFrom +=
                "<div class='form-group mt-3'><button type='submit' class='btn btn-primary'>" +
                localize("Update") +
                "</button><a class='btn btn-secondary mx-2' onclick='addLoadFromForAll(" +
                stringifiedObj +
                ")'>" +
                localize("Create") +
                "</a></div>";
        } else {
            updateFrom +=
                "<div class='form-group mt-3'><button type='submit' class='btn btn-primary'>" +
                localize("Update") +
                "</button><a class='btn btn-secondary mx-2' onclick='addLoadFromForAll(" +
                stringifiedObj +
                ")'>" +
                localize("Create") +
                "</a><a class='btn btn-danger mx-2' onclick='loadDeleteFrom(" +
                stringifiedObj +
                ")'>" +
                localize("Delete") +
                "</a></div>";
        }
    }
    updateFrom += "</div>";
    updateFrom += "</div>";
    //  button text

    $("#addCoaFrom").html("");
    $("#editCoaFrom").html("");
    $("#deleteCoaFrom").html("");

    return updateFrom;
}

function addNewCoaFrom(ObjData) {
    var headLabel = Number(ObjData.head_level) + 1;
    var currentHeadLabel = ObjData.head_level;
    var note_no = "";
    var lines = "<div class='row g-4'>";
    lines += "<div class='col-md-7'>";

    // name start
    lines += "<div class='row'>";
    lines +=
        "<label for='name' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'>Ledger Name<i class='text-danger'>*</i></label>";
    lines += "<div class='col-sm-8 col-md-12 col-xl-8'>";
    lines +=
        "<input type='text' class='form-control' id='name' name='name'  required >";
    lines += "</div>";
    lines += "</div>";
    // name end

    // note start
    if (currentHeadLabel == 2 || currentHeadLabel == 3) {
        lines += "<div class='row'>";
        lines +=
            "<label for='note_no' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'>" +
            localize("Note No") +
            "</label>";
        lines += "<div class='col-sm-8 col-md-12 col-xl-8'>";
        lines +=
            "<input type='text'  class='form-control'  name='note_no' value='" +
            note_no +
            "'>";
        lines += "</div>";
        lines += "</div>";
    }
    // note  end

    // radio button start
    lines += "<div class='row mt-2'>";
    lines +=
        "<label for='bed_no' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold required'>" +
        localize("Status") +
        "</label>";
    lines += "<div class='col-sm-8 col-md-12 col-xl-8 mt-2'>";

    lines +=
        "<input class='form-check-input m-1' type='radio' name='is_active' value='1' id='is_active' checked>";
    lines +=
        "<label class='form-check-label' for='is_active'>" +
        localize("Active") +
        "</label>";
    lines +=
        "<input class='form-check-input m-1' type='radio' name='is_active' value='0' id='is_active'>";
    lines +=
        "<label class='form-check-label' for='is_active'>" +
        localize("Disable") +
        "</label>";

    lines += "</div>";
    lines += "</div>";
    // radio button End
    lines += "</div>";
    lines += "</div>";

    // hidden Field start
    lines +=
        "<input type='hidden' name='head_level' value=" +
        headLabel +
        " id='head_level'>";
    lines +=
        "<input type='hidden' name='parent_id' value=" +
        ObjData.id +
        " id='parent_id'>";
    lines +=
        "<input type='hidden' name='account_type_id' value=" +
        ObjData.account_type_id +
        " id='account_type_id'>";

    //hidden Field end

    // button text
    lines += "<div class='col-md-6'>";
    lines += "<div class='row'>";
    lines += "<div class='col-md-4'></div>";
    lines += "<div class='col-md-8'>";
    lines +=
        "<div class='form-group mt-3'><button type='submit' class='btn btn-primary'>Add</button></div>";
    lines += "</div>";
    lines += "</div>";
    lines += "</div>";
    // button text

    return lines;
}

function addLoadFromForAll(allFormLoad) {
    var headLabel = Number(allFormLoad.chartOfAccount.head_level) + 1;
    var currentHeadLabel = allFormLoad.chartOfAccount.head_level;
    var note_no = "";

    var addFrom = "<div class='row g-4'>";
    addFrom += "<div class='col-md-12'>";

    // row start
    addFrom += "<div class='row'>";
    addFrom +=
        "<label for='name' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'> Ledger Name <i class='text-danger'>*</i></label>";
    addFrom += "<div class='col-sm-8 col-md-12 col-xl-8'>";
    addFrom +=
        "<input type='text' class='form-control' id='name' name='name'  required >";
    addFrom += "</div>";
    addFrom += "</div>";
    // row end

    //note start
    if (currentHeadLabel == 4 || currentHeadLabel == 5) {
        addFrom += "<div class='row'>";
        addFrom +=
            "<label for='note_no' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'>Note No</label>";
        addFrom += "<div class='col-sm-8 col-md-12 col-xl-8'>";
        addFrom +=
            "<input type='text' class='form-control' id='note_no' value='" +
            note_no +
            "' name='note_no'>";
        addFrom += "</div>";
        addFrom += "</div>";
    }
    // note end

    if (Number(allFormLoad.chartOfAccount.head_level) == 4) {
        // var addELcurrency = $("#currency").val();
        // var addELcurrencyData = JSON.parse(addELcurrency);
        // currency start
        // addFrom += "<div class='row'>";
        // addFrom +=
        //     "<label for='currency' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'>Currency</label>";
        // addFrom += "<div class='col-sm-8 col-md-12 col-xl-8'>";
        // addFrom +=
        //     "<select class='form-control' id='currency' name='currency' >";
        // addFrom += "<option value=''>Select Currency</option>";
        // for (var i = 0; i < addELcurrencyData.length; i++) {
        //     addFrom +=
        //         "<option value='" +
        //         addELcurrencyData[i].id +
        //         "'>" +
        //         addELcurrencyData[i].title +
        //         "</option>";
        // }
        // addFrom += "</select>";
        // addFrom += "</div>";
        // addFrom += "</div>";
        // currency end
    }

    //radio button start
    addFrom += "<div class='row mt-2'>";
    addFrom +=
        "<label for='bed_no' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold required'>Status</label>";
    addFrom += "<div class='col-sm-8 col-md-12 col-xl-8 mt-2'>";

    addFrom +=
        "<input class='form-check-input m-1' type='radio' name='is_active' value='1' id='is_active' checked >";
    addFrom +=
        "<label class='form-check-label' for='is_active'>" +
        localize("active") +
        "</label>";
    addFrom +=
        "<input class='form-check-input m-1' type='radio' name='is_active' value='0' id='is_active' >";
    addFrom +=
        "<label class='form-check-label' for='is_active'>Disable</label>";
    addFrom += "</div>";
    addFrom += "</div>";
    //radio button End

    //hidden Field start
    addFrom +=
        "<input type='hidden' name='head_level' value=" +
        headLabel +
        " id='head_level'>";
    addFrom +=
        "<input type='hidden' name='parent_id' value=" +
        allFormLoad.chartOfAccount.id +
        " id='parent_id'>";
    addFrom +=
        "<input type='hidden' name='account_type_id' value=" +
        allFormLoad.chartOfAccount.account_type_id +
        " id='account_type_id'>";

    //hidden Field end

    //Head Label 3 and account head type Asset
    if (
        Number(allFormLoad.chartOfAccount.head_level) == 2 &&
        Number(allFormLoad.chartOfAccount.account_type_id) == 1
    ) {
        //Asset radio button start
        addFrom += "<div class='row mt-2'>";
        addFrom +=
            "<label for='asset_type' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'>" +
            localize("Type") +
            "</label>";
        addFrom += "<div class='col-sm-8 col-md-12 col-xl-8 mt-2'>";

        addFrom +=
            "<input class='form-check-input m-1' type='radio' name='asset_type' value='is_stock' id='is_stock'>";
        addFrom +=
            "<label class='form-check-label' for='is_stock'>Is Stock</label>";
        addFrom +=
            "<input class='form-check-input m-1' type='radio' name='asset_type' value='is_fixed_asset' id='is_fixed_asset'   >";
        addFrom +=
            "<label class='form-check-label' for='is_fixed_asset'>Is Fixed Asset</label>";

        addFrom += "</div>";
        addFrom += "</div>";

        //Asset radio button End
    }

    if (
        Number(allFormLoad.chartOfAccount.head_level) == 3 &&
        (Number(allFormLoad.chartOfAccount.account_type_id) == 2 ||
            Number(allFormLoad.chartOfAccount.account_type_id) == 5)
    ) {
        //Asset radio button start
        addFrom += "<div class='row mt-2'>";
        addFrom +=
            "<label for='asset_type' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'>" +
            localize("Type") +
            "</label>";
        addFrom += "<div class='col-sm-8 col-md-12 col-xl-8 mt-2'>";
        addFrom +=
            "<input class='form-check-input m-1' type='checkbox' name='asset_type' value='is_fixed_asset' id='le_is_fixed_asset' >";
        addFrom +=
            "<label class='form-check-label' for='is_fixed_asset'>Is Fixed Asset</label>";
        addFrom += "</div>";
        addFrom += "</div>";

        addFrom += "<div class='row mt-2 d-none' id='fixedAssetField'>";
        //Fixed Asset Code start
        addFrom += "<div class='row'>";
        addFrom +=
            "<label for='depreciation_code' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'>" +
            localize("Depreciation Code") +
            "</label>";
        addFrom += "<div class='col-sm-8 col-md-12 col-xl-8'>";
        addFrom +=
            "<input type='text' class='form-control' id='depreciation_code' name='depreciation_code' >";
        addFrom += "</div>";
        addFrom += "</div>";

        //Fixed Asset Code End
        addFrom += "</div>";
    }

    if (
        Number(allFormLoad.chartOfAccount.head_level) == 4 &&
        Number(allFormLoad.chartOfAccount.account_type_id) == 1
    ) {
        //Asset radio button start
        addFrom += "<div class='row mt-2'>";
        addFrom +=
            "<label for='asset_type' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'>" +
            localize("Type") +
            "</label>";
        addFrom += "<div class='col-sm-8 col-md-12 col-xl-8 mt-2'>";

        addFrom +=
            "<input class='form-check-input  m-1' type='radio' name='asset_type' value='is_stock' id = 'asset_is_stock' >";
        addFrom +=
            "<label class='form-check-label' for='asset_is_stock'>Is Stock</label>";

        addFrom +=
            "<input class='form-check-input  m-1' type='radio' name='asset_type' value='is_fixed_asset' id = 'asset_is_fixed_asset'  >";
        addFrom +=
            "<label class='form-check-label' for='asset_is_fixed_asset'>Is Fixed Asset</label>";

        addFrom +=
            "<input class='form-check-input m-1' type='radio' name='asset_type' value='is_subtype'  id = 'asset_is_subtype' >";
        addFrom +=
            "<label class='form-check-label' for='asset_is_subtype'>" +
            localize("Is Sub Type") +
            "</label>";

        addFrom +=
            "<input class='form-check-input mr-3 m-1' type='radio' name='asset_type' value='is_cash' id = 'asset_is_cash' >";
        addFrom +=
            "<label class='form-check-label' for='asset_is_cash'>Is Cash Nature</label>";

        addFrom +=
            "<input class='form-check-input m-1' type='radio' name='asset_type' value='is_bank'  id = 'asset_is_bank' >";
        addFrom +=
            "<label class='form-check-label' for='asset_is_bank'>Is Bank Nature</label>";

        addFrom += "</div>";
        addFrom += "</div>";

        //Asset radio button End

        addFrom += "<div class='row mt-2 d-none' id='fixedAssetField'>";
        //Fixed Asset Code start
        addFrom += "<div class='row'>";
        addFrom +=
            "<label for='asset_code' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'> Fixed Asset Code </label>";
        addFrom += "<div class='col-sm-8 col-md-12 col-xl-8'>";
        addFrom +=
            "<input type='text' class='form-control' id='asset_code' name='asset_code' >";
        addFrom += "</div>";
        addFrom += "</div>";

        addFrom += "<div class='row'>";
        addFrom +=
            "<label for='depreciation_rate' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'> Depreciation Rate % </label>";
        addFrom += "<div class='col-sm-8 col-md-12 col-xl-8'>";
        addFrom +=
            "<input type='text' class='form-control' id='depreciation_rate' name='depreciation_rate' >";
        addFrom += "</div>";
        addFrom += "</div>";

        //Fixed Asset Code End
        addFrom += "</div>";

        addFrom += "<div class='row mt-2 d-none' id='SubtypeAssetField'>";
        // Account subtype Dropdown start
        addFrom += "<div class='col-md-12 mt-3'>";
        addFrom += "<div class='row'>";
        addFrom +=
            "<label class='col-md-4'>" + localize("Sub Type") + "</label>";
        addFrom += "<div class='col-md-8'>";

        addFrom +=
            "<select id='account_sub_type_id' name='account_sub_type_id' class='form-select'>";
        var subtypeAcc = $("#accountSubType").val();
        var acSubtype = JSON.parse(subtypeAcc);
        $.each(acSubtype, function (key, value) {
            addFrom +=
                "<option value = '" +
                value.id +
                "'>" +
                value.name +
                "</option>";
        });
        addFrom += "</select>";
        addFrom += "</div>";
        addFrom += "</div>";
        addFrom += "</div>";
        // Account subtype Dropdown end
        addFrom += "</div>";
    }

    if (
        Number(allFormLoad.chartOfAccount.head_level) == 4 &&
        (Number(allFormLoad.chartOfAccount.account_type_id) == 3 ||
            Number(allFormLoad.chartOfAccount.account_type_id) == 4)
    ) {
        // Asset radio button start
        addFrom += "<div class='row mt-2'>";
        addFrom +=
            "<label for='asset_type' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'>" +
            localize("Type") +
            "</label>";
        addFrom += "<div class='col-sm-8 col-md-12 col-xl-8 mt-2'>";

        addFrom +=
            "<input class='form-check-input m-1' type='checkbox' name='asset_type' value='is_subtype'  id = 'expense_is_subtype' >";
        addFrom +=
            "<label class='form-check-label' for='asset_type'>" +
            localize("Is Sub Type") +
            "</label>";
        addFrom += "</div>";
        addFrom += "</div>";

        addFrom +=
            "<div class='row mt-2 d-none' id='SubtypeAssetFieldExpense'>";
        // Account subtype Dropdown start
        addFrom += "<div class='col-md-12 mt-3'>";
        addFrom += "<div class='row'>";
        addFrom +=
            "<label class='col-md-4'>" + localize("Sub Type") + "</label>";
        addFrom += "<div class='col-md-8'>";

        addFrom +=
            "<select id='account_sub_type_id' name='account_sub_type_id' class='form-select'>";
        var subtypeAccExpense = $("#accountSubType").val();
        var acSubtypeExpense = JSON.parse(subtypeAccExpense);
        $.each(acSubtypeExpense, function (key, value) {
            addFrom +=
                "<option value = '" +
                value.id +
                "'>" +
                value.name +
                "</option>";
        });
        addFrom += "</select>";
        addFrom += "</div>";
        addFrom += "</div>";
        addFrom += "</div>";
        // Account subtype Dropdown end
        addFrom += "</div>";

        //Asset radio button End
    }

    if (
        Number(allFormLoad.chartOfAccount.head_level) == 4 &&
        (Number(allFormLoad.chartOfAccount.account_type_id) == 2 ||
            Number(allFormLoad.chartOfAccount.account_type_id) == 5)
    ) {
        //Asset radio button start
        addFrom += "<div class='row mt-2'>";
        addFrom +=
            "<label for='asset_type' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'>" +
            localize("Type") +
            "</label>";
        addFrom += "<div class='col-sm-8 col-md-12 col-xl-8 mt-2'>";

        addFrom +=
            "<input class='form-check-input  m-1' type='radio' name='asset_type' value='is_fixed_asset' id='le_is_fixed_asset'  >";
        addFrom +=
            "<label class='form-check-label' for='le_is_fixed_asset'>Is Fixed Asset</label>";

        addFrom +=
            "<input class='form-check-input m-1' type='radio' name='asset_type' value='is_subtype'  id = 'le_is_subtype' >";
        addFrom +=
            "<label class='form-check-label' for='le_is_subtype'>" +
            localize("Is Sub Type") +
            "</label>";

        addFrom += "</div>";
        addFrom += "</div>";
        //Asset radio button End

        addFrom += "<div class='row mt-2 d-none' id='fixedAssetField'>";
        //Fixed Asset Code start
        addFrom += "<div class='row'>";
        addFrom +=
            "<label for='depreciation_code' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'> Depreciation Code </label>";
        addFrom += "<div class='col-sm-8 col-md-12 col-xl-8'>";
        addFrom +=
            "<input type='text' class='form-control' id='depreciation_code' name='depreciation_code' >";
        addFrom += "</div>";
        addFrom += "</div>";

        //Fixed Asset Code End
        addFrom += "</div>";

        addFrom += "<div class='row mt-2 d-none' id='leSubtypeAssetField'>";
        // Account subtype Dropdown start
        addFrom += "<div class='col-md-12 mt-3'>";
        addFrom += "<div class='row'>";
        addFrom +=
            "<label class='col-md-4'>" + localize("Sub Type") + "</label>";
        addFrom += "<div class='col-md-8'>";

        addFrom +=
            "<select id='account_sub_type_id' name='account_sub_type_id' class='form-select'>";
        var AccSubTypes = $("#accountSubType").val();
        var AccSubTypesParse = JSON.parse(AccSubTypes);
        $.each(AccSubTypesParse, function (key, value) {
            addFrom +=
                "<option value = '" +
                value.id +
                "'>" +
                value.name +
                "</option>";
        });
        addFrom += "</select>";
        addFrom += "</div>";
        addFrom += "</div>";
        addFrom += "</div>";
        // Account subtype Dropdown end
        addFrom += "</div>";
    }

    addFrom += "</div>";
    addFrom += "</div>";
    // button text
    addFrom += "<div class='col-md-6'>";
    addFrom += "<div class='row'>";
    addFrom += "<div class='col-md-4'></div>";
    addFrom += "<div class='col-md-8'>";
    addFrom +=
        "<div class='form-group mt-3'><button type='submit' class='btn btn-success'>Add</button></div>";
    addFrom += "</div>";
    addFrom += "</div>";
    addFrom += "</div>";
    // button text

    $("#addCoaFrom").html("");
    $("#editCoaFrom").html("");
    $("#deleteCoaFrom").html("");

    $("#addCoaFrom").html(addFrom);
}

$(document).on("click", "#asset_is_fixed_asset", function () {
    var isChecked = $(this).is(":checked");
    if (isChecked == true) {
        $("#fixedAssetField").removeClass("d-none");
        $("#SubtypeAssetField").addClass("d-none");
    }
});

$(document).on("click", "#asset_is_stock", function () {
    var isChecked = $(this).is(":checked");
    if (isChecked == true) {
        $("#fixedAssetField").addClass("d-none");
        $("#SubtypeAssetField").addClass("d-none");
    }
});

$(document).on("click", "#asset_is_subtype", function () {
    var isChecked = $(this).is(":checked");
    if (isChecked == true) {
        $("#fixedAssetField").addClass("d-none");
        $("#SubtypeAssetField").removeClass("d-none");
    }
});

$(document).on("click", "#asset_is_cash", function () {
    var isChecked = $(this).is(":checked");
    if (isChecked == true) {
        $("#fixedAssetField").addClass("d-none");
        $("#SubtypeAssetField").addClass("d-none");
    }
});

$(document).on("click", "#asset_is_bank", function () {
    var isChecked = $(this).is(":checked");
    if (isChecked == true) {
        $("#fixedAssetField").addClass("d-none");
        $("#SubtypeAssetField").addClass("d-none");
    }
});

// Asset 4 label add

// Expense 4 label add

$(document).on("click", "#expense_is_subtype", function () {
    var isChecked = $(this).is(":checked");
    if (isChecked == true) {
        $("#SubtypeAssetFieldExpense").removeClass("d-none");
    }
    if (isChecked == false) {
        $("#SubtypeAssetFieldExpense").addClass("d-none");
    }
});

// Liabilities & Share Holder 5th label add
$(document).on("click", "#le_is_fixed_asset", function () {
    var isChecked = $(this).is(":checked");
    if (isChecked == true) {
        $("#fixedAssetField").removeClass("d-none");
        $("#leSubtypeAssetField").addClass("d-none");
    }
});

$(document).on("click", "#le_is_subtype", function () {
    var isChecked = $(this).is(":checked");
    if (isChecked == true) {
        $("#fixedAssetField").addClass("d-none");
        $("#leSubtypeAssetField").removeClass("d-none");
    }
});

// Liabilities & Share Holder 5th label add
$(document).on("click", "#editAsset_is_stock", function () {
    var isChecked = $(this).is(":checked");
    if (isChecked == true) {
        $("#editFixedAssetField").addClass("d-none");
        $("#editSubtypeAssetField").addClass("d-none");
    }
});

$(document).on("click", "#editAsset_is_cash", function () {
    var isChecked = $(this).is(":checked");
    if (isChecked == true) {
        $("#editFixedAssetField").addClass("d-none");
        $("#editSubtypeAssetField").addClass("d-none");
    }
});

$(document).on("click", "#editAsset_is_bank", function () {
    var isChecked = $(this).is(":checked");
    if (isChecked == true) {
        $("#editFixedAssetField").addClass("d-none");
        $("#editSubtypeAssetField").addClass("d-none");
    }
});

$(document).on("click", "#editAsset_is_fixed_asset", function () {
    var isChecked = $(this).is(":checked");
    if (isChecked == true) {
        $("#editFixedAssetField").removeClass("d-none");
        $("#editSubtypeAssetField").addClass("d-none");
    }
});

$(document).on("click", "#editAsset_is_subtype", function () {
    var isChecked = $(this).is(":checked");
    if (isChecked == true) {
        $("#editFixedAssetField").addClass("d-none");
        $("#editSubtypeAssetField").removeClass("d-none");
    }
});
// Liabilities & Share Holder 5th label add

// Income Expense 4th Label Edit
$(document).on("click", "#edit_is_subtype", function () {
    var isChecked = $(this).is(":checked");
    if (isChecked == true) {
        $("#editSubtypeExpIncField").removeClass("d-none");
    }
    if (isChecked == false) {
        $("#editSubtypeExpIncField").addClass("d-none");
    }
});
// Income Expense 4th Label Edit

// Liability & Equity 4th Label Edit
$(document).on("click", "#edit_is_fixed_asset", function () {
    var isChecked = $(this).is(":checked");
    if (isChecked == true) {
        $("#editSubtypeLibEqField").addClass("d-none");
        $("#editFixedAssetLEField").removeClass("d-none");
    }
});

$(document).on("click", "#editIsSubType", function () {
    var isChecked = $(this).is(":checked");
    if (isChecked == true) {
        $("#editFixedAssetLEField").addClass("d-none");
        $("#editSubtypeLibEqField").removeClass("d-none");
    }
});
// Liability & Equity 4th Label Edit

function loadDeleteFrom(data) {
    var delForm = "<div class='row'>";
    // row start
    delForm += "<div class='row'>";
    delForm +=
        "<label for='name' class='col-form-label col-sm-4 col-md-12 col-xl-4 fw-semibold'> Ledger Name <i class='text-danger'>*</i></label>";
    delForm += "<div class='col-sm-8 col-md-12 col-xl-8'>";
    delForm +=
        "<input type='text' class='form-control' id='name' name='name' value = '" +
        data.chartOfAccount.name +
        "'  readonly >";
    delForm += "</div>";
    delForm += "</div>";
    // row end

    delForm += "</div>";
    delForm += "</div>";

    delForm +=
        "<input type='hidden' name='id' value=" + data.chartOfAccount.id + ">";

    // button text
    delForm += "<div class='row'>";
    delForm += "<div class='col-md-8'>";
    delForm +=
        "<div class='form-group mt-3'><button type='submit' class='btn btn-danger'>" +
        localize("Confirm Delete") +
        "</button></div>";
    delForm += "</div>";
    delForm += "</div>";
    // button text

    $("#addCoaFrom").html("");
    $("#editCoaFrom").html("");
    $("#deleteCoaFrom").html("");
    $("#deleteCoaFrom").html(delForm);
}

function loader($show = "show") {
    if ($show == "show") {
        $("#coa-loader").show();
        $("#coa-form").hide();
    } else {
        // add 500 millisecond delay to loader hide
        setTimeout(function () {
            $("#coa-loader").hide();
            $("#coa-form").show();
        }, 500);
    }
}
