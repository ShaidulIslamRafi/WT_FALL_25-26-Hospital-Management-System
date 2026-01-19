function loadDoctor(){
        var xhttp= new XMLHttpRequest();
        xhttp.onreadystatechange= function (){
            if (this.readyState === 4 && this.status === 200){
                document.getElementById("doctorCount").innerHTML=this.responseText;
            }
        };
        xhttp.open("GET","doctor_count.php",true);
        xhttp.send();
    }
    function loadPatient(){
        var xhttp= new XMLHttpRequest();
        xhttp.onreadystatechange= function (){
            if (this.readyState === 4 && this.status === 200){
                document.getElementById("patientCount").innerHTML=this.responseText;
            }
        };
        xhttp.open("GET","patient_count.php",true);
        xhttp.send();
    }
    function loadBooking(){
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function(){
    if(this.readyState === 4 && this.status === 200){
      document.getElementById("bookingCount").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET","booking_count.php",true);
  xhttp.send();
}

function loadTodayBooking(){
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function(){
    if(this.readyState === 4 && this.status === 200){
      document.getElementById("todayBookingCount").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET","today_booking_count.php",true);
  xhttp.send();
}

    
    loadDoctor();
    loadPatient();
    loadBooking();
    loadTodayBooking();


    setInterval(function(){
        loadDoctor();
        loadPatient();
        loadBooking();
        loadTodayBooking();
},5000);