<?php

class Img
{
    static function creerMin($img, $random, $chemin, $nom, $mlargeur = 100, $mhauteur = 100)
    {
        // On supprime l'extension du nom
        $nom = substr($nom, 0, -4);
        // On récupère les dimensions de l'image
        $dimension = getimagesize($img);
        // On crée une image à partir du fichier récup
        if (substr(strtolower($img), -4) == ".jpg") {
            $image = imagecreatefromjpeg($img);
        } else if (substr(strtolower($img), -4) == ".png") {
            $image = imagecreatefrompng($img);
        } else if (substr(strtolower($img), -4) == ".gif") {
            $image = imagecreatefromgif($img);
        } // L'image ne peut etre redimensionne
        else
            return false;
        // Création des miniatures
        // On cré une image vide de la largeur et hauteur voulue
        $miniature = imagecreatetruecolor($mlargeur, $mhauteur);
        imagealphablending($miniature, false);
        imagesavealpha($miniature, true);
        // On va gérer la position et le redimensionnement de la grande image
        if ($dimension[0] > ($mlargeur / $mhauteur) * $dimension[1]) {
            $dimY = $mhauteur;
            $dimX = $mhauteur * $dimension[0] / $dimension[1];
            $decalX = -($dimX - $mlargeur) / 2;
            $decalY = 0;
        }
        if ($dimension[0] < ($mlargeur / $mhauteur) * $dimension[1]) {
            $dimX = $mlargeur;
            $dimY = $mlargeur * $dimension[1] / $dimension[0];
            $decalY = -($dimY - $mhauteur) / 2;
            $decalX = 0;
        }
        if ($dimension[0] == ($mlargeur / $mhauteur) * $dimension[1]) {
            $dimX = $mlargeur;
            $dimY = $mhauteur;
            $decalX = 0;
            $decalY = 0;
        }
        // on modifie l'image crée en y plaçant la grande image redimensionné et décalée
        imagecopyresampled($miniature, $image, $decalX, $decalY, 0, 0, $dimX, $dimY, $dimension[0], $dimension[1]);
        // On sauvegarde le tout
        if (strtolower(substr($img, -3)) == 'jpg') {
            imagejpeg($miniature, $chemin . "/" . $_SESSION['auth']->id . "_" .$random. $nom . ".jpg", 90);
        } elseif (strtolower(substr($img, -3)) == 'png') {
            $color = imagecolorallocatealpha($miniature, 0, 0, 0, 127);
            imagefill($miniature, 0, 0, $color);
            imagepng($miniature, $chemin . "/" . $_SESSION['auth']->id . "_" .$random. $nom . ".png", 2);
        }
        return true;
    }
    static function superPhoto($img, $chemin, $nom, $name)
    {
        $nom = substr($nom, 0, -4);
        if (substr(strtolower($img), -4) == ".jpg") {
            $image = imagecreatefromjpeg($img);
        } else if (substr(strtolower($img), -4) == ".png") {
            $image = imagecreatefrompng($img);
        } else if (substr(strtolower($img), -4) == ".gif") {
            $image = imagecreatefromgif($img);
        }
        else
            return false;
        $stamp = imagecreatefrompng($name);
        $destination_x = 10;
        $destination_y =  235;
        imagecopy($image, $stamp, $destination_x, $destination_y, 0, 0, imagesx($stamp), imagesy($stamp));
        // On sauvegarde le tout
        if (strtolower(substr($img, -3)) == 'jpg') {
            imagejpeg($image, $chemin . "/". $nom . "_" . substr($name, 27, -4) . ".jpg", 90);
        } elseif (strtolower(substr($img, -3)) == 'png') {
            imagealphablending($image, false);
            imagesavealpha($image, true);
            $color = imagecolorallocatealpha($image, 0, 0, 0, 127);
            imagefill($image, 0, 0, $color);
            imagepng($image, $chemin . "/". $nom . "_" . substr($name, 27, -4) . ".png", 2);
        }
        return true;
    }

    static function profil($img, $chemin, $nom, $mlargeur = 100, $mhauteur = 100)
    {
        // On supprime l'extension du nom
        $nom = substr($nom, 0, -4);
        // On récupère les dimensions de l'image
        $dimension = getimagesize($img);
        // On crée une image à partir du fichier récup
        if (substr(strtolower($img), -4) == ".jpg") {
            $image = imagecreatefromjpeg($img);
        } else if (substr(strtolower($img), -4) == ".png") {
            $image = imagecreatefrompng($img);
        } else if (substr(strtolower($img), -4) == ".gif") {
            $image = imagecreatefromgif($img);
        } // L'image ne peut etre redimensionne
        else
            return false;
        // Création des miniatures
        // On cré une image vide de la largeur et hauteur voulue
        $miniature = imagecreatetruecolor($mlargeur, $mhauteur);
        imagealphablending($miniature, false);
        imagesavealpha($miniature, true);
        // On va gérer la position et le redimensionnement de la grande image
        if ($dimension[0] > ($mlargeur / $mhauteur) * $dimension[1]) {
            $dimY = $mhauteur;
            $dimX = $mhauteur * $dimension[0] / $dimension[1];
            $decalX = -($dimX - $mlargeur) / 2;
            $decalY = 0;
        }
        if ($dimension[0] < ($mlargeur / $mhauteur) * $dimension[1]) {
            $dimX = $mlargeur;
            $dimY = $mlargeur * $dimension[1] / $dimension[0];
            $decalY = -($dimY - $mhauteur) / 2;
            $decalX = 0;
        }
        if ($dimension[0] == ($mlargeur / $mhauteur) * $dimension[1]) {
            $dimX = $mlargeur;
            $dimY = $mhauteur;
            $decalX = 0;
            $decalY = 0;
        }
        // on modifie l'image crée en y plaçant la grande image redimensionné et décalée
        imagecopyresampled($miniature, $image, $decalX, $decalY, 0, 0, $dimX, $dimY, $dimension[0], $dimension[1]);
        // On sauvegarde le tout
        if (strtolower(substr($img, -3)) == 'jpg') {
            imagejpeg($miniature, $chemin . "/" . $nom . ".jpg", 90);
        } elseif (strtolower(substr($img, -3)) == 'png') {
            $color = imagecolorallocatealpha($miniature, 0, 0, 0, 127);
            imagefill($miniature, 0, 0, $color);
            imagepng($miniature, $chemin . "/" . $nom . ".png", 2);
        }
        return true;
    }
}
?>