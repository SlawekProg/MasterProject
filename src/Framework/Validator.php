<?php

declare(strict_types=1);

namespace Framework;

use Framework\Contracts\RuleInterface;
use Framework\Exceptions\ValidationException;


class Validator
{
    private array $rules = [];

    public function add(string $alias, RuleInterface $rule)
    {
        $this->rules[$alias] = $rule;
    }

    public function validate(array $formData, array $fields)
    {
        $errors = [];

        foreach ($fields as $filedName => $rules) {
            foreach ($rules as $rule) {
                $ruleParams = [];
                if (str_contains($rule, ':')) {
                    [$rule, $ruleParams] = explode(':', $rule);
                    $ruleParams = explode(',', $ruleParams);
                }

                $ruleValidator = $this->rules[$rule];

                if ($ruleValidator->validate($formData, $filedName, $ruleParams)) {
                    continue;
                }

                $errors[$filedName][] = $ruleValidator->getMessage(
                    $formData,
                    $filedName,
                    $ruleParams
                );
            }
        }

        if (count($errors)) {
            throw new ValidationException($errors);
        }
    }
}
