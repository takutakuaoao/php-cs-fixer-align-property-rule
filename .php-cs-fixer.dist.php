<?php

declare(strict_types=1);

$config = new PhpCsFixer\Config();

return $config
    ->setRules([
        '@Symfony'                => true,
        '@PSR12'                  => true,
        'concat_space'            => ['spacing' => 'one'],
        'binary_operator_spaces'  => ['operators' => ['=>' => 'align_single_space_minimal', '=' => 'align_single_space_minimal']],
        'function_typehint_space' => true,
        'php_unit_method_casing'  => ['case' => 'snake_case'],
    ]);
