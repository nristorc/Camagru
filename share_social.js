(function(){

    var popupcenter = function(url, title, width, height){

        var popupWidth = width || 640;
        var popupHeight = height || 320;

        var window_top = window.screenTop || window.screenY;
        var window_left = window.screenLeft || window.screenX;

        var window_width = window.innerWidth || document.documentElement.clientWidth;
        var window_height = window.innerHeight || document.documentElement.clientHeight;



        var popupLeft = window_left + window_width / 2 - popupWidth / 2;
        var popupTop = window_top + window_height / 2 - popupHeight / 2;

        var popup = window.open(url, title, "scrollbars=yes, width=" + popupWidth + ", height="
            + popupHeight + ", top=" + popupTop + ", left=" + popupLeft + "");
        popup.focus();
        return true;
    };

    if (document.querySelector('.share_twitter') === null)
        return false;
    else{
        document.querySelector('.share_twitter').addEventListener('click', function (e) {
            e.preventDefault();
            var url = this.getAttribute('data-url');
            var shareUrl = "https://twitter.com/intent/tweet?text=" + encodeURIComponent(document.title) +
                "&via=NinaRistorcelli" + "&url=" + encodeURIComponent(url);

            popupcenter(shareUrl, "Partager sur Twitter");

        });
    }

    if (document.querySelector('.share_facebook') === null)
        return false;
    else{
        document.querySelector('.share_facebook').addEventListener('click', function (e) {
            e.preventDefault();
            var url = this.getAttribute('data-url');
            var shareUrl = "https://facebook.com/sharer/sharer.php?u=" + encodeURIComponent(url);

            popupcenter(shareUrl, "Partager sur Facebook");

        });
    }

})();