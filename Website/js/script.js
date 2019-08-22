new WOW().init();


$(document).ready(function() {
    $("#signupmobile, #qmobile, #cmobile").keydown(function(e) {
        // Allow: backspace, delete, tab, escape and enter
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
            // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) ||
            // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
});

//Alert auto hide
$(document).ready(function() {
    $(".alert").hide();
    $("#myWish").click(function showAlert() {
        $(".alert").fadeTo(2000, 500).slideUp(500, function() {
            $(".alert").slideUp(500);
        });
    });
});

$(document).ready(function() {
    $(".alert").hide();
    $("#hidealert1").click(function showAlert() {
        $(".alert").fadeTo(2000, 500).slideUp(500, function() {
            $(".alert").slideUp(500);
        });
    });
});

$(document).ready(function() {
    $(".alert").hide();
    $("#qalerthide").click(function showAlert() {
        $(".alert").fadeTo(2000, 500).slideUp(500, function() {
            $(".alert").slideUp(500);
        });
    });
});

$(document).ready(function() {
    $(".alert").hide();
    $("#oalerthide").click(function showAlert() {
        $(".alert").fadeTo(2000, 500).slideUp(500, function() {
            $(".alert").slideUp(500);
        });
    });
});
$(document).ready(function() {
    $(".alert").hide();
    $("#balerthide").click(function showAlert() {
        $(".alert").fadeTo(2000, 500).slideUp(500, function() {
            $(".alert").slideUp(500);
        });
    });
});

$(document).ready(function() {
    $(".alert").hide();
    $("#calerthide").click(function showAlert() {
        $(".alert").fadeTo(5000, 500).slideUp(500, function() {
            $(".alert").slideUp(500);
        });
    });
});


// function book(id){
// 	document.getElementById("book").style.display = "block";
// 	document.getElementById("cart").style.display = "none";
// }

function bookingclose() {
    document.getElementById("book").style.display = "none";
    document.getElementById("cart").style.display = "block";
    document.getElementById("bname").value = "";
    document.getElementById("bemail").value = "";
    document.getElementById("bmobile").value = "";
    document.getElementById('baddress').value = "";
}

function clearsearch() {
    document.getElementById('Search').value = "";
    document.getElementById("suggestion").style.display = "none";
    document.getElementById('Search').innerHTML = "none";
    document.getElementById("nav-suggestion").style.display = "none";

}


function join() {
    document.getElementById("joinform").style.display = "block";
    document.getElementById("joincontent").style.display = "none";
}

function closejoin() {
    document.getElementById("joinform").style.display = "none";
    document.getElementById("joincontent").style.display = "block";
    document.getElementById("joinname").value = "";
    document.getElementById("joinemail").value = "";
    document.getElementById("joinmobile").value = "";

}

function signupshow() {
    document.getElementById("").style.display = "none";
    $("#signupform").modal("show");

}
// Location
// jQuery( document ).ready( function( $ ){

//     navigator.geolocation.getCurrentPosition(
//     function( position ){ // success cb
//         console.log( position );
//     },
//     function(){ // fail cb
//     }
// );

// });



$('#confirmpass').on('keyup', function() {

    if ($('#signuppassword').val() == $('#confirmpass').val()) {
        $('#cnfpassalert').html('<small><i class="fa fa-check"></i> Correct</small>').css('color', 'green');
    } else {
        document.getElementById("cnfpassalert").style.display = "none";
    }
});


function myFunction() {
    var x = document.getElementById("confirmpass");
    if (x.type === "password") {
        x.type = "text";
        $("#eye").addClass("fa-eye-slash");
        $("#eye").removeClass("fa-eye");

    } else {
        x.type = "password";
        $("#eye").addClass("fa-eye");
        $("#eye").removeClass("fa-eye-slash");
    }
}




// Function to generate OTP 
// function generateOTP() { 

//   // Declare a digits variable 
//   // which stores all digits 
//   var digits = '0123456789'; 
//   let OTP = ''; 
//   for (let i = 0; i < 4; i++ ) { 
//     OTP += digits[Math.floor(Math.random() * 10)]; 
//   } 
//   return OTP; 
// } 

// ("OTP of 4 digits: ");
// alert( generateOTP() ); 



function snackbar() {

    var x = document.getElementById("snackbar");

    // Add the "show" class to DIV
    x.className = "show";

    // After 3 seconds, remove the show class from DIV
    setTimeout(function() { x.className = x.className.replace("show", ""); }, 3000);
}



$(window).on('scroll', function() {
    if (window.innerWidth > 1200) {
        menu();
        //  return preventDefault();
    }
});

function menu() {
    window.onscroll = function() { scrollFunction() };

    function scrollFunction() {
        if (document.body.scrollTop > 400 || document.documentElement.scrollTop > 400) {
            document.getElementById("small-search").style.display = "block";

        } else {
            document.getElementById("small-search").style.display = "none";

        }
    };
}

$('.carousel').carousel({
    interval: 1000 * 3
});




$().ready(function() {
    $("#cardhover1").hover(function() {
            $("#cardhover1").removeClass("z-depth-0");
            $("#cardhover1").addClass("z-depth-3");
        },
        function() {
            $("#cardhover1").addClass("z-depth-0");
            $("#cardhover1").removeClass("z-depth-3");
        });
});

$().ready(function() {
    $("#cardhover2").hover(function() {
            $("#cardhover2").removeClass("z-depth-0");
            $("#cardhover2").addClass("z-depth-3");
        },
        function() {
            $("#cardhover2").addClass("z-depth-0");
            $("#cardhover2").removeClass("z-depth-3");
        });
});

$().ready(function() {
    $("#cardhover3").hover(function() {
            $("#cardhover3").removeClass("z-depth-0");
            $("#cardhover3").addClass("z-depth-3");
        },
        function() {
            $("#cardhover3").addClass("z-depth-0");
            $("#cardhover3").removeClass("z-depth-3");
        });
});



// $(document).ready(function() {

//     if (localStorage.getItem("UserName") === null) {
//         // alert("Please Login");
//         document.getElementById("log").style.display = "block";
//         document.getElementById("useraccount").style.display = "none";
//     } else {
//         let User = localStorage.getItem('UserName');
//         document.getElementById("log").style.display = "none";
//         document.getElementById("useraccount").style.display = "block";
//         document.getElementById("user").innerHTML = "<i class='fa fa-lg fa-user'></i> &nbsp;&nbsp;" + User;
//         document.getElementById("snackbar").innerHTML = "<div class='animated fadeInRight primary-color white-text p-3 text-center rounded font-weight-bolder'>Welcome " + User;
//         "</div>";
//         snackbar();
//         return false;
//     }

// });


//For Appstore redirect to coming soon page with date & time

var countDownDate = new Date("Dec 7, 2019 15:45:45").getTime();
var countdownfunction = setInterval(function() {
    // Get todays date and time
    var now = new Date().getTime();
    // Find the distance between now an the count down date
    var distance = countDownDate - now;
    // Time calculations for days, hours, minutes and seconds
    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    // Output the result in an element with id="demo"
    document.getElementById("demo").innerHTML = days + 'd ' + hours + 'h ' + minutes + 'm ' + seconds + 's ';
    // If the count down is over, write some text 
    if (distance < 0) {
        clearInterval(countdownfunction);
        document.getElementById("demo").innerHTML = "EXPIRED";
    }
}, 1000);

$('.owl-carousel').owlCarousel({
    items: 1,
    loop: true,
    autoplay: true,
    dots: false,
    autoplayTimeout: 8000
});


// Shuffle js filter and masonry
var Shuffle = window.Shuffle;
var jQuery = window.jQuery;

var myShuffle = new Shuffle(document.querySelector('.shuffle-wrapper'), {
    itemSelector: '.shuffle-item',
    buffer: 1
});

jQuery('input[name="shuffle-filter"]').on('change', function(evt) {
    var input = evt.currentTarget;
    if (input.checked) {
        myShuffle.filter(input.value);
    }
});

$('.portfolio-gallery').each(function() {
    $(this).find('.popup-gallery').magnificPopup({
        type: 'image',
        gallery: {
            enabled: true
        }
    });
});