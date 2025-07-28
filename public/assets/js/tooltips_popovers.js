// Tooltip js
"use strict";
$(function() {
    var tooltip_init = {
        init: function () {
            // Destroy existing tooltips first to avoid conflicts
            $('[data-bs-toggle="tooltip"]').tooltip('dispose');

            // Initialize tooltips only on elements that don't already have them
            $("button:not([data-bs-toggle='tooltip'])").tooltip();
            $("a:not([data-bs-toggle='tooltip'])").tooltip();
            $("input:not([data-bs-toggle='tooltip'])").tooltip();
            $("li:not([data-bs-toggle='tooltip'])").tooltip();
        }
    };
    tooltip_init.init()
});


$(function() {
    $("#myPopover").popover({

    });
});
$(function() {
    $("#myPopover01").popover({

    });
});


$(function() {
    $("#myPopover2").popover({

    });
});

$(function() {
    $("#myPopover3").popover({

    });
});

$(function() {
    $("#myPopover4").popover({

    });
});
