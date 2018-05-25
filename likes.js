    document.querySelector('.vote_like', '#vote').addEventListener('click', function (e) {
        e.preventDefault();
        vote(1);
    });

    document.querySelector('.vote_dislike', '#vote').addEventListener('click', function (e) {
        e.preventDefault();
        vote(-1);
    });

    function vote(value) {

        var vote = document.getElementById('vote');

        var request = new XMLHttpRequest();

        request.open('POST', 'likes.php', true);
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        request.onreadystatechange = function () {
            if (request.readyState === 4 && request.status === 200) {
                vote.classList.remove("is-liked", "is-disliked");
                data_response = JSON.parse(request.responseText);
                document.getElementById("vote_like_bar").style.width=100*(parseInt(data_response.like_count)/(parseInt(data_response.like_count)+parseInt(data_response.dislike_count)))+"%";
                if (value === 1) {
                    vote.classList.add("is-liked");
                    document.getElementById("like_count").innerHTML = data_response.like_count;
                    document.getElementById("dislike_count").innerHTML = data_response.dislike_count;
                }
                if (value === -1) {
                    vote.classList.add("is-disliked");
                    document.getElementById("like_count").innerHTML = data_response.like_count;
                    document.getElementById("dislike_count").innerHTML = data_response.dislike_count;
                }
                 if (data_response.like_count === 0 && data_response.dislike_count === 0) {
                     document.getElementById("vote_like_bar").style.width = "100%";
                     vote.classList.remove("is-liked", "is-disliked");
                 }
            }
        };
        request.send("ref_photo="+vote.getAttribute('data-ref_photo')+"&ref_id="+vote.getAttribute('data-ref_id')+"&user_id="+vote.getAttribute('data-user_id')+"&vote="+value);
    }
