<?php

namespace Tcc\Controllers;

use \Tcc\App\Bases\BaseController;
use Tcc\Models\Usuario;

class UserController extends BaseController {
    public static function login() {
        if ($_SESSION["is_logged"] ?? false) {
            header("Location: /"); 
            exit();
        }

        return view("pages.login");
    }

    public static function post_login() {
        if ($_SESSION["is_logged"] ?? false) {
            header("Location: /"); 
            exit();
        }

        $data = [
            "email"     => [
                "value" => $_POST['email'] ?? "",
            ],
            "password"  => [
                "value" => $_POST['password'] ?? "",
            ],
        ];

        $is_valid = true;

        foreach ($data as $field => $field_data) {
            $data[$field]['valid'] = true;
            $data[$field]['valid_message'] = "";

            if (($field_data['value'] ?? "") == "") {
                $is_valid = false;
                $data[$field]['valid'] = false;
                $data[$field]['valid_message'] = "Campo obrigatório.";
                continue;
            }

            if ($field === "email") {
                if (!filter_var(($field_data['value'] ?? ""), FILTER_VALIDATE_EMAIL)) {
                    $is_valid = false;
                    $data[$field]['valid'] = false;
                    $data[$field]['valid_message'] = "Email inválido.";
                    continue;
                }
            }

            if ($field === "password") {
                if (strlen($field_data['value'] ?? "") < 8) {
                    $is_valid = false;
                    $data[$field]['valid'] = false;
                    $data[$field]['valid_message'] = "Senha deve ter pelo menos 8 caracteres.";
                    continue;
                }
            }
        }
        
        $busca_usuario = Usuario::fetchAllWhere("UPPER(email) = UPPER(?)", [$data['email']['value'] ?? '']);
        if (sizeof($busca_usuario) <= 0) {
            $is_valid = false;
            $data['email']['valid'] = false;
            $data['email']['valid_message'] = "Usuário com este email não foi encontrado.";
        } else {
            $usuario = $busca_usuario[0];
            if (!password_verify($data['password']['value'] ?? '', $usuario->password)) {
                $is_valid = false;
                $data['password']['valid'] = false;
                $data['password']['valid_message'] = "A senha utilizada esta incorreta.";
            }
        }

        if ($is_valid) {
            $_SESSION["is_logged"] = true;
            $_SESSION["logged_user"] = $usuario;

            header("Location: /"); 
            exit();
        } else {
            return view("pages.login", ["data" => $data]);
        }
    }

    public static function register() {
        if ($_SESSION["is_logged"] ?? false) {
            header("Location: /"); 
            exit();
        }

        return view("pages.register");
    }

    public static function post_register() {
        if ($_SESSION["is_logged"] ?? false) {
            header("Location: /"); 
            exit();
        }

        $data = [
            "email"     => [
                "value" => $_POST['email'] ?? "",
            ],
            "name"      => [
                "value" => $_POST['name'] ?? "",
            ],
            "campus_id" => [
                "value" => $_POST['campus_id'] ?? "",
            ],
            "password"  => [
                "value" => $_POST['password'] ?? "",
            ],
            "terms"     => [
                "value" => $_POST['terms'] ?? "",
            ],
        ];

        $is_valid = true;

        foreach ($data as $field => $field_data) {
            $data[$field]['valid'] = true;
            $data[$field]['valid_message'] = "";

            if (($field_data['value'] ?? "") == "") {
                $is_valid = false;
                $data[$field]['valid'] = false;
                $data[$field]['valid_message'] = "Campo obrigatório.";
                continue;
            }

            if ($field === "email") {
                if (!filter_var(($field_data['value'] ?? ""), FILTER_VALIDATE_EMAIL)) {
                    $is_valid = false;
                    $data[$field]['valid'] = false;
                    $data[$field]['valid_message'] = "Email inválido.";
                    continue;
                }
            }

            if ($field === "password") {
                if (strlen($field_data['value'] ?? "") < 8) {
                    $is_valid = false;
                    $data[$field]['valid'] = false;
                    $data[$field]['valid_message'] = "Senha deve ter pelo menos 8 caracteres.";
                    continue;
                }
            }
        }

        $busca_usuario = Usuario::fetchAllWhere("UPPER(email) = UPPER(?)", [$data['email']['value'] ?? '']);
        if (sizeof($busca_usuario) > 0) {
            $is_valid = false;
            $data['email']['valid'] = false;
            $data['email']['valid_message'] = "Email já está em uso.";
        }

        if ($is_valid) {
            $usuario = new Usuario([
                "email"     => $data["email"]['value'],
                "name"      => $data["name"]['value'],
                "campus_id" => $data["campus_id"]['value'],
                "password"  => password_hash($data["password"]['value'], PASSWORD_BCRYPT),
                "is_admin"  => 0,
            ]);
            $usuario->save();

            header("Location: /login"); 
            exit();
        } else {
            return view("pages.register", ["data" => $data]);
        }
    }

    public static function logout() {
        if (!($_SESSION["is_logged"] ?? false)) {
            header("Location: /"); 
            exit();
        }

        unset($_SESSION["is_logged"]);
        unset($_SESSION["logged_user"]);

        header("Location: /"); 
        exit();
    }

    public static function my_files() {
        if (!($_SESSION["is_logged"] ?? false)) {
            header("Location: /"); 
            exit();
        }

        return view("pages.my_files");
    }
}

?>
