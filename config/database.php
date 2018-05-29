<?php

    $DB_DSN = "mysql:host=127.0.0.1;";

    $DB_USER = "root1";

    $DB_PASSWORD = "root00";

    $CREATE_DB = "CREATE DATABASE IF NOT EXISTS camagru";

    $DB_MEMBERS = "CREATE TABLE IF NOT EXISTS camagru.members (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            email VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL,
            lastname VARCHAR(255) NOT NULL,
            firstname VARCHAR(255) NOT NULL,
            birthdate VARCHAR (10) NOT NULL,
            login VARCHAR(255) NOT NULL,
            confirmation_token VARCHAR(60) NULL,
            confirmed_at DATETIME NULL,
            reset_token VARCHAR(60) NULL,
            reset_at DATETIME NULL,
            remember_token VARCHAR(255) NULL,
            path_to_avatar VARCHAR(255) DEFAULT 'images/miniatures_profil/avatar_default.png',
            pref_comments_email INT NOT NULL DEFAULT '1')";

    $DB_PHOTO = "CREATE TABLE IF NOT EXISTS camagru.photo (
            id_photo INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            id_member INT(6),
            login VARCHAR(255) NOT NULL,
            path_to_photo VARCHAR(255) NULL,
            like_count INT(11) NULL DEFAULT '0',
            dislike_count INT(11) NULL DEFAULT '0',
            creation_date DATETIME NOT NULL)";

    $DB_COMMENT = "CREATE TABLE IF NOT EXISTS camagru.comments (
            id_comment INT(11) NOT NULL AUTO_INCREMENT,
            photo_id INT NOT NULL,
            member_id INT NOT NULL,
            content LONGTEXT NOT NULL,
            parent_id INT NOT NULL DEFAULT '0',
            PRIMARY KEY (id_comment)) ENGINE = InnoDB";

    $DB_VOTE = "CREATE TABLE IF NOT EXISTS camagru.votes (
            id INT NOT NULL AUTO_INCREMENT,
            ref_id INT NULL,
            ref_photo VARCHAR(50) NULL,
            user_id INT NULL,
            vote INT NULL ,
            created_at DATETIME NULL ,
            PRIMARY KEY (id)) ENGINE = InnoDB";