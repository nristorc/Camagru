<?php
require 'inc/bootstrap.php';
App::getAuth()->restrict();
require "inc/header.php";

?>
<body>
<section>
    <div id="add_image">
        <h3> Your upload </h3>
        <?php
        $db = App::getDatabase();
        $ret = $db->query("SELECT login, path_to_photo, id_photo, creation_date FROM camagru.photo  WHERE id_member = ? ORDER BY creation_date DESC ", [$_SESSION['auth']->id]);
        while ($display_gallery = $ret->fetch())
        {
            ?>
            <div id="past_upload">
                <img src="<?= $display_gallery->path_to_photo;?>"/>
                <br/>
                <?= $display_gallery->creation_date; ?> <br/><br/>
            </div>
            <?php
        }
        ?>
    </div>

    <div id="image">
        <div class="tab">
            <button class="tablinks" onclick="openTab(event, 'webcam')"> Image from Webcam </button>
            <button class="tablinks" onclick="openTab(event, 'file_upload')"> Image from Computer </button>
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
            <button id="startbutton"><a style="color: black; text-decoration: none" href="webcamData.php"> Take a photo </a></button>
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
                Select image to upload:
                <input type="file" name="file" id="file" onchange="form.submit();"/><br/><br/>
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
            <button id="superp_photo_chat" data-href="images/superposition_image/chat.png" onclick="superpChat(this)">
                <img src="images/superposition_image/chat.png" alt="photo_chat">
            </button><br/>
            <button id="superp_photo_chien" data-href="images/superposition_image/chien.png" onclick="superpChien(this)">
                <img src="images/superposition_image/chien.png" alt="photo_chien">
            </button><br/>
            <button id="superp_photo_perroquet" data-href="images/superposition_image/perroquet.png" onclick="superpPerroquet(this)">
                <img src="images/superposition_image/perroquet.png" alt="photo_perroquet">
            </button><br/>
            <button id="superp_photo_unicorn" data-href="images/superposition_image/unicorn.png" onclick="superpLicorne(this)">
                <img src="images/superposition_image/unicorn.png" alt="photo_licorne">
            </button><br/>
            <button id="superp_photo_chevre" data-href="images/superposition_image/chevre.png" onclick="superpChevre(this)">
                <img src="images/superposition_image/chevre.png" alt="photo_chevre">
            </button><br/>
        </div>
    </div>
    <script src="scripts.js"></script>
</section>
</body>
<?php require 'inc/footer.php'?>