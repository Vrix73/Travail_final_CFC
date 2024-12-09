<?php
/**
 * TITRE : VoyageMémo
 * Auteur : Guilain Fernandez
 * Description : la class Stage
 * Date : 25/04/2024
 */
class Stage
{
    public function __construct($InId = -1, $InTitle = "", $InDate = "", $InDurationDays = "", $InDescription = "", $InTravelId ="")
    {
        $this->id = $InId;
        $this->title = $InTitle;
        $this->date = $InDate;
        $this->durationDays = $InDurationDays;
        $this->description = $InDescription;
        $this->travelId = $InTravelId;
    }

    public $id;

    public $title;

    public $date;

    public $durationDays;

    public $description;

    public $travelId;
}

/**
 * Crée un nouveau stage dans la table 'stages'.
 * 
 * @param string $title Le titre du stage.
 * @param string $date La date du stage (au format YYYY-MM-DD).
 * @param int $durationDays La durée du stage en jours.
 * @param string $description La description du stage.
 * @param int $travelsId L'identifiant du voyage auquel le stage est associé.
 * @return bool Retourne vrai si la création du stage est réussie, sinon retourne faux.
 */
function createStage($title, $date, $durationDays, $description, $travelsId)
{
     // Requête SQL pour insérer un nouveau stage dans la table 'stages'
    $sql = "INSERT INTO stages (`title`, `date`, `duration_days`, `description`, `travels_id`) VALUES(:t, :da, :dd, :de, :ti);";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $tab = array(':t' => $title, ':da' => $date, ':dd' => $durationDays, ':de' => $description, ':ti' => $travelsId);
        foreach ($tab as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $statement->execute();
          // Retourne vrai si la création du stage est réussie
        return true;
    } catch (PDOException $error) {
        // En cas d'erreur, retourne faux
        return false;
    }
}

/**
 * Récupère les stages associés à un voyage spécifique.
 * 
 * @param int $travel L'identifiant du voyage.
 * @return array|bool Retourne un tableau contenant des objets Stage représentant les stages associés au voyage, ou faux en cas d'erreur.
 */
function getStageByTravelId($travel)
{
    $travelStage = [];
    // Requête SQL pour sélectionner les stages associés au voyage spécifié
    $sql = "SELECT `id`, `title`, `date`, `duration_days`, `description`, `travels_id`
    FROM `stages`
    WHERE  `travels_id` = :l";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $tab = array(":l" => $travel);
        foreach ($tab as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $statement->execute();
    } catch (PDOException $e) {
        // En cas d'erreur, retourne faux
        return false;
    }
    while ($row = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        // Ajoute un nouvel objet Stage au tableau $travelStage en utilisant les données de la base de données
        $travelStage[] = new Stage($row['id'], $row['title'], $row['date'], $row['duration_days'], $row['description'], $row['travels_id']);
    }
    // Retourne le tableau contenant les objets Stage représentant les stages associés au voyage
    return $travelStage;
}

/**
 * Ajoute une activité à une étape dans la table 'stages_has_activity'.
 * 
 * @param int $idStage L'identifiant du stage auquel ajouter l'activité.
 * @param int $idActivity L'identifiant de l'activité à ajouter.
 * @return bool Retourne vrai si l'ajout est réussi, sinon retourne faux.
 */
function addActivity($idStage, $idActivity)
{
    // Requête SQL pour insérer une nouvelle entrée dans la table 'stages_has_activity'
    $sql = "INSERT INTO stages_has_activity (`stages_id`, `activity_id`) VALUES(:is ,:ia);";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $tab = array(':is' => $idStage, ':ia' => $idActivity);
        foreach ($tab as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $statement->execute();
        // Retourne vrai si l'ajout est réussi
        return true;
    } catch (PDOException $error) {
        // En cas d'erreur, retourne faux
        return false;
    }
}

/**
 * Supprime un stage de la table 'stages' en fonction de son identifiant.
 * 
 * @param int $id L'identifiant du stage à supprimer.
 * @return bool Retourne vrai si la suppression du stage est réussie, sinon retourne faux.
 */
function deleteStageById($id)
{
    // Requête SQL pour supprimer le stage avec l'identifiant donné
    $sql = "DELETE 
    FROM `stages`
    WHERE `id` = :i";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $tab = array(':i' => $id);
        foreach ($tab as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $statement->execute();
        // Retourne vrai si la suppression du stage est réussie
        return true;
    } catch (PDOException $e) {
        // En cas d'erreur, retourne faux
        return false;
    }
}

/**
 * Récupère les informations d'une étape à partir de son identifiant.
 * 
 * @param int $idStage L'identifiant du stage à récupérer.
 * @return array|bool Retourne un tableau contenant un objet Stage représentant le stage correspondant à l'identifiant donné, ou faux en cas d'erreur.
 */
function getStageById($idStage)
{
    $travelStage = [];
    // Requête SQL pour sélectionner les informations du stage avec l'identifiant donné
    $sql = "SELECT `id`, `title`, `date`, `duration_days`, `description`, `travels_id`
    FROM `stages`
    WHERE  `id` = :i";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $tab = array(":i" => $idStage);
        foreach ($tab as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $statement->execute();
    } catch (PDOException $e) {
        return false;
    }
    while ($row = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        // Ajoute un nouvel objet Stage au tableau $travelStage en utilisant les données de la base de données
        $travelStage[] = new Stage($row['id'], $row['title'], $row['date'], $row['duration_days'], $row['description'], $row['travels_id']);
    }
    // Retourne le tableau contenant l'objet Stage représentant le stage correspondant à l'identifiant donné
    return $travelStage;
}

/**
 * Met à jour les informations d'une étape dans la table 'stages'.
 * 
 * @param int $id L'identifiant du stage à mettre à jour.
 * @param string $title Le titre du stage.
 * @param string $date La date du stage (au format YYYY-MM-DD).
 * @param int $durationDays La durée du stage en jours.
 * @param string $description La description du stage.
 * @return bool Retourne vrai si la mise à jour est réussie, sinon retourne faux.
 */
function stageUpdate($id, $title, $date, $durationDays, $description)
{
    // Requête SQL pour mettre à jour les informations du stage
    $sql = "UPDATE stages 
    SET `title` = :ti, `date` = :da, `duration_days` = :du, `description` = :de 
    WHERE `id` = :i";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $tab = array(':ti' => $title, ':i' => $id, ':da' => $date, ':du' => $durationDays, ':de' => $description);
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