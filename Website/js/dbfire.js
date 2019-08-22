/* Login Email */
// firebase.auth().onAuthStateChanged(function(user) {
//     if (user) {
//         //window.location='./index.html';
//         var user = firebase.auth().currentUser;
//         if (user != null) {
//             var email = user.email;
//             document.getElementById("log").style.display = "none";
//             document.getElementById("useraccount").style.display = "block";
//             document.getElementById("").innerHTML = "Welcome: &nbsp;&nbsp;" + email;
//             // console.log("email");
//         }
//     } else {
//         console.log("no user");
//     }
// });


$(document).ready(function() {
    categories();
});

function categories() {
    var db = firebase.firestore();

    var sn = '';
    db.collection("categories").orderBy("catname", "asc").get().then(function(querySnapshot) {
        var i = 1;
        querySnapshot.forEach(function(doc) {

            // doc.data() is never undefined for query doc snapshots 
            //  console.log(doc.id, " => ", doc.data());
            var val = doc.data();


            // sn+="<div class='col-2 p-1'>";
            // sn+="<div class='card card-sm'>";
            // sn+="<div class='card-body pb-0 text-center pb-3' data-toggle='modal' data-target='#modalPoll-1' id='"+val.catname+"' onclick='funct(this.id)' style='background-color:#658fb0;'>";
            // sn+="<img src='"+val.imageurl+"' alt='image' class='rounded' width='50px'>";
            // sn+="</div>";
            // sn+="<div class='card-footer  text-center' data-toggle='modal' data-target='#modalPoll-1' id='"+val.catname+"' onclick='funct(this.id)'>";
            // sn+="<small class='p-1 ' id='sml'>"+val.catname+"</small>";
            // sn+="</div>";
            // sn+="</div>";
            // sn+="</div>";

            sn += "<div class='col-sm-2 col-md-2 col-lg-2 p-1'>";
            sn += "<div class='card card-sm hoverable' data-toggle='modal' data-target='#modalPoll-1' id='" + val.catname + "' onclick='funct(this.id)' style='min-height: 205px;'>";
            sn += "<div class='card-body text-center' style='border-radius: 50%; background-color: #f1f1f1;'>";
            sn += "<img src='" + val.imageurl + "' alt='icon' class='p-1' width='100%'>";
            sn += "</div>";
            sn += "<h6 class='mt-1 text-center font-weight-bolder'>" + val.catname + "</h6>";
            sn += "</div>";
            sn += "</div>";

        });
        $('#fireservices').append(sn);
    });
}

function funct(caten) {
    displayServices(caten);
}


/************For Mobile*************/
$(document).ready(function() {
    mobser();
});

function mobser() {
    var db = firebase.firestore();

    var sn = '';
    db.collection("categories").orderBy("catname", "asc").get().then(function(querySnapshot) {
        var i = 1;
        querySnapshot.forEach(function(doc) {

            // doc.data() is never undefined for query doc snapshots 
            //  console.log(doc.id, " => ", doc.data());
            var val = doc.data();

            // sn +="<div class='col-md-3 services-item single-item text-center' data-toggle='modal' data-target='#modalPoll-1' id='"+val.catname+"' onclick='funct(this.id)'>";   
            // sn +="<img src='"+val.imageurl+"' class='rounded'>";   
            // sn += "<h6>"+val.catname+"</h6>";
            // sn +="</div>";

            sn += "<div class='col-6 p-1'>";
            sn += "<div class='card rounded'>";
            sn += "<div class='card-body pb-0 text-center pb-3' data-toggle='modal' data-target='#modalPoll-1' id='" + val.catname + "' onclick='funct(this.id)'>";
            sn += "<img src='" + val.imageurl + "' alt='image' class='rounded' width='50px'>";
            sn += "</div>";
            sn += "<div class='card-footer gray pt-0 text-center' data-toggle='modal' data-target='#modalPoll-1' id='" + val.catname + "' onclick='funct(this.id)'>";
            sn += "<small>" + val.catname + "</small>";
            sn += "</div>";
            sn += "</div>";
            sn += "</div>";


        });
        $('#mobileservices').append(sn);
    });
}

function funct(caten) {
    displayServices(caten);
}

/**********Services***************/


function displayServices(catname) {
    var db = firebase.firestore();
    document.getElementById("lead").innerHTML = catname;
    document.getElementById("ser-list").innerHTML = " ";
    var cn = '';
    db.collection("services").where("category", "==", catname, ).get().then(function(querySnapshot) {
        var i = 1;
        querySnapshot.forEach(function(doc) {

            // doc.data() is never undefined for query doc snapshots 
            //  console.log(doc.id, " => ", doc.data());
            var val = doc.data();


            cn += "<li class='mt-2 text-decoration-none' id='" + doc.id + "' data-toggle='modal' data-target='#modalQuickView' onclick='displaydetails(this.id)'>" + val.servicename + "- <sub>" + val.price[0] + "</sub></li>";

        });
        $('#ser-list').append(cn);
    });
}

function displaydetails(details) {
    info();


}


function displaydetails(id) {
    console.log("docid:" + id);
    var db = firebase.firestore();

    db.collection("services").doc("" + id).get().then(function(snapshot) {
        console.log("db" + id);
        if (snapshot.exists) {
            console.log("Document data:", snapshot.data());

            var val = snapshot.data();
            var content = '';
            var content1 = '';
            document.getElementById("photo").src = val.imageurl;
            document.getElementById("servicetitle").innerHTML = val.servicename;
            val.price.forEach(function(item, index, array) {
                console.log(item);
                content += '<p id="serviceprices">' + item + '</p>';
            });
            $("#serviceprice").append(content);

            val.inclusion.forEach(function(item, index, array) {
                console.log(item);
                content1 += '<p id="serviceinclusion">' + item + '</p>';

            });
            $("#serviceinclusion").append(content1);
        } else {
            // doc.data() will be undefined in this case
            console.log("No such document!");
        }

    });
}


