<?php

class Validator
{
    private $errors = [];
    private $warnings = [];

    private $rules = [];
    private $data = [];

    public function __construct($data = [], $rules = [])
    {
        $this->data = $data;
        $this->rules = $rules;
    }

    public function validate()
    {
        foreach ($this->rules as $field => $rules) {
            foreach ($rules as $rule => $data) {
                if (!$this->check("validate_{$rule}")) {
                    $this->warnings['notFound'][] = "Функция 'Validator::validate_{$rule}' для поля '{$field}' не определена";

                    continue;
                }

                if (!$this->{"validate_{$rule}"}($this->data[$field], $data['value'])) {
                    $this->errors[$field][] = $data['message'];
                }
            }
        }

        return [
            'warnings' => $this->warnings,
            'errors'   => $this->errors,
        ];
    }

    public function getErrors()
    {
        return $this->errors;
    }

    private function check($method)
    {
        return method_exists('Validator', $method);
    }

//    Лабораторная работа №4 сдана
    private function validate_min($value, $param)
    {
        if (is_string($value)) {
            return mb_strlen($value, 'utf-8') >= $param;
        }

        return $value >= $param;
    }

    private function validate_max($value, $param)
    {
        if (is_string($value)) {
            return mb_strlen($value, 'utf-8') <= $param;
        }

        return $value <= $param;
    }

    private function validate_email($value, $param)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    private function validate_equal($value, $param)
    {
        return $value === $param;
    }

    private function validate_unique($value, $array) {
        return !in_array($value, $array);
    }
}