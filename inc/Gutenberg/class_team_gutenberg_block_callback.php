<?php

class Theme_Gutenberg_Block_Callback
{
    public static function callback_team_members_block_type($attributes)
    {
        ob_start();
        isset($attributes['selectedCategory']) && $attributes['selectedCategory'] ? $catId = (int)$attributes['selectedCategory'] : $catId = 0;
        isset($attributes['selectedTemplate']) && $attributes['selectedTemplate'] ? $templateId = (int)$attributes['selectedTemplate'] : $templateId = 1;
        isset($attributes['className']) && $attributes['className'] ? $className = 'class="' . $attributes['className'] . '"' : $className = '';
        ?>
        <div <?= $className ?> data-category="<?= $catId ?>" data-template="<?= $templateId ?>" id="team-template"></div>
        <div class="collapse pt-3" id="lebenslauf"></div>
        <?php
        return ob_get_clean();
    }

    public static function callback_leistungen_block_type($attributes)
    {
        ob_start();
        isset($attributes['selectedCategory']) && $attributes['selectedCategory'] ? $catId = (int)$attributes['selectedCategory'] : $catId = 0;
        isset($attributes['selectedTemplate']) && $attributes['selectedTemplate'] ? $templateId = (int)$attributes['selectedTemplate'] : $templateId = 1;
        isset($attributes['className']) && $attributes['className'] ? $className = 'class="' . $attributes['className'] . '"' : $className = '';
        ?>
        <div <?= $className ?> data-category="<?= $catId ?>" data-template="<?= $templateId ?>" id="leistungen-template"></div>
        <?php
        return ob_get_clean();
    }
}