"use strict";
jQuery(document).ready(function () {
    var color = _wplwl_get_email_params.bg_color;
    var label = _wplwl_get_email_params.custom_label;
    var prize_pieces = _wplwl_get_email_params.prize_type;
    var wplwl_auto_close = parseInt(_wplwl_get_email_params.auto_close);
    var wplwl_notify_position = _wplwl_get_email_params.position;
    var wplwl_show_again = _wplwl_get_email_params.show_again;
    var wplwl_show_again_unit = _wplwl_get_email_params.show_again_unit;
    var time_if_close=_wplwl_get_email_params.time_if_close;
    switch (wplwl_show_again_unit) {
        case 'm':
            wplwl_show_again *= 60;
            break;
        case 'h':
            wplwl_show_again *= 60 * 60;
            break;
        case 'd':
            wplwl_show_again *= 60 * 60 * 24;
            break;
        default:
    }
    var intent_type = _wplwl_get_email_params.intent;
    var initial_time = _wplwl_get_email_params.show_wheel;
    var wplwl_center_color = _wplwl_get_email_params.wheel_center_color;
    var wplwl_border_color = _wplwl_get_email_params.wheel_border_color;
    var wplwl_dot_color = _wplwl_get_email_params.wheel_dot_color;
    var gdpr_checkbox = _wplwl_get_email_params.gdpr;
    var slice_text_color = _wplwl_get_email_params.slice_text_color;
    var slices = prize_pieces.length;
    var sliceDeg = 360 / slices;
    var deg = -(sliceDeg / 2);
    var cv = document.getElementById('wplwl_canvas');
    var ctx = cv.getContext('2d');
    var canvas_width;
    var wd_width, wd_height;
    wd_width = window.innerWidth;
    wd_height = window.innerHeight;
    if (wd_width > wd_height) {
        canvas_width = wd_height;
    } else {
        canvas_width = wd_width;
    }
    cv.width = canvas_width * 0.75 + 16;
    cv.height = cv.width;
    var width = cv.width;// size
    var center = (width) / 2; // center
    jQuery('.wplwl_wheel_spin').css({'width': canvas_width * 0.75 + 16 + 'px', 'height': canvas_width * 0.75 + 16 + 'px'});
    if (_wplwl_get_email_params.pointer_position == 'center') {
        jQuery('<style type="text/css">.wplwl_pointer:before{font-size:' + parseInt(width / 4) + 'px; }</style>').appendTo('head');
        jQuery('.wplwl_pointer').css({'font-size':parseInt(width / 4) + 'px'})
    }
    var wheel_text_size;
    wheel_text_size = parseInt(width / 28);
    if (wd_height >= 4 * wd_width / 3) {
        jQuery('.wplwl_wheel_content_right').css({'width': '100%', 'max-width': '100%'});
        jQuery('.wplwl-wheel-content-wrapper .wplwl_wheel_content_left').css({'width': '100%','margin':0});
        jQuery('.wplwl-wheel-content-wrapper .wplwl_wheel_content_left .wplwl_wheel_spin').css({'margin': '0 auto'});

    } else {
        var content_right_w = jQuery('.wplwl_lucky_wheel_content').width();
        if (wd_width > 1.8 * wd_height) {
            jQuery('.wplwl_lucky_wheel_content').css({'width': '60%'});
            content_right_w = content_right_w * 0.6;
        } else if (wd_width > 1.2 * wd_height) {
            content_right_w = content_right_w * 0.7;
            jQuery('.wplwl_lucky_wheel_content').css({'width': '70%'});
        }
        jQuery('.wplwl-wheel-content-wrapper .wplwl_wheel_content_right').css({
            'width': content_right_w - width * 0.55 - 65 + 'px',
            'max-width': content_right_w - width * 0.55 - 65 + 'px',
            'margin-left': parseInt(width / 25)+'px'
        });
        jQuery('.wplwl-wheel-content-wrapper .wplwl_wheel_content_left').css({'margin-left': -canvas_width * 0.75 * 0.45 + 'px'});
    }

    function deg2rad(deg) {
        return deg * Math.PI / 180;
    }

    function drawSlice(deg, color) {
        ctx.beginPath();
        ctx.fillStyle = color;
        ctx.moveTo(center, center);
        var r;
        if (width <= 480) {
            r = width / 2 - 10;
        } else {
            r = width / 2 - 14;
        }
        ctx.arc(center, center, r, deg2rad(deg), deg2rad(deg + sliceDeg));
        ctx.lineTo(center, center);
        ctx.fill();
    }

    function drawPoint(deg, color) {
        ctx.save();
        ctx.beginPath();
        ctx.fillStyle = color;
        ctx.shadowBlur = 1;
        ctx.shadowOffsetX = 8;
        ctx.shadowOffsetY = 8;
        ctx.shadowColor = 'rgba(0,0,0,0.2)';
        ctx.arc(center, center, width / 8, 0, 2 * Math.PI);
        ctx.fill();

        ctx.clip();
        ctx.restore();
    }

    function drawBorder(borderC, dotC, lineW, dotR, des, shadColor) {
        ctx.beginPath();
        ctx.strokeStyle = borderC;
        ctx.lineWidth = lineW;
        ctx.shadowBlur = 1;
        ctx.shadowOffsetX = 8;
        ctx.shadowOffsetY = 8;
        ctx.shadowColor = shadColor;
        ctx.arc(center, center, center, 0, 2 * Math.PI);
        ctx.stroke();
        var x_val, y_val, deg;
        deg = sliceDeg / 2;
        var center1 = center - des;
        for (var i = 0; i < slices; i++) {
            ctx.beginPath();
            ctx.fillStyle = dotC;
            x_val = center + center1 * Math.cos(deg * Math.PI / 180);
            y_val = center - center1 * Math.sin(deg * Math.PI / 180);
            ctx.arc(x_val, y_val, dotR, 0, 2 * Math.PI);
            ctx.fill();
            deg += sliceDeg;
        }
    }

    function drawText(deg, text,color) {
        ctx.save();
        ctx.translate(center, center);
        ctx.rotate(deg2rad(deg));
        ctx.textAlign = "right";
        ctx.fillStyle = color;
        ctx.font = '300 ' + wheel_text_size + 'px Helvetica';
        ctx.shadowOffsetX = 0;
        ctx.shadowOffsetY = 0;
        text = text.replace(/&#(\d{1,4});/g, function (fullStr, code) {
            return String.fromCharCode(code);
        });
        ctx.fillText(text, 7 * center / 8, wheel_text_size / 2 - 2);
        ctx.restore();
    }

    function wplwl_trim(x) {
        return x.replace(/^\s+|\s+$/gm, '');
    }

//cookie
    function setCookie(cname, cvalue, expire) {
        var d = new Date();
        d.setTime(d.getTime() + (expire * 1000));
        var expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    function overlay_function() {
        jQuery('.wplwl-overlay').on('click', function () {
            jQuery(this).hide();
            jQuery('html').removeClass('wplwl-html');
            jQuery('.wplwl_wheel_icon').addClass('wplwl_show');
            jQuery('.wplwl_lucky_wheel_content').css({'margin-left': '-110%', 'opacity': '0'});
            var wplwl_delay = setTimeout(function () {
                jQuery('.wplwl_lucky_wheel_content').hide();
            }, 1000);
            clearTimeout(wplwl_delay);
            setCookie('wplwl_cookie','closed',time_if_close);
            if (_wplwl_get_email_params.hide_popup != 'on') {
                console.log(_wplwl_get_email_params.hide_popup);
                switch (wplwl_notify_position) {
                    case 'top-left':
                        jQuery('.wplwl_wheel_icon').css({
                            'margin-left': '0',
                            'transition': '2s',
                            'transform': 'rotate(720deg)'
                        });
                        break;
                    case 'top-right':
                        jQuery('.wplwl_wheel_icon').css({
                            'margin-right': '0',
                            'transition': '2s',
                            'transform': 'rotate(-720deg)'
                        });
                        break;
                    case 'bottom-left':
                        jQuery('.wplwl_wheel_icon').css({
                            'margin-left': '0',
                            'transition': '2s',
                            'transform': 'rotate(720deg)'
                        });
                        break;
                    case 'bottom-right':
                        console.log(wplwl_notify_position);
                        jQuery('.wplwl_wheel_icon').css({
                            'margin-right': '0',
                            'transition': '2s',
                            'transform': 'rotate(-720deg)'
                        });
                        break;

                    case 'middle-left':
                        jQuery('.wplwl_wheel_icon').css({
                            'margin-left': '0',
                            'transition': '2s',
                            'transform': 'rotate(720deg)'
                        });
                        break;
                    case 'middle-right':
                        jQuery('.wplwl_wheel_icon').css({
                            'margin-right': '0',
                            'transition': '2s',
                            'transform': 'rotate(-720deg)'
                        });
                        break;
                }
            }
        });
    }

    function spins_wheel(stop_position, result) {
        var angle = 0;
        var wheel_stop = (360 - (sliceDeg * stop_position)) + 720 * 8;
        var my_spin = setInterval(function () {
            jQuery('#wplwl_canvas').css({
                "-moz-transform": "rotate(" + angle + "deg)",
                "-webkit-transform": "rotate(" + angle + "deg)",
                "-o-transform": "rotate(" + angle + "deg)",
                "-ms-transform": "rotate(" + angle + "deg)"
            });
            jQuery('#wplwl_canvas2').css({
                "-moz-transform": "rotate(" + angle + "deg)",
                "-webkit-transform": "rotate(" + angle + "deg)",
                "-o-transform": "rotate(" + angle + "deg)",
                "-ms-transform": "rotate(" + angle + "deg)"
            });
            if (angle < 360 * 2 || angle > (wheel_stop - (360 * 3))) {
                if (angle < 360 || angle > (wheel_stop - (360 * 2))) {
                    if (angle < 360 / 2 || angle > (wheel_stop - (360))) {
                        if (angle < 360 / 4 || angle > wheel_stop - (360 / 2)) {
                            if (angle > wheel_stop - (360 / 4)) {
                                if (angle > wheel_stop - (360 / 6)) {
                                    if (angle > wheel_stop - (360 / 8)) {
                                        angle += 0.5;
                                    } else {
                                        angle += 1;
                                    }
                                } else {
                                    angle += 1.5;
                                }
                            } else {
                                angle += 2;
                            }
                        } else {
                            angle += 2.5;
                        }
                    } else {
                        angle += 3;
                    }
                } else {
                    angle += 3.5;
                }
            } else {
                angle += 4;
            }
            if (angle >= wheel_stop) {
                jQuery('#wplwl_canvas').css({
                    "-moz-transform": "rotate(" + wheel_stop + "deg)",
                    "-webkit-transform": "rotate(" + wheel_stop + "deg)",
                    "-o-transform": "rotate(" + wheel_stop + "deg)",
                    "-ms-transform": "rotate(" + wheel_stop + "deg)"
                });
                jQuery('#wplwl_canvas2').css({
                    "-moz-transform": "rotate(" + wheel_stop + "deg)",
                    "-webkit-transform": "rotate(" + wheel_stop + "deg)",
                    "-o-transform": "rotate(" + wheel_stop + "deg)",
                    "-ms-transform": "rotate(" + wheel_stop + "deg)"
                });
                jQuery('.wplwl-overlay').unbind();
                jQuery('.wplwl-overlay').on('click', function () {
                    jQuery(this).hide();
                    jQuery('html').removeClass('wplwl-html');
                    jQuery('.wplwl_lucky_wheel_content').css({'margin-left': '-110%', 'opacity': '0'});
                    jQuery('.wplwl_wheel_spin').css({'margin-left': '0', 'transition': '2s'});
                    setTimeout(function () {
                        jQuery('.wplwl_lucky_wheel_content').hide();
                    }, 2000);
                });

                jQuery('.wplwl_user_lucky').html('<div class="wplwl-frontend-result">' + result + '</div>');
                jQuery('.wplwl_user_lucky').fadeIn(300);
                if (wplwl_auto_close >0) {
                    setTimeout(function () {
                        jQuery('.wplwl-overlay').hide();
                        jQuery('html').removeClass('wplwl-html');
                        jQuery('.wplwl_lucky_wheel_content').css({'margin-left': '-110%', 'opacity': '0'});
                        jQuery('.wplwl_wheel_spin').css({'margin-left': '0', 'transition': '2s'});
                        setTimeout(function () {
                            jQuery('.wplwl_lucky_wheel_content').hide();
                        }, 2000);
                    }, wplwl_auto_close*1000);
                }
                clearInterval(my_spin);
            }
        }, 0.01);
    }

    function isValidEmailAddress(emailAddress) {
        var pattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i;
        return pattern.test(emailAddress);
    }

    function check_email() {
        jQuery('.wplwl_field_input ').on('keypress', function (e) {
            if (jQuery(this).focus() && e.keyCode === 13) {
                jQuery('#wplwl_chek_mail').click();
            }
        });
        jQuery('#wplwl_chek_mail').on('click', function () {
            if('on'===gdpr_checkbox && !jQuery('.wplwl-gdpr-checkbox-wrap input[type="checkbox"]').prop('checked')){
                alert('Please agree with our term and condition.');
                return false;
            }
            jQuery(this).unbind();
            jQuery('.wplwl-overlay').unbind();
            var check_mail_text = jQuery(this).html();
            jQuery('#wplwl_player_mail').prop('disabled', true);
            if (getCookie('wplwl_cookie') === "") {
                if (jQuery('#wplwl_player_mail').val() != '') {
                    if (isValidEmailAddress(jQuery('#wplwl_player_mail').val())) {
                        jQuery('#wplwl_error_mail').html('');
                        jQuery('#wplwl_chek_mail').addClass('wplwl-adding');
                        var wplwl_email = jQuery('#wplwl_player_mail').val();
                        var wplwl_name = jQuery('#wplwl_player_name').val();
                        jQuery.ajax({
                            type: 'post',
                            dataType: 'json',
                            url: _wplwl_get_email_params.ajaxurl,
                            data: {
                                action: 'wplwl_get_email',
                                user_email: wplwl_email,
                                user_name: wplwl_name
                            },
                            success: function (response) {
                                if (response.allow_spin === 'yes') {
                                    jQuery('.wplwl-show-again-option').hide();
                                    jQuery('.wplwl-close-wheel').hide();
                                    jQuery('.hide-after-spin').show();
                                    spins_wheel(response.stop_position, response.result_notification);

                                    setCookie('wplwl_cookie', wplwl_email, wplwl_show_again);
                                } else {
                                    alert(response.allow_spin);
                                    jQuery('#wplwl_chek_mail').removeClass('wplwl-adding');
                                    jQuery('#wplwl_player_mail').prop('disabled', false);
                                    check_email();
                                    overlay_function();
                                }
                            }
                        });

                    } else {
                        jQuery('#wplwl_player_mail').prop('disabled', false);
                        check_email();
                        overlay_function();
                        jQuery('#wplwl_error_mail').html('*Please enter a valid email address');
                        jQuery('#wplwl_player_mail').focus();
                    }
                } else {
                    jQuery('#wplwl_player_mail').prop('disabled', false);
                    check_email();
                    overlay_function();
                    jQuery('#wplwl_error_mail').html('*Please enter your email');
                    jQuery('#wplwl_player_mail').focus();
                }
            } else {
                alert('You can only spin 1 time every ' + wplwl_show_again + ' minutes!');
                jQuery('#wplwl_player_mail').prop('disabled', false);
                check_email();
                overlay_function();
            }
        });
    }

    function wplwl_rand(min, max) {
        if (max > min) {
            return Math.floor(Math.random() * (max - min + 1)) + min;
        } else {
            return Math.floor(Math.random() * (min - max + 1)) + max;
        }
    }

    overlay_function();
    check_email();
    var center1 = 32;

    if (!getCookie('wplwl_cookie') || getCookie('wplwl_cookie') == "") {
        jQuery('.hide-after-spin').bind('click', function () {
            jQuery('.wplwl-overlay').hide();
            jQuery('html').removeClass('wplwl-html');
            jQuery('.wplwl_lucky_wheel_content').css({'margin-left': '-110%', 'opacity': '0'});
            jQuery('.wplwl_wheel_spin').css({'margin-left': '0', 'transition': '2s'});
            setTimeout(function () {
                jQuery('.wplwl_lucky_wheel_content').hide();
            }, 2000);
        });

        jQuery('.wplwl-reminder-later-a').unbind();
        jQuery('.wplwl-reminder-later-a').bind('click', function () {
            setCookie('wplwl_cookie', 'reminder_later', 24 * 60 * 60);

            jQuery('.wplwl_wheel_icon').addClass('wplwl_show');

            jQuery('.wplwl-overlay').hide();
            jQuery('html').removeClass('wplwl-html');
            jQuery('.wplwl_lucky_wheel_content').css({'margin-left': '-110%', 'opacity': '0'});
            var wplwl_delay = setTimeout(function () {
                jQuery('.wplwl_lucky_wheel_content').hide();
            }, 1000);
            clearTimeout(wplwl_delay);
        });
        jQuery('.wplwl-never-again a').unbind();
        jQuery('.wplwl-never-again a').bind('click', function () {
            setCookie('wplwl_cookie', 'never_show_again', 7 * 24 * 60 * 60);

            jQuery('.wplwl_wheel_icon').addClass('wplwl_show');

            jQuery('.wplwl-overlay').hide();
            jQuery('html').removeClass('wplwl-html');
            jQuery('.wplwl_lucky_wheel_content').css({'margin-left': '-110%', 'opacity': '0'});
            var wplwl_delay = setTimeout(function () {
                jQuery('.wplwl_lucky_wheel_content').hide();
            }, 1000);
            clearTimeout(wplwl_delay);
        });
        jQuery('.wplwl-close a').on('click', function () {
            jQuery('.wplwl-overlay').hide();
            jQuery('html').removeClass('wplwl-html');
            jQuery('.wplwl_wheel_icon').addClass('wplwl_show');
            jQuery('.wplwl_lucky_wheel_content').css({'margin-left': '-110%', 'opacity': '0'});
            var wplwl_delay = setTimeout(function () {
                jQuery('.wplwl_lucky_wheel_content').hide();
            }, 1000);
            clearTimeout(wplwl_delay);
            if (_wplwl_get_email_params.hide_popup != 'on') {
                switch (wplwl_notify_position) {
                    case 'top-left':
                        jQuery('.wplwl_wheel_icon').css({
                            'margin-left': '0',
                            'transition': '2s',
                            'transform': 'rotate(720deg)'
                        });
                        break;
                    case 'top-right':
                        jQuery('.wplwl_wheel_icon').css({
                            'margin-right': '0',
                            'transition': '2s',
                            'transform': 'rotate(-720deg)'
                        });
                        break;
                    case 'bottom-left':
                        jQuery('.wplwl_wheel_icon').css({
                            'margin-left': '0',
                            'transition': '2s',
                            'transform': 'rotate(720deg)'
                        });
                        break;
                    case 'bottom-right':
                        jQuery('.wplwl_wheel_icon').css({
                            'margin-right': '0',
                            'transition': '2s',
                            'transform': 'rotate(-720deg)'
                        });
                        break;

                    case 'middle-left':
                        jQuery('.wplwl_wheel_icon').css({
                            'margin-left': '0',
                            'transition': '2s',
                            'transform': 'rotate(720deg)'
                        });
                        break;
                    case 'middle-right':
                        jQuery('.wplwl_wheel_icon').css({
                            'margin-right': '0',
                            'transition': '2s',
                            'transform': 'rotate(-720deg)'
                        });
                        break;
                }
            }
        });
        jQuery('.wplwl-close-wheel span').on('click', function () {
            jQuery('.wplwl-overlay').hide();
            jQuery('html').removeClass('wplwl-html');
            jQuery('.wplwl_wheel_icon').addClass('wplwl_show');
            jQuery('.wplwl_lucky_wheel_content').css({'margin-left': '-110%', 'opacity': '0'});
            var wplwl_delay = setTimeout(function () {
                jQuery('.wplwl_lucky_wheel_content').hide();
            }, 1000);
            clearTimeout(wplwl_delay);
            setCookie('wplwl_cookie','closed',time_if_close);
            if (_wplwl_get_email_params.hide_popup != 'on') {
                switch (wplwl_notify_position) {
                    case 'top-left':
                        jQuery('.wplwl_wheel_icon').css({
                            'margin-left': '0',
                            'transition': '2s',
                            'transform': 'rotate(720deg)'
                        });
                        break;
                    case 'top-right':
                        jQuery('.wplwl_wheel_icon').css({
                            'margin-right': '0',
                            'transition': '2s',
                            'transform': 'rotate(-720deg)'
                        });
                        break;
                    case 'bottom-left':
                        jQuery('.wplwl_wheel_icon').css({
                            'margin-left': '0',
                            'transition': '2s',
                            'transform': 'rotate(720deg)'
                        });
                        break;
                    case 'bottom-right':
                        jQuery('.wplwl_wheel_icon').css({
                            'margin-right': '0',
                            'transition': '2s',
                            'transform': 'rotate(-720deg)'
                        });
                        break;

                    case 'middle-left':
                        jQuery('.wplwl_wheel_icon').css({
                            'margin-left': '0',
                            'transition': '2s',
                            'transform': 'rotate(720deg)'
                        });
                        break;
                    case 'middle-right':
                        jQuery('.wplwl_wheel_icon').css({
                            'margin-right': '0',
                            'transition': '2s',
                            'transform': 'rotate(-720deg)'
                        });
                        break;
                }
            }
        });


        jQuery('.wp-lucky-wheel-popup-icon').on('click', function () {
            jQuery('.wplwl_wheel_icon').removeClass('wplwl_show');
            jQuery('.wplwl-overlay').show();
            jQuery('html').addClass('wplwl-html');
            jQuery('.wplwl_lucky_wheel_content').css({'margin-left': '0', 'opacity': '1'});
            switch (wplwl_notify_position) {
                case 'top-left':
                    jQuery('.wplwl_wheel_icon').css({
                        'margin-left': '-400px',
                        'transition': '2s',
                        'transform': 'rotate(-360deg)'
                    });
                    break;
                case 'top-right':
                    jQuery('.wplwl_wheel_icon').css({
                        'margin-right': '-400px',
                        'transition': '2s',
                        'transform': 'rotate(360deg)'
                    });
                    break;
                case 'bottom-left':
                    jQuery('.wplwl_wheel_icon').css({
                        'margin-left': '-400px',
                        'transition': '2s',
                        'transform': 'rotate(-360deg)'
                    });
                    break;
                case 'bottom-right':
                    jQuery('.wplwl_wheel_icon').css({
                        'margin-right': '-400px',
                        'transition': '2s',
                        'transform': 'rotate(360deg)'
                    });
                    break;

                case 'middle-left':
                    jQuery('.wplwl_wheel_icon').css({
                        'margin-left': '-400px',
                        'transition': '2s',
                        'transform': 'rotate(-360deg)'
                    });
                    break;
                case 'middle-right':
                    jQuery('.wplwl_wheel_icon').css({
                        'margin-right': '-400px',
                        'transition': '2s',
                        'transform': 'rotate(360deg)'
                    });
                    break;
            }
        });

        for (var i = 0; i < slices; i++) {
            drawSlice(deg, color[i]);
            drawText(deg + sliceDeg / 2, label[i],slice_text_color);
            deg += sliceDeg;

        }
        cv = document.getElementById('wplwl_canvas1');
        ctx = cv.getContext('2d');
        cv.width = canvas_width * 0.75 + 16;
        cv.height = cv.width;
        drawPoint(deg, wplwl_center_color);

        if (width <= 480) {
            drawBorder(wplwl_border_color, 'rgba(0,0,0,0)', 20, 4, 5, 'rgba(0,0,0,0.2)');

        } else {
            drawBorder(wplwl_border_color, 'rgba(0,0,0,0)', 30, 6, 7, 'rgba(0,0,0,0.2)');
        }

        cv = document.getElementById('wplwl_canvas2');
        ctx = cv.getContext('2d');

        cv.width = canvas_width * 0.75 + 16;
        cv.height = cv.width;
        if (width <= 480) {
            drawBorder('rgba(0,0,0,0)', wplwl_dot_color, 20, 4, 5, 'rgba(0,0,0,0)');

        } else {
            drawBorder('rgba(0,0,0,0)', wplwl_dot_color, 30, 6, 7, 'rgba(0,0,0,0)');
        }

        if (intent_type === 'popup_icon') {
            var notify_time_out = setTimeout(function () {
                jQuery('.wplwl_wheel_icon').addClass('wplwl_show');
                switch (wplwl_notify_position) {
                    case 'top-left':
                        jQuery('.wplwl_wheel_icon').css({
                            'margin-left': '0',
                            'transition': '2s',
                            'transform': 'rotate(720deg)'
                        });
                        break;
                    case 'top-right':
                        jQuery('.wplwl_wheel_icon').css({
                            'margin-right': '0',
                            'transition': '2s',
                            'transform': 'rotate(-720deg)'
                        });
                        break;
                    case 'bottom-left':
                        jQuery('.wplwl_wheel_icon').css({
                            'margin-left': '0',
                            'transition': '2s',
                            'transform': 'rotate(720deg)'
                        });
                        break;
                    case 'bottom-right':
                        jQuery('.wplwl_wheel_icon').css({
                            'margin-right': '0',
                            'transition': '2s',
                            'transform': 'rotate(-720deg)'
                        });
                        break;

                    case 'middle-left':
                        jQuery('.wplwl_wheel_icon').css({
                            'margin-left': '0',
                            'transition': '2s',
                            'transform': 'rotate(720deg)'
                        });
                        break;
                    case 'middle-right':
                        jQuery('.wplwl_wheel_icon').css({
                            'margin-right': '0',
                            'transition': '2s',
                            'transform': 'rotate(-720deg)'
                        });
                        break;
                }

            }, initial_time * 1000);
        } else if (intent_type === 'show_wheel') {
            setTimeout(function () {
                jQuery('.wplwl-overlay').show();
                jQuery('html').addClass('wplwl-html');
                jQuery('.wplwl_lucky_wheel_content').css({'margin-left': '0', 'opacity': '1'});
            }, initial_time * 1000);
        }
    }

    function drawPopupIcon() {
        cv = document.getElementById('wplwl_popup_canvas');
        ctx = cv.getContext('2d');

        for (var k = 0; k < slices; k++) {
            drawSlice1(deg, color[k]);
            deg += sliceDeg;
        }
        drawPoint1(wplwl_center_color);
        drawBorder1(wplwl_border_color, wplwl_dot_color, 4, 1, 0);

    }

    drawPopupIcon();

    function drawSlice1(deg, color) {
        ctx.beginPath();
        ctx.fillStyle = color;
        ctx.moveTo(center1, center1);
        ctx.arc(center1, center1, 32, deg2rad(deg), deg2rad(deg + sliceDeg));
        ctx.lineTo(center1, center1);
        ctx.fill();
    }

    function drawPoint1(color) {
        ctx.save();
        ctx.beginPath();
        ctx.fillStyle = color;
        ctx.arc(center1, center1, 8, 0, 2 * Math.PI);
        ctx.fill();
        ctx.restore();
    }

    function drawBorder1(borderC, dotC, lineW, dotR, des) {
        ctx.beginPath();
        ctx.strokeStyle = borderC;
        ctx.lineWidth = lineW;
        ctx.arc(center1, center1, center1, 0, 2 * Math.PI);
        ctx.stroke();
        var x_val, y_val, deg;
        deg = sliceDeg / 2;
        var center2 = center1 - des;
        for (var i = 0; i < slices; i++) {
            ctx.beginPath();
            ctx.fillStyle = dotC;
            x_val = center1 + center2 * Math.cos(deg * Math.PI / 180);
            y_val = center1 - center2 * Math.sin(deg * Math.PI / 180);
            ctx.arc(x_val, y_val, dotR, 0, 2 * Math.PI);
            ctx.fill();
            deg += sliceDeg;
        }
    }
});


