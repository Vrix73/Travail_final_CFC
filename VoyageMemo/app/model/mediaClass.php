<?php

/**
 * TITRE : VoyageMémo
 * Auteur : Guilain Fernandez
 * Description : la class media
 * Date : 25/04/2024
 */
class Media
{
    public function __construct($InId = -1, $InAddDate = "", $InFilenameVignette = "", $InFilenameHd = "")
    {
        $this->id = $InId;
        $this->addDate = $InAddDate;
        $this->filenameVignette = $InFilenameVignette;
        $this->filenameHd = $InFilenameHd;
    }

    public $id;

    public $addDate;

    public $filenameVignette;

    public $filenameHd;
}

/**
 * Crée un nouveau média dans la table 'media'.
 * 
 * @param string $date La date d'ajout du média (au format YYYY-MM-DD).
 * @param string $fileNameVignette Le nom du fichier vignette.
 * @param string $fileNameHd Le nom du fichier HD.
 * @param int $stageId L'identifiant du stage auquel le média est associé.
 * @return bool Retourne vrai si la création du média est réussie, sinon retourne faux.
 */
function createMedia($date, $fileNameVignette, $fileNameHd, $stageId)
{
     // Requête SQL pour insérer un nouveau média dans la table 'media'
    $sql = "INSERT INTO media (`add_date`, `filename_vignette`, `filename_hd`, `stages_id`) VALUES(:da, :fv, :fh, :si);";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $tab = array(':da' => $date, ':fv' => $fileNameVignette, ':fh' => $fileNameHd, ':si' => $stageId);
        foreach ($tab as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $statement->execute();
        // Retourne vrai si la création du média est réussie
        return true;
    } catch (PDOException $error) {
        // En cas d'erreur, retourne faux
        return false;
    }
}

/**
 * Récupère les médias associés à un stage spécifique.
 * 
 * @param int $stageId L'identifiant du stage.
 * @return array|bool Retourne un tableau contenant des objets Media représentant les médias associés au stage, ou faux en cas d'erreur.
 */
function getMediaByStageId($stageId)
{
    $stageMedia = [];
    // Requête SQL pour sélectionner les médias associés au stage spécifié
    $sql = "SELECT `id`, `add_date`, `filename_vignette`, `filename_hd`
    FROM `media`
    WHERE  `stages_id` = :si";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $tab = array(":si" => $stageId);
        foreach ($tab as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $statement->execute();
    } catch (PDOException $e) {
        // En cas d'erreur, retourne faux
        return false;
    }
    while ($row = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        // Ajoute un nouvel objet Media au tableau $stageMedia en utilisant les données de la base de données
        $stageMedia[] = new Media($row['id'], $row['add_date'], $row['filename_vignette'], $row['filename_hd']);
    }
    // Retourne le tableau contenant les objets Media représentant les médias associés au stage
    return $stageMedia;
}

/**
 * Récupère les informations d'un média à partir de son identifiant.
 * 
 * @param int $idMedia L'identifiant du média à récupérer.
 * @return array|bool Retourne un tableau contenant un objet Media représentant le média correspondant à l'identifiant donné, ou faux en cas d'erreur.
 */
function getMediaById($idMedia)
{
    $stageMedia = [];
    // Requête SQL pour sélectionner les informations du média avec l'identifiant donné
    $sql = "SELECT `id`, `add_date`, `filename_vignette`, `filename_hd`
    FROM `media`
    WHERE  `id` = :i";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $tab = array(":i" => $idMedia);
        foreach ($tab as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $statement->execute();
    } catch (PDOException $e) {
         // En cas d'erreur, retourne faux
        return false;
    }
    while ($row = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        // Ajoute un nouvel objet Media au tableau $stageMedia en utilisant les données de la base de données
        $stageMedia[] = new Media($row['id'], $row['add_date'], $row['filename_vignette'], $row['filename_hd']);
    }
     // Retourne le tableau contenant l'objet Media représentant le média correspondant à l'identifiant donné
    return $stageMedia;
}

/**
 * Supprime un média de la table 'media' en fonction de son identifiant.
 * 
 * @param int $id L'identifiant du média à supprimer.
 * @return bool Retourne vrai si la suppression du média est réussie, sinon retourne faux.
 */
function deleteMediaById($id)
{
    // Requête SQL pour supprimer le média avec l'identifiant donné
    $sql = "DELETE 
    FROM `media`
    WHERE `id` = :i";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $tab = array(':i' => $id);
        foreach ($tab as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $statement->execute();
        // Retourne vrai si la suppression du média est réussie
        return true;
    } catch (PDOException $e) {
        // En cas d'erreur, retourne faux
        return false;
    }
}
