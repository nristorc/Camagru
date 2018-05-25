(function(){
    var x = document.getElementsByClassName("reply");
        for (var i = 0; i < x.length; i++) {
            x[i].addEventListener("click", function (e) {
                e.preventDefault();
                var parent_id = this.getAttribute('data-id');

                document.querySelector('h4').textContent = "Répondre à ce commentaire";

                var parent_val = document.getElementById('parent_id');
                parent_val.value = parent_id;
            }, false);
        }
    }
)();
