<?php
require 'inc/bootstrap.php';
App::getAuth()->restrict();
require "inc/header.php";

?>
<body>
<section>
    <div id="add_image">
        <h3> Vos Montages </h3>
        <?php
        $db = App::getDatabase();
        $ret = $db->query("SELECT login, path_to_photo, id_photo, creation_date FROM camagru.photo  WHERE id_member = ? ORDER BY creation_date DESC ", [$_SESSION['auth']->id]);
        while ($display_gallery = $ret->fetch())
        {
            ?>
            <div id="past_upload">
                <img onclick='self.location.href="comments_likes.php?id_photo=<?=$display_gallery->id_photo;?>"' src="<?= $display_gallery->path_to_photo;?>"/>
                <br/>
                <?= $display_gallery->creation_date; ?> <br/><br/>
            </div>
            <?php
        }
        ?>
    </div>

    <div id="image">
        <div class="tab">
            <button class="tablinks" onclick="openTab(event, 'webcam')"> Accéder à la Webcam </button>
            <button class="tablinks" onclick="openTab(event, 'file_upload')"> Accéder à vos documents </button>
        </div>
        <div id="webcam" class="tabcontent">
            <br/>
            <video id="video"></video>
            <br/>
            <div class="wrapper">
                <canvas id="canvas"></canvas>
                <img id="output_web" src=""/>
            </div>
            <br/>
            <button id="startbutton"><a style="color: black; text-decoration: none" href="webcamData.php"> Prendre une photo </a></button>
            <br/>
            <form method="POST" name="form1">
                <input name="hidden_data" id='hidden_data' type="hidden"/>
            </form>
            <form method="post" id="final_submit" action="webcamData.php">
                <input type="submit" value="Valider" name="submit"/>
            </form>
        </div>

        <div id="file_upload" class="tabcontent" style="display: block">
            <form method="post" action="webcamData.php" enctype="multipart/form-data" id="form">
                Selectionner une image:
                <input type="file" name="file" id="file" onchange="ValidateSize(this)"/><br/><br/>
                <div id="wrapper">
                    <img id="default_upload" src="ressources/default_upload.jpg"/>
                    <img id="output" src="
                    <?php
                        if (isset($_SESSION['fileToUpload']) && !empty($_SESSION['fileToUpload']))
                            echo $_SESSION['fileToUpload']['path_tmp'];
                    ?> "/><br/>
                    <img id="output2" src=""/>
                </div>
           </form>
            <br/>
            <form method="post" id="final_submit" action="webcamData.php">
                <input type="submit" value="Valider" name="submit"/>
            </form>
        </div>
    </div>

    <div id="edit_image">
        <div id="watermarks">
            <h3> Watermarks </h3>
            <button id="superp_photo_chat" data-href="images/superposition_image/chat.png" onclick="superpPhoto(this, 'chat')">
                <img src="images/superposition_image/chat.png" alt="photo_chat">
            </button><br/>
            <button id="superp_photo_chien" data-href="images/superposition_image/chien.png" onclick="superpPhoto(this, 'chien')">
                <img src="images/superposition_image/chien.png" alt="photo_chien">
            </button><br/>
            <button id="superp_photo_perroquet" data-href="images/superposition_image/perroquet.png" onclick="superpPhoto(this, 'perroquet')">
                <img src="images/superposition_image/perroquet.png" alt="photo_perroquet">
            </button><br/>
            <button id="superp_photo_unicorn" data-href="images/superposition_image/unicorn.png" onclick="superpPhoto(this, 'unicorn')">
                <img src="images/superposition_image/unicorn.png" alt="photo_licorne">
            </button><br/>
            <button id="superp_photo_chevre" data-href="images/superposition_image/chevre.png" onclick="superpPhoto(this, 'chevre')">
                <img src="images/superposition_image/chevre.png" alt="photo_chevre">
            </button><br/>
        </div>
    </div>
    <script src="scripts.js"></script>
</section>
</body>
    <div class="clear"></div>
<?php require 'inc/footer.php'?>