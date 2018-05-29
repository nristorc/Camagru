(function()
{
    var streaming = false,
        video        = document.querySelector('#video'),
        canvas       = document.querySelector('#canvas'),
        startbutton  = document.querySelector('#startbutton'),
        width = 500,
        height = 0;

    var constraints = {audio: false, video: true};
    navigator.mediaDevices = undefined;
    var media = navigator.mediaDevices;

    media.getUserMedia(constraints)
        .then((stream) => {
        video.srcObject = stream;
    video.onloadedmetadata = (e) => {
        video.play();};
    })
    .catch((err) => { console.log("An error occured! " + err); });

    video.addEventListener('canplay', function()
    {
        if (!streaming)
        {
            height = video.videoHeight / (video.videoWidth/width);
            video.setAttribute('width', width);
            video.setAttribute('height', height);
            canvas.setAttribute('width', width);
            canvas.setAttribute('height', height);
            streaming = true;
        }
    }, false);

    function takepicture()
    {
        canvas.width = width;
        canvas.height = height;
        canvas.getContext('2d').drawImage(video, 0, 0, width, height);
    }

    function uploadPic()
    {
        document.getElementById('hidden_data').value = canvas.toDataURL('image/png', 0.5);
        var fd = new FormData(document.forms["form1"]);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'webcamData.php', true);
        console.log(xhr);
        xhr.onload = function () {

        };
        xhr.send(fd);
    }

    startbutton.addEventListener('click', function(ev)
    {
        takepicture();
        ev.preventDefault();
    }, false);

    startbutton.addEventListener('click', function(ev)
    {
        uploadPic();
        ev.preventDefault();
        console.log();
    }, false);

})();

/* -------------------------------------------------------------- */

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
    }
    else
        form.submit();
}