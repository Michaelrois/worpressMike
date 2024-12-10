<?php

// What are you doing here? Get out of here!
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Artist get fields function
function learn_artist_get_fields()
{
    return array(
        /* Section name */
        array(
            'id' => 'artist_names',
            'type' => 'heading',
            'header' => __("Nomenclature de l'artiste", "custom-posts-learning"),
        ),
        array(
            'label' => __("Nom artiste", "custom-posts-learning"),
            'id' => 'artist_name',
            'type' => 'text',
            'desc' => __("Le nom de l'artiste", "custom-posts-learning"),
            'required' => true,
        ),
        array(
            'label' => __("Nom réel", "custom-posts-learning"),
            'id' => 'artist_real_name',
            'type' => 'text',
            'desc' => __("Le nom réel de l'artiste", "custom-posts-learning"),
        ),
        // Section infos
        array(
            'id' => 'artist_infos',
            'type' => 'heading',
            'header' => __("Infos de l'artiste", "custom-posts-learning"),
        ),
        array(
            'label' => __('Date de naissance', "custom-posts-learning"),
            'id' => 'artist_birthday',
            'type' => 'date',
            'desc' => __("La date de naissance de l'artiste", "custom-posts-learning"),
        ),
        array(
            'label' => __('Date de mort', "custom-posts-learning"),
            'id' => 'artist_death',
            'type' => 'date',
            'desc' => __("La date de mort de l'artiste", "custom-posts-learning"),
        ),
        array(
            'label' =>__('Pays', "custom-posts-learning"),
            'id' => 'artist_country',
            'type' => 'text',
            'desc' => __("Le pays d'origine de l'artiste", "custom-posts-learning"),
        ),
        array(
            'label' => __('Région', "custom-posts-learning"),
            'id' => 'artist_region',
            'type' => 'text',
            'desc' => __("La région, l'état ou la province d'origine de l'artiste", "custom-posts-learning"),
        ),
        array(
            'label' => __('Ville', "custom-posts-learning"),
            'id' => 'artist_city',
            'type' => 'text',
            'desc' => __("La ville d'origine de l'artiste", "custom-posts-learning"),
        ),
        array(
            'label' => __('Description', "custom-posts-learning"),
            'id' => 'artist_description',
            'type' => 'textarea',
            'desc' => __("La description de l'artiste", "custom-posts-learning"),
        ),
    )
}

//  Song get fields function
function learn_song_get_fields()
{
    return array(
        /* Section title */
        array(
            'id' => 'song_titles',
            'type' => 'heading',
            'header' => __("Nomenclature de la chanson", "custom-posts-learning"),
        ),
        array(
            'label' => __("Titre originel", "custom-posts-learning"),
            'id' => 'song_original_title',
            'type' => 'text',
            'desc' => __("Le titre originel de la chanson", "custom-posts-learning"),
            'required' => true,
        ),
        array(
            'label' => __("Titre français", "custom-posts-learning"),
            'id' => 'song_french_title',
            'type' => 'text',
            'desc' => __("Le titre français de la chanson", "custom-posts-learning"),
        ),
        array(
            'label' => __("Titre anglais", "custom-posts-learning"),
            'id' => 'song_english_title',
            'type' => 'text',
            'desc' => __("Le titre anglais de la chanson", "custom-posts-learning"),
        ),
        /* Section details*/ 
        array(
            'id' => 'song_details',
            'type' => 'heading',
            'header' => __("Détails de la chanson", "custom-posts-learning"),
        ),
        array(
            'label' => __("Artiste", "custom-posts-learning"),
            'id' => 'song_artist',
            'type' => 'post_association',
            'post_type' => 'learn_artist',
            'desc' => __("Entrez et sélectionnez le nom de l'artiste", "custom-posts-learning"),
            'required' => true,
        ),
        array(
            'label' => __("Collaboration", "custom-posts-learning"),
            'id' => 'song_collaboration',
            'type' => 'post_association',
            'post_type' => 'learn_artist',
            'desc' => __("Entrez et sélectionnez le nom de l'artiste", "custom-posts-learning"),
        ),
        array(
            'label' => __("Durée", "custom-posts-learning"),
            'id' => 'song_duration',
            'type' => 'text',
            'desc' => __("La durée de la chanson", "custom-posts-learning"),
        ),
        array(
            'label' => __("Album", "custom-posts-learning"),
            'id' => 'song_album',
            'type' => 'post_association',
            'post_type' => 'learn_album',
            'desc' => __("Entrex et  sélectionnez le nom de l'album", "custom-posts-learning"),
        ),
        array(
            'label' => __("Genre", "custom-posts-learning"),
            'id' => 'song_genre',
            'type' => 'text',
            'desc' => __("Le genre de la chanson", "custom-posts-learning"),
        ),
        array(
            'label' => __("Date", "custom-posts-learning"),
            'id' => 'song_date',
            'type' => 'text',
            'desc' => __("La date de sortie de la chanson", "custom-posts-learning"),
        ),
        array(
            'label' => __("Lien", "custom-posts-learning"),
            'id' => 'song_link',
            'type' => 'text',
            'desc' => __("Le lien vers la chanson", "custom-posts-learning"),
        ),
    )
}

