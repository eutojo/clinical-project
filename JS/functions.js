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

function subjectPromt(){
    alert('Subject doesn\'t exist or you do not have permission to view their details.');
    window.location = './subjects.php';
}

function researcherPrompt(admin){
    alert('Researcher doesn\'t exist or you do not have permission to view their details.');

    if(admin == 1){
        window.location = './researchers.php';
    } else {
        window.location = './home.php';
    }
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
function validInfo(page){
    // Check if all input is valid
    if(page == "researcher"){
        if(validateName('first','researcher') && validateName('last','researcher') &&
        validateID('researcher') && validatePassword1() && validatePassword2()){
            return true;
        }
    } else if(page=="subject"){
        if(validateID('subject') && validateName('first','subject') && validateName('last','subject') && validateBirthday('subject') && validateContact('subject')){
            console.log('yes');
            return true;
            
        }
    } else if(page=="inv_researcher"){
        var researcher_name = document.getElementById('researcher_name');
        if(researcher_name.readOnly == true){
            updateSubject('inv_researcher');
            return false;
        }
        if(updateSubject('inv_researcher')){
            return true;
        }
    }else{
        var subject_name = document.getElementById('subject_name');
        if(subject_name.readOnly == true){
            updateSubject();
            return false;
        }
        if(updateSubject()){
            return true;
        }
    }

    alert("Please fix the errors before submitting.");
    return false;
}

// Just to check the format of the password - so it can be used in validatePassword1 
// and validatePassword2
function simplePasswordCheck(page){
    var passwordRE = /(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]){8,}/;
    var lengthRE = /^.{8,}$/

    if(page == "inv_researcher"){
        var inputPassword = document.getElementById('researcher_password1');
        if (inputPassword.value == ""){
            return true;
        }
    } else {
        var inputPassword = document.getElementById('new__researcher_password1');
    }
    if(passwordRE.test(inputPassword.value) && lengthRE.test(inputPassword.value)){
        return true;
    } 
}

// To ensure that the chosen password is valid
function validatePassword1(page){

    if(page == "inv_researcher"){
        var error = document.getElementById('validation__inv_researcher_password1');
        var reenterPassword = document.getElementById('researcher_password2');
    
    } else {
        var error = document.getElementById('validation__researcher_password1');
        var reenterPassword = document.getElementById('new__researcher_password2');
    }
   
    // To make sure to update the message for Password2 if the user decides
    // to change Password 1
    if(reenterPassword.value != "") {
        if(page == "inv_researcher"){
            validatePassword2("inv_researcher");

        } else {
            validatePassword2();
        }
    }

    // Check if password valid
    if(page == "inv_researcher"){
        if(simplePasswordCheck('inv_researcher')){
            error.innerHTML = "No errors.";
            error.classList.add("correct");
            error.classList.remove("incorrect");
        return true;
        }
    } else {
        // Check if the entered password is valid
        if(simplePasswordCheck()){
            error.innerHTML = "No errors.";
            error.classList.add("correct");
            error.classList.remove("incorrect");
            return true;
        }
    }
    
    // If not valid, error message
    error.classList.add("incorrect");
    error.classList.remove("correct");
    error.innerHTML = "Password must contain at least 8 characters and include uppercase, lowercase and numbers."
    
    return false;
    
}


