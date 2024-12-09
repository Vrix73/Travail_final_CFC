<?php

/**
 * TITRE : VoyageMémo
 * Auteur : Guilain Fernandez
 * Description : la class des voyage
 * Date : 29/04/2024
 */
class Travel
{
    public function __construct($InId = -1, $InTitle = "", $InNumberDisplay, $InUserId)
    {
        $this->id = $InId;
        $this->title = $InTitle;
        $this->numberDisplay = $InNumberDisplay;
        $this->userId = $InUserId;
    }

    public $id;

    public $title;

    public $numberDisplay;

    public $userId;
}

/**
 * Crée un nouveau voyage dans la base de données avec le titre, le nombre d'affichage et l'ID de l'utilisateur spécifiés.
 * 
 * @param string $title Le titre du voyage
 * @param int $numberDisplay Le nombre d'affichages pour le voyage
 * @param int $userId L'ID de l'utilisateur associé au voyage
 * @return bool Retourne true si le voyage est créé avec succès, sinon false.
 */
function createTravel($title, $numberDisplay, $userId)
{
    // Requête SQL pour insérer un nouveau voyage dans la table 'travels'
    $sql = "INSERT INTO travels (`title`, `number_display`, `users_id`) VALUES(:t, :nd, :u);";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        // Tableau des paramètres à lier à la requête
        $tab = array(':t' => $title, ':nd' => $numberDisplay, ':u' => $userId);
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
 * Récupère tous les voyages depuis la base de données.
 * 
 * @return array|bool Retourne un tableau d'objets Travel contenant tous les voyages si trouvés,
 *                     sinon retourne false en cas d'erreur ou si aucun voyage n'est trouvé.
 */
function getAllTravel()
{
    $allTravel = [];
    // Requête SQL pour récupérer tous les voyages de la table 'travels'
    $sql = "SELECT `id`, `title`, `number_display`, `users_id`
    FROM `travels`";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $tab = array();
        foreach ($tab as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $statement->execute();
    } catch (PDOException $e) {
        // En cas d'erreur, retourne false
        return false;
    }
    while ($row = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        // Crée un objet Travel avec les données récupérées de la base de données
        $allTravel[] = new Travel($row['id'], $row['title'], $row['number_display'], $row['users_id']);
    }
    // Retourne le tableau de tous les voyages (peut être vide si aucun voyage n'est trouvé)
    return $allTravel;
}

/**
 * Récupère les voyages associés à un utilisateur à partir de son identifiant dans la table 'travels'.
 * 
 * @param int $userId L'identifiant de l'utilisateur.
 * @return array|bool Retourne un tableau contenant des objets Travel représentant les voyages associés à l'utilisateur, ou faux en cas d'erreur.
 */
function getTravelByUserId($userId)
{
    $allTravel = [];
    // Requête SQL pour récupérer les voyages associés à l'utilisateur avec l'identifiant donné
    $sql = "SELECT `id`, `title`, `number_display`
    FROM `travels`
    WHERE `users_id` = :ui";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $tab = array(':ui' => $userId);
        foreach ($tab as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $statement->execute();
    } catch (PDOException $e) {
        // En cas d'erreur, retourne faux
        return false;
    }
    while ($row = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        // Ajoute un nouvel objet Travel au tableau $allTravel en utilisant les données de la base de données
        $allTravel[] = new Travel($row['id'], $row['title'], $row['number_display'], $row['users_id']);
    }
    // Retourne le tableau contenant des objets Travel représentant les voyages associés à l'utilisateur, ou faux en cas d'erreur
    return $allTravel;
}

/**
 * Récupère un voyage à partir de son identifiant dans la table 'travels'.
 * 
 * @param int $id L'identifiant du voyage à récupérer.
 * @return array|bool Retourne un tableau contenant un objet Travel représentant le voyage trouvé, ou faux si aucun voyage n'est trouvé ou en cas d'erreur.
 */
function getTravelById($id)
{
    $allTravel = [];
    // Requête SQL pour récupérer le voyage avec l'identifiant donné
    $sql = "SELECT `id`, `title`, `number_display`, `users_id`
    FROM `travels`
    WHERE id = :i";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $tab = array(':i' => $id);
        foreach ($tab as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $statement->execute();
    } catch (PDOException $e) {
        // En cas d'erreur, retourne false
        return false;
    }
    while ($row = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        // Ajoute un nouvel objet Travel au tableau $allTravel en utilisant les données de la base de données
        $allTravel[] = new Travel($row['id'], $row['title'], $row['number_display'], $row['users_id']);
    }
    // Retourne le tableau contenant un objet Travel représentant le voyage trouvé, ou faux si aucun voyage n'est trouvé ou en cas d'erreur
    return $allTravel;
}

/**
 * Supprime un voyage de la table 'travels' en fonction de son identifiant.
 * 
 * @param int $id L'identifiant du voyage à supprimer.
 * @return bool Retourne vrai si la suppression du voyage est réussie, sinon retourne faux.
 */
function deleteTravelById($id)
{
    // Requête SQL pour supprimer le voyage avec l'identifiant donné
    $sql = "DELETE 
    FROM `travels`
    WHERE `id` = :i";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $tab = array(':i' => $id);
        foreach ($tab as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $statement->execute();
        // Retourne vrai si la suppression du voyage est réussie
        return true;
    } catch (PDOException $e) {
        // En cas d'erreur, retourne faux
        return false;
    }
}

/**
 * Met à jour le titre d'un voyage dans la table 'travels'.
 * 
 * @param int $id L'identifiant du voyage à mettre à jour.
 * @param string $title Le nouveau titre du voyage.
 * @return bool Retourne vrai si la mise à jour du voyage est réussie, sinon retourne faux.
 */
function travelUpdate($id, $title)
{
    // Requête SQL pour mettre à jour le titre du voyage dans la table 'travels'
    $sql = "UPDATE 
    travels 
    SET `title` = :t
    WHERE `id` = :i";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        // Tableau des paramètres à lier à la requête
        $tab = array(':t' => $title, ':i' => $id);
        foreach ($tab as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $statement->execute();
        // Retourne vrai si la mise à jour du voyage est réussie
        return true;
    } catch (PDOException $error) {
        // En cas d'erreur, retourne faux
        return false;
    }
}

/**
 * Met à jour le nombre de visite d'un voyage dans la table 'travels'.
 * 
 * @param int $id L'identifiant du voyage à mettre à jour.
 * @param string $title Le nouveau titre du voyage.
 * @return bool Retourne vrai si la mise à jour du voyage est réussie, sinon retourne faux.
 */
function travelNumberDisplayUpdate($id, $numberDisplay)
{
    // Requête SQL pour mettre à jour le nombre de visite du voyage dans la table 'travels'
    $sql = "UPDATE 
    travels 
    SET `number_display` = :nd
    WHERE `id` = :i";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        // Tableau des paramètres à lier à la requête
        $tab = array(':nd' => $numberDisplay, ':i' => $id);
        foreach ($tab as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $statement->execute();
        // Retourne vrai si la mise à jour du voyage est réussie
        return true;
    } catch (PDOException $error) {
        // En cas d'erreur, retourne faux
        return false;
    }
}
