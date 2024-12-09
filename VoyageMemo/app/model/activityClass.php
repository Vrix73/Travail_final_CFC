<?php
/**
 * TITRE : VoyageMémo
 * Auteur : Guilain Fernandez
 * Description : la class activitées
 * Date : 29/04/2024
 */
class Activity
{
    public function __construct($InId = -1, $InName = "")
    {
        $this->id = $InId;
        $this->name = $InName;
    }

    public $id;

    public $name;
}

/**
 * Récupère toutes les activités depuis la base de données.
 * 
 * @return array|bool Retourne un tableau d'objets Activity contenant toutes les activités si trouvées,
 *                     sinon retourne false en cas d'erreur ou si aucune activité n'est trouvée.
 */
function getAllActivity()
{
    $allActivity = [];
    // Requête SQL pour récupérer toutes les activités de la table 'activity'
    $sql = "SELECT `id`, `name`
    FROM `activity`";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $tab = array();
        foreach ($tab as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $statement->execute();
    } catch (PDOException $e) {
        return false;
    }
    while ($row = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        // On crée l'objet Activity en l'initialisant avec les données provenant de la base de données
        $allActivity[] = new Activity($row['id'], $row['name']);
    }
    // Retourne le tableau de toutes les activités (peut être vide si aucune activité n'est trouvée)
    return $allActivity;
}

/**
 * Récupère les activités associées à un stage spécifique.
 * 
 * @param int $stage L'identifiant du stage.
 * @return array|bool Retourne un tableau d'objets Activity représentant les activités associées au stage, ou faux en cas d'erreur.
 */
function getActivityByStageId($stage)
{
    $stageActivity = [];
    // Requête SQL pour sélectionner les activités associées au stage spécifié
    $sql = "SELECT `activity`.`id`, `activity`.`name`
    FROM `stages_has_activity`
    INNER JOIN `activity` ON `stages_has_activity`.`activity_id` = `activity`.`id`
    WHERE `stages_has_activity`.`stages_id` = :s";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $tab = array(":s" => $stage);
        foreach ($tab as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $statement->execute();
    } catch (PDOException $e) {
        return false;
    }
    while ($row = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
         // Ajoute un nouvel objet Activity au tableau $stageActivity en utilisant les données de la base de données
        $stageActivity[] = new Activity($row['id'], $row['name']);
    }
    // Retourne le tableau d'objets Activity représentant les activités associées au stage
    return $stageActivity;
}