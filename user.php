<?php

class User
{
    public $name;
    public $email;
    private $password;

    public function __construct($name, $email, $password) {
        if (!self::validate_email($email)) {
            throw new Exception("Invalid email format");
        }

        if (!self::check_password($password)) {
            throw new Exception("Invalid password. Password must be at least 12 characters long and include at least one uppercase letter, one lowercase letter, and one special character.");
        }

        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    public static function validate_email($email){
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function check_password($password){
        $length = strlen($password) >= 12;
        $uppercase = preg_match('/[A-Z]/', $password);
        $lowercase = preg_match('/[a-z]/', $password);
        $special = preg_match('/[\W]/', $password);

        return $length && $uppercase && $lowercase && $special;
    }

    public function copy_with($name = null, $email = null, $password = null){
        $newName = $name ?? $this->name;
        $newEmail = $email ?? $this->email;
        $newPassword = $password ?? $this->password;

        return new User($newName, $newEmail, $newPassword);
    }
}


header("Content-Type: application/json");

try {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['action'])) {
        throw new Exception("No action specified.");
    }

    switch ($input['action']) {
        case 'create':
            if (!isset($input['name'], $input['email'], $input['password'])) {
                throw new Exception("Missing required parameters");
            }

            $user = new User($input['name'], $input['email'], $input['password']);
            echo json_encode([
                "success" => true,
                "message" => "User created successfully.",
                "user" => [
                    "name" => $user->name,
                    "email" => $user->email,
                ]
            ]);
            break;

        case 'copy':
            if (!isset($input['original_user'])) {
                throw new Exception("Missing original user data.");
            }

            $original = $input['original_user'];
            $originalUser = new User($original['name'], $original['email'], $original['password']);

            $updatedUser = $originalUser->copy_with(
                $input['name'] ?? null,
                $input['email'] ?? null,
                $input['password'] ?? null
            );

            echo json_encode([
                "success" => true,
                "message" => "User copied successfully.",
                "user" => [
                    "name" => $updatedUser->name,
                    "email" => $updatedUser->email,
                ]
            ]);
            break;

        default:
            throw new Exception("Invalid action.");
    }
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage(),
    ]);
}
