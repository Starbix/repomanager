setInterval(function () {
    getContainerState();
}, 2000);

/**
 *  Slide panel opening
 */
$(document).on('click','.slide-panel-btn',function () {
    var name = $(this).attr('slide-panel');
    openPanel(name);
});

/**
 *  Slide panel closing
 */
$(document).on('click','.slide-panel-close-btn',function () {
    closePanel();
});

/**
 *  Event: mark log as read
 */
$(document).on('click','.acquit-log-btn',function () {
    var id = $(this).attr('log-id');

    ajaxRequest(
        // Controller:
        'general',
        // Action:
        'acquitLog',
        // Data:
        {
            id: id
        },
        // Print success alert:
        false,
        // Print error alert:
        true,
        // Reload containers:
        ['header/general-log-messages']
    );
});

/**
 *  Event: hide slided window on escape button press
 */
$(document).keyup(function (e) {
    if (e.key === "Escape") {
        closePanel();
    }
});

/**
 *  Event: stop task
 */
$(document).on('click','.stop-task-btn',function () {
    var pid = $(this).attr('pid');

    ajaxRequest(
        // Controller:
        'task',
        // Action:
        'stopTask',
        // Data:
        {
            pid: pid
        },
        // Print success alert:
        true,
        // Print error alert:
        true
    );
});

/**
 *  Event: print a copy icon on element with .copy class
 */
$(document).on('mouseenter','.copy',function () {
    $(this).append('<img src="/assets/icons/duplicate.svg" class="icon-lowopacity icon-copy" title="Copy to clipboard">');
});

/**
 *  Event: remove copy icon on element with .copy class
 */
$(document).on('mouseleave','.copy',function () {
    $(this).find('.icon-copy').remove();
});

/**
 *  Event: copy parent text on click on element with .icon-copy class
 */
$(document).on('click','.icon-copy',function (e) {
    // Prevent parent to be triggered
    e.stopPropagation();

    var text = $(this).parent().text().trim();

    navigator.clipboard.writeText(text).then(() => {
        printAlert('Copied to clipboard', 'success');
    },() => {
        printAlert('Failed to copy', 'error');
    });
});

/**
 *  Event: click on a reloadable table page number
 */
$(document).on('click','.reloadable-table-page-btn',function () {
    /**
     *  Get table name and offset from parent
     */
    var table = $(this).parents('.reloadable-table').attr('table');
    var page = $(this).attr('page');

    /**
     *  Calculate offset (page * 10 - 10)
     */
    offset = parseInt(page) * 10 - 10;

    /**
     *  If offset is negative, set it to 0
     */
    if (offset < 0) {
        offset = 0;
    }

    /**
     *  Set cookie for PHP to load the right content
     *  e.g tables/tasks/list-done/offset
     */
    setCookie('tables/' + table + '/offset', offset, 1);

    reloadTable(table, offset);
});

/**
 *  Reload opened or closed elements that where opened/closed before reloading
 */
function reloadOpenedClosedElements()
{
    /**
     *  Retrieve sessionStorage with key finishing by /opened (<element>/opened)
     */
    var openedElements = Object.keys(sessionStorage).filter(function (key) {
        return key.endsWith('/opened');
    });

    /**
     *  If there are /opened elements set to true, open them
     */
    openedElements.forEach(function (element) {
        if (sessionStorage.getItem(element) == 'true') {
            var element = element.replace('/opened', '');
            $(element).show();
        }
        if (sessionStorage.getItem(element) == 'false') {
            var element = element.replace('/opened', '');
            $(element).hide();
        }
    });
}



/**
 * Reload panel and execute function if needed
 * @param {*} panel
 * @param {*} myfunction
 */
function reloadPanel(panel, myfunction = null)
{
    /**
     *  Print a loading icon on the bottom of the page
     */
    printLoading();

    /**
     *  Check if panel has children with class .veil-on-reload
     *  If so print a veil on them
     */
    printLoadingVeilByParentClass('slide-panel-reloadable-div[slide-panel="' + panel + '"]');

    $('.slide-panel-reloadable-div[slide-panel="' + panel + '"]').load(' .slide-panel-reloadable-div[slide-panel="' + panel + '"] > *', function () {
        /**
         *  If myfunction is not null, execute it after reloading
         */
        if (myfunction != null) {
            myfunction();
        }

        /**
         *  Reload opened or closed elements that where opened/closed before reloading
         */
        reloadOpenedClosedElements();
    });

    /**
     *  Hide loading icon
     */
    hideLoading();
}

