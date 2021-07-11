// Clipboard
clipStore = '';

function clipCopy() {
    // Ignore copying if clipStore empty
    if (clipStore === '') return;

    // Check if clipboard API is available
    if (typeof navigator.clipboard === 'undefined') {
        $('.msg .clipStatus').html(' (Copy failed)');
        console.error('Error: Failed to utilise clipboard API -- ensure this website is being accessed via HTTPS and that you are using a supported browser.');
        return;
    } else {
        // Not ideal, but this is to avoid tripping a code verification issue
        // on very old Safari versions, even if the code will never be
        // executed on such versions. I know. I hate it, too.
        toExec = "navigator.clipboard.writeText(clipStore).then(() => {$('.msg .clipStatus').html(' (Copied)');}).catch(err => {$('.msg .clipStatus').html(' (Copy failed)');console.error('Error: Failed to utilise clipboard API: ' + err);});";
        eval(toExec);
    }
}

// Anchor link handler
$(document).on('click', 'a[href^="#"]', function (event) {
    event.preventDefault();

    $('html, body').animate({
        scrollTop: $($.attr(this, 'href')).offset().top
    }, 500);
});

$(document).on('click', 'button[name="getstarted"]', function (event) {
    event.preventDefault();

    $('html, body').animate({
        scrollTop: $('#shortenerform').offset().top
    }, 500);
});

// Store step data
stepIdentiferStore = [];
// Maximum allowed additional steps
stepCountMax = 4;

// Message builder
function msgBuild(type, msg, copy, log) {

    // Hold on to count
    stepCount = stepIdentiferStore.length;

    // Update message type
    if (type === 'success') {
        if ($('.msg').hasClass('error')) {
            $('.msg').removeClass('error');
            $('.msg').addClass('success');
        } else if (!$('.msg').hasClass('success')) {
            $('.msg').addClass('success');
        }
    } else {
        if ($('.msg').hasClass('success')) {
            $('.msg').removeClass('success');
            $('.msg').addClass('error');
        } else if (!$('.msg').hasClass('error')) {
            $('.msg').addClass('error');
        }
        if (log) {
            console.error('Error: ' + copy);
        }
    }

    // Set message
    $('.msg .txt').html(msg);

    // Copy to clipboard
    window.clipStore = copy;

    // Show message
    if ($('.msg').hasClass('hidden')) {
        $('.msg').removeClass('hidden');
    }
    
    // Enable form
    formLock(false);
    
    // Scroll back up
    $('html,body').animate({
        scrollTop: $('#shortenerform').offset().top
    }, 500);
    
}

// Form handler
// Form lock
function formLock(state) {
    if (state) {
        // Disable input
        $('input[type=button]').prop('disabled', true);
        $('input[type=submit]').prop('disabled', true);
    } else {
        // Enable input
        $('input[type=button]').prop('disabled', false);
        $('input[type=submit]').prop('disabled', false);

        // Disable button if no extra steps
        if (stepIdentiferStore.length === 0) {
            $('.remove').prop('disabled', true);
        }
        // Disable button if reached maximum steps
        if (stepIdentiferStore.length === stepCountMax) {
            $('.add').prop('disabled', true);
        }
    }
}

// Submission handler
$(document).on('submit', '.stepDat', function(e) {
    
    // Lock form
    formLock(true);

    formDat();
    
    return false;
});

// JSON format verifier
function checkJSON(jsonIn, skipString) {
    try {
        if (!skipString) {
            jsonVer = window.JSON.stringify(jsonIn);
            jsonVer = window.JSON.parse(jsonVer);
        } else {
            jsonVer = window.JSON.parse(jsonIn);
        }
        if (jsonVer && typeof jsonVer === "object" && jsonVer !== null) {
            return true;
        }
    } catch (e) {
        console.error('JSON validation failed :: ' + e);
    }
    return false;
}