// To ensure that passwords match
function validatePassword2(page){
            
    if (page == "inv_researcher"){
        var inputPassword = document.getElementById('researcher_password1');
        var reenterPassword = document.getElementById('researcher_password2');
        var error = document.getElementById('validation__inv_researcher_password2');
        if(inputPassword.value == "" && reenterPassword.value==""){
            return true;
        }
    } else {
        var inputPassword = document.getElementById('new__researcher_password1');
        var reenterPassword = document.getElementById('new__researcher_password2');
        var error = document.getElementById('validation__researcher_password2');
    }
    


    // Check if the a value has been entered into the first
    // password box
    if(inputPassword == ""){
        error.innerHTML = "Please input password first."
        error.classList.add("incorrect");
        error.classList.remove("correct");
        return false;
    }

    if(page == "inv_researcher"){
        if(!simplePasswordCheck('inv_researcher')){
            error.innerHTML = "Password is invalid. Please fix this."
            error.classList.add("incorrect");
            error.classList.remove("correct");
            return false;
        }
    } else {
        // Check if the entered password is valid
        if(!simplePasswordCheck()){
            error.innerHTML = "Password is invalid. Please fix this."
            error.classList.add("incorrect");
            error.classList.remove("correct");
            return false;
        }
    }
    

    // Check if the passwords match
    if(inputPassword.value == reenterPassword.value){
        error.innerHTML = "No errors."
        error.classList.add("correct");
        error.classList.remove("incorrect");
        return true;
    } 
       
    // If not valid, error message
    error.innerHTML = "Passwords must match."
    error.classList.add("incorrect");
    error.classList.remove("correct");

    return false;
    
}

// ID validation TODO:
function validateID(page){
    

    var researcher_flag = 0;
    if(page == "researcher") {
        var IDRE = /^[A-Z]{4,6}$/;
        var inputID = document.getElementById('new__researcher_id');
        var error = document.getElementById('validation__researcher_id');
        researcher_flag = 1;
    } else {
        var IDRE = /^[A-Z]{2}[0-9]{2}$/;
        var inputID = document.getElementById('new__subject_id');
        var error = document.getElementById('validation__subject_id');
    }

    if(IDRE.test(inputID.value)){
        $.ajax({
            type: "POST",
            url: '../PHP/check-id.php',
            data:{input_ID:inputID.value, researcher_flag: researcher_flag},
            success:function(html) {
                if(html == 1){
                    error.innerHTML = "ID must be unique.";
                    error.classList.add("incorrect");
                    error.classList.remove("correct");
                    return false;
                } else {
                    error.innerHTML = "No errors.";
                    error.classList.add("correct");
                    error.classList.remove("incorrect");
                    return true;
                }                
            }
    
       });
    } else {
        error.innerHTML = "Please enter a valid ID";
        error.classList.add("incorrect");
        error.classList.remove("correct");
    
        return false;
    }
}

function validateContact(page){
    var contactRE = /^\d{10}$/;

    if(page=='subject'){
        var inputContact = document.getElementById('new__subject_contact');
        var error = document.getElementById('validation__subject_contact');
    } else {
        var inputContact = document.getElementById('subject_contact');
        var error = document.getElementById('validation__inv_subject_contact');
        if(inputContact.value == ""){
            return true;
        }
    }
    
    if(contactRE.test(inputContact.value)){
        error.innerHTML = "No errors.";
        error.classList.add("correct");
        error.classList.remove("incorrect");
        return true;
    }

    error.innerHTML = "Please enter a valid contact";
    error.classList.add("incorrect");
    error.classList.remove("correct");
    return false;
}

