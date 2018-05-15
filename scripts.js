(function()
{
    var streaming = false,
        video        = document.querySelector('#video'),
        canvas       = document.querySelector('#canvas'),
        photo        = document.querySelector('#photo'),
        startbutton  = document.querySelector('#startbutton'),
        nonebutton = document.querySelector('#nonebutton'),
        graybutton = document.querySelector('#graybutton'),
        sepiabutton = document.querySelector('#sepiabutton'),
        blurbutton = document.querySelector('#blurbutton'),
        brightbutton = document.querySelector('#brightbutton'),
        contrastbutton = document.querySelector('#contrastbutton'),
        rev1button = document.querySelector('#rev1button'),
        rev2button = document.querySelector('#rev2button'),
        rev3button = document.querySelector('#rev3button'),
        saturbutton = document.querySelector('#saturbutton'),
        invertbutton = document.querySelector('#invertbutton'),
        width = 500,
        height = 0;

    var filters = [
        'none',
        'grayscale',
        'sepia',
        'blur',
        'brightness',
        'contrast',
        'hue-rotate',
        'hue-rotate2',
        'hue-rotate3',
        'saturate',
        'invert'
    ];

    let constraints = {audio: false, video: true};
    navigator.mediaDevices = undefined;
    let media = navigator.mediaDevices;

    media.getUserMedia(constraints)
        .then((stream) => {
        video.srcObject = stream;
    video.onloadedmetadata = (e) => {video.play();};
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
        var data = canvas.toDataURL('image/png', 0.5);
        //photo.setAttribute('src', data);
    }

    function uploadPic()
    {
        var dataURL = canvas.toDataURL('image/png', 0.5);
        document.getElementById('hidden_data').value = dataURL;
        var fd = new FormData(document.forms["form1"]);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'webcamData.php', true);
        console.log(xhr);
        xhr.upload.onprogress = function(e)
        {
            if (e.lenghtComputable)
            {
                var percentComplete = (e.loaded / e.total) * 100;
                alert('Successfully uploaded');
            }
        };
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

/* -------------------------------------------------------------- */


function showPicture() {
    var sourceOfPicture = "images/superposition_image/chat.png";
    var img = document.getElementById('output2')
    img.src = sourceOfPicture.replace('90x90', '225x225');
    img.style.display = "block";
}

function showPicture2() {
    var sourceOfPicture = "images/superposition_image/chien.png";
    var img = document.getElementById('output2')
    img.src = sourceOfPicture.replace('90x90', '225x225');
    img.style.display = "block";
}

function showPicture3() {
    var sourceOfPicture = "images/superposition_image/perroquet.png";
    var img = document.getElementById('output2')
    img.src = sourceOfPicture.replace('90x90', '225x225');
    img.style.display = "block";
}

function showPicture4() {
    var sourceOfPicture = "images/superposition_image/unicorn.png";
    var img = document.getElementById('output2')
    img.src = sourceOfPicture.replace('90x90', '225x225');
    img.style.display = "block";
}