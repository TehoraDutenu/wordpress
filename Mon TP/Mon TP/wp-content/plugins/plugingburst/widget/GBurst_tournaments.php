<?php

/* -- Création du widget des compétitions */
class GBurst_tournaments extends WP_Widget
{
    public function __construct()
    {
        // - Options du widget
        $widget_ops = [
            "className" => "GB_tournaments",
            "description" => __("Organisation de tournois"),
            "customize_selective_refresh" => true,
        ];
        parent::__construct(
            "tournois",
            __("Tournois"),
            $widget_ops
        );
    }

    // - Affichage
    public function form($instance)
    {
        $instance = wp_parse_args((array) $instance, [
            "nom" => "",
            "prenom" => "",
            "pseudo" => "",
            "email" => "",
            "competition" => "",
            "label" => "",
            "date_debut" => "",
            "date_fin" => ""
        ]);
?>

        <div>
            <label for="<?php echo $this->get_field_id('nom') ?>">Nom</label>
            <input type="text" name="<?php echo $this->get_field_name('nom') ?>" id="<?php echo $this->get_field_id('nom') ?>" value="<?php echo esc_attr($instance['nom']) ?>">
        </div>
        <div>
            <label for="<?php echo $this->get_field_id('prenom') ?>">Prenom</label>
            <input type="text" name="<?php echo $this->get_field_name('prenom') ?>" id="<?php echo $this->get_field_id('prenom') ?>" value="<?php echo esc_attr($instance['prenom']) ?>">
        </div>
        <div>
            <label for="<?php echo $this->get_field_id('pseudo') ?>">Pseudo</label>
            <input type="text" name="<?php echo $this->get_field_name('pseudo') ?>" id="<?php echo $this->get_field_id('pseudo') ?>" value="<?php echo esc_attr($instance['pseudo']) ?>">
        </div>
        <div>
            <label for="<?php echo $this->get_field_id('email') ?>">Email</label>
            <input type="text" name="<?php echo $this->get_field_name('email') ?>" id="<?php echo $this->get_field_id('email') ?>" value="<?php echo esc_attr($instance['email']) ?>">
        </div>
        <div>
            <label for="<?php echo $this->get_field_id('competition') ?>">Compétition</label>
            <select name="<?php echo $this->get_field_name('competition') ?>" id="<?php echo $this->get_field_id('competition') ?>" value="<?php echo esc_attr($instance['competition']) ?>"></select>
        </div>
        <div>
            <label for="<?php echo $this->get_field_id('label') ?>">Label</label>
            <select name="<?php echo $this->get_field_name('label') ?>" id="<?php echo $this->get_field_id('label') ?>" value="<?php echo esc_attr($instance['label']) ?>"></select>
        </div>
        <div>
            <label for="<?php echo $this->get_field_id('date_debut') ?>">Début :</label>
            <select name="<?php echo $this->get_field_name('date_debut') ?>" id="<?php echo $this->get_field_id('date_debut') ?>" value="<?php echo esc_attr($instance['date_debut']) ?>"></select>
        </div>
        <div>
            <label for="<?php echo $this->get_field_id('date_fin') ?>">Fin :</label>
            <select name="<?php echo $this->get_field_name('date_fin') ?>" id="<?php echo $this->get_field_id('date_fin') ?>" value="<?php echo esc_attr($instance['date_fin']) ?>"></select>
        </div>
<?php
    }

    // - Update
    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;

        $old_instance['nom'] = sanitize_text_field($new_instance['nom']);
        $old_instance['prenom'] = sanitize_text_field($new_instance['prenom']);
        $old_instance['pseudo'] = sanitize_text_field($new_instance['pseudo']);
        $old_instance['email'] = sanitize_text_field($new_instance['email']);
        $old_instance['competition'] = sanitize_text_field($new_instance['competition']);

        $old_instance['label'] = sanitize_text_field($new_instance['label']);
        $old_instance['date_debut'] = sanitize_text_field($new_instance['date_debut']);
        $old_instance['date_fin'] = sanitize_text_field($new_instance['date_fin']);

        return $instance;
    }

    // - Widget
    public function widget($args, $instance)
    {
        // - Titre
        $title = "Joueurs";
        // - Nom 
        ($instance[''] != 0) ? $nom = $instance['nom'] : $nom = "Doe";
        // - Prénom
        ($instance[''] != 0) ? $prenom = $instance['prenom'] : $prenom = "John";
        // - Pseudo
        ($instance[''] != 0) ? $pseudo = $instance['pseudo'] : $pseudo = "Johnnoe";
        // - Email
        ($instance[''] != 0) ? $email = $instance['email'] : $email = "johnnoedoe@gmail.com";
        // - Compétition
        ($instance[''] != 0) ? $competition = $instance['competition'] : $competition = "Wargame";
    }
}
