function openTab(evt, tab)
{
    var i, tabcontent, tablinks;

    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    document.getElementById(tab).style.display = "block";
    evt.currentTarget.className += " active";
}

function ValidateSize(file){
    var FileSize = file.files[0].size / 1024 / 1024;
    if (FileSize > 2){
        alert('Le fichier doit faire moins de 2 MB');
        window.location.replace("edit_profile.php");
    }
    else
        form.submit();
}