/**
 * Ajax: Get and reload container
 * @param {*} container
 */
function reloadContainer(container)
{
    /**
     *  If the container to reload does not exist, return
     */
    if (!$('.reloadable-container[container="' + container + '"]').length) {
        return;
    }

    /**
     *  Print a loading icon on the bottom of the page
     */
    printLoading();

    /**
     *  Check if container has children with class .veil-on-reload
     *  If so print a veil on them
     */
    printLoadingVeilByParentClass('reloadable-container[container="' + container + '"]');

    ajaxRequest(
        // Controller:
        'general',
        // Action:
        'getContainer',
        // Data:
        {
            sourceUrl: window.location.href,
            sourceUri: window.location.pathname,
            container: container
        },
        // Print success alert:
        false,
        // Print error alert:
        true,
        // Reload container:
        [],
        // Execute functions on success:
        [
            // Replace container with itself, with new content
            "$('.reloadable-container[container=\"" + container + "\"]').replaceWith(jsonValue.message)",
            // Reload opened or closed elements that were opened/closed before reloading
            "reloadOpenedClosedElements()"
        ]
    );

    /**
     *  Hide loading icon
     */
    hideLoading();
}

/**
 *  Ajax: Get all containers state and reload them if needed
 */
function getContainerState()
{
    $.ajax({
        type: "POST",
        url: "/ajax/controller.php",
        data: {
            controller: "general",
            action: "getContainerState"
        },
        dataType: "json",
        success: function (data, textStatus, jqXHR) {
            /**
             *  Parse results and compare with current state
             */
            jsonValue = jQuery.parseJSON(jqXHR.responseText);
            containersArray = jQuery.parseJSON(jsonValue.message);
            containersArray.forEach(obj => {
                Object.entries(obj).forEach(([key, value]) => {
                    if (key == 'Container') {
                        containerName = value;
                    }
                    if (key == 'Id') {
                        containerStateId = value;
                    }
                });

                /**
                 *  If current container does not appear in cookies yet, add it
                 */
            if (getCookie(containerName) == "") {
                setCookie(containerName, containerStateId, 365);
                /**
                 *  Else compare current state with cookie state
                 */
            } else {
                var cookieState = getCookie(containerName);

                /**
                 *  If state has changed, reload container and update cookie
                 */
                if (cookieState != containerStateId) {
                    setCookie(containerName, containerStateId, 365);
                    reloadContainer(containerName);
                }
            }
            });
        },
        error: function (jqXHR, textStatus, thrownError) {
            jsonValue = jQuery.parseJSON(jqXHR.responseText);
            printAlert(jsonValue.message, 'error');
        },
    });
}

/**
 * Ajax: Get and reload table
 * @param {*} table
 * @param {*} offset
 */
function reloadTable(table, offset)
{
    printLoading();

    ajaxRequest(
        // Controller:
        'general',
        // Action:
        'getTable',
        // Data:
        {
            table: table,
            offset: offset,
            sourceUrl: window.location.href,
            sourceUri: window.location.pathname,
            sourceGetParameters: getGetParams()
        },
        // Print success alert:
        false,
        // Print error alert:
        true,
        // Reload container:
        [],
        // Execute functions on success:
        [
            // Replace table with itself, with new content
            "$('.reloadable-table[table=\"" + table + "\"]').replaceWith(jsonValue.message)"
        ]
    );

    /**
     *  Hide loading icon
     */
    hideLoading();
}

/**
 * Ajax: Get and print alert box
 * @param {*} name
 */
function getConfirmBox(name)
{
    ajaxRequest(
        // Controller:
        'general',
        // Action:
        'getConfirmBox',
        // Data:
        {
            name: name
        },
        // Print success alert:
        false,
        // Print error alert:
        true,
        // Reload containers:
        [],
        // Execute functions on success:
        [
            "closeConfirmBox()",
            "$('#newalert').remove()",
            "$('footer').append(jsonValue.message)"
        ]
    );
}
