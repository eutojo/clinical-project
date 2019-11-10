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