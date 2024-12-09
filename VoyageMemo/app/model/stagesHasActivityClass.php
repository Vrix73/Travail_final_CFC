<?php
/**
 * TITRE : VoyageMémo
 * Auteur : Guilain Fernandez
 * Description : la class StageHasActivity
 * Date : 06/05/2024
 */
class StageHasActivity
{
    public function __construct($InStageId = "", $InActivityId = "")
    {
        $this->stagesId = $InStageId;
        $this->activityId = $InActivityId;
    }

    public $stagesId;

    public $activityId;
}

/**
 * Supprime l'association entre un stage et une activité dans la table 'stages_has_activity'.
 * 
 * @param int $stageId L'identifiant du stage.
 * @param int $activityId L'identifiant de l'activité.
 * @return bool Retourne vrai si la suppression de l'association est réussie, sinon retourne faux.
 */
function deleteStageActivityAssociationByStageIdAndActivityId($stageId, $activityId)
{
    // Requête SQL pour supprimer l'association entre le stage et l'activité
    $sql = "DELETE 
    FROM `stages_has_activity`
    WHERE `stages_id` = :si
    AND `activity_id` = :ai";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $tab = array(':si' => $stageId, ':ai' => $activityId);
        foreach ($tab as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $statement->execute();
        // Retourne vrai si la suppression de l'association est réussie
        return true;
    } catch (PDOException $e) {
         // En cas d'erreur, retourne faux
        return false;
    }
}