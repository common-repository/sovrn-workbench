(function($) {
    'use strict';

    $(document).ready(function() {

        // var numAd = 1;

        if (sovrnMixpanelToken) {
            initializeMixpanel();
            setMixpanelListeners();
        }

        adminMenuRouting();
        pageIdModalController();
        setWelcomeModalListeners();
        sovrnNewModalController();
        sovrnUserModalController();
        amp_admin_settings();
        ampStepCheck();
        setToggleAmpButton();
        setToggleTwitterButton();
        setDisconnectTwitterButton();
        setToggleFacebookButton();
        setToggleFacebookInstantArticlesButton();
        setDisconnectFacebookButton();
        setToggleGooglePlusButton();
        setDisconnectGooglePlusButton();
        setToggleAppleNewsButton();
        setDisconnectAppleNewsButton();
        setPublishModal();
        setPublishButtonName();
        setFbiaSignUpButton();

        // close_ad();
        // render_ad_inputs();
        // add_another_ad();
        // close_ad();

    });


    function initializeMixpanel() {

        // load mixpanel library
        (function(e,a){if(!a.__SV){var b=window;try{var c,l,i,j=b.location,g=j.hash;c=function(a,b){return(l=a.match(RegExp(b+"=([^&]*)")))?l[1]:null};g&&c(g,"state")&&(i=JSON.parse(decodeURIComponent(c(g,"state"))),"mpeditor"===i.action&&(b.sessionStorage.setItem("_mpcehash",g),history.replaceState(i.desiredHash||"",e.title,j.pathname+j.search)))}catch(m){}var k,h;window.mixpanel=a;a._i=[];a.init=function(b,c,f){function e(b,a){var c=a.split(".");2==c.length&&(b=b[c[0]],a=c[1]);b[a]=function(){b.push([a].concat(Array.prototype.slice.call(arguments,
            0)))}}var d=a;"undefined"!==typeof f?d=a[f]=[]:f="mixpanel";d.people=d.people||[];d.toString=function(b){var a="mixpanel";"mixpanel"!==f&&(a+="."+f);b||(a+=" (stub)");return a};d.people.toString=function(){return d.toString(1)+".people (stub)"};k="disable time_event track track_pageview track_links track_forms register register_once alias unregister identify name_tag set_config reset people.set people.set_once people.increment people.append people.union people.track_charge people.clear_charges people.delete_user".split(" ");
            for(h=0;h<k.length;h++)e(d,k[h]);a._i.push([b,c,f])};a.__SV=1.2;b=e.createElement("script");b.type="text/javascript";b.async=!0;b.src="undefined"!==typeof MIXPANEL_CUSTOM_LIB_URL?MIXPANEL_CUSTOM_LIB_URL:"file:"===e.location.protocol&&"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js".match(/^\/\//)?"https://cdn.mxpnl.com/libs/mixpanel-2-latest.min.js":"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js";c=e.getElementsByTagName("script")[0];c.parentNode.insertBefore(b,c)}})(document,window.mixpanel||[]);

        // initialize mixpanel library with token
        mixpanel.init(sovrnMixpanelToken);

        // set sovrnSite as mixpanel identity
        mixpanel.identify(sovrnSite);

    }


    function handleMixpanelEvent(e) {

        // set event names
        var labels = {
            switch_clicked: 'Switch Clicked',
            button_clicked: 'Button Clicked',
            link_clicked:   'Link Clicked',
            tab_clicked:    'Tab Clicked',
            element_name:   'Element Name',
        };

        var elem = $(e.currentTarget);
        var labelId = elem.attr('data-sovrn-mixpanel-event');
        var elemName = elem.attr('data-sovrn-mixpanel-element-name');
        var label = labels[labelId];
        var elemNameLabel = labels['element_name'];
        var props = {};
        props[elemNameLabel] = elemName;

        if (sovrnMixpanelToken) {
            mixpanel.track(label, props);
        }

        // console.log(label, props);

    }


    function setMixpanelListeners() {

        // find all mixpanel event elements and add listener
        $('[data-sovrn-mixpanel-event]').unbind('mousedown').mousedown(handleMixpanelEvent);

    }


    var adTwo = $('#amp-ad-two');
    var adThree = $('#amp-ad-three');
    var adFour = $('#amp-ad-four');
    var adFive = $('#amp-ad-five');
    var adSix = $('#amp-ad-six');


    function adminMenuRouting() {
        var fbiaTab = $('#fbia-tab');
        var ampTab = $('#amp-tab');
        var appleTab = $('#apple-tab');
        var sovrnTab = $('#sovrn-tab');

        var fbiaLayout = $('#fbia-layout');
        var ampLayout = $('#amp-layout');
        var appleLayout = $('#apple-layout');
        var sovrnLayout = $('#sovrn-layout');

        var adminTab = $('.admin-tab');

        sovrnLayout.addClass('sovrn-inactive');
        fbiaLayout.addClass('sovrn-inactive');
        appleLayout.addClass('sovrn-inactive');

        ampTab.addClass('sovrn-active');

        fbiaTab.click(function() {
            fbiaTab.addClass('sovrn-active');
            ampTab.removeClass('sovrn-active');
            appleTab.removeClass('sovrn-active');
            sovrnTab.removeClass('sovrn-active');
            fbiaLayout.removeClass('sovrn-inactive');
            ampLayout.addClass('sovrn-inactive');
            appleLayout.addClass('sovrn-inactive');
            sovrnLayout.addClass('sovrn-inactive');
        });

        ampTab.click(function() {
            ampTab.addClass('sovrn-active');
            fbiaTab.removeClass('sovrn-active');
            appleTab.removeClass('sovrn-active');
            sovrnTab.removeClass('sovrn-active');
            ampLayout.removeClass('sovrn-inactive');
            fbiaLayout.addClass('sovrn-inactive');
            appleLayout.addClass('sovrn-inactive');
            sovrnLayout.addClass('sovrn-inactive');
        });

        appleTab.click(function() {
            appleTab.addClass('sovrn-active');
            fbiaTab.removeClass('sovrn-active');
            ampTab.removeClass('sovrn-active');
            sovrnTab.removeClass('sovrn-active');
            appleLayout.removeClass('sovrn-inactive');
            ampLayout.addClass('sovrn-inactive');
            fbiaLayout.addClass('sovrn-inactive');
            sovrnLayout.addClass('sovrn-inactive');

        });

        sovrnTab.click(function() {
            sovrnTab.addClass('sovrn-active');
            fbiaTab.removeClass('sovrn-active');
            appleTab.removeClass('sovrn-active');
            ampTab.removeClass('sovrn-active');
            sovrnLayout.removeClass('sovrn-inactive');
            ampLayout.addClass('sovrn-inactive');
            appleLayout.addClass('sovrn-inactive');
            fbiaLayout.addClass('sovrn-inactive');
        });

        ampTab.hover(function() {
            ampTab.toggleClass('sovrn-hovered');
        });
        fbiaTab.hover(function() {
            fbiaTab.toggleClass('sovrn-hovered');
        });
        appleTab.hover(function() {
            appleTab.toggleClass('sovrn-hovered');
        });
        sovrnTab.hover(function() {
            sovrnTab.toggleClass('sovrn-hovered');
        });
        sovrnTab.hover(function() {
            sovrnTab.toggleClass('sovrn-hovered');
        });

    }


    function pageIdModalController() {
        var pidOpen = $('#open-pid');
        var pidModal = $('#page-id-modal');
        var pidClose = $('#page-id-close');
        var pidBackground = $('#page-id-modal-bg');

        pidOpen.click(function(){
            pidBackground.css({'display':'inline-block'});
            pidModal.css({'display':'inline-block'});
            window.scrollTo(0,0);
        });
        pidClose.click(function(){
            pidBackground.css({'display':'none'});
            pidModal.css({'display':'none'});
        });

    }


    function setWelcomeModalListeners() {

        var checkboxTos = $('#checkbox-tos-elem input');
        var checkboxPrivacy = $('#checkbox-privacy-elem input');
        var welcomeDialogSubmitButton = $('#welcome-dialog-form #submit');
        welcomeDialogSubmitButton.prop('disabled', true);

        var handleTermsCheckboxClick = function(e) {

            var checkboxTosVal = checkboxTos.is(':checked');
            var checkboxPrivacyVal = checkboxPrivacy.is(':checked');

            if (checkboxTosVal && checkboxPrivacyVal) {
                welcomeDialogSubmitButton.prop('disabled', false);
            } else {
                welcomeDialogSubmitButton.prop('disabled', true);
            }

        };

        checkboxTos.click(handleTermsCheckboxClick);
        checkboxPrivacy.click(handleTermsCheckboxClick);

    }


    function sovrnNewModalController() {
        var sovrnNewOpen = $('#sovrn-new-open');
        var sovrnNewModal = $('#sovrn-new-modal');
        var sovrnNewClose = $('#sovrn-new-close');
        var sovrnNewBackground = $('#sovrn-new-modal-bg');

        sovrnNewOpen.click(function(){
            sovrnNewBackground.css({'display':'inline-block'});
            sovrnNewModal.css({'display':'inline-block'});
            window.scrollTo(0,0);
        });
        sovrnNewClose.click(function(){
            sovrnNewBackground.css({'display':'none'});
            sovrnNewModal.css({'display':'none'});
        });

    }


    function sovrnUserModalController() {
        var sovrnUserOpen = $('#sovrn-user-open');
        var sovrnUserModal = $('#sovrn-user-modal');
        var sovrnUserClose = $('#sovrn-user-close');
        var sovrnUserBackground = $('#sovrn-user-modal-bg');

        sovrnUserOpen.click(function(){
            sovrnUserBackground.css({'display':'inline-block'});
            sovrnUserModal.css({'display':'inline-block'});
            window.scrollTo(0,0);
        });
        sovrnUserClose.click(function(){
            sovrnUserBackground.css({'display':'none'});
            sovrnUserModal.css({'display':'none'});
        });

    }


    // **********************
    // * AMP ADMIN SETTINGS *
    // **********************


    function amp_admin_settings() {
        /**
         *
         * * * * * * * * * * * * * * *
         * Color Picker
         * * * * * * * * * * * * * * *
         *
         */

        // $('.my-color-field').wpColorPicker();


        /**
         *
         * * * * * * * * * * * * * * *
         * Logo Preview/Remove
         * * * * * * * * * * * * * * *
         *
         */

        var logo = $('input#client_logo').val();
        var logopreview = $('.client_logo');
        var logorm = $('.logo-rm');
        var logochoose = $('#client_logo_button');
        var logosave = $('.logo-save');
        var mediainsert = $('.media-button-insert');

        $(logosave).hide();

        if( logo ){
            $(logopreview).attr('src',logo);
            $(logorm).show();
        } else {
            $(logopreview).hide();
            $(logorm).hide();
        }

        $(logorm).on('click', function(){
            $('input#client_logo').val('');
            $(logorm).css('display','none');
            $(logochoose).css('display','none');
            $(logopreview).hide();
            $(logosave).fadeIn();
        });


        /**
         *
         * * * * * * * * * * * * * * *
         * Media Uploader
         * * * * * * * * * * * * * * *
         *
         */

        var _custom_media = true,
            _orig_send_attachment = wp.media.editor.send.attachment;

        $('.uploader .admin-button').click(function(e) {
            var send_attachment_bkp = wp.media.editor.send.attachment;
            var button = $(this);
            var id = button.attr('id').replace('_button', '');
            _custom_media = true;
            wp.media.editor.send.attachment = function(props, attachment){
                if ( _custom_media ) {
                    $("#"+id).val(attachment.url);
                    $(logopreview).show();
                    $(logopreview).attr('src',attachment.url);
                    $(logochoose).css('display','none');
                    $(logosave).fadeIn();
                } else {
                    return _orig_send_attachment.apply( this, [props, attachment] );
                };
            }

            wp.media.editor.open(button);
            return false;
        });

        $('.add_media').on('click', function(){
            _custom_media = false;
        });

        // amp page design preview
        var titlebar = $('input#client_hex_color').val();
        $('.amp-preview .title-bar').css('background',titlebar);


        // **********************
        // * AMP ADMIN SETTINGS *
        // **********************

        // get default tab id
        var getDefaultTabId = function() {

            var availableTabs = $('ul.tabs li')
                .map(function(i, elem) {
                    var tabId = $(elem).attr('data-tab');
                    if (tabId) {
                        return tabId;
                    }
                }).toArray();

            // set default as first tab id (sovrn)
            var defaultTabId = availableTabs[0];

            // check if isTermsAgreed, isRegistered, isLoggedIn
            if ((typeof isRegistered !== 'undefined' && isRegistered === true) &&
                (typeof isLoggedIn !== 'undefined' && isLoggedIn === true)) {
                var hash = window.location.hash.substring(1)
                if (hash && availableTabs.indexOf(hash) > -1) {
                    defaultTabId = hash;
                }
            }

            return defaultTabId;

        };

        // set selected tab
        var setSelectedTab = function(tabId) {
            $('ul.tabs li').removeClass('current');
            $('.tab-content').removeClass('current');
            $('#'+tabId+'-tab').addClass('current');
            $('#'+tabId+'-tab-content').addClass('current');
        };

        // set default tab on page load
        setSelectedTab(getDefaultTabId());

        // set click listener for tab switching
        $('ul.tabs li').click(function(){
            var tabId = $(this).attr('data-tab');
            setSelectedTab(tabId);
        });

        // set click listener for tab switching
        $('.sovrn-tab-button').click(function(){
            setSelectedTab('sovrn');
        });

        // privacy-policy dialog
        var dialog5 = $('dialog#privacy-policy-dialog')[0];
        var showDialogButton5 = $('#show-privacy-policy-dialog')[0];
        var showDialogButton5sso = $('#show-sso-privacy-policy-dialog')[0];
        if (dialog5 && showDialogButton5sso && showDialogButton5) {
            if (!dialog5.showModal) {
                dialogPolyfill.registerDialog(dialog5);
            }
            showDialogButton5.addEventListener('click', function () {
                dialog5.showModal();
            });
            showDialogButton5sso.addEventListener('click', function () {
                dialog5.showModal();
            });
            dialog5.querySelector('.close').addEventListener('click', function () {
                dialog5.close();
            });
        }

        // terms-and-conditions dialog
        var dialog6 = $('dialog#terms-and-conditions-dialog')[0];
        var showDialogButton6 = $('#show-terms-and-conditions-dialog')[0];
        var showDialogButton6sso = $('#show-sso-terms-and-conditions-dialog')[0];
        if (dialog6 && showDialogButton6 && showDialogButton6sso) {
            if (!dialog6.showModal) {
                dialogPolyfill.registerDialog(dialog6);
            }
            showDialogButton6.addEventListener('click', function () {
                dialog6.showModal();
            });
            showDialogButton6sso.addEventListener('click', function () {
                dialog6.showModal();
            });
            dialog6.querySelector('.close').addEventListener('click', function () {
                dialog6.close();
            });
        }


        // facebook dialog
        var dialog2 = $('dialog#facebook-dialog')[0];
        var showDialogButton2 = $('#show-dialog-fb')[0];
        if (dialog2 && showDialogButton2) {
            if (!dialog2.showModal) {
                dialogPolyfill.registerDialog(dialog2);
            }
            showDialogButton2.addEventListener('click', function() {
                dialog2.showModal();
            });
            dialog2.querySelector('.close').addEventListener('click', function() {
                dialog2.close();
            });
        }

    }

    function ampStepCheck() {
        var stepTwoContainer = $('#step-container-two');
        var stepThreeContainer = $('#step-container-three');

        var colorInput = $('#client_hex_color').val();
        var logoInput = $('#client_logo').val();
        var gaInput = $('#client_ga_id').val();

        var adOne = $('#ampAdOne').val();
        var adTwo = $('#ampAdTwo').val();
        var adThree = $('#ampAdThree').val();
        var adFour = $('#ampAdFour').val();
        var adFive = $('#ampAdFive').val();
        var adSix = $('#ampAdSix').val();

        // Step 2
        if(colorInput || logoInput || gaInput) {
            stepTwoContainer.css("background-color","#e5bb00");
        }

        // Step 3
        if(adOne || adTwo || adThree || adFour || adFive || adSix) {
            stepThreeContainer.css("background-color","#e5bb00");
        }

    }


    // handle admin notice
    var handleAdminNotice = function(noticeType, msg) {

        var noticeElem = $('<div class="notice notice-'+noticeType+' is-dismissible sovrn-notice"><p>'+msg+'</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>');
        var closeAdminNotice = function() {
            noticeElem.removeClass('sovrn-notice-show');
            setTimeout(function() {
                noticeElem.remove();
            }, 250);
        };
        noticeElem.on('click', 'button', closeAdminNotice);
        setTimeout(function() {
            closeAdminNotice();
        }, 10000);

        var wpBodyContent = $('#wpbody-content').first();
        var wpBodyContentWrapH1 = $('#wpbody-content .wrap h1').first();

        if (wpBodyContentWrapH1.length) {
            noticeElem.insertAfter(wpBodyContentWrapH1);
        } else {
            noticeElem.prependTo(wpBodyContent);
        }

        setTimeout(function() {
            noticeElem.addClass('sovrn-notice-show');
        }, 10);

    };


    var showConfirmDialog = function(dialogName, title, msg, actions) {

        // get confirm dialog elements
        var dialog = $('#confirm-dialog');
        var dialogTitle = $('#confirm-dialog-title');
        var dialogMsg = $('#confirm-dialog-msg');
        var dialogActions = $('#confirm-dialog-actions');

        // define close dialog function
        var closeDialog = function() {

            // close dialog
            dialog[0].close();

            // clear contents of dialog title
            dialogTitle.html('');

            // clear contents of dialog msg
            dialogMsg.html('');

            // clear contents of dialog actions
            dialogActions.html('');

        };

        // copy, reverse, and iterate on actions array
        // each action contains a label, callback function, id,  and boolean for closing dialog
        actions
            .slice()
            .reverse()
            .forEach(function(item, i) {

                // create action element
                var actionName = item[0].replace(/ /g, '-').toLowerCase();
                var elemName = 'confirm-'+dialogName+'-dialog-'+actionName;
                var actionElem = $('<button type="button" class="mdl-button">'+item[0]+'</button>');
                actionElem.attr('data-sovrn-mixpanel-event', 'button_clicked');
                actionElem.attr('data-sovrn-mixpanel-element-name', elemName);
                actionElem.attr('id', actionName+"-disconnect");

                // add click listener for action element
                actionElem.click(function(e) {
                    if (item[1]) {
                        item[1]();
                    }
                    if (item[2]) {
                        closeDialog();
                    }
                    handleMixpanelEvent(e);
                });

                // append action element to dialog actions
                dialogActions.append(actionElem);

            });

        // insert title of dialog
        dialogTitle.html(title);

        // insert msg of dialog
        dialogMsg.html(msg);

        // apply polyfill to dialog
        if (!dialog[0].showModal) {
            dialogPolyfill.registerDialog(dialog[0]);
        }

        // show dialog
        dialog[0].showModal();

    };


    function setToggleAmpButton() {

        // get MDL switch element
        var toggleAmpSwitch = $('#toggle-amp-switch');

        // define toggle amp function
        var doToggleAmp = function() {

            // set variables
            var form = $('form#toggle-amp-form');
            var formInput = $('input#toggle-amp-input');
            var progressBar = $('#toggle-amp-progress-bar');
            var mdlSwitchElem = toggleAmpSwitch;
            var mdlSwitch = mdlSwitchElem[0];
            var val = formInput.val();
            var newVal = val === '1' ? '' : '1';
            var data = form.serialize();

            // handle start
            var handleStart = function() {
                mdlSwitchElem.css('pointer-events', 'none');
                progressBar.addClass('sovrn-mdl-progress-show');
            };

            // handle end
            var handleEnd = function() {
                mdlSwitchElem.css('pointer-events', 'auto');
                progressBar.removeClass('sovrn-mdl-progress-show');
                formInput.val(newVal);
                if (newVal === '1') {
                    mdlSwitch.MaterialSwitch.on();
                } else {
                    mdlSwitch.MaterialSwitch.off();
                }
            };

            // handle error
            var handleError = function() {
                progressBar.removeClass('sovrn-mdl-progress-show');
                formInput.val(val);
                if (val === '1') {
                    mdlSwitch.MaterialSwitch.on();
                } else {
                    mdlSwitch.MaterialSwitch.off();
                }
            };

            // make post request
            $.post('options.php', data)
                .success( function() {
                    handleEnd();
                    var msg = newVal === '1' ? 'AMP is now enabled.' : 'AMP is now disabled.';
                    handleAdminNotice('success', msg);
                })
                .error( function() {
                    handleEnd();
                    handleError();
                    handleAdminNotice('error', 'An error has occurred.');
                });

            // start
            handleStart();

        };

        // add click listener for MDL switch
        toggleAmpSwitch.unbind('mousedown').mousedown(function(e) {

            // set actions required for confirm dialog
            var actions = [
                ['Cancel', null, true],
                ['Confirm', doToggleAmp.bind(this), true],
            ];

            // get current toggle state of switch
            var isEnabled = toggleAmpSwitch.children('input[type=checkbox]').is(':checkbox:checked');

            // check if toggle is currently enabled
            if (isEnabled) {

                // show confirm dialog with desire arguments
                showConfirmDialog('disable-amp', 'Disable AMP', 'Are you sure you want to disable AMP?', actions);

            } else {

                // trigger toggling AMP
                doToggleAmp();

            }

        });

    }


    function setToggleTwitterButton() {

        // get MDL switch element
        var toggleTwitterSwitch = $('#toggle-twitter-switch');

        // define toggle twitter function
        var doToggleTwitter = function() {

            // set variables
            var form = $('form#toggle-twitter-form');
            var formInput = $('input#toggle-twitter-input');
            var progressBar = $('#toggle-twitter-progress-bar');
            var mdlSwitchElem = toggleTwitterSwitch;
            var mdlSwitch = mdlSwitchElem[0];
            var val = formInput.val();
            var newVal = val === '1' ? '' : '1';
            var data = form.serialize();

            // handle start
            var handleStart = function() {
                mdlSwitchElem.css('pointer-events', 'none');
                progressBar.addClass('sovrn-mdl-progress-show');
            };

            // handle end
            var handleEnd = function() {
                mdlSwitchElem.css('pointer-events', 'auto');
                progressBar.removeClass('sovrn-mdl-progress-show');
                formInput.val(newVal);
                if (newVal === '1') {
                    mdlSwitch.MaterialSwitch.on();
                } else {
                    mdlSwitch.MaterialSwitch.off();
                }
            };

            // handle error
            var handleError = function() {
                progressBar.removeClass('sovrn-mdl-progress-show');
                formInput.val(val);
                if (val === '1') {
                    mdlSwitch.MaterialSwitch.on();
                } else {
                    mdlSwitch.MaterialSwitch.off();
                }
            };

            // make post request
            $.post('options.php', data)
                .success( function(res) {
                    handleEnd();
                    var msg = newVal === '1' ? 'Twitter sharing is now enabled.' : 'Twitter sharing is now disabled.';
                    handleAdminNotice('success', msg);
                })
                .error( function(err) {
                    handleEnd();
                    handleError();
                    handleAdminNotice('error', 'An error has occurred.');
                });

            // start
            handleStart();


        };

        // add click listener for MDL switch
        toggleTwitterSwitch.unbind('mousedown').mousedown(function(e) {

            // set actions required for confirm dialog
            var actions = [
                ['Cancel', null, true],
                ['Confirm', doToggleTwitter.bind(this), true],
            ];

            // get current toggle state of switch
            var isEnabled = toggleTwitterSwitch.children('input[type=checkbox]').is(':checkbox:checked');

            // check if toggle is currently enabled
            if (isEnabled) {

                // show confirm dialog with desire arguments
                showConfirmDialog('disable-twitter', 'Disable Twitter sharing', 'Are you sure you want to disable Twitter sharing?', actions);

            } else {

                // trigger toggling twitter sharing
                doToggleTwitter();

            }

        });

    }


    function setDisconnectTwitterButton() {

        // get disconnect twitter button element
        var disconnectTwitterButton = $('#disconnect-twitter-button');

        // define disconnect twitter function
        var doDisconnectTwitter = function() {

            // get disconnect twitter form element
            var form = $('form#disconnect-twitter-form');

            // submit form
            form.submit();

        };

        // add click listener for disconnect twitter button
        disconnectTwitterButton.unbind('mousedown').mousedown(function(e) {

            // set actions required for confirm dialog
            var actions = [
                ['Cancel', null, true],
                ['Confirm', doDisconnectTwitter.bind(this), true],
            ];

            // show confirm dialog with desire arguments
            showConfirmDialog('disconnect-twitter', 'Disconnect Twitter', 'Are you sure you want to disconnect your Twitter account?', actions);

        });

    }


    function setToggleFacebookButton() {

        // get MDL switch element
        var toggleFacebookSharingSwitch = $('#toggle-fb-share-switch');

        // define toggle facebook function
        var doToggleFacebook = function() {

            // set variables
            var form = $('form#toggle-facebook-form');
            var formInput = $('input#toggle-facebook-input');
            var progressBar = $('#toggle-facebook-progress-bar');
            var mdlSwitchElem = toggleFacebookSharingSwitch;
            var mdlSwitch = mdlSwitchElem[0];
            var val = formInput.val();
            var newVal = val === '1' ? '' : '1';
            var data = form.serialize();

            // handle start
            var handleStart = function() {
                mdlSwitchElem.css('pointer-events', 'none');
                progressBar.addClass('sovrn-mdl-progress-show');
            };

            // handle end
            var handleEnd = function() {
                mdlSwitchElem.css('pointer-events', 'auto');
                progressBar.removeClass('sovrn-mdl-progress-show');
                formInput.val(newVal);
                if (newVal === '1') {
                    mdlSwitch.MaterialSwitch.on();
                } else {
                    mdlSwitch.MaterialSwitch.off();
                }
            };

            // handle error
            var handleError = function() {
                progressBar.removeClass('sovrn-mdl-progress-show');
                formInput.val(val);
                if (val === '1') {
                    mdlSwitch.MaterialSwitch.on();
                } else {
                    mdlSwitch.MaterialSwitch.off();
                }
            };

            // make post request
            $.post('options.php', data)
                .success( function(res) {
                    handleEnd();
                    var msg = newVal === '1' ? 'Facebook sharing is now enabled.' : 'Facebook sharing is now disabled.';
                    handleAdminNotice('success', msg);
                })
                .error( function(err) {
                    handleEnd();
                    handleError();
                    handleAdminNotice('error', 'An error has occurred.');
                });

            // start
            handleStart();


        };

        // add click listener for MDL switch
        toggleFacebookSharingSwitch.unbind('mousedown').mousedown(function(e) {

            // set actions required for confirm dialog
            var actions = [
                ['Cancel', null, true],
                ['Confirm', doToggleFacebook.bind(this), true],
            ];

            // get current toggle state of switch
            var isEnabled = toggleFacebookSharingSwitch.children('input[type=checkbox]').is(':checkbox:checked');

            // check if toggle is currently enabled
            if (isEnabled) {

                // show confirm dialog with desire arguments
                showConfirmDialog('disable-facebook', 'Disable Facebook sharing', 'Are you sure you want to disable Facebook sharing?', actions);

            } else {

                // trigger toggling facebook sharing
                doToggleFacebook();

            }

        });

    }


    function setToggleFacebookInstantArticlesButton() {

        // get MDL switch element
        var toggleFBIASwitch = $('#toggle-fbia-switch');

        // define toggle facebook function
        var doToggleFacebookInstantArticles = function() {

            // set variables
            var form = $('form#start-fbia-form');
            var formInput = $('input#toggle-fbia-input');
            var progressBar = $('#toggle-fbia-progress-bar');
            var mdlSwitchElem = toggleFBIASwitch;
            var mdlSwitch = mdlSwitchElem[0];
            var val = formInput.val();
            var newVal = val === '1' ? '' : '1';
            var data = form.serialize();

            // handle start
            var handleStart = function() {
                mdlSwitchElem.css('pointer-events', 'none');
                progressBar.addClass('sovrn-mdl-progress-show');
            };

            // handle end
            var handleEnd = function() {
                mdlSwitchElem.css('pointer-events', 'auto');
                progressBar.removeClass('sovrn-mdl-progress-show');
                formInput.val(newVal);

                if (newVal === '1') {
                    mdlSwitch.MaterialSwitch.on();
                } else {
                    mdlSwitch.MaterialSwitch.off();
                }
            };

            // handle error
            var handleError = function() {
                progressBar.removeClass('sovrn-mdl-progress-show');
                formInput.val(val);
                if (val === '1') {
                    mdlSwitch.MaterialSwitch.on();
                } else {
                    mdlSwitch.MaterialSwitch.off();
                }
            };

            // make post request
            $.post('options.php', data)
                .success( function(res) {
                    handleEnd();
                    var msg = newVal === '1' ? 'Facebook Instant Articles is now enabled.' : 'Facebook Instant Articles is now disabled.';
                    handleAdminNotice('success', msg);
                })
                .error( function(err) {
                    handleEnd();
                    handleError();
                    handleAdminNotice('error', 'An error has occurred.');
                });

            // start
            handleStart();


        };

        // add click listener for MDL switch
        toggleFBIASwitch.unbind('mousedown').mousedown(function(e) {

            // set actions required for confirm dialog
            var actions = [
                ['Cancel', null, true],
                ['Confirm', doToggleFacebookInstantArticles.bind(this), true],
            ];

            // get current toggle state of switch
            var isEnabled = toggleFBIASwitch.children('input[type=checkbox]').is(':checkbox:checked');

            // check if toggle is currently enabled
            if (isEnabled) {

                // show confirm dialog with desire arguments
                showConfirmDialog('disable-fbia', 'Disable Facebook Instant Articles', 'Are you sure you want to disable Facebook Instant Articles?', actions);

            } else {

                // trigger toggling facebook sharing
                doToggleFacebookInstantArticles();

            }

        });

    }


    function setDisconnectFacebookButton() {

        // get disconnect facebook button element
        var disconnectFacebookButton = $('#disconnect-facebook-button');

        // define disconnect facebook function
        var doDisconnectFacebook = function() {

            // get disconnect facebook form element
            var form = $('form#disconnect-facebook-form');

            // submit form
            form.submit();

        };

        // add click listener for disconnect facebook button
        disconnectFacebookButton.unbind('mousedown').mousedown(function(e) {

            // set actions required for confirm dialog
            var actions = [
                ['Cancel', null, true],
                ['Confirm', doDisconnectFacebook.bind(this), true],
            ];

            // show confirm dialog with desire arguments
            showConfirmDialog('disconnect-facebook', 'Disconnect Facebook', 'Are you sure you want to disconnect your Facebook account?', actions);

        });

    }


    function setToggleGooglePlusButton() {

        // get MDL switch element
        var toggleGooglePlusSwitch = $('#toggle-google-plus-switch');

        // define toggle google-plus function
        var doToggleGooglePlus = function() {

            // set variables
            var form = $('form#toggle-google-plus-form');
            var formInput = $('input#toggle-google-plus-input');
            var progressBar = $('#toggle-google-plus-progress-bar');
            var mdlSwitchElem = toggleGooglePlusSwitch;
            var mdlSwitch = mdlSwitchElem[0];
            var val = formInput.val();
            var newVal = val === '1' ? '' : '1';
            var data = form.serialize();

            // handle start
            var handleStart = function() {
                mdlSwitchElem.css('pointer-events', 'none');
                progressBar.addClass('sovrn-mdl-progress-show');
            };

            // handle end
            var handleEnd = function() {
                mdlSwitchElem.css('pointer-events', 'auto');
                progressBar.removeClass('sovrn-mdl-progress-show');
                formInput.val(newVal);
                if (newVal === '1') {
                    mdlSwitch.MaterialSwitch.on();
                } else {
                    mdlSwitch.MaterialSwitch.off();
                }
            };

            // handle error
            var handleError = function() {
                progressBar.removeClass('sovrn-mdl-progress-show');
                formInput.val(val);
                if (val === '1') {
                    mdlSwitch.MaterialSwitch.on();
                } else {
                    mdlSwitch.MaterialSwitch.off();
                }
            };

            // make post request
            $.post('options.php', data)
                .success( function(res) {
                    handleEnd();
                    var msg = newVal === '1' ? 'Google+ sharing is now enabled.' : 'Google+ sharing is now disabled.';
                    handleAdminNotice('success', msg);
                })
                .error( function(err) {
                    handleEnd();
                    handleError();
                    handleAdminNotice('error', 'An error has occurred.');
                });

            // start
            handleStart();


        };

        // add click listener for MDL switch
        toggleGooglePlusSwitch.unbind('mousedown').mousedown(function(e) {

            // set actions required for confirm dialog
            var actions = [
                ['Cancel', null, true],
                ['Confirm', doToggleGooglePlus.bind(this), true],
            ];

            // get current toggle state of switch
            var isEnabled = toggleGooglePlusSwitch.children('input[type=checkbox]').is(':checkbox:checked');

            // check if toggle is currently enabled
            if (isEnabled) {

                // show confirm dialog with desire arguments
                showConfirmDialog('disable-google-plus', 'Disable Google+ sharing', 'Are you sure you want to disable Google+ sharing?', actions);

            } else {

                // trigger toggling google-plus sharing
                doToggleGooglePlus();

            }

        });

    }


    function setDisconnectGooglePlusButton() {

        // get disconnect google-plus button element
        var disconnectGooglePlusButton = $('#disconnect-google-plus-button');

        // define disconnect google-plus function
        var doDisconnectGooglePlus = function() {

            // get disconnect google-plus form element
            var form = $('form#disconnect-google-plus-form');

            // submit form
            form.submit();

        };

        // add click listener for disconnect google-plus button
        disconnectGooglePlusButton.unbind('mousedown').mousedown(function(e) {

            e.preventDefault();

            // set actions required for confirm dialog
            var actions = [
                ['Cancel', null, true],
                ['Confirm', doDisconnectGooglePlus.bind(this), true],
            ];

            // show confirm dialog with desire arguments
            showConfirmDialog('disconnect-google-plus', 'Disconnect Google+', 'Are you sure you want to disconnect your Google+ account?', actions);

        });

    }


    function setToggleAppleNewsButton() {

        // get MDL switch element
        var toggleAppleNewsSwitch = $('#toggle-apple-news-switch');

        // define toggle apple-news function
        var doToggleAppleNews = function() {

            // set variables
            var form = $('form#toggle-apple-news-form');
            var formInput = $('input#toggle-apple-news-input');
            var progressBar = $('#toggle-apple-news-progress-bar');
            var mdlSwitchElem = toggleAppleNewsSwitch;
            var mdlSwitch = mdlSwitchElem[0];
            var val = formInput.val();
            var newVal = val === '1' ? '' : '1';
            var data = form.serialize();

            // handle start
            var handleStart = function() {
                mdlSwitchElem.css('pointer-events', 'none');
                progressBar.addClass('sovrn-mdl-progress-show');
            };

            // handle end
            var handleEnd = function() {
                mdlSwitchElem.css('pointer-events', 'auto');
                progressBar.removeClass('sovrn-mdl-progress-show');
                formInput.val(newVal);
                if (newVal === '1') {
                    mdlSwitch.MaterialSwitch.on();
                } else {
                    mdlSwitch.MaterialSwitch.off();
                }
            };

            // handle error
            var handleError = function() {
                progressBar.removeClass('sovrn-mdl-progress-show');
                formInput.val(val);
                if (val === '1') {
                    mdlSwitch.MaterialSwitch.on();
                } else {
                    mdlSwitch.MaterialSwitch.off();
                }
            };

            // make post request
            $.post('options.php', data)
                .success( function(res) {
                    handleEnd();
                    var msg = newVal === '1' ? 'Apple News sharing is now enabled.' : 'Apple News sharing is now disabled.';
                    handleAdminNotice('success', msg);
                })
                .error( function(err) {
                    handleEnd();
                    handleError();
                    handleAdminNotice('error', 'An error has occurred.');
                });

            // start
            handleStart();


        };

        // add click listener for MDL switch
        toggleAppleNewsSwitch.unbind('mousedown').mousedown(function(e) {

            // set actions required for confirm dialog
            var actions = [
                ['Cancel', null, true],
                ['Confirm', doToggleAppleNews.bind(this), true],
            ];

            // get current toggle state of switch
            var isEnabled = toggleAppleNewsSwitch.children('input[type=checkbox]').is(':checkbox:checked');

            // check if toggle is currently enabled
            if (isEnabled) {

                // show confirm dialog with desire arguments
                showConfirmDialog('disable-apple-news', 'Disable Apple News sharing', 'Are you sure you want to disable Apple News sharing?', actions);

            } else {

                // trigger toggling apple-news sharing
                doToggleAppleNews();

            }

        });

    }


    function setDisconnectAppleNewsButton() {

        // get disconnect apple-news button element
        var disconnectAppleNewsButton = $('#disconnect-apple-news-button');

        // define disconnect apple-news function
        var doDisconnectAppleNews = function() {

            // get disconnect apple-news form element
            var form = $('form#disconnect-apple-news-form');

            // submit form
            form.submit();

        };

        // add click listener for disconnect apple-news button
        disconnectAppleNewsButton.unbind('mousedown').mousedown(function(e) {

            // set actions required for confirm dialog
            var actions = [
                ['Cancel', null, true],
                ['Confirm', doDisconnectAppleNews.bind(this), true],
            ];

            // show confirm dialog with desire arguments
            showConfirmDialog('disconnect-apple-news', 'Disconnect Apple News', 'Are you sure you want to disconnect your Apple News account?', actions);

        });

    }


    function setFbiaSignUpButton() {

        // add fbia sign up button listener
        $('#fbia-sign-up-button').click(function(e) {



        });

        // add fbia in progress switch listener
        $('#fbia-in-progress-switch').click(function(e) {

            // open new window is href url
            window.open($(this).attr('href'));

        });

    }


    function setPublishModal() {

        var publishModalLayout = $('#publish-modal-layout');

        // toggle social option
        var toggleSocialOption = function(e) {
            var elem = $(this);
            if (elem.hasClass('publish-modal-social-inactive')) {
                elem.removeClass('publish-modal-social-inactive')
                elem.children('input').eq(0).prop('checked', true);
            } else {
                elem.addClass('publish-modal-social-inactive')
                elem.children('input').eq(0).prop('checked', false);
            }
        };

        // get social toggle elements
        var facebookToggle = $('#publish-modal-social-toggle-facebook');
        var twitterToggle = $('#publish-modal-social-toggle-twitter');
        var googlePlusToggle = $('#publish-modal-social-toggle-google-plus');
        var appleNewsToggle = $('#publish-modal-social-toggle-apple-news');

        // add listeners to social toggles
        facebookToggle.click(toggleSocialOption);
        twitterToggle.click(toggleSocialOption);
        googlePlusToggle.click(toggleSocialOption);
        appleNewsToggle.click(toggleSocialOption);

        // close publish modal
        var closePublishModal = function() {
            publishModalLayout.removeClass('publish-modal-layout-show');
        };

        // show publish modal
        var showProgressBar = function(elemId) {
            $(elemId).addClass('sovrn-mdl-progress-show');
        };

        // hide progress bar
        var hideProgressBar = function(elemId) {
            $(elemId).removeClass('sovrn-mdl-progress-show');
        };

        // add publish-modal submit listener
        $('button#publish-modal-cancel-button').click(closePublishModal);

        // add publish modal submit listener
        $('button#publish-modal-submit-button').click(function(e) {

            // set variables
            var submitButton = $(this);
            var form = $('form#publish-modal-form');
            var progressBarElemId = '#publish-modal-progress-bar';
            var data = form.serialize();

            // handle start
            var handleStart = function() {
                showProgressBar(progressBarElemId);
                submitButton.prop("disabled",true);
            };

            // handle end
            var handleEnd = function() {
                hideProgressBar(progressBarElemId);
                closePublishModal();
            };

            // handle error
            var handleError = function() {
                hideProgressBar(progressBarElemId);
                closePublishModal();
            };

            // make post request
            $.post('options.php', data)
                .success( function(res) {
                    handleEnd();
                    handleAdminNotice('success', 'Successfully shared post.');
                })
                .error( function(err) {
                    handleEnd();
                    handleError();
                    handleAdminNotice('error', 'An error has occurred.');
                });

            // start
            handleStart();

        });

    }


    function setPublishButtonName() {

        // check if isSharingEnabled is set to true
        if (typeof isSharingEnabled !== 'undefined' && isSharingEnabled === true) {

            // get publish button element
            var element = $('input#publish');

            // check if publish button is set to 'Publish', rather than 'Update'
            if (element.attr('name') === 'publish' && element.attr('value') === 'Publish') {

                // replaces button name
                var replaceButtonName = function() {

                    // set desire name to replace with
                    var desiredName = 'Publish + Share';

                    // make sure name replacement happens after name is changed
                    setTimeout(function() {

                        // replace button name with desired name
                        element.attr('value', desiredName);

                    }, 0);

                };

                // replaces button name on page ready
                replaceButtonName();

                // on click, WP changes button back to 'Publish', so
                // need to replace name again on click
                element.click(replaceButtonName);

                // on click for the "Save ..." button, WP changes button
                // back to 'Publish', so need to replace name again on click
                $('#save-post').click(replaceButtonName);

                // on click for the OK button for status change dropdown
                // menu, WP changes button back to 'Publish', so need to
                // replace name again on click
                $('.save-post-status.hide-if-no-js.button').click(replaceButtonName);

            }

        }

    }

})(jQuery);
