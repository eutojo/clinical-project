// Check Permissions
function invalidLogin(){
    alert('Invalid username or password.');
}

function loginPrompt(){
    alert('You must login to view this page.');
    window.location = './home.php';
}

function adminPrompt(){
    alert('You must be an administrator to view this page.');
    window.location = './home.php';
}

// Filtering

function subjectsFilter(){
    var subject_row = document.getElementsByName('subject-row');
    var researcher_subjects_only = document.getElementById('researcher_subjects_only');

    if(researcher_subjects_only.checked == true){
        var i;
        for (i = 0; i < subject_row.length; i++) {
            subject_row[i].classList.add("hide");
          }
    } else {
        var i;
        for (i = 0; i < subject_row.length; i++) {
            subject_row[i].classList.remove("hide");
        }
    }
}

// New Input Validation
// Final check before submission
function validInfo(){

    // Check if all input is valid
    if(validateEmail() && validatePassword1() && validatePassword2() && validateName('first') &&
    validateName('surname') && validateBirthday()){
        return true;
    }
    alert("Please fix the errors before submitting.");
    return false;
}

// Just to check the format of the password - so it can be used in validatePassword1 
// and validatePassword2
function simplePasswordCheck(){
    var passwordRE = /(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]){8,}/;
    var lengthRE = /^.{8,}$/
    var inputPassword = document.getElementById('input__password1');
    if(passwordRE.test(inputPassword.value) && lengthRE.test(inputPassword.value)){
        return true;
    } 
}

// To ensure that the chosen password is valid
function validatePassword1(){

    var element = document.getElementById('validation__password1');
    var reenterPassword = document.getElementById('input__password2');

    // To make sure to update the message for Password2 if the user decides
    // to change Password 1
    if(reenterPassword.value != "") {
        validatePassword2();
    }

    // Check if password valid
    if(simplePasswordCheck()){
        element.innerHTML = "No errors.";
        element.classList.add("correct");
        element.classList.remove("incorrect");
        return true;
    }
    
    // If not valid, error message
    element.classList.add("incorrect");
    element.classList.remove("correct");
    element.innerHTML = "Password must contain at least 8 characters and include uppercase, lowercase and numbers."
    
    return false;
    
}


// To ensure that passwords match
function validatePassword2(){
            
    var inputPassword = document.getElementById('input__password1');
    var reenterPassword = document.getElementById('input__password2');
    var element = document.getElementById('validation__password2');


    // Check if the a value has been entered into the first
    // password box
    if(inputPassword == ""){
        element.innerHTML = "Please input password first."
        element.classList.add("incorrect");
        element.classList.remove("correct");
        return false;
    }

    // Check if the entered password is valid
    if(!simplePasswordCheck()){
        element.innerHTML = "Password is invalid. Please fix this."
        element.classList.add("incorrect");
        element.classList.remove("correct");
        return false;
    }

    // Check if the passwords match
    if(inputPassword.value == reenterPassword.value){
        element.innerHTML = "No errors."
        element.classList.add("correct");
        element.classList.remove("incorrect");
        return true;
    } 
       
    // If not valid, error message
    element.innerHTML = "Passwords must match."
    element.classList.add("incorrect");
    element.classList.remove("correct");

    return false;
    
}

// Name validation
function validateName(type){
    var nameRE = /^[a-zA-Z'-\s]+$/;
    
    if(type == "first"){
        var inputName = document.getElementById('input__name');
        var error = 'validation__name';
        var element = document.getElementById(error);
    } else {
        var inputName = document.getElementById('input__surname');
        var error = 'validation__surname';
        var element = document.getElementById(error);
    }

    if(nameRE.test(inputName.value)){
        element.innerHTML = "No errors.";
        element.classList.add("correct");
        element.classList.remove("incorrect");
        return true;
    } 

    element.innerHTML = "Please enter a valid name";
    element.classList.add("incorrect");
    element.classList.remove("correct");

    return false;
}

function removeSubject(researcher_id, subject_id){
    if(subject_id == "NULL"){
        subject_id = null;
        console.log('subject null');
    }

    if(researcher_id == "NULL"){
        researcher_id = null;
        console.log('researcher null');
    }

    $.ajax({
        type: "POST",
        url: '../PHP/remove-entry-logic.php',
        data:{researcher_id:researcher_id, subject_id: subject_id},
        success:function(html) {
            location.reload();
        }

   });
}