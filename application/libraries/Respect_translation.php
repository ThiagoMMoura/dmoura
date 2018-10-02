<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Respect_translation{
    
    public function translate($text){
        if ($text == '{{name}} must be an integer number') {
            return '{{name}} deve ser um número inteiro';
        }
        if ($text == '{{name}} must be an odd number') {
            return '{{name}} deve ser um número ímpar';
        }
        if ($text == 'These {{failed}} rules must pass for {{name}}') {
            return 'Essas {{failed}} regras devem ser seguidas para {{name}}';
        }
        if ($text == '{{name}} must contain only letters (a-z)') {
            return '{{name}} deve conter somente letras (a-z)';
        }
        if ($text == '{{name}} must be a valid date') {
            return '{{name}} deve ser uma data válida';
        }
        if ($text == '{{name}} must have a length between {{minValue}} and {{maxValue}}') {
            return '{{name}} deve ter o comprimento entre {{minValue}} e {{maxValue}}';
        } else {
            log_message('DEBUG', "Respect translation message: '" . $text . "'");
        }
        return $text;
    }
}

