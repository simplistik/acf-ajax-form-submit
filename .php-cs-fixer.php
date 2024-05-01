<?php

return ( new PhpCsFixer\Config() )
    ->setRules( [
      // PSR and general PHP rules
      // '@Symfony'                              => true,
      'indentation_type'                      => true,
      'linebreak_after_opening_tag'           => true,
      'full_opening_tag'                      => true,
      'no_closing_tag'                        => true,
      'lowercase_keywords'                    => true,
      'lowercase_static_reference'            => true,
      'lowercase_cast'                        => true,
      'native_function_casing'                => true,
      'single_quote'                          => true,

      // Array and string rules
      'array_indentation'                     => true,
      'array_syntax'                          => ['syntax' => 'short'],
      'concat_space'                          => ['spacing' => 'one'],
      'no_useless_concat_operator'            => true,
      'normalize_index_brace'                 => true,
      'trim_array_spaces'                     => true,
      'whitespace_after_comma_in_array'       => true,
      'no_whitespace_before_comma_in_array'   => true,

      // Function and class rules
      'function_typehint_space'               => true,
      'return_type_declaration'               => true,
      'no_spaces_after_function_name'         => true,
      'function_declaration'                  => ['closure_function_spacing' => 'none', 'closure_fn_spacing' => 'none'],
      'class_attributes_separation'           => ['elements' => ['method' => 'one']],
      'class_definition'                      => ['single_item_single_line' => true, 'inline_constructor_arguments' => true],
      'no_blank_lines_after_class_opening'    => true,
      'single_class_element_per_statement'    => true,

      // Control structure rules
      'control_structure_continuation_position' => ['position' => 'same_line'],
      'elseif'                                  => true,
      'no_useless_else'                         => true,
      'switch_case_semicolon_to_colon'          => true,
      'switch_case_space'                       => true,

      // Space and alignment rules
      'binary_operator_spaces'                => [
        'operators' => [
          '=>' => 'align',
          '='  => 'align',
        ],
      ],
      'cast_spaces'                           => true,
      'concat_space'                          => ['spacing' => 'one'],
      'declare_equal_normalize'               => true,
      'single_space_around_construct'         => true,
      'spaces_inside_parentheses'             => ['space' => 'single'],
      'space_after_semicolon'                 => true,
      'ternary_operator_spaces'               => true,
      'unary_operator_spaces'                 => true,
      'no_spaces_around_offset'               => ['positions' => ['outside']],
      'logical_operators'                     => true,

      // PHPDoc rules
      'phpdoc_align'                          => true,
      'phpdoc_indent'                         => true,

      // Other rules
      'blank_line_after_opening_tag'           => true,
      'blank_line_before_statement'            => true,
      'combine_consecutive_unsets'             => true,
      'echo_tag_syntax'                        => ['format' => 'long'],
      'include'                                => true,
      'multiline_whitespace_before_semicolons' => false,
      'no_extra_blank_lines'                   => [
        'tokens' => [
          'curly_brace_block',
          'extra',
          'throw',
          'use',
        ],
      ],
      'no_mixed_echo_print'                         => ['use' => 'echo'],
      'no_multiline_whitespace_around_double_arrow' => true,
      'no_singleline_whitespace_before_semicolons'  => true,
      'no_space_around_double_colon'                => true,
      'no_whitespace_in_blank_line'                 => true,
      'object_operator_without_whitespace'          => true,
      'ordered_class_elements'                      => ['sort_algorithm' => 'alpha', 'case_sensitive' => true],
      'ordered_interfaces'                          => ['order' => 'alpha', 'case_sensitive' => true],
      'short_scalar_cast'                           => true,
      'standardize_not_equals'                      => true,
      'ternary_to_null_coalescing'                  => true,
    ] )
    ->setRiskyAllowed( true )
    ->setIndent( '  ' )
    ->setLineEnding( "\n" )
;