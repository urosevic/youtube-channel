function ytcFieldSwitch(fIds, fSelectors, newState) {
    fSelectors.forEach(function(fSelector, index){
        ytcWidgetField = document.querySelector("label[for='" + fIds + fSelector + "']");
        if ("hide" == newState) {
            ytcWidgetField.classList.add("hidden");
        } else {
            ytcWidgetField.classList.remove("hidden");
        }
    });
}

function ytcToggle(fieldsGroup, fIds) {
    var fTrigger = document.getElementById(fIds + fieldsGroup);
    var fSelectors, fState;
    switch (fieldsGroup) {
        case "resource":
            fSelectors = ["random"];
            fState = 2 == fTrigger.value ? "hide" : "show";
            break;
        case "responsive":
            fSelectors = ["width"];
            fState = fTrigger.checked ? "hide" : "show";
            break;
        case "display":
            fSelectors = ["thumb_quality", "no_thumb_title"];
            fState = "thumbnail" !== fTrigger.value ? "hide" : "show";
            break;
        case "showtitle":
            fSelectors = ["linktitle", "titletag"];
            fState = "none" == fTrigger.value ? "hide" : "show";
            break;
        case "showdesc":
            fSelectors = ["desclen"];
            fState = !fTrigger.checked ? "hide" : "show";
            break;
        case "link_to":
            fSelectors = ["goto_txt", "popup_goto"];
            fState = "none" == fTrigger.value ? "hide" : "show";
            break;
    }
    if ("hide" == fState) {
        ytcFieldSwitch(fIds, fSelectors, "hide");
    } else if ("show" == fState) {
        ytcFieldSwitch(fIds, fSelectors, "show");
    }
}
