<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('bootstrap/cache')
    ->exclude('config')
    ->exclude('node_modules')
    ->exclude('public')
    ->exclude('public/hot')
    ->exclude('public/storage')
    ->exclude('storage/app')
    ->exclude('storage/framework')
    ->exclude('storage/logs')
    ->exclude('vendor')
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true)
    ->in(__DIR__);

$config = new PhpCsFixer\Config();
return $config->setRules([
    '@PSR2' => true,
    'array_indentation' => true,
    'array_syntax' => ['syntax' => 'short'],
    'binary_operator_spaces' => ['operators' => ['=>' => 'align', '=' => 'align']],
    'blank_line_after_opening_tag' => true,
    'blank_line_before_statement' => true,
    'braces' => ['allow_single_line_closure' => true],
    'cast_spaces' => true,
    'class_attributes_separation' => ['elements' => ['const' => 'one', 'method' => 'one', 'property' => 'one']],
    'combine_consecutive_unsets' => true,
    'concat_space' => ['spacing' => 'one'],
    'declare_equal_normalize' => ['space' => 'single'],
    'include' => true,
    'linebreak_after_opening_tag' => true,
    'lowercase_cast' => true,
    'method_argument_space' => ['on_multiline' => 'ignore'],
    'no_blank_lines_after_class_opening' => true,
    'no_blank_lines_after_phpdoc' => true,
    'no_break_comment' => false,
    'no_extra_blank_lines' => ['tokens' => ['extra']],
    'no_multiline_whitespace_around_double_arrow' => true,
    'multiline_whitespace_before_semicolons' => ['strategy' => 'no_multi_line'],
    'no_spaces_around_offset' => true,
    'no_trailing_comma_in_singleline_array' => true,
     'no_unused_imports' => false,
    'no_useless_else' => true,
    'no_useless_return' => true,
    'no_whitespace_before_comma_in_array' => true,
    'no_whitespace_in_blank_line' => true,
    'normalize_index_brace' => true,
    'object_operator_without_whitespace' => true,
    'ordered_class_elements' => ['sort_algorithm' => 'alpha'],
    'ordered_imports' => ['sort_algorithm' => 'alpha'],
    'phpdoc_indent' => true,
    'phpdoc_to_comment' => true,
    'phpdoc_trim' => true,
    'return_type_declaration' => ['space_before' => 'one'],
    'single_blank_line_before_namespace' => true,
    'single_line_after_imports' => true,
    'single_quote' => true,
    'space_after_semicolon' => ['remove_in_empty_for_expressions' => true],
    'ternary_operator_spaces' => true,
    'ternary_to_null_coalescing' => true,
    'trailing_comma_in_multiline' => true,
    'trim_array_spaces' => true,
    'unary_operator_spaces' => true,
    'whitespace_after_comma_in_array' => true,
])
    ->setLineEnding("\n")
    ->setUsingCache(false)
    ->setFinder($finder)
    ;
