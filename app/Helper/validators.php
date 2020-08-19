<?php

/** @var \Illuminate\Validation\Factory $validator */

Validator::extend(
    'valid_password',
    function ($attribute, $value, $parameters)
    {
        return Hash::check( $value, Auth::user()->password );
    }
);

Validator::extend(
    'greater_than',
    function ($attribute, $value, $parameters) {
        return ((Request::input($parameters[0])) < $value);
    }
);

Validator::replacer('greater_than', function ($message, $attribute, $rule, $parameters) {
    return str_replace(':number', toWord($parameters[0]), $message);
});

Validator::extend(
    'greater_than_equal_to',
    function ($attribute, $value, $parameters) {
        return ((Request::input($parameters[0])) <= $value);
    }
);

Validator::replacer('greater_than_equal_to', function ($message, $attribute, $rule, $parameters) {
    return str_replace(':number', toWord($parameters[0]), $message);
});

Validator::extend(
    'less_than',
    function ($attribute, $value, $parameters) {
        return ((Request::input($parameters[0])) >= $value);
    }
);

Validator::replacer('less_than', function ($message, $attribute, $rule, $parameters) {
    return str_replace(':number', toWord($parameters[0]), $message);
});

Validator::extend(
    'less_than_equal_to',
    function ($attribute, $value, $parameters) {
        return ((Request::input($parameters[0])) >= $value);
    }
);

Validator::replacer('less_than_equal_to', function ($message, $attribute, $rule, $parameters) {
    return str_replace(':number', toWord($parameters[0]), $message);
});