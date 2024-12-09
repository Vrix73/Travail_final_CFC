<?php

/**
 * TITRE : VoyageMémo
 * Auteur : Guilain Fernandez
 * Description : la class user
 * Date : 25/04/2024
 */
class User
{
    public function __construct($InId = -1, $InLogin = "", $InPassword = "")
    {
        $this->id = $InId;
        $this->login = $InLogin;
        $this->password = $InPassword;
    }

    public $id;

    public $login;

    public $password;
}

/**
 * Crée un nouvel utilisateur dans la base de données avec le login et le mot de passe spécifiés.
 * 
 * @param string $login Le nom d'utilisateur du nouvel utilisateur
 * @param string $password Le mot de passe du nouvel utilisateur
 * @return bool Retourne true si l'utilisateur est créé avec succès, sinon false.
 */
function createUser($login, $password)
{
    // Requête SQL pour insérer un nouvel utilisateur avec le login et le mot de passe spécifiés
    $sql = "INSERT INTO users (`login`, `password`) VALUES(:l, :p);";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $tab = array(':l' => $login, ':p' => password_hash($password, PASSWORD_DEFAULT));
        foreach ($tab as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $statement->execute();
        // Retourne true si l'insertion est réussie
        return true;
    } catch (PDOException $error) {
        // En cas d'erreur, retourne false
        return false;
    }
}

/**
 * Vérifie si un utilisateur existe dans la table 'users' en fonction de son nom d'utilisateur.
 * 
 * @param string $login Le nom d'utilisateur à vérifier.
 * @return int|bool Retourne le nombre d'utilisateurs trouvés avec le nom d'utilisateur donné, ou faux en cas d'erreur.
 */
function verifyUserExiste($login)
{
    // Requête SQL pour compter le nombre d'utilisateurs avec le nom d'utilisateur donné
    $sql = "SELECT COUNT(*)
    FROM users
    WHERE  `login` = :l";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $tab = array(":l" => $login);
        foreach ($tab as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $statement->execute();
        $data = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);
    } catch (PDOException $e) {
        // En cas d'erreur, retourne faux
        return false;
    }
    // Retourne le nombre d'utilisateurs trouvés avec le nom d'utilisateur donné
    return $data['COUNT(*)'];
}

/**
 * Récupère un utilisateur à partir de son nom d'utilisateur (login) dans la table 'users'.
 * 
 * @param string $login Le nom d'utilisateur à rechercher.
 * @return array|bool Retourne un tableau contenant un objet User représentant l'utilisateur trouvé, ou faux en cas d'erreur ou si aucun utilisateur n'est trouvé.
 */
function getUser($login)
{
    $userVerify = [];
    // Requête SQL pour récupérer l'utilisateur avec le nom d'utilisateur donné
    $sql = "SELECT `id`, `login`, `password`
    FROM `users`
    WHERE  `login` = :l";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $tab = array(":l" => $login);
        foreach ($tab as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $statement->execute();
    } catch (PDOException $e) {
        // En cas d'erreur, retourne faux
        return false;
    }
    while ($row = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        // Ajoute un nouvel objet User au tableau $userVerify en utilisant les données de la base de données
        $userVerify[] = new User($row['id'], $row['login'], $row['password']);
    }
    // Retourne le tableau contenant un objet User représentant l'utilisateur trouvé, ou faux en cas d'erreur ou si aucun utilisateur n'est trouvé
    return $userVerify;
}
