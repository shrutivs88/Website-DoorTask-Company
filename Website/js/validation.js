  //Login Connection
  function login() {

      var email = document.getElementById('lemail').value;
      var password = document.getElementById('lpassword').value;

      if (email == "") {
          $("#loginstatus").html("<div class='alert animated fadeInDown'><i class='fas fa-exclamation-triangle text-danger'></i> <small class='text-danger font-weight-bold'>Email cannot be empty</small></div>");
          return false;
      } else {
          var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
          if (!re.test(email)) {
              $("#loginstatus").html("<div class='alert animated fadeInDown'><i class='fas fa-exclamation-triangle text-warning'></i><small class='text-warning font-weight-bold'> Invalid email</small></div>");
              return false;
          }
      }
      if (password == "") {
          $("#loginstatus").html("<div class='alert animated fadeInDown'><i class='fas fa-exclamation-triangle text-danger'></i> <small class='text-danger font-weight-bold'>Password cannot be empty</small></div>");
          return false;
      } else if (password.length < 6) {
          $("#loginstatus").html("<div class='alert animated fadeInDown'><i class='fas fa-exclamation-triangle text-warning'></i><small class='text-warning font-weight-bold'> Invalid password</small></div>");
          return false;
      }

      firebase.auth().signInWithEmailAndPassword(email, password).catch(function(error) {
          // Handle Errors here.
          var errorCode = error.code;
          var errorMessage = error.message;
          document.getElementById("log").style.display = "none";
          document.getElementById("useraccount").style.display = "block";
          document.getElementById('user').innerHTML = "<i class='fa fa-lg fa-user'></i>Welcome:<br>" + email;
          $("#userlink").html("<div class='animated fadeInRight danger-color white-text p-3 text-center rounded font-weight-bolder'>Logout</div>");
          $("#loginalert").html("<div class='alert alert-success animated fadeInRight'><a class='close' data-dismiss='alert' href='#'>x</a><small>Login Successful</small></div>");
          loginclear();
      });
  }




  //below code is for API which is from Nodejs

  //   let data = {

  //       "email": email,
  //       "password": password,
  //   }
  //   console.log(data);
  //   fetch('http://18.217.12.123:8000/login', {
  //           method: "POST",
  //           headers: {
  //               'Accept': 'application/json',
  //               'Content-Type': 'application/json'
  //           },
  //           body: JSON.stringify(data)
  //       })
  //       .then(response => response.json())
  //       .then(response => {
  //           console.log('Success:', JSON.stringify(response))
  //           console.log(response.statusCode);


  //           if (response.statusCode == 400) {
  //               alert("Email not found");
  //           }

  //           if (response.data.role == 6) {
  //               localStorage.setItem('UserData', JSON.stringify(response.data));
  //               // alert("Role 6");

  //           } else {

  //               alert("Email not found");
  //               return false;
  //           }

  //           if (response.statusCode == 200) {

  //               // alert('Login successfully');

  //               localStorage.setItem('UserName', JSON.stringify(response.data.fname));
  //               let User = localStorage.getItem('UserName');
  //               document.getElementById("log").style.display = "none";
  //               document.getElementById("useraccount").style.display = "block";
  //               document.getElementById("user").innerHTML = "<i class='fa fa-lg fa-user'></i> &nbsp;&nbsp;" + User;
  //               document.getElementById("snackbar").innerHTML = "<div class='animated fadeInRight success-color white-text p-3 text-center rounded font-weight-bolder'>Login Successful, Welcome</div>";
  //               snackbar();
  //               loginclear();
  //               $('#modalLRForm .close').click();

  //           } else {
  //               alert("Login Failed");
  //           }
  //       })
  //       .catch(error => console.error('Error:', error));




  //   function closelogin() {
  //       alert("close");
  //       //   $('#modalLRForm .close').click();
  //       $("#loginalert").html("<div class='alert alert-success animated fadeInRight'><a class='close' data-dismiss='alert' href='#'>x</a><small>Login Successful</small></div>");
  //       $("#modalLRForm").addClass(".close");
  //       //   window.onclick = function(event) {
  //       //       if (event.target == modal) {
  //       //           modal.style.display = "none";
  //       //       }
  //       //   }

  //   }

  function loginclear() {
      document.getElementById("lemail").value = "";
      document.getElementById("lemail").focus();
      document.getElementById("lpassword").value = "";
      document.getElementById("lpassword").focus();
      $("#lpassword, #lemail").removeClass('active');
      //$("#modalLRForm").modal("hide");
      setTimeout(function() { $('#modalLRForm').close(); }, 1000);

  }



  function signup() {
      var fname = document.getElementById('signupfname').value;
      var lname = document.getElementById('signuplname').value;
      var password = document.getElementById('signuppassword').value;
      var mobile = document.getElementById('signupmobile').value;
      var email = document.getElementById('signupemail').value;
      var confirm = document.getElementById("confirmpass").value;

      console.log(fname + lname + password + mobile + email + confirm);


      if (fname == "") {
          $("#signupstatus").html("<div class='alert animated fadeInUp'><i class='fas fa-exclamation-triangle text-danger'></i> <small class='text-danger font-weight-bold'>First name cannot be empty</small></div>");
          return false;
      }
      if (lname == "") {
          $("#signupstatus").html("<div class='alert animated fadeInUp'><i class='fas fa-exclamation-triangle text-danger'></i> <small class='text-danger font-weight-bold'>Last name cannot be empty</small></div>");
          return false;
      }

      if (email == "") {
          $("#signupstatus").html("<div class='alert animated fadeInUp'><i class='fas fa-exclamation-triangle text-danger'></i> <small class='text-danger font-weight-bold'>Email cannot be empty</small></div>")
          return false;

      } else {
          var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
          if (!re.test(email)) {
              $("#signupstatus").html("<div class='alert animated fadeInUp'><i class='fas fa-exclamation-triangle text-warning'></i> <small class='text-warning font-weight-bold'>Email should be Ex: abc@gmail.com</small></div>")
              return false;
          }
      }

      if (mobile == "") {
          $("#signupstatus").html("<div class='alert animated fadeInUp'><i class='fas fa-exclamation-triangle text-danger'></i> <small class='text-danger font-weight-bold'>Mobile number cannot be empty</small></div>")

          return false;

      } else if (mobile.length < 10) {
          $("#signupstatus").html("<div class='alert animated fadeInUp'><i class='fas fa-exclamation-triangle text-warning'></i> <small class='text-warning font-weight-bold'>Invalid mobile number</small></div>")
          return false;
      }

      if (password == "") {
          $("#signupstatus").html("<div class='alert animated fadeInUp'><i class='fas fa-exclamation-triangle text-danger'></i> <small class='text-danger font-weight-bold'>Password cannot be empty</small></div>")
          return false;

      } else if (password.length < 6) {
          $("#signupstatus").html("<div class='alert animated fadeInUp'><i class='fas fa-exclamation-triangle text-warning'></i> <small class='text-warning font-weight-bold'>Password atleast 6 letters</small></div>");
          return false;
      }

      if (confirm == "") {
          $("#signupstatus").html("<div class='alert animated fadeInUp'><i class='fas fa-exclamation-triangle text-danger'></i> <small class='text-danger font-weight-bold'>Please enter the password</small></div>")
          return false;

      }

      var db = firebase.firestore();
      db.collection("signup").add({
              firstname: fname,
              lastname: lname,
              email: email,
              mobile: mobile,
              password: password,
              confirmpassword: confirm
          }).then(function(docRef) {
              console.log("Document written with ID: ", docRef.id);
              //  console.log(name+email+mobile+text);


              $("#signupform1").html("<div class='alert alert-success alert-sm animated fadeInUp'><a class='close' data-dismiss='alert' href='#'>x</a><small>Signup Successful!</small></div>");
              signupreset();


          })
          .catch(function(error) {
              console.error("Error adding document: ", error);
          });
  }
  //For Nodejs API the below code will work on the 
  //   let data = {
  //       "fname": fname,
  //       "lname": lname,
  //       "email": email,
  //       "phone": mobile,
  //       "password": confirm,
  //       "role": '6',
  //   }

  //   console.log(data);
  //   fetch('http://18.217.12.123:8000/admin/createuser', {
  //           method: "POST",
  //           headers: {
  //               'Accept': 'application/json',
  //               'Content-Type': 'application/json'
  //           },
  //           body: JSON.stringify(data)
  //       })
  //       .then(response => response.json())
  //       .then(response => {
  //           console.log('Success:', JSON.stringify(response))
  //           console.log(response.statusCode);
  //           console.log(data);
  //           if (response.statusCode == 200) {
  //               //alert('Signup successfully');
  //               signupreset();
  //               $('#signupform .close').click();
  //               document.getElementById("snackbar").innerHTML = "<div class='animated fadeInRight success-color white-text p-3 text-center rounded font-weight-bolder'>Signup Successful</div>";
  //               snackbar();

  //           } else {
  //               alert('Please fill all the fields');
  //           }
  //       })
  //       .catch(error => console.error('Error:', error));
  // var data = firebase.auth().createUserWithEmailAndPassword(email,password).then(function(snapshot){
  // var email = firebase.auth().currentUser;
  //console.log(email);
  // console.log(data);
  // closelogin();

  function signupreset() {
      document.getElementById('signupfname').value = "";
      document.getElementById('signuplname').value = "";
      document.getElementById('signuppassword').value = "";
      document.getElementById('signupmobile').value = "";
      document.getElementById('signupemail').value = "";
      document.getElementById("confirmpass").value = "";
      document.getElementById('signupfname').focus();
      document.getElementById('signuplname').focus();
      document.getElementById('signuppassword').focus();
      document.getElementById('signupmobile').focus();
      document.getElementById('signupemail').focus();
      document.getElementById("confirmpass").focus();
      document.getElementById("cnfpassalert").style.display = "none";
  }


  function closesignup() {

      $("#loginalert").html("<div class='alert alert-success animated fadeInRight'><a class='close' data-dismiss='alert' href='#'>x</a><small>Login Successful</small></div>");
      $("#modalLRForm").addClass(".close");
  }


  //Logout function

  function logout() {
      //   localStorage.clear();
      document.getElementById("useraccount").style.display = "none";
      document.getElementById("log").style.display = "block";
      // $("#userlink").html("<div class='animated fadeInRight danger-color white-text p-3 text-center rounded font-weight-bolder'>Logout</div>");
      //  snackbar();
  }



  //Quotation form 
  function quotation() {

      console.log('quotaion called');

      var name = document.getElementById('qname').value;
      var email = document.getElementById('qemail').value;
      var mobile = document.getElementById("qmobile").value;
      var text = document.getElementById('qtext').value;


      if (name == "") {
          $("#quotationstatus").html("<div class='alert alert-danger alert-sm animated fadeInUp'><a class='close' data-dismiss='alert' href='#'>x</a><small>Name cannot be empty</small></div>")
          return false;
      }

      if (email == "") {
          $("#quotationstatus").html("<div class='alert alert-danger alert-sm animated fadeInUp'><a class='close' data-dismiss='alert' href='#'>x</a><small>Email cannot be empty</small></div>")
          return false;
      } else {
          var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
          if (!re.test(email)) {
              $("#quotationstatus").html("<div class='alert alert-warning alert-sm animated fadeInUp'><a class='close' data-dismiss='alert' href='#'>x</a><small>Invalid Email</small></div>")
              return false;
          }
      }

      if (mobile == "") {
          $("#quotationstatus").html("<div class='alert alert-danger alert-sm animated fadeInUp'><a class='close' data-dismiss='alert' href='#'>x</a><small>Mobile number cannot be empty</small></div>")
          return false;

      } else if (mobile.length < 10) {
          $("#quotationstatus").html("<div class='alert alert-danger alert-sm animated fadeInUp'><a class='close' data-dismiss='alert' href='#'>x</a><small>Invalid mobile number</small></div>")
          return false;
      }


      if (text == "") {
          $("#quotationstatus").html("<div class='alert alert-danger alert-sm animated fadeInUp'><a class='close' data-dismiss='alert' href='#'>x</a><small>Please enter your requirement</small></div>")
          return false;
      } else {
          quot();

      }
  }



  function quot() {
      console.log('firebase quot called');
      var db = firebase.firestore();
      //   db.settings({
      //   timestampsInSnapshots: true
      // });

      var name = document.getElementById('qname').value;
      var mobile = document.getElementById('qmobile').value;
      var email = document.getElementById('qemail').value;
      var text = document.getElementById('qtext').value;

      db.collection("contactform").add({
              name: name,
              email: email,
              mobile: mobile,
              text: text


          }).then(function(docRef) {
              console.log("Document written with ID: ", docRef.id);
              console.log(name + email + mobile + text);
              success();
              restforms();

          })
          .catch(function(error) {
              console.error("Error adding document: ", error);
              alert("Data Not saved");
          });

  }



  function restforms() {
      document.getElementById("qname").value = "";
      document.getElementById("qname").focus();
      document.getElementById("qemail").value = "";
      document.getElementById("qemail").focus();
      document.getElementById("qmobile").value = "";
      document.getElementById("qmobile").focus();
      document.getElementById("qtext").value = "";
      document.getElementById("qtext").focus();
  }

  function success() {
      document.getElementById("quote").style.display = "none";
      document.getElementById("qhead").style.display = "none";
      document.getElementById("qalerthide").style.display = "none";
      document.getElementById("success").style.display = "block";
  }

  function quote() {
      document.getElementById("quote").style.display = "block";
      document.getElementById("qhead").style.display = "block";
      document.getElementById("qalerthide").style.display = "block";
      document.getElementById("success").style.display = "none";
  }



  //  Offers validation

  function offer() {

      console.log('offers called');

      var name = document.getElementById('oname').value;
      var email = document.getElementById('oemail').value;
      var mobile = document.getElementById("omobile").value;


      if (name == "") {
          $("#offersstatus").html("<div class='alert alert-danger alert-sm animated fadeInUp'><a class='close' data-dismiss='alert' href='#'>x</a><small>Name cannot be empty</small></div>")
          return false;
      }

      if (email == "") {
          $("#offersstatus").html("<div class='alert alert-danger alert-sm animated fadeInUp'><a class='close' data-dismiss='alert' href='#'>x</a><small>Email cannot be empty</small></div>")
          return false;
      } else {
          var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
          if (!re.test(email)) {
              $("#offersstatus").html("<div class='alert alert-warning alert-sm animated fadeInUp'><a class='close' data-dismiss='alert' href='#'>x</a><small>Invalid Email</small></div>")
              return false;
          }
      }

      if (mobile == "") {
          $("#offersstatus").html("<div class='alert alert-danger alert-sm animated fadeInUp'><a class='close' data-dismiss='alert' href='#'>x</a><small>Mobile cannot be empty</small></div>")
          return false;
      } else {
          offe();

      }
  }



  function offe() {
      console.log('firebase offer called');
      var db = firebase.firestore();
      //   db.settings({
      //   timestampsInSnapshots: true
      // });

      var name = document.getElementById('oname').value;
      var mobile = document.getElementById('oemail').value;
      var email = document.getElementById('omobile').value;


      db.collection("contactform").add({
              name: name,
              email: email,
              mobile: mobile,



          }).then(function(docRef) {
              console.log("Document written with ID: ", docRef.id);
              console.log(name + email + mobile);
              offersuccess();
              resetoffer();

          })
          .catch(function(error) {
              console.error("Error adding document: ", error);
              alert("Data Not saved");
          });

  }



  function resetoffer() {
      document.getElementById("oname").value = "";
      document.getElementById("oemail").value = "";
      document.getElementById("omobile").value = ""
  }

  function offersuccess() {
      document.getElementById("offerquote").style.display = "none";
      document.getElementById("ohead").style.display = "none";
      document.getElementById("oalerthide").style.display = "none";
      document.getElementById("offersuccess").style.display = "block";
      $(document).ready(function() {
          resetoffer();
      });
  }

  function offerquote() {
      document.getElementById("offerquote").style.display = "block";
      document.getElementById("ohead").style.display = "block";
      document.getElementById("oalerthide").style.display = "block";
      document.getElementById("offersuccess").style.display = "none";
  }



  // Contact form
  function contact() {

      console.log('contact called');

      var name = document.getElementById('cname').value;
      var email = document.getElementById('cemail').value;
      var mobile = document.getElementById("cmobile").value;
      var text = document.getElementById('ctext').value;


      if (name == "") {
          $("#contactstatus").html("<div class='alert alert-danger alert-sm animated fadeInUp'><a class='close' data-dismiss='alert' href='#'>x</a><small>Name cannot be empty</small></div>")
          return false;
      }

      if (email == "") {
          $("#contactstatus").html("<div class='alert alert-danger alert-sm animated fadeInUp'><a class='close' data-dismiss='alert' href='#'>x</a><small>Email cannot be empty</small></div>")
          return false;
      } else {
          var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
          if (!re.test(email)) {
              $("#contactstatus").html("<div class='alert alert-warning alert-sm animated fadeInUp'><a class='close' data-dismiss='alert' href='#'>x</a><small>Invalid Email</small></div>")
              return false;
          }
      }

      if (mobile == "") {
          $("#contactstatus").html("<div class='alert alert-danger alert-sm animated fadeInUp'><a class='close' data-dismiss='alert' href='#'>x</a><small>Mobile cannot be empty</small></div>")
          return false;
      }


      if (text == "") {
          $("#contactstatus").html("<div class='alert alert-danger alert-sm animated fadeInUp'><a class='close' data-dismiss='alert' href='#'>x</a><small>Please enter your requirement</small></div>")
          return false;
      } else {
          document.getElementsByName("form").submit();

          //    cont();
          alert(" Name : " + name + " n Email : " + email + " n Form Tag : <form> Form Submitted Successfully......");
      }
  }



  function cont() {
      // console.log('firebase contact called');
      var db = firebase.firestore();
      //   db.settings({
      //   timestampsInSnapshots: true
      // });

      var name = document.getElementById('cname').value;
      var mobile = document.getElementById('cmobile').value;
      var email = document.getElementById('cemail').value;
      var text = document.getElementById('ctext').value;

      db.collection("contactform").add({
              name: name,
              email: email,
              mobile: mobile,
              text: text,


          }).then(function(docRef) {
              console.log("Document written with ID: ", docRef.id);
              //  console.log(name+email+mobile+text);
              $("#contactsuccess").html("<div class='alert alert-success alert-sm animated fadeInUp'><a class='close' data-dismiss='alert' href='#'>x</a><small>Thanks for subscribing</small></div>")
              resetcontact();

          })
          .catch(function(error) {
              console.error("Error adding document: ", error);
              alert("Data Not saved");
          });

  }


  function resetcontact() {
      document.getElementById("cname").value = "";
      document.getElementById("cemail").value = "";
      document.getElementById("cmobile").value = "";
      document.getElementById('ctext').value = "";
  }



  //Booking Validation

  function booking() {

      console.log('Booking called');

      var name = document.getElementById('bname').value;
      var mobile = document.getElementById("bmobile").value;
      var email = document.getElementById('bemail').value;
      var address = document.getElementById('baddress').value;


      if (name == "") {
          $("#bookstatus").html("<div class='alert alert-danger alert-sm animated fadeInUp'><a class='close' data-dismiss='alert' href='#'>x</a><small>Name cannot be empty</small></div>")
          return false;
      }

      if (email == "") {
          $("#bookstatus").html("<div class='alert alert-danger alert-sm animated fadeInUp'><a class='close' data-dismiss='alert' href='#'>x</a><small>Email cannot be empty</small></div>")
          return false;
      } else {
          var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
          if (!re.test(email)) {
              $("#bookstatus").html("<div class='alert alert-warning alert-sm animated fadeInUp'><a class='close' data-dismiss='alert' href='#'>x</a><small>Invalid Email</small></div>")
              return false;
          }
      }

      if (mobile == "") {
          $("#bookstatus").html("<div class='alert alert-danger alert-sm animated fadeInUp'><a class='close' data-dismiss='alert' href='#'>x</a><small>Mobile cannot be empty</small></div>")
          return false;
      }


      if (address == "") {
          $("#bookstatus").html("<div class='alert alert-danger alert-sm animated fadeInUp'><a class='close' data-dismiss='alert' href='#'>x</a><small>Please enter address</small></div>")
          return false;
      } else {

          order();

      }
  }

  function order() {
      console.log('firebase contact called');
      var db = firebase.firestore();
      //   db.settings({
      //   timestampsInSnapshots: true
      // });

      var name = document.getElementById('bname').value;
      var mobile = document.getElementById('bmobile').value;
      var email = document.getElementById('bemail').value;
      var address = document.getElementById('baddress').value;

      db.collection("orders").add({
              name: name,
              email: email,
              mobile: mobile,
              address: address


          }).then(function(docRef) {
              console.log("Document written with ID: ", docRef.id);
              console.log(name + email + mobile + address);
              resetbook();


          })
          .catch(function(error) {
              console.error("Error adding document: ", error);
              alert("Data Not saved");
          });

  }

  function resetbook() {
      $("#bookstatus").html("<div class='alert alert-success animated fadeInUp'><a class='close' data-dismiss='alert' href='#'>x</a><small>Thanks for Booking with us</small></div>")
      document.getElementById("bname").value = "";
      document.getElementById("bemail").value = "";
      document.getElementById("bmobile").value = "";
      document.getElementById('baddress').value = "";
  }


  // Join 

  function joinform() {

      console.log('join called');

      var name = document.getElementById('joinname').value;
      var email = document.getElementById('joinemail').value;
      var mobile = document.getElementById("joinmobile").value;


      if (name == "") {
          $("#joinstatus").html("<div class='alert alert-danger alert-sm animated fadeInUp'><a class='close' data-dismiss='alert' href='#'>x</a><small>Name cannot be empty</small></div>")
          return false;
      }

      if (mobile == "") {
          $("#joinstatus").html("<div class='alert alert-danger alert-sm animated fadeInUp'><a class='close' data-dismiss='alert' href='#'>x</a><small>Mobile cannot be empty</small></div>")
          return false;
      } else {
          joindata();
      }
  }


  function joindata() {
      console.log('firebase offer called');
      var db = firebase.firestore();
      //   db.settings({
      //   timestampsInSnapshots: true
      // });

      var name = document.getElementById('joinname').value;
      var mobile = document.getElementById('joinemail').value;
      var email = document.getElementById('joinmobile').value;


      db.collection("vendorform").add({
              name: name,
              email: email,
              mobile: mobile,



          }).then(function(docRef) {
              console.log("Document written with ID: ", docRef.id);
              console.log(name + email + mobile);
              resetjoin();

          })
          .catch(function(error) {
              console.error("Error adding document: ", error);
              alert("Data Not saved");
          });

  }

  function resetjoin() {
      $("#joinstatus").html("<div class='alert alert-success alert-sm animated fadeInUp'><a class='close' data-dismiss='alert' href='#'>x</a><small>Thanks for joining with us, our team will get back to you soon</small></div>")
      document.getElementById("joinname").value = "";
      document.getElementById("joinemail").value = "";
      document.getElementById("joinmobile").value = "";

  }

  function username(user) {
      console.log("fc" + user.fname);

      if (user == true) {
          document.getElementById("log").style.display = "none";
          document.getElementById("useraccount").style.display = "block";
          document.getElementById("user").innerHTML = "Welcome: &nbsp;&nbsp;" + user.fname + " " + user.lname;
      }
  }