function formDat() {
    // Send out request
    $.post('/backend/create.php', $('.stepDat').serialize())
    .done(function(data) {

        resType = 'error';
        
        if (data !== '') {
            
            if (checkJSON(data, false)) {
                
                jsonDat = window.JSON.stringify(data);
                jsonDat = window.JSON.parse(jsonDat);
                resType = jsonDat.type;
                resMsg = jsonDat.message;
                
                
                if (resType === 'success') {
                    
                    // Reset URL fields
                    $('input[type=url]').val('');
                    // Reset text fields
                    $('input[type=text]').val('');
                    // Reset all dropdowns
                    $('select').val($('select option:first').val());
                    
                    // Remove additional
                    $('.addedstep').remove();
                    stepIdentiferStore = [];
                    // Message
                    text = '<b>Link created <span class="clipStatus"></span></b><br>' + resMsg;
                    msgBuild(resType, text, resMsg, true);
                } else {
                    // Error messages in a valid response
                    // Message
                    text = '<b>Error<span class="clipStatus"></span></b><br>' + resMsg;
                    msgBuild(resType, text, resMsg, false);

                }
            } else {
                // Response found to not be in JSON format
                // Store error message in variable ready for copying
                error = 'Response not JSON formatted -- Response data: ' + data;
                // Set message
                text = '<b>Error<span class="clipStatus"></span></b><br>Malformed response';
                msgBuild(resType, text, error, true);
            }
        } else {
            // Response found to be empty
            // Store error message in variable ready for copying
            error = 'Empty response';

            // Set message
            text = '<b>Error<span class="clipStatus"></span></b><br>Empty response';
            msgBuild(resType, text, error, true);
        }
    })
    .fail(function(_, textStatus, errorThrown) {
        
        // Failed request or bad response
        // Store error message in variable ready for copying
        error = textStatus + ' ' + errorThrown;

        // Set message
        text = '<b>Error<span class="clipStatus"></span></b><br>Communication error';
        msgBuild('error', text, error, true);
    });
}

$(document).ready(function () {
    $(document).on('click', '.clippy', function (event) {
        event.preventDefault();

        clipCopy();
    });

    $(document).on('change', '.advancedcheckbox', function (_) {
        checked = $(this).is(':checked');
        element = $(this).parent()[0].parentElement.children[1];
        if (checked) {
            $(element).addClass('show');
        } else {
            $(element).removeClass('show');
        }
    });

    i = 0;
    // Check and copy data from default input boxes
    if ($('.dynamic_field') !== null && $('.dynamic_field').length > 0) {
        mainContents = $('.dynamic_field')[0].innerHTML;
    }

    // Add step
    $('.add').click(function (event) {
        event.preventDefault();

        // Disallow additional fields if hit limit
        if (stepIdentiferStore.length === stepCountMax) return;

        i++;

        // (Re)set temporary store
        mainTempContents = '';

        // On-the-fly patching
        mainTempContents = '<hr class="dash">' + mainContents;
        mainTempContents = mainTempContents.replace(/(id="advancedtoggle)[0-9]+(")/, '$1' + i + '$2');
        mainTempContents = mainTempContents.replace(/(for="advancedtoggle)[0-9]+(")/, '$1' + i + '$2');

        // Clone data
        stepBuilder = '';
        stepBuilder += '<div class="dynamic_field addedstep step' + i + '">';
        stepBuilder += mainTempContents;
        stepBuilder += '</div>';
        $('.fieldblock').append(stepBuilder);

        stepIdentiferStore.push(i);

        // Disable button if reached maximum steps
        if (stepIdentiferStore.length === stepCountMax) {
            $('.add').prop('disabled', true);
        }

        $('.remove').prop('disabled', false);
    });
    // Remove step
    $(document).on('click', '.remove', function (event) {
        event.preventDefault();

        // Halt execution
        if (stepIdentiferStore.length === 0) return;

        button_id = stepIdentiferStore[(stepIdentiferStore.length - 1)];
        $('.step' + button_id + '').remove();

        i--;

        stepIdentiferStore.pop();

        // Disable button if no extra steps
        if (stepIdentiferStore.length === 0) {
            $('.remove').prop('disabled', true);
        }

        // Re-enable add button if no longer maxed out
        if (stepIdentiferStore.length === (stepCountMax - 1)) {
            $('.add').prop('disabled', false);
        }
    });
});
