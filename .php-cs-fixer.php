<?php

$finder = new PhpCsFixer\Finder();
$finder
    ->in(__DIR__);
;

$config = new PhpCsFixer\Config();

return $config
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setRules([
        "@PSR2" => true,
        "@Symfony" => true,
        "@Symfony:risky" => true,
        "align_multiline_comment" => true,
        "array_indentation" => true,
        "array_syntax" => ["syntax" => "short"],
        "concat_space" => ["spacing" => "one"],
        "declare_strict_types" => true,
        "error_suppression" => false,
        "explicit_indirect_variable" => true,
        "explicit_string_variable" => true,
        "final_internal_class" => true,
        "fully_qualified_strict_types" => true,
        "heredoc_to_nowdoc" => true,
        "linebreak_after_opening_tag" => true,
        "logical_operators" => true,
        "list_syntax" => ["syntax" => "short"],
        "mb_str_functions" => true,
        "method_chaining_indentation" => true,
        "multiline_comment_opening_closing" => true,
        "no_alternative_syntax" => true,
        "no_php4_constructor" => true,
        "no_superfluous_elseif" => true,
        "no_superfluous_phpdoc_tags" => [
            'allow_mixed' => true,
        ],
        "no_unreachable_default_argument_value" => true,
        "no_useless_else" => true,
        "no_useless_return" => true,
        "not_operator_with_space" => true,
        "php_unit_test_annotation" => [
            "style" => "annotation",
        ],
        "php_unit_method_casing" => [
            "case" => "snake_case",
        ],
        "php_unit_test_class_requires_covers" => false,
        "phpdoc_order" => true,
        "phpdoc_to_return_type" => true,
        "protected_to_private" => false,
        "random_api_migration" => true,
        "return_assignment" => true,
        "self_accessor" => false,
        "simplified_null_return" => true,
        "single_quote" => false,
        "strict_comparison" => true,
        "strict_param" => true,
        "string_line_ending" => true,
        "ternary_to_null_coalescing" => true,
        "void_return" => true,
    ]);