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
            console.log(coaId);
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
            console.log(data.data);
            if (data.data.head_level == 1 && data.data.parent_id == 0) {
                // var addForm = addNewCoaFrom(data);
                // $("#addCoaFrom").html(addForm);
                $("#editCoaFrom").html("");
                $("#deleteCoaFrom").html("");
            }
            if (
                data.data.head_level == 1 ||
                data.data.head_level == 2 ||
                data.data.head_level == 3 ||
                data.data.head_level == 4
            ) {
                // checkUpdate(data);
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
            $("#delCoaFrom").html("");
            // var fromUpdate = loadUpdateFrom(data);
            // $("#editCoaFrom").html(fromUpdate);
        },
    });
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
