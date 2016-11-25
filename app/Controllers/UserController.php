<?php
namespace App\Controllers;

use App\App;
use App\Mail;
use App\Post;
use App\File;
use App\Controller;
use App\Models\UserModel;
use App\Sanitize;
use App\Session;
use App\View;

use ReCaptcha\ReCaptcha;

class UserController extends Controller
{
    protected $page_data = [];

    public function actionIndex()
    {
        if (Session::exists('id')) {
            if (Post::exists('form_action')) {
                switch (Post::get('form_action')) {
                    case 'logout':
                        $this->formActionLogout();
                        break;
                    case 'update':
                        $this->formActionUpdate();
                        break;
                }
            }
            $userModel = new UserModel('localhost', 'project');
            $user = $userModel->id(Session::get('id'));
            if (file_exists(DIR_UPLOAD . $user['avatar']) && is_file(DIR_UPLOAD . $user['avatar'])) {
                $user['avatar'] = DS . str_replace(BASE, '', DIR_UPLOAD) . $user['avatar'];
            } else {
                $user['avatar'] = '/assets/images/no_image_available.svg';
            }
            View::show('user/index', ['user' => $user]);
        } else {
            header('Location: /');
        }
    }

    public function actionLogin()
    {
        if (Post::exists('form_action') && Post::get('form_action') === 'login') {
            $this->formActionLogin();
        }
        View::show('user/login');
    }

    public function actionRegister()
    {
        if (Post::exists('form_action') && Post::get('form_action') === 'register') {
            $this->formActionRegister();
        }
        View::show('user/register');
    }

    public function actionFiles()
    {
        $userModel = new UserModel('localhost', 'project');
        $userFiles = $userModel->userFiles(Session::get('id'));
        View::show('user/files', ['userFiles' => $userFiles]);
    }

    public function actionAllusers()
    {
        $userModel = new UserModel('localhost', 'project');
        $users = $userModel->allUsers();
        for ($i=0; $i<count($users); $i++) {
            if (!isset($users[$i]['age'])) {
                $users[$i]['adult'] = "Возраст не указан";
            } elseif ($users[$i]['age'] > 18) {
                $users[$i]['adult'] = "Совершеннолетний";
            } else {
                $users[$i]['adult'] = "Несовершеннолетний";
            }
            if (file_exists(DIR_UPLOAD . $users[$i]['avatar']) && is_file(DIR_UPLOAD . $users[$i]['avatar'])) {
                $users[$i]['avatar'] = DS . str_replace(BASE, '', DIR_UPLOAD) . $users[$i]['avatar'];
            } else {
                $users[$i]['avatar'] = '/assets/images/no_image_available.svg';
            }
        }

        View::show('user/allusers', ['users' => $users]);
    }

    public function formActionLogin()
    {
        $login = Post::exists('login') ? Sanitize::input(Post::get('login')) : false;
        $password = Post::exists('password') ? Sanitize::input(Post::get('password')) : false;
        if ($login && $password) {
            $userModel = new UserModel('localhost', 'project');
            $user = $userModel->login($login);
            if ($user['login'] === $login && $user['password'] === $password) {
                Session::set('id', $user['id']);
                header('Location: /user/index');
            } else {
                App::set('error', 'Логин или пароль введены неверно');
            }
        } else {
            App::set('error', 'Не введены логин или пароль');
        }
    }

    public function formActionRegister()
    {
        $reglogin = Post::exists('reglogin') ? Sanitize::input(Post::get('reglogin')) : false;
        $regpassword = Post::exists('regpassword') ? Sanitize::input(Post::get('regpassword')) : false;
        $recaptchaForm = Post::exists('g-recaptcha-response') ? Post::get('g-recaptcha-response') : false;
        $recaptcha = new ReCaptcha('6LdS-QwUAAAAADFDgT7xbYrO9GgUpye08dU9RPpn');
        $recaptcha_response = $recaptcha->verify($recaptchaForm, $_SERVER['REMOTE_ADDR']);
        if ($recaptcha_response->isSuccess()) {
            if ($reglogin && $regpassword) {
                $userModel = new UserModel('localhost', 'project');
                $user = $userModel->login($reglogin);
                if ($user['login'] != $reglogin) {
                    $userModel->register($reglogin, $regpassword);
                    Mail::send();
                    header('Location: /user/index');
                } else {
                    App::set('error', 'Такой логин уже есть в базе');
                }
            } else {
                App::set('error', 'Не введен логин или пароль');
            }
        } else {
            App::set('error', 'Ошибка капчи');
        }
    }

    public function formActionUpdate()
    {
        $img_ext = ['jpg', 'jpeg', 'png', 'gif'];
        $img_mime = ['image/jpeg', 'image/pjpeg', 'image/png', 'image/gif'];
        $id = Session::get('id');
        $input_name = $_POST['name'] ? Sanitize::input(Post::get('name')) : null;
        $input_age = $_POST['age'] ? Sanitize::input(Post::get('age')) : null;
        $input_about = $_POST['about'] ? Sanitize::input(Post::get('about')) : null;
        $input_file = $_FILES['picture']['size'] ? $_FILES['picture'] : null;
        $userModel = new UserModel('localhost', 'project');
        if ($input_file) {
            $ext = File::ext($input_file['name']);
            $filename = $input_file['name'];
            $filetype = $input_file['type'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if ($input_file['error'] !== UPLOAD_ERR_OK) {
                $error = "Ошибка при загрузке файла";
                return $error;
            } elseif (!in_array($ext, $img_ext) || (!in_array($filetype, $img_mime))) {
                $error = "Допустима загрузка только файлов изображений";
                return $error;
            } else {
                try {
                    $input_file_tmp = empty($input_file['tmp_name']) ? null : $input_file['tmp_name'];
                    $input_file_ext = strtolower(pathinfo($input_file['name'], PATHINFO_EXTENSION));
                    $output_file_name = $id . '-' . uniqid() . '-' . time() . '.' . $input_file_ext;
                    if (is_dir(DIR_UPLOAD) && is_writable(DIR_UPLOAD)) {
                        $output_file = DIR_UPLOAD . $output_file_name;
                        move_uploaded_file($input_file_tmp, $output_file);
                        $userModel->addPicture($id, $output_file_name);
                    } else {
                        App::set('error', 'Директория для загрузки файлов не доступна на запись или не существует');
                    }
                } catch (\Exception $e) {
                    App::set('error', 'Возникла непредвиденная ошибка');
                }
            }
        }
        $userModel->update($id, $input_name, $input_age, $input_about);
    }

    public function formActionLogout()
    {
        Session::destroy();
        header('Location: /');
    }
}
