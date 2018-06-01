function superpPhoto(e, name)
{
        var request = new XMLHttpRequest();
        var params = "href="+e.getAttribute('data-href');
        var sourceOfPicture = "images/superposition_image/" + name + ".png";
        var img = document.getElementById('output2');
        var img2 = document.getElementById('output_web');
        img.src = sourceOfPicture.replace('90x90', '225x225');
        img.style.display = "block";
        img2.src = sourceOfPicture.replace('90x90', '225x225');
        img2.style.display = "block";
        console.log(e.getAttribute('data-href'));
        request.open('POST', 'webcamData.php', true);
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        request.onreadystatechange = function()
        {
            if (request.readyState === 4 && request.status === 200)
            {
                console.log(request.responseText);
            }
        };
        request.send(params);

}