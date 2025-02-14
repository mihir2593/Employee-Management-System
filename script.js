function myfunc() {
    var empid = document.getElementById("empid").value;
    if (empid == "") {
      document.getElementById("empidmessage").innerHTML = " ** Please enter empid";
    } else {
      document.getElementById("empidmessage").innerHTML = " ";
    }

    var depid = document.getElementById("depid").value;
    if (depid == "") {
      document.getElementById("depnamemessage").innerHTML = " ** Please select department";
    } else {
      document.getElementById("depnamemessage").innerHTML = " ";
    }

    var name = document.getElementById("name").value;
    if (name == "") {
      document.getElementById("namemessage").innerHTML = " ** Please enter username";
    } else {
      document.getElementById("namemessage").innerHTML = " ";
    }

    var designation = document.getElementById("designation").value;
    if (designation == "") {
      document.getElementById("designationmessage").innerHTML = " ** Please enter designation";
    } else {
      document.getElementById("designationmessage").innerHTML = " ";
    }

    var salary = document.getElementById("salary").value;
    if (salary == "") {
      document.getElementById("salarymessage").innerHTML = " ** Please enter Your Salary **" ;
      return false;  
    } else {
      document.getElementById("salarymessage").innerHTML = " ";
    }

    var image = document.getElementById('image'); 
        var file = image.files[0];

        var maxFileSize = 2 * 1024 * 1024; 
        if (file.size > maxFileSize) {
            document.getElementById("imagemessage").innerHTML =" ** File size is too large. Maximum allowed size is 2MB.";
            image.value = ""; 
            return false;
        }else{
          document.getElementById("imagemessage").innerHTML = " ";

        }

    
  document.myform.action = "insert_data.php"; 

  return true; 
}