// Name validation
function validateName(type, page){
    var nameRE = /^[a-zA-Z'-\s]+$/;
    
    if(type == "first"){
        console.log(page);
        if(page == "researcher"){
            
            var inputName = document.getElementById('new__researcher_name');
            var error = document.getElementById('validation__researcher_name');
        } else if(page=="subject"){
            var inputName = document.getElementById('new__subject_name');
            var error = document.getElementById('validation__subject_name');
        } else if(page =="inv_researcher"){
            var inputName = document.getElementById('researcher_name');
            var error = document.getElementById('validation__inv_researcher_name');
        } else {
            var inputName = document.getElementById('subject_name');
            var error = document.getElementById('validation__inv_subject_name');
        }
        
    } else {
        if(page == "researcher"){
            var inputName = document.getElementById('new__researcher_surname');
            var error = document.getElementById('validation__researcher_surname');
        } else if(page=="subject"){
            var inputName = document.getElementById('new__subject_surname');
            var error = document.getElementById('validation__subject_surname');
        } else if(page =="inv_researcher"){
            var inputName = document.getElementById('researcher_surname');
            var error = document.getElementById('validation__inv_researcher_surname');
        } else {
            var inputName = document.getElementById('subject_surname');
            var error = document.getElementById('validation__inv_subject_surname');
        }
        
    }

    if(nameRE.test(inputName.value)){
        error.innerHTML = "No errors.";
        error.classList.add("correct");
        error.classList.remove("incorrect");
        return true;
    } 

    error.innerHTML = "Please enter a valid name";
    error.classList.add("incorrect");
    error.classList.remove("correct");

    return false;
}

function removeSubject(researcher_id, subject_id){
    if(subject_id == "NULL"){
        subject_id = null;
    }

    if(researcher_id == "NULL"){
        researcher_id = null;
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

function validateBirthday(page){
    var today = new Date();

    // Valid dates between 1900-2099 -> Must update if want to keep reusing :(
    var birthdayRE = /^((0)[1-9]|(1)[0-9]|(2)[0-9]|(3)[0-2])(\/)((0)[1-9]|(1)[0-2])(\/)((19)[0-9][0-9]|(20)[0-9][0-9])$/
    
    if(page == 'subject'){
        var inputBirthday = document.getElementById('new__subject_dob'); 
        var error = document.getElementById('validation__subject_dob');
    } else {
        var inputBirthday = document.getElementById('subject_dob'); 
        var error = document.getElementById('validation__inv_subject_dob');
    }
    

    // Check if valid birthday format
    if(birthdayRE.test(inputBirthday.value)){
        var month = parseInt(inputBirthday.value.slice(3,-5));
        var day = parseInt(inputBirthday.value.slice(0,-8));
        var year = parseInt(inputBirthday.value.slice(-4));

        inputDate = new Date(year, month-1, day);

        // Ensure that the input date is not in the future
        // A birthday of today is not valid, babies shouldn't have to register
        if((inputDate < today) && (inputDate.getDate() == day)){
            error.innerHTML = "No errors.";
            error.classList.add("correct");
            error.classList.remove("incorrect");
            return true;
        } 

    } 

    error.innerHTML = "Please enter a valid birthday.";
    error.classList.add("incorrect");
    error.classList.remove("correct");

    return false;
}

// Changing details
function updateSubject(page){

    if(page == "inv_researcher"){
        var researcher_name = document.getElementById('researcher_name');
        var researcher_surname = document.getElementById('researcher_surname');
        var researcher_password1 = document.getElementById('researcher_password1');
        var researcher_password2 = document.getElementById('researcher_password2');
        var researcher_admin = document.getElementById('researcher_admin');
        
        if(researcher_name.readOnly){
            researcher_name.readOnly = false;
            researcher_surname.readOnly = false;
            researcher_password1.readOnly = false;
            researcher_password2.readOnly = false;
            researcher_admin.disabled = false;
            document.form__change_researcher.submit_button.value = "Submit";
            return false;
        } else {
            researcher_admin.readOnly = false;
            researcher_surname.readOnly = false;
            researcher_password1.readOnly = false;
            researcher_password2.readOnly = false;
            researcher_admin.disabled = false;
            document.form__change_researcher.submit_button.value = "Edit details";

            if(validateName('first','inv_researcher') && validateName('last','inv_researcher') && 
            validatePassword1('inv_researcher') && validatePassword2('inv_researcher')){
                return true;
            }

            return false;
        }
    } else {
        var subject_name = document.getElementById('subject_name');
        var subject_surname = document.getElementById('subject_surname');
        var subject_dob = document.getElementById('subject_dob');
        var subject_gender = document.getElementById('subject_gender');
        var subject_contact = document.getElementById('subject_contact');
        if(subject_name.readOnly){
            subject_name.readOnly = false;
            subject_surname.readOnly = false;
            subject_dob.readOnly = false;
            subject_contact.readOnly = false;
            document.form__change_subject.submit_button.value = "Submit";
            return false;
        } else {
            subject_name.readOnly = true;
            subject_surname.readOnly = true;
            subject_dob.readOnly = true;
            subject_contact.readOnly = true;
            document.form__change_subject.submit_button.value = "Edit details";
    
            if(validateName('first','inv_subject') && validateName('last','inv_subject') &&
            validateBirthday('inv_subject') && validateContact('inv_subject')){
                return true;
            }
            return false;
        }
    }
    
}