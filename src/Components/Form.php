<?php

namespace Web\App\Components;

class Form
{
    public static function text(array $inputContent = [], array $errors = []): string
    {
        $class = null;
        $label = array_key_exists('label', $inputContent) ? $inputContent['label'] : null;
        $type = $inputContent['type'] ?? 'text';
        $name = array_key_exists('name', $inputContent) ? 'name="'.$inputContent['name'].'"' : null;
        $id = array_key_exists('name', $inputContent) ? 'id="'.$inputContent['name'].'"' : null;
        $placeholder = array_key_exists('placeholder', $inputContent) ? 'placeholder="'.$inputContent['placeholder'].'"' : null;
        $disabled = array_key_exists('disabled', $inputContent) && $inputContent['disabled'] === true ? 'disabled' : null; 
        $required = array_key_exists('required', $inputContent) && $inputContent['required'] === true ? 'required' : null;
        $value = array_key_exists('value', $inputContent) ? 'value="'.$inputContent['value'].'"' : null;
        if (!empty($errors) && array_key_exists($inputContent['name'], $errors)) {
            $class = ' red basic ';
            $label = $errors[$inputContent['name']];
        }
        return <<<HTML
        <div class="field">
            <input type="{$type}" {$name} {$placeholder} {$value} {$id} {$disabled} {$required}>
            <div class="ui pointing bottom {$class} label">{$label}</div>
        </div>
HTML;
    }

    public static function checkbox(array $inputContent = [], bool $slide = false, bool $toggle = false): string
    {
        $class = null;
        if($slide === true) $class = 'slider';
        if($toggle == true) $class = 'toggle';
        $slider = $slide === true ? '<label></label>' : null;
        $label = array_key_exists('label', $inputContent) ? $inputContent['label'] : null;
        $name = array_key_exists('name', $inputContent) ? 'name="'.$inputContent['name'].'"' : null;
        $id = array_key_exists('name', $inputContent) ? 'id="'.$inputContent['name'].'"' : null;
        $for = array_key_exists('name', $inputContent) ? 'for="'.$inputContent['name'].'"' : null;
        $value = array_key_exists('value', $inputContent) ? 'value="'.$inputContent['value'].'"' : null;
        $checked = array_key_exists('checked', $inputContent) && $inputContent['checked'] === true ? 'checked' : null;
        return <<<HTML
        <div class="inline field">
            <div class="ui {$class} checkbox">
                <input type="checkbox" {$name} tabindex="0" class="hidden" {$checked} {$id} {$value}>
                <label {$for}>{$label}</label>
            </div>
            {$slider}
        </div>
HTML;
    }

    public static function check(array $inputContent = []): string
    {
        $label = array_key_exists('label', $inputContent) ? $inputContent['label'] : null;
        $name = array_key_exists('name', $inputContent) ? 'name="'.$inputContent['name'].'"' : null;
        $id = array_key_exists('name', $inputContent) ? 'id="'.$inputContent['value'].'"' : null;
        $for = array_key_exists('name', $inputContent) ? 'for="'.$inputContent['value'].'"' : null;
        $value = array_key_exists('value', $inputContent) ? 'value="'.$inputContent['value'].'"' : null;
        $form = array_key_exists('form', $inputContent) ? 'form="'.$inputContent['form'].'"' : null;
        $checked = array_key_exists('checked', $inputContent) && $inputContent['checked'] === true ? 'checked' : null; 
        $data = array_key_exists('data', $inputContent) && $inputContent['data'] === true ? 'data-checked-target="'.$inputContent['value'].'"' : null;
        return <<<HTML
        <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" {$name} {$form} {$id} {$checked} {$value} {$data}>
            <label class="custom-control-label" {$for}>{$label}</label>
        </div>
HTML;
    }

    public static function file(array $input = []): string 
    {
        $name = array_key_exists('name', $input) ? 'name="'.$input['name'].'"' : null;
        $label = array_key_exists('label', $input) ? '<label for="'.$input['name'].'">'.$input['label'].'</label>' : null;
        return <<<HTML
        <div class="inline field">
            {$label}
            <input type="file" {$name} tabindex="0" class="hidden">
        </div>
HTML;
    }

