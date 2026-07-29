<?php
namespace App\Core;

class Request
{
    private $params = [];

    public function setParams($params)
    {
        $this->params = $params;
    }

    public function param($index, $default = null)
    {
        return $this->params[$index] ?? $default;
    }

    public function input($key, $default = null)
    {
        $data = array_merge($_POST, $_GET);
        return $data[$key] ?? $default;
    }

    public function all()
    {
        return array_merge($_GET, $_POST);
    }

    public function file($key)
    {
        return $_FILES[$key] ?? null;
    }

    public function hasFile($key)
    {
        return !empty($_FILES[$key]) && $_FILES[$key]['error'] === UPLOAD_ERR_OK;
    }

    public function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function isMethod($method)
    {
        return strtoupper($this->method()) === strtoupper($method);
    }

    public function isAjax()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    public function wantsJson()
    {
        return $this->isAjax() || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);
    }

    public function path()
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    public function validate($rules)
    {
        $errors = [];
        foreach ($rules as $field => $ruleSet) {
            $ruleList = is_array($ruleSet) ? $ruleSet : explode('|', $ruleSet);
            $value = $this->input($field);
            foreach ($ruleList as $rule) {
                if ($rule === 'required' && empty($value)) {
                    $errors[$field][] = "The {$field} field is required";
                }
                if (strpos($rule, 'min:') === 0) {
                    $min = explode(':', $rule)[1];
                    if (strlen($value) < $min) {
                        $errors[$field][] = "The {$field} must be at least {$min} characters";
                    }
                }
                if (strpos($rule, 'max:') === 0) {
                    $max = explode(':', $rule)[1];
                    if (strlen($value) > $max) {
                        $errors[$field][] = "The {$field} must not exceed {$max} characters";
                    }
                }
                if ($rule === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = "The {$field} must be a valid email";
                }
            }
        }
        return $errors;
    }
}
