<?php
require 'inc/bootstrap.php';
App::getAuth()->restrict();
require "inc/header.php";

?>
<section>
    <div id="add_image">
        <h3> Your upload </h3>
        <?php
        $db = App::getDatabase();
        $ret = $db->query("SELECT login, path_to_photo, id_photo, creation_date FROM camagru.photo  WHERE id_member = ? ORDER BY creation_date DESC ", [$_SESSION['auth']->id]);
        while ($display_gallery = $ret->fetch())
        {
            ?>
            <div>
                <img id=past_upload" src="<?= $display_gallery->path_to_photo;?>" /> <br/>
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
            <video id="video"></video><br/>
            <canvas id="canvas"></canvas>
            <button id="startbutton"> Take a photo </button><br/>
            <a href="webcam.php"><button onclick="uploadPic()"> Upload photo </button></a><br/>
            <form method="POST" name="form1">
                <input name="hidden_data" id='hidden_data' type="hidden"/>
            </form>
        </div>

        <div id="file_upload" class="tabcontent" style="display: block">
            <form method="post" action="webcamData.php" enctype="multipart/form-data" id="form">
                Select image to upload:
                <input type="file" name="file" id="file" onchange="form.submit();"/><br/>
                <img id="output" src="
                <?php
                    if (isset($_SESSION['fileToUpload']) && !empty($_SESSION['fileToUpload']))
                        echo $_SESSION['fileToUpload']['path_tmp'];
                ?> "/><br/>
           </form>
        </div id="filter">
        <h3> Filter </h3>
            <input type="image" id="nonebutton" src="ressources/nofilter.jpg">
            <input type="image" id="graybutton" src="ressources/nofilter.jpg" style="filter: grayscale(100%);">
            <input type="image" id="sepiabutton" src="ressources/nofilter.jpg" style="filter: sepia(0.8);">
            <input type="image" id="blurbutton" src="ressources/nofilter.jpg" style="filter: blur(3px);">
            <input type="image" id="brightbutton" src="ressources/nofilter.jpg" style="filter: brightness(3);">
            <input type="image" id="contrastbutton" src="ressources/nofilter.jpg" style="filter: contrast(4);">
            <input type="image" id="rev1button" src="ressources/nofilter.jpg" style="filter: hue-rotate(90deg);">
            <input type="image" id="rev2button" src="ressources/nofilter.jpg" style="filter: hue-rotate(180deg);">
            <input type="image" id="rev3button" src="ressources/nofilter.jpg" style="filter: hue-rotate(270deg);">
            <input type="image" id="saturbutton" src="ressources/nofilter.jpg" style="filter: saturate(10);">
            <input type="image" id="invertbutton" src="ressources/nofilter.jpg" style="filter: invert(1);">
        </div>

    <div id="edit_image">
        <div id="watermarks">
            <h3> Watermarks </h3>
            <button id="superp_photo_chat" data-href="images/superposition_image/chat.png">
                <img src="images/superposition_image/chat.png" name="href"></button><br/>
            <button id="superp_photo_chien" data-href="images/superposition_image/chien.png">
                <img src="images/superposition_image/chien.png" name="href"></button><br/>
            <button id="superp_photo_perroquet" data-href="images/superposition_image/perroquet.png">
                <img src="images/superposition_image/perroquet.png" name="href"></button><br/>
            <button id="superp_photo_licorne" data-href="images/superposition_image/unicorn.png">
                <img src="images/superposition_image/unicorn.png" name="href"></button><br/>
            <form method="post" id="final_submit" action="webcamData.php">
                <input type="submit" value="Valider" name="submit"/>
            </form>
        </div>
    </div>

</section>

<?php require 'inc/footer.php'?>