// Album get fields function
function learn_album_get_fields()
{
    return array(
        /* Section title */
        array(
            'id' => 'album_titles',
            'type' => 'heading',
            'header' => __("Nomenclature de l'album", "custom-posts-learning"),
        ),
        array(
            'label' => __("Titre originel", "custom-posts-learning"),
            'id' => 'album_original_title',
            'type' => 'text',
            'desc' => __("Le titre originel de l'album", "custom-posts-learning"),
            'required' => true,
        ),
        array(
            'label' => __("Titre français", "custom-posts-learning"),
            'id' => 'album_french_title',
            'type' => 'text',
            'desc' => __("Le titre français de l'album", "custom-posts-learning"),
        ),
        array(
            'label' => __("Titre anglais", "custom-posts-learning"),
            'id' => 'album_english_title',
            'type' => 'text',
            'desc' => __("Le titre anglais de l'album", "custom-posts-learning"),
        ),
        /* Section details*/
        array(
            'id' => 'album_details',
            'type' => 'heading',
            'header' => __("Détails de l'album", "custom-posts-learning"),
        ),
        array(
            'label' => __("Artiste", "custom-posts-learning"),
            'id' => 'album_artist',
            'type' => 'post_association',
            'post_type' => 'learn_artist',
            'desc' => __("Entrez et sélectionnez le nom de l'artiste", "custom-posts-learning"),
            'required' => true,
        ),
        array(
            'label' => __("Collaboration", "custom-posts-learning"),
            'id' => 'album_collaboration',
            'type' => 'post_association',
            'post_type' => 'learn_artist',    
            'desc' => __("Entrez et sélectionnez le nom de l'artiste", "custom-posts-learning"),
        ),
        array(
            'label' => __("Année", "custom-posts-learning"),
            'id' => 'album_year',
            'type' => 'text',
            'desc' => __("L'annee de sortie de l'album", "custom-posts-learning"),
        ),
        array(
            'label' => __("Genre", "custom-posts-learning"),
            'id' => 'album_genre',
            'type' => 'text',
            'desc' => __("Le genre de l'album", "custom-posts-learning"),
        ),
        array(
            'label' => __("Chansons", "custom-posts-learning"),
            'id' => 'album_songs',
            'type' => 'post_association',
            'post_type' => 'learn_song',
            'desc' => __("Entrez et sélectionnez les chansons de l'album", "custom-posts-learning"),
        ),
        array(
            'label' => __("Nombre de chansons", "custom-posts-learning"),
            'id' => 'album_number_of_songs',
            'type' => 'text',
            'desc' => __("Le nombre de chansons de l'album", "custom-posts-learning"),
        ),
        array(
            'label' => __("Durée", "custom-posts-learning"),
            'id' => 'album_duration',
            'type' => 'text',
            'desc' => __("La durée de l'album", "custom-posts-learning"),
        )
    )
}