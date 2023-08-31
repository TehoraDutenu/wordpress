<?php
class GBurst_Database_Service
{
    public function __construct()
    {
        // TODO:
    }

    // -- Tables
    public static function create_db()
    {
        global $wpdb;

        // - Table Compétition
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}competition (
            id INT AUTO_INCREMENT PRIMARY KEY,
            label VARCHAR(150) NOT NULL,
            date_debut DATE NOT NULL,
            date_fin DATE NOT NULL
        )");
        $count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}competition");

        if ($count == 0) {
            $wpdb->insert("{$wpdb->prefix}competition", [
                "label" => "Wargame",
                "date_debut" => "2023-09-01",
                "date_fin" => "2023-09-10"
            ]);
        }


        // - Table Joueur
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}joueur (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nom VARCHAR(150) NOT NULL,
            prenom VARCHAR(150) NOT NULL,
            pseudo VARCHAR(150) NOT NULL,
            email VARCHAR(150) NOT NULL,
            competition_id INT NOT NULL,
            FOREIGN KEY (competition_id) REFERENCES {$wpdb->prefix}competition (id)
        )");
        $count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}joueur");

        if ($count == 0) {
            $wpdb->insert("{$wpdb->prefix}joueur", [
                "nom" => "Doe",
                "prenom" => "John",
                "pseudo" => "Johnnoe",
                "email" => "johnnoedoe@gmail.com",
                "competition_id" => 1
            ]);
        }

        // - Table Poule
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}poule (
            id INT AUTO_INCREMENT PRIMARY KEY,
            label VARCHAR(10) NOT NULL
        )");
        $count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}poule");

        if ($count == 0) {
            $wpdb->insert("{$wpdb->prefix}poule", [
                "label" => "Poule A"
            ]);
        }


        // - Table rencontre/match
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}rencontre (
            id INT AUTO_INCREMENT PRIMARY KEY,
            joueur1_id INT NOT NULL,
            joueur2_id INT NOT NULL,
            date_rencontre DATE NOT NULL,
            is_poule BOOLEAN DEFAULT true NOT NULL 
        )");
        $count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}rencontre");

        if ($count == 0) {
            $wpdb->insert("{$wpdb->prefix}rencontre", [
                "joueur1_id" => "1",
                "joueur2_id" => "2",
                "date_rencontre" => "2023-09-10",
                "is_poule" => true
            ]);
        }


        // - Table répartition
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}repartition (
            id INT AUTO_INCREMENT PRIMARY KEY,
            competition_id INT NOT NULL,
            poule_id INT NOT NULL,
            joueur_id INT NOT NULL,
            FOREIGN KEY (competition_id) REFERENCES {$wpdb->prefix}competition (id),
            FOREIGN KEY (poule_id) REFERENCES {$wpdb->prefix}poule (id),
            FOREIGN KEY (joueur_id) REFERENCES {$wpdb->prefix}joueur (id)
        )");
        $count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}repartition");

        if ($count == 0) {
            $wpdb->insert("{$wpdb->prefix}repartition", [
                "competition_id" => 1,
                "poule_id" => 1,
                "joueur_id" => 1
            ]);
        }


        // - Table Score
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}score (
            id INT AUTO_INCREMENT PRIMARY KEY,
            points INT NOT NULL,
            joueur_id INT NOT NULL,
            rencontre_id INT NOT NULL,
            FOREIGN KEY (joueur_id) REFERENCES {$wpdb->prefix}joueur (id),
            FOREIGN KEY (rencontre_id) REFERENCES {$wpdb->prefix}rencontre (id)
        )");
        $count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}score");

        if ($count == 0) {
            $wpdb->insert("{$wpdb->prefix}score", [
                "points" => 1,
                "joueur_id" => 1,
                "rencontre_id" => 1
            ]);
        }
    }


    // - Récupérer le label d'une compétition
    public function get_competition_by_id($competition_id)
    {
        global $wpdb;

        $query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}competition WHERE id = %d", $competition_id);
        $competition = $wpdb->get_row($query);

        return $competition;
    }

    // - Récupérer la liste des joueurs
    public function findAllJoueur()
    {
        global $wpdb;

        $res = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}joueur");
        return $res;

        $comres = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}competition");
        return $comres;
    }

    // - Récupérer la liste des compétitions
    public function findAllCompetition()
    {
        global $wpdb;

        $comres = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}competition");
        return $comres;
    }

    // - Enregistrer un joueur
    public function save_joueur()
    {
        global $wpdb;

        // - Vérifier les champs obligatoires
        if (!isset($_POST['nom']) || !isset($_POST['prenom']) || !isset($_POST['pseudo']) || !isset($_POST['email']) || !isset($_POST['competition'])) {
            echo "Tous les champs doivent être remplis.";
            return;
        }

        $data = [
            "nom" => $_POST["nom"],
            "prenom" => $_POST["prenom"],
            "pseudo" => $_POST["pseudo"],
            "email" => $_POST["email"],
            "competition" => $_POST["competition"]
        ];


        $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}joueur WHERE email = '" . $data["email"] . "'");
        if (empty($row)) {
            $wpdb->insert("{$wpdb->prefix}joueur", $data);
        } else {
            echo "Cet email est déjà enregistré";
        }
    }

    // - Enregistrer une compétition
    public function save_competition()
    {
        global $wpdb;

        // - Vérifier les champs obligatoires
        if (!isset($_POST['label']) || !isset($_POST['date_debut']) || !isset($_POST['date_fin'])) {
            echo "Tous les champs doivent être remplis.";
            return;
        }

        $data = [
            "nom" => $_POST["label"],
            "prenom" => $_POST["date_debut"],
            "pseudo" => $_POST["date_fin"]
        ];


        $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}competition WHERE label = '" . $data["label"] . "'");
        if (empty($row)) {
            $wpdb->insert("{$wpdb->prefix}competition", $data);
        } else {
            echo "Ce tournoi est déjà enregistré";
        }
    }

    // - Supprimer un joueur
    public function delete_joueur($ids)
    {
        global $wpdb;

        if (!is_array($ids)) {
            $ids = [$ids];
        }
        $wpdb->query("DELETE FROM {$wpdb->prefix}joueur WHERE id IN (" . implode(",", $ids) . ")");
    }

    // - Supprimer une compétition
    public function delete_competition($ids)
    {
        global $wpdb;

        if (!is_array($ids)) {
            $ids = [$ids];
        }
        $wpdb->query("DELETE FROM {$wpdb->prefix}competition WHERE id IN (" . implode(",", $ids) . ")");
    }

    // - Récupérer les compétitions
    public function get_all_competitions()
    {
        global $wpdb;

        $query = "SELECT * FROM {$wpdb->prefix}competition";
        $competitions = $wpdb->get_results($query);

        return $competitions;
    }
}
