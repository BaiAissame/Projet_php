<?php

class UserValidator
{
    public Object $user;
    private array $errors = [];

    public function __construct(Object $user, string $pwdConfirm)
    {
        $this->user = $user;

        // Vérifier le prénom
        if (strlen($user->firstname) < 2) {
            $this->errors[] = "Le prénom doit faire plus de 2 caractères";
        }

        // Vérifier le nom
        if (strlen($user->lastname) < 2) {
            $this->errors[] = "Le nom doit faire plus de 2 caractères";
        }

        // Vérifier l'email
        if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "L'email est invalide";
        }

        // 🔥 Vérifier le mot de passe AVANT de le hasher
        if (
            strlen($pwdConfirm) < 8 ||
            !preg_match("/[a-z]/", $pwdConfirm) ||
            !preg_match("/[0-9]/", $pwdConfirm) ||
            !preg_match("/[A-Z]/", $pwdConfirm)
        ) {
            $this->errors[] = "Le mot de passe doit faire au min 8 caractères, avec au moins une minuscule, une majuscule et un chiffre";
        }

        // 🔥 Vérifier si les mots de passe correspondent avant le hachage
        if ($pwdConfirm !== $user->password) {
            $this->errors[] = "Le mot de passe de confirmation ne correspond pas";
        }
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     */
    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }
}
