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
        photo.setAttribute('src', data);
    }

    startbutton.addEventListener('click', function(ev)
    {
        takepicture();
        ev.preventDefault();
    }, false);

    nonebutton.addEventListener('click', function()
    {
        canvas.className = '';
        var effect = filters[0 % filters.length];
        if (effect)
            canvas.classList.add(effect);
    }, false);

    graybutton.addEventListener('click', function()
    {
        canvas.className = '';
        var effect = filters[1 % filters.length];
        if (effect)
            canvas.classList.add(effect);
    }, false);

    sepiabutton.addEventListener('click', function()
    {
        canvas.className = '';
        var effect = filters[2 % filters.length];
        if (effect)
            canvas.classList.add(effect);
    }, false);

    blurbutton.addEventListener('click', function()
    {
        canvas.className = '';
        var effect = filters[3 % filters.length];
        if (effect)
            canvas.classList.add(effect);
    }, false);

    brightbutton.addEventListener('click', function()
    {
        canvas.className = '';
        var effect = filters[4 % filters.length];
        if (effect)
            canvas.classList.add(effect);
    }, false);

    contrastbutton.addEventListener('click', function()
    {
        canvas.className = '';
        var effect = filters[5 % filters.length];
        if (effect)
            canvas.classList.add(effect);
    }, false);

    rev1button.addEventListener('click', function()
    {
    canvas.className = '';
    var effect = filters[6 % filters.length];
    if (effect)
        canvas.classList.add(effect);
    }, false);

    rev2button.addEventListener('click', function()
    {
        canvas.className = '';
        var effect = filters[7 % filters.length];
        if (effect)
            canvas.classList.add(effect);
    }, false);

    rev3button.addEventListener('click', function()
    {
        canvas.className = '';
        var effect = filters[8 % filters.length];
        if (effect)
            canvas.classList.add(effect);
    }, false);

    saturbutton.addEventListener('click', function()
    {
        canvas.className = '';
        var effect = filters[9 % filters.length];
        if (effect)
            canvas.classList.add(effect);
    }, false);

    invertbutton.addEventListener('click', function()
    {
        canvas.className = '';
        var effect = filters[10 % filters.length];
        if (effect)
            canvas.classList.add(effect);
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

var loadFile = function(event)
{
    var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
};

function uploadPic()
{
    var dataURL = canvas.toDataURL('image/png', 0.5);
    document.getElementById('hidden_data').value = dataURL;
    var fd = new FormData(document.forms["form1"]);
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'upload_data.php', true);
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