<?php

namespace Hupa\StarterV2;

use Register_Child_Hooks;
use stdClass;

class Post_Miglieder_Metabox
{
    private static $instance;
    protected Register_Child_Hooks $main;
    private string $basename;

    /**
     * @return static
     */
    public static function instance(string $basename, Register_Child_Hooks $main): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($basename, $main);
        }
        return self::$instance;
    }

    public function __construct(string $basename, Register_Child_Hooks $main)
    {
        $this->main = $main;
        $this->basename = $basename;
        if (current_user_can('edit_posts')) {
            add_action('load-post.php', array($this, 'init_mitglieder_classic_metabox'));
            add_action('load-post-new.php', array($this, 'init_mitglieder_classic_metabox'));
        }
    }

    public function init_mitglieder_classic_metabox(): void
    {
        add_action('add_meta_boxes', array($this, 'fn_add_mitglieder_metabox'));
        add_action('save_post', array($this, 'fn_save_mitglieder_metabox'), 10, 2);
    }

    public function fn_add_mitglieder_metabox($post_type): void
    {

        $post_types = array('mitglieder');
        if (in_array($post_type, $post_types)) {
            add_meta_box(
                'mitglieder_custom_metabox',
                __('VDIV Mitglieder', 'bootscore'),
                array($this, 'fn_render_mitglieder_metabox'),
                array('mitglieder'),
                'normal',
                'high',
                array('__back_compat_meta_box' => true)
            );
        }
    }

    public function fn_render_mitglieder_metabox($post): void
    {
        wp_nonce_field('nonce_mitglieder_metabox', 'mitglieder_metabox_update_nonce');
        $mitgliederStrasse = get_post_meta($post->ID, '_mitglieder_strasse', true);
        $mitgliederHnr = get_post_meta($post->ID, '_mitglieder_hnr', true);
        $mitgliederOrt = get_post_meta($post->ID, '_mitglieder_ort', true);
        $mitgliederPlz = get_post_meta($post->ID, '_mitglieder_plz', true);
        $mitgliederTelefon = get_post_meta($post->ID, '_mitglieder_telefon', true);
        $mitgliederMobil = get_post_meta($post->ID, '_mitglieder_mobil', true);
        $mitgliederFax = get_post_meta($post->ID, '_mitglieder_fax', true);
        $mitgliederEmail = get_post_meta($post->ID, '_mitglieder_email', true);
        $mitgliederWeb = get_post_meta($post->ID, '_mitglieder_web', true);
        $mitgliederVorname = get_post_meta($post->ID, '_mitglieder_vorname', true);
        $mitgliederNachname = get_post_meta($post->ID, '_mitglieder_nachname', true);
        $mitgliederBeschreibung = get_post_meta($post->ID, '_mitglieder_beschreibung', true);

        ?>
        <div class="wp-bs-starter-wrapper">
            <div class="col-xl-8 col-lg-10 col-12 mx-auto">
                <div class="card my-3">
                    <h5 class="card-header mb-0 d-flex align-items-center">
                        <i class="icon-hupa-white mt-1 me-1" style="color: red"></i>
                        <span>VDIV Mitglied</span>
                    </h5>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-xl-6 col-12">
                                <label for="inputVorname"
                                       class="form-label mb-1">Vorname</label>
                                <input type="text" name="vorname" value="<?= $mitgliederVorname ?>" id="inputVorname"
                                       class="form-control">
                            </div>
                            <div class="col-xl-6 col-12">
                                <label for="inputNachname"
                                       class="form-label mb-1">Nachname</label>
                                <input type="text" name="nachname" value="<?= $mitgliederNachname ?>" id="inputNachname"
                                       class="form-control">
                            </div>
                            <div class="col-xl-9 col-lg-8 col-12">
                                <label for="inputStrasse"
                                       class="form-label mb-1">Strasse</label>
                                <input type="text" name="strasse" value="<?= $mitgliederStrasse ?>" id="inputStrasse"
                                       class="form-control">
                            </div>
                            <div class="col-xl-3 col-lg-4 col-12">
                                <label for="inputHausnummer"
                                       class="form-label mb-1">Hausnummer</label>
                                <input type="text" name="hnr" value="<?= $mitgliederHnr ?>" id="inputHausnummer"
                                       class="form-control">
                            </div>
                            <div class="col-xl-3 col-lg-4 col-12">
                                <label for="inputPlz"
                                       class="form-label mb-1">Postleitzahl</label>
                                <input type="text" name="plz" value="<?= $mitgliederPlz ?>" id="inputPlz"
                                       class="form-control">
                            </div>
                            <div class="col-xl-9 col-lg-8 col-12">
                                <label for="inputOrt"
                                       class="form-label mb-1">Ort</label>
                                <input type="text" name="ort" value="<?= $mitgliederOrt ?>" id="inputOrt"
                                       class="form-control">
                            </div>
                            <div class="col-xl-6 col-12">
                                <label for="inputTelefon"
                                       class="form-label mb-1">Telefon</label>
                                <input type="text" name="telefon" value="<?= $mitgliederTelefon ?>" id="inputTelefon"
                                       class="form-control">
                            </div>
                            <div class="col-xl-6 col-12">
                                <label for="inputMobil"
                                       class="form-label mb-1">Mobil</label>
                                <input type="text" name="mobil" value="<?= $mitgliederMobil ?>" id="inputMobil"
                                       class="form-control">
                            </div>
                            <div class="col-xl-6 col-12">
                                <label for="inputFax"
                                       class="form-label mb-1">Fax</label>
                                <input type="text" name="fax" value="<?= $mitgliederFax ?>" id="inputFax"
                                       class="form-control">
                            </div>
                            <div class="col-xl-6 col-12"></div>
                            <div class="col-xl-6 col-12">
                                <label for="inputEmail"
                                       class="form-label mb-1">E-Mail</label>
                                <input type="email" name="email" value="<?= $mitgliederEmail ?>" id="inputEmail"
                                       class="form-control">
                            </div>
                            <div class="col-xl-6 col-12">
                                <label for="inputWeb"
                                       class="form-label mb-1">Web</label>
                                <input type="url" name="web" value="<?= $mitgliederWeb ?>" id="inputWeb"
                                       class="form-control">
                            </div>
                            <div class="col-12">
                                <label for="textBeschreibung" class="form-label">Beschreibung</label>
                                <textarea class="form-control" name="beschreibung" id="textBeschreibung" rows="5"><?=$mitgliederBeschreibung?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public function fn_save_mitglieder_metabox($post_id, $post): void
    {
        $nonce_name = $_POST['mitglieder_metabox_update_nonce'] ?? '';
        $nonce_action = 'nonce_mitglieder_metabox';

        // Check if nonce is valid.
        if (!wp_verify_nonce($nonce_name, $nonce_action)) {
            return;
        }

        // Check if user has permissions to save data.
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Check if not an auto save.
        if (wp_is_post_autosave($post_id)) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check if not a revision.
        if (wp_is_post_revision($post_id)) {
            return;
        }

        $record = new stdClass();
        $record->vorname = filter_input(INPUT_POST, 'vorname', FILTER_UNSAFE_RAW);
        $record->nachname = filter_input(INPUT_POST, 'nachname', FILTER_UNSAFE_RAW);
        $record->strasse = filter_input(INPUT_POST, 'strasse', FILTER_UNSAFE_RAW);
        $record->hnr = filter_input(INPUT_POST, 'hnr', FILTER_UNSAFE_RAW);
        $record->plz = filter_input(INPUT_POST, 'plz', FILTER_UNSAFE_RAW);
        $record->ort = filter_input(INPUT_POST, 'ort', FILTER_UNSAFE_RAW);
        $record->telefon = filter_input(INPUT_POST, 'telefon', FILTER_UNSAFE_RAW);
        $record->mobil = filter_input(INPUT_POST, 'mobil', FILTER_UNSAFE_RAW);
        $record->fax = filter_input(INPUT_POST, 'fax', FILTER_UNSAFE_RAW);
        $record->email = filter_input(INPUT_POST, 'email', FILTER_UNSAFE_RAW);
        $record->web = filter_input(INPUT_POST, 'web', FILTER_UNSAFE_RAW);
        $record->beschreibung = filter_input(INPUT_POST, 'beschreibung', FILTER_UNSAFE_RAW);

        update_post_meta($post_id, '_mitglieder_vorname', $record->vorname, false);
        update_post_meta($post_id, '_mitglieder_nachname', $record->nachname, false);
        update_post_meta($post_id, '_mitglieder_strasse', $record->strasse, false);
        update_post_meta($post_id, '_mitglieder_hnr', $record->hnr, false);
        update_post_meta($post_id, '_mitglieder_plz', $record->plz, false);
        update_post_meta($post_id, '_mitglieder_ort', $record->ort, false);
        update_post_meta($post_id, '_mitglieder_telefon', $record->telefon, false);
        update_post_meta($post_id, '_mitglieder_mobil', $record->mobil, false);
        update_post_meta($post_id, '_mitglieder_fax', $record->fax, false);
        update_post_meta($post_id, '_mitglieder_email', $record->email, false);
        update_post_meta($post_id, '_mitglieder_web', $record->web, false);
        update_post_meta($post_id, '_mitglieder_beschreibung', htmlspecialchars($record->beschreibung), false);
    }
}
