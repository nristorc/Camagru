<?php

    class Auth
    {

        private $options = [
            'restriction_msg' => "Vous n'avez pas le droit d'accéder à cette page"
        ];

        private $session;

        public function __construct($session, $options = [])
        {
            $this->options = array_merge($this->options, $options);
            $this->session = $session;
        }

        public function hashPassword($password){
            return password_hash($password, PASSWORD_BCRYPT);
        }



        public function register($db, $firstname, $lastname, $birthdate, $login, $password, $email)
        {

            $password = $this->hashPassword($password);

            $token = Str::random(60);

            $db->query("INSERT INTO camagru.members SET firstname = ?, lastname = ?, birthdate = ?, login = ?, password = ?, email = ?, confirmation_token = ?",
                [$firstname, $lastname, $birthdate, $login, $password, $email, $token]);
            $user_id = $db->lastInsertId();
            mail($email, 'Confirmation de votre compte', "Afin de valider votre compte merci de cliquer sur ce lien\n\nhttp://127.0.0.1:8080/Camagru/confirm.php?id=$user_id&token=$token");
        }

        public function confirm($db, $user_id, $token)
        {

            $user = $db->query('SELECT * FROM camagru.members WHERE id = ?', [$user_id])->fetch();

            if ($user && $user->confirmation_token == $token) {

                $db->query('UPDATE camagru.members SET confirmation_token = NULL, confirmed_at = NOW() WHERE id = ?', [$user_id]);

                $this->session->write('auth', $user);

                return true;
            }
            return false;
        }

        public function restrict()
        {
            if (!$this->session->read('auth')) {
                $this->session->setFlash('danger', $this->options['restriction_msg']);
                header('Location: account.php');
                exit();
            }
        }

        public function user()
        {
            if (!$this->session->read('auth')) {
                return false;
            }
            return $this->session->read('auth');
        }

        public function connect($user)
        {
            $this->session->write('auth', $user);
        }

        public function connectFromCookie($db)
        {

            if (isset($_COOKIE['remember']) && !$this->user()) {

                $remember_token = $_COOKIE['remember'];
                $parts = explode('==', $remember_token);
                $user_id = $parts[0];

                $user = $db->query('SELECT * FROM camagru.members WHERE id = ?', [$user_id])->fetch();


                if ($user) {
                    $expected = $user_id . '==' . $user->remember_token . sha1($user_id . 'ratonlaveurs');

                    if ($expected == $remember_token) {
                        $this->connect($user);
                        setcookie('remember', $remember_token, time() + 60 * 60 * 24 * 7);
                    } else {
                        setcookie('remember', NULL, -1);
                    }
                } else {
                    setcookie('remember', NULL, -1);
                }
            }
        }

        public function login($db, $login, $password, $remember = false)
        {
            $user = $db->query('SELECT * FROM camagru.members WHERE (login = :login OR email = :login) AND confirmed_at IS NOT NULL', ['login' => $login])->fetch();

            if (password_verify($password, $user->password)) {
                $this->connect($user);

                if ($remember) {
                    $this->remember($db, $user->id);
                }

                return $user;
            } else
                return false;
        }

        public function remember($db, $user_id)
        {
            $remember_token = Str::random(255);
            $db->query('UPDATE camagru.members SET remember_token = ? WHERE id = ?', [$remember_token, $user_id]);

            setcookie('remember', $user_id . '==' . $remember_token . sha1($user_id . 'ratonlaveurs'), time() + 60 * 60 * 24 * 7);
        }

        public function logout()
        {
            setcookie('remember', NULL, -1);

            $files = glob('images/tmp/' . $_SESSION['auth']->id . "_*");
            foreach ($files as $file)
                unlink($file);

            $this->session->delete('auth');
            $this->session->delete('fileToUpload');
            $this->session->delete('photo_superp');
            $this->session->delete('webcam_tempo_name');
            $this->session->delete('webcam_path_tmp');
        }

        public function resetPassword($db, $email)
        {

            $user = $db->query('SELECT * FROM camagru.members WHERE email = ? AND confirmed_at IS NOT NULL', [$email])->fetch();

            if ($user) {
                $reset_token = Str::random(60);
                $db->query('UPDATE camagru.members SET reset_token = ?, reset_at = NOW() WHERE id = ?', [$reset_token, $user->id]);
                mail($_POST['email'], 'Réinitialisation de votre mot de passe', "Afin de réinitialiser merci de cliquer sur ce lien\n\nhttp://127.0.0.1:8080/Camagru/reset_password.php?id={$user->id}&token=$reset_token");
                return $user;
            }
            return false;
        }

        public function getUserFromResetToken($db, $user_id, $token){

            return $db->query('SELECT * FROM camagru.members WHERE id = ? AND reset_token IS NOT NULL AND reset_token = ? AND reset_at > DATE_SUB(NOW(), INTERVAL 30 MINUTE)',
                [$user_id, $token])->fetch();

        }
    }
?>