    public static function select(array $inputContent = []): string
    {
        $optionsTable = [];
        $option = null;
        if (array_key_exists('options', $inputContent)) {
            foreach ($inputContent['options'] as $options) {
                $optionsTable[] = self::option($options);
            }
            $option = implode(PHP_EOL, $optionsTable);
        }
        $name = array_key_exists('name', $inputContent) ? 'name="'.$inputContent['name'].'"' : null;
        $id = array_key_exists('name', $inputContent) ? 'id="'.$inputContent['name'].'"' : null;
        $multiple = array_key_exists('multiple', $inputContent) && $inputContent['multiple'] === true ? 'multiple=""' : null;
        return <<<HTML
        <select class="ui fluid search dropdown" {$name} {$id} {$multiple}>
            {$option}
        </select>
HTML;
    }

    public static function textarea(array $input, array $errors = [], int $rows = 4, string $class = 'field'): string
    {
        $bootstrap_class = null; 
        if($class !== 'field') $bootstrap_class = 'class="form-control"';
        $label = array_key_exists('label', $input) ? $input['label'] : null;
        $name = array_key_exists('name', $input) ? 'name="'.$input['name'].'"' : null;
        $value = array_key_exists('value', $input) ? $input['value'] : null;
        $placeholder = array_key_exists('placeholder', $input) ? 'placeholder="'.$input['placeholder'].'"' : null;
        $form = array_key_exists('form', $input) ? 'form="'.$input['form'].'"' : null;
        $id = array_key_exists('id', $input) ? 'id="'.$input['id'].'"' : null;
        $label_class = null;
        if (!empty($errors) && array_key_exists($input['name'], $errors)) {
            $label_class = ' red basic ';
            $label = $errors[$input['name']];
        }
        return <<<HTML
        <div class="{$class}">
            <textarea rows="{$rows}" {$name} {$bootstrap_class} {$placeholder} {$form} {$id}>{$value}</textarea>
            <div class="ui pointing bottom {$label_class} label">{$label}</div>
        </div>
HTML;
    }

    public static function input(array $inputContent = [], array $errors = []): string
    {
        $error = null;
        $class = 'form-group';
        $form_control = 'form-control';
        $label_class = null;
        $radio_label = null;
        $display = 'style="display:none"';
        $label = array_key_exists('label', $inputContent) ? $inputContent['label'] : null;
        $type = $inputContent['type'] ?? 'text';
        $name = array_key_exists('name', $inputContent) ? 'name="'.$inputContent['name'].'"' : null;
        $id = array_key_exists('name', $inputContent) ? 'id="'.$inputContent['name'].'"' : null;
        $placeholder = array_key_exists('placeholder', $inputContent) ? 'placeholder="'.$inputContent['placeholder'].'"' : null;
        $disabled = array_key_exists('disabled', $inputContent) && $inputContent['disabled'] === true ? 'disabled' : null; 
        $required = array_key_exists('required', $inputContent) && $inputContent['required'] === true ? 'required' : null;
        $value = array_key_exists('value', $inputContent) ? 'value="'.$inputContent['value'].'"' : null;
        $form = array_key_exists('form', $inputContent) ? 'form="'.$inputContent['form'].'"' : null;
        if (!empty($errors) && array_key_exists($inputContent['name'], $errors)) {
            $display =  'style="display:block;color:red;"';
            $error = $errors[$inputContent['name']];
        }
        $labelled = '<label for="'.$name.'">'.$label.'</label>';
        if ($type === 'radio' | $type === 'checkbox') {
            $class = 'form-check form-check-inline mb-3';
            $form_control = 'form-check-input';
            $label_class = 'class="form-check-label"';
            $labelled = null;
            $radio_label = '<label for="'.$name.'" '.$label_class.'>'.$label.'</label>';
        }

        return <<<HTML
        <div class="{$class}">
            {$labelled}
            <input type="{$type}" class="{$form_control}" {$name} {$id} {$placeholder} {$value} {$form} {$disabled} {$required}>
            {$radio_label}
            <small class="form-text text-muted" {$display}>{$error}</small>
        </div>
HTML;
    }

    public static function button(string $label = "Enregistrer", string $color = 'positive', $icon = 'save'): string
    {
        return <<<HTML
        <button class="ui {$color} button"><i class="ui icon {$icon}"></i>{$label}</button>
HTML;
    }

    private static function option(array $options): string
    {
        $option = null;
        foreach ($options as $value => $label) {
            $option = '<option value="'.$value.'">'.$label.'</option>';
        }
        return $option;
    }
}