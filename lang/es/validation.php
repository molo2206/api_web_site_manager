<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'Le :attribute field must be accepted.',
    'accepted_if' => 'Le :attribute field must be accepted when :other is :value.',
    'active_url' => 'Le :attribute field must be a valid URL.',
    'after' => 'Le :attribute field must be a date after :date.',
    'after_or_equal' => 'Le :attribute field must be a date after or equal to :date.',
    'alpha' => 'Le :attribute field must only contain letters.',
    'alpha_dash' => 'Le :attribute field must only contain letters, numbers, dashes, and underscores.',
    'alpha_num' => 'Le :attribute field must only contain letters and numbers.',
    'array' => 'Le :attribute field must be an array.',
    'ascii' => 'Le :attribute field must only contain single-byte alphanumeric characters and symbols.',
    'before' => 'Le :attribute field must be a date before :date.',
    'before_or_equal' => 'Le :attribute field must be a date before or equal to :date.',
    'between' => [
        'array' => 'Le :attribute field must have between :min and :max items.',
        'file' => 'Le :attribute field must be between :min and :max kilobytes.',
        'numeric' => 'Le :attribute field must be between :min and :max.',
        'string' => 'Le :attribute field must be between :min and :max characters.',
    ],
    'boolean' => 'Le :attribute field must be true or false.',
    'confirmed' => 'Le :attribute field confirmation does not match.',
    'current_password' => 'Le password is incorrect.',
    'date' => 'Le :attribute field must be a valid date.',
    'date_equals' => 'Le :attribute field must be a date equal to :date.',
    'date_format' => 'Le :attribute field must match the format :format.',
    'decimal' => 'Le :attribute field must have :decimal decimal places.',
    'declined' => 'Le :attribute field must be declined.',
    'declined_if' => 'Le :attribute field must be declined when :other is :value.',
    'different' => 'Le :attribute field and :other must be different.',
    'digits' => 'Le :attribute field must be :digits digits.',
    'digits_between' => 'Le :attribute field must be between :min and :max digits.',
    'dimensions' => 'Le :attribute field has invalid image dimensions.',
    'distinct' => 'Le :attribute field has a duplicate value.',
    'doesnt_end_with' => 'Le :attribute field must not end with one of the following: :values.',
    'doesnt_start_with' => 'Le :attribute field must not start with one of the following: :values.',
    'email' => 'Le :attribute field must be a valid email address.',
    'ends_with' => 'Le :attribute field must end with one of the following: :values.',
    'enum' => 'Le selected :attribute is invalid.',
    'exists' => 'Le selected :attribute is invalid.',
    'file' => 'Le :attribute field must be a file.',
    'filled' => 'Le :attribute field must have a value.',
    'gt' => [
        'array' => 'Le :attribute field must have more than :value items.',
        'file' => 'Le :attribute field must be greater than :value kilobytes.',
        'numeric' => 'Le :attribute field must be greater than :value.',
        'string' => 'Le :attribute field must be greater than :value characters.',
    ],
    'gte' => [
        'array' => 'Le :attribute field must have :value items or more.',
        'file' => 'Le :attribute field must be greater than or equal to :value kilobytes.',
        'numeric' => 'Le :attribute field must be greater than or equal to :value.',
        'string' => 'Le :attribute field must be greater than or equal to :value characters.',
    ],
    'image' => 'Le :attribute field must be an image.',
    'in' => 'Le selected :attribute is invalid.',
    'in_array' => 'Le :attribute field must exist in :other.',
    'integer' => 'Le :attribute field must be an integer.',
    'ip' => 'Le :attribute field must be a valid IP address.',
    'ipv4' => 'Le :attribute field must be a valid IPv4 address.',
    'ipv6' => 'Le :attribute field must be a valid IPv6 address.',
    'json' => 'Le :attribute field must be a valid JSON string.',
    'lowercase' => 'Le :attribute field must be lowercase.',
    'lt' => [
        'array' => 'Le :attribute field must have less than :value items.',
        'file' => 'Le :attribute field must be less than :value kilobytes.',
        'numeric' => 'Le :attribute field must be less than :value.',
        'string' => 'Le :attribute field must be less than :value characters.',
    ],
    'lte' => [
        'array' => 'Le :attribute field must not have more than :value items.',
        'file' => 'Le :attribute field must be less than or equal to :value kilobytes.',
        'numeric' => 'Le :attribute field must be less than or equal to :value.',
        'string' => 'Le :attribute field must be less than or equal to :value characters.',
    ],
    'mac_address' => 'Le :attribute field must be a valid MAC address.',
    'max' => [
        'array' => 'Le :attribute field must not have more than :max items.',
        'file' => 'Le :attribute field must not be greater than :max kilobytes.',
        'numeric' => 'Le :attribute field must not be greater than :max.',
        'string' => 'Le :attribute field must not be greater than :max characters.',
    ],
    'max_digits' => 'Le :attribute field must not have more than :max digits.',
    'mimes' => 'Le :attribute field must be a file of type: :values.',
    'mimetypes' => 'Le :attribute field must be a file of type: :values.',
    'min' => [
        'array' => 'Le :attribute field must have at least :min items.',
        'file' => 'Le :attribute field must be at least :min kilobytes.',
        'numeric' => 'Le :attribute field must be at least :min.',
        'string' => 'Le :attribute field must be at least :min characters.',
    ],
    'min_digits' => 'Le :attribute field must have at least :min digits.',
    'missing' => 'Le :attribute field must be missing.',
    'missing_if' => 'Le :attribute field must be missing when :other is :value.',
    'missing_unless' => 'Le :attribute field must be missing unless :other is :value.',
    'missing_with' => 'Le :attribute field must be missing when :values is present.',
    'missing_with_all' => 'Le :attribute field must be missing when :values are present.',
    'multiple_of' => 'Le :attribute field must be a multiple of :value.',
    'not_in' => 'Le selected :attribute is invalid.',
    'not_regex' => 'Le :attribute field format is invalid.',
    'numeric' => 'Le :attribute field must be a number.',
    'password' => [
        'letters' => 'Le :attribute field must contain at least one letter.',
        'mixed' => 'Le :attribute field must contain at least one uppercase and one lowercase letter.',
        'numbers' => 'Le :attribute field must contain at least one number.',
        'symbols' => 'Le :attribute field must contain at least one symbol.',
        'uncompromised' => 'Le given :attribute has appeared in a data leak. Please choose a different :attribute.',
    ],
    'present' => 'Le :attribute field must be present.',
    'prohibited' => 'Le :attribute field is prohibited.',
    'prohibited_if' => 'Le :attribute field is prohibited when :other is :value.',
    'prohibited_unless' => 'Le :attribute field is prohibited unless :other is in :values.',
    'prohibits' => 'Le :attribute field prohibits :other from being present.',
    'regex' => 'Le :attribute field format is invalid.',
    'required' => 'Le :attribute field is required.',
    'required_array_keys' => 'Le :attribute field must contain entries for: :values.',
    'required_if' => 'Le :attribute field is required when :other is :value.',
    'required_if_accepted' => 'Le :attribute field is required when :other is accepted.',
    'required_unless' => 'Le :attribute field is required unless :other is in :values.',
    'required_with' => 'Le :attribute field is required when :values is present.',
    'required_with_all' => 'Le :attribute field is required when :values are present.',
    'required_without' => 'Le :attribute field is required when :values is not present.',
    'required_without_all' => 'Le :attribute field is required when none of :values are present.',
    'same' => 'Le :attribute field must match :other.',
    'size' => [
        'array' => 'Le :attribute field must contain :size items.',
        'file' => 'Le :attribute field must be :size kilobytes.',
        'numeric' => 'Le :attribute field must be :size.',
        'string' => 'Le :attribute field must be :size characters.',
    ],
    'starts_with' => 'Le :attribute field must start with one of the following: :values.',
    'string' => 'Le :attribute field must be a string.',
    'timezone' => 'Le :attribute field must be a valid timezone.',
    'unique' => 'Le :attribute has already been taken.',
    'uploaded' => 'Le :attribute failed to upload.',
    'uppercase' => 'Le :attribute field must be uppercase.',
    'url' => 'Le :attribute field must be a valid URL.',
    'ulid' => 'Le :attribute field must be a valid ULID.',
    'uuid' => 'Le :attribute field must be a valid UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
