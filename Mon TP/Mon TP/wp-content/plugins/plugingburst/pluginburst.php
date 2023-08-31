<?php

/*
Plugin Name: GBurst
Description: Le plugin des compétitions
Author: Agnes
Version: 1.0
*/

// -- Import du widget GBurst_tournaments
require_once plugin_dir_path(__FILE__) . "/widget/GBurst_tournaments.php";
// -- Import de la DB GBurst_Database_Service
require_once plugin_dir_path(__FILE__) . "service/GBurst_Database_Service.php";
// -- Import de Joueur_List
require_once plugin_dir_path(__FILE__) . "Joueur_List.php";
// -- Import de Competition_List
require_once plugin_dir_path(__FILE__) . "Competition_List.php";



// -- Classe du plugin
class GBurst
{
    private $dal;

    // - Constructeur
    public function __construct()
    {
        // - Création des tables à l'activation du plugin 
        register_activation_hook(__FILE__, array("GBurst_Database_Service", "create_db"));

        // - Enregistrement du widget
        add_action('widgets_init', function () {
            register_widget("GBurst_tournaments");
        });

        // - Instanciation de la classe GBurst_Database_Service
        $this->dal = new GBurst_Database_Service();

        // - Enregistrement du menu Joueurs
        add_action("admin_menu", array($this, "add_menu_joueur"));
    }

    // -- Affichage du formulaire d'ajout de joueur
    public function afficherFormulaireAjout()
    {

        echo "<h2>" . get_admin_page_title() . "</h2>";
        echo "<form method='post' action=''>";
        echo "<input type='hidden' name='send' value='ok'>";
        // - Input nom
        echo "<div>" .
            "<label for='nom'>Nom</label>" .
            "<input type='text' name='nom' id='nom' class='widefat' required>" .
            "</div>";
        // - Input prenom
        echo "<div>" .
            "<label for='prenom'>Prénom</label>" .
            "<input type='text' name='prenom' id='prenom' class='widefat' required>" .
            "</div>";
        // - Input pseudo
        echo "<div>" .
            "<label for='pseudo'>Pseudo</label>" .
            "<input type='text' name='pseudo' id='pseudo' class='widefat' required>" .
            "</div>";
        // - Input email
        echo "<div>" .
            "<label for='email'>Email</label>" .
            "<input type='email' name='email' id='email' class='widefat' required>" .
            "</div>";

        // - Récupérer la liste des compétitions
        $competitions = $this->dal->get_all_competitions();
        echo "<div>" .
            "<label for='competition'>Compétition</label>" .
            "<select name='competition' id='competition_id' class='widefat' required>";
        foreach ($competitions as $competition) {
            echo "<option value='" . $competition->id . "'>" . $competition->label . "</option>";
        }
        echo "</select>" .
            "</div>";

        // - Input Ajouter
        echo "<div>" .
            "<input type='submit' value='Ajouter' class='button'>" .
            "</div>";
        echo "</form>";


        // -- Formulaire ajout tournoi
        echo "<form method='post' action=''>";
        echo "<h2>Ajouter Compétition</h2>";
        // - Nom de la compétition
        // - Input nom
        echo "<div>" .
            "<label for='label'>Nom Compétition</label>" .
            "<input type='text' name='nom-competition' id='nom-competition' class='widefat' required>" .
            "</div>";
        // - Input date début
        echo "<div>" .
            "<label for='date_debut'>Début :</label>" .
            "<input type='date' name='date_debut' id='date_debut' required>" .
            "</div>";
        // - Input date fin
        echo "<div>" .
            "<label for='date_fin'>Fin :</label>" .
            "<input type='date' name='date_fin' id='date_fin' required>" .
            "</div>";
        // - Input Ajouter
        echo "<div>" .
            "<input type='submit' value='Ajouter' class='button'>" .
            "</div>";
        echo "</form>";
    }

    // - Affichage de la liste de joueurs
    public function afficherListes()
    {
        echo "<h2>" . get_admin_page_title() . "</h2>";

        if (isset($_POST['send']) && $_POST['send'] == 'ok') {
            $this->dal->save_joueur();
        }

        if (isset($_POST['send']) && $_POST['send'] == 'ok') {
            $this->dal->save_competition();
        }

        if (isset($_POST['action']) && $_POST['action'] == 'delete-joueur') {
            $this->dal->delete_joueur($_POST['delete-joueur']);
        }

        if (isset($_POST['action']) && $_POST['action'] == 'delete-competition') {
            $this->dal->delete_competition($_POST['delete-competition']);
        }

        $table1 = new Joueur_List();
        $table1->prepare_items();
        echo "<form method='post'>";
        $table1->display();
        echo "</form>";

        echo "<hr>";
        echo "<h3>Compétitions</h3>";

        $table2 = new Competition_List();
        $table2->prepare_items();
        echo "<form method='post'>";
        $table2->display();
        echo "</form>";
    }

    // - Menu pour gérer les tournois
    public function add_menu_joueur()
    {
        // - Menu
        add_menu_page(
            "Joueurs",
            "Compétitions",
            "manage_options",
            "competitions",
            array($this, "afficherListes"),
            "dashicons-superhero",
            40
        );

        // - Sous-menu
        add_submenu_page(
            "competitions",
            "Ajouter",
            "Ajouter",
            "manage_options",
            "ajouter-joueur",
            array($this, "afficherFormulaireAjout")
        );
    }
}
new GBurst();
