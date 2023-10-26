function menuSearch(e) {
    var $input = $(e);
    var list = $("a:not(.exculude-search)");
    var uniqueList = [];

    list.each(function (index, item) {
        var text = $(item).text().trim();
        if (text.match(new RegExp($input.val(), "gi"))) {
            var hrf = $(item).attr("href");
            var link_txt = $(item).text().trim();
            if (hrf === undefined || hrf === "" || hrf.startsWith("#")) {
                return;
            }
            var newItem = {
                label: link_txt,
                hrf: hrf,
            };
            // Check if the item already exists in uniqueList
            var exists = uniqueList.some(function (uniqueItem) {
                return uniqueItem.hrf === newItem.hrf;
            });
            if (!exists) {
                uniqueList.push(newItem);
            }
        }
    });

    var options = {
        source: uniqueList,
        select: function (event, ui) {
            event.preventDefault();
            $input.val(ui.item.label);
            window.location.href = ui.item.hrf;
            return false;
        },
        appendTo: "body",
        open: function (event, ui) {
            $(this).autocomplete("widget").css("z-index", 9999);
        },
    };

    $input.autocomplete(options);
}