function book() {

    document.getElementById("book").style.display = "block";
    document.getElementById("cart").style.display = "none";

}

//Search tag in Menu bar & carousel too

function searchServices() {
    clearSuggestions();
    document.getElementById("Search").style.shadow = "none";
    var db = firebase.firestore();
    var input = document.getElementById("Search").value;
    input = input.toLowerCase();
    var sn = '';

    db.collection("services").orderBy("servicename", "asc").get().then(function(querySnapshot) {
        var i = 1;
        querySnapshot.forEach(function(doc) {
            console.log("Search called");

            // doc.data() is never undefined for query doc snapshots 
            //  console.log(doc.id, " => ", doc.data());
            var val = doc.data();
            var lowerCaseService = val.servicename.toLowerCase();
            if (lowerCaseService.includes(input)) {
                sn += "<p id='" + doc.id + "' data-toggle='modal' data-target='#modalQuickView' onclick='displaydetails(this.id)'>" + val.servicename + "</p>";
            }

        });


        $('#suggestion').append(sn);
        document.getElementById("suggestion").style.display = "";
        $('#nav-suggestion').append(sn);
        document.getElementById("nav-suggestion").style.display = "";
        document.getElementById("suggestion").style.display = "block";
        document.getElementById("nav-suggestion").style.display = "block";

    });


}

function searchServicesNav() {
    clearSuggestions();
    document.getElementById("Search_nav").style.shadow = "none";
    var db = firebase.firestore();
    var input = document.getElementById("Search_nav").value;
    input = input.toLowerCase();
    var sn = '';

    db.collection("services").orderBy("servicename", "asc").get().then(function(querySnapshot) {
        var i = 1;
        querySnapshot.forEach(function(doc) {
            console.log("Search called");

            // doc.data() is never undefined for query doc snapshots 
            //  console.log(doc.id, " => ", doc.data());
            var val = doc.data();
            var lowerCaseService = val.servicename.toLowerCase();
            if (lowerCaseService.includes(input)) {
                sn += "<p id='" + doc.id + "' data-toggle='modal' data-target='#modalQuickView' onclick='displaydetails(this.id)'>" + val.servicename + "</p>";
            }

        });


        clearSuggestions();
        $('#suggestion').append(sn);
        document.getElementById("suggestion").style.display = "";
        $('#nav-suggestion').append(sn);
        document.getElementById("nav-suggestion").style.display = "";
        document.getElementById("suggestion").style.display = "block";
        document.getElementById("nav-suggestion").style.display = "block";

    });
}

function clearSuggestions() {
    $("#nav-suggestion").empty();
    $("#suggestion").empty();
}


function searchclear() {
    document.getElementById("suggestion").style.display = "";
    document.getElementById("nav-suggestion").style.display = "";
    //  document.getElementById('Search').value = "";
    document.getElementById("nav-suggestion").value = "";
    document.getElementById("suggestion").value = "";
    document.getElementById("suggestion").style.display = "none";
    document.getElementById("nav-suggestion").style.display = "none";

    // document.getElementById('Search').innerHTML = "";
}




//Old method Search tag in Menu bar & carousel too

// function searchServices() {
//     console.log("search");
//     document.getElementById("Search").style.shadow = "none";
//     var db = firebase.firestore();
//     var input = document.getElementById("Search").value;
//     var sn = '';
//     console.log(input);
//     db.collection("services").orderBy("servicename", "asc").startAt(input.toUpperCase()).get().then(function(querySnapshot) {
//         var i = 1;
//         querySnapshot.forEach(function(doc) {
//             console.log("Search called");

//             // doc.data() is never undefined for query doc snapshots 
//             //  console.log(doc.id, " => ", doc.data());
//             var val = doc.data();

//             sn += "<p id='" + doc.id + "' data-toggle='modal' data-target='#modalQuickView' onclick='displaydetails(this.id)'>" + val.servicename + "</p>";
//         });


//         $('#suggestion').append(sn);
//         document.getElementById("suggestion").style.display = "";
//         $('#nav-suggestion').append(sn);
//         document.getElementById("nav-suggestion").style.display = "";
//         document.getElementById("suggestion").style.display = "block";
//         document.getElementById("nav-suggestion").style.display = "block";

//     });


// }


// function searchclear() {
//     document.getElementById("suggestion").style.display = "";
//     document.getElementById("nav-suggestion").style.display = "";
//     //  document.getElementById('Search').value = "";
//     document.getElementById("suggestion").style.display = "none";
//     document.getElementById("nav-suggestion").style.display = "none";

//     // document.getElementById('Search').innerHTML = "";
// }



$(document).ready(function() {
    footerlinks();
});


function footerlinks() {

    var db = firebase.firestore();
    var cn = '';
    db.collection("services").orderBy("servicename").get().then(function(querySnapshot) {
        var i = 1;
        querySnapshot.forEach(function(doc) {

            // doc.data() is never undefined for query doc snapshots 
            //  console.log(doc.id, " => ", doc.data());
            var val = doc.data();


            cn += "<p id='" + doc.id + "' data-toggle='modal' data-target='#modalQuickView' onclick='displaydetails(this.id)' style='cursor: pointer;'>" + val.servicename + "</p>";

        });
        $('#serviceslist').append(cn);
    });

}