<?php
/*
* app/validators.php
*/

Validator::extend('alpha_spaces', function($attribute, $value)
{
	return preg_match('/^[\pL\s]+$/u', $value);
});

Validator::extend('alpha_num_dash', function($attribute, $value)
{
	return preg_match('/^[á-úÁ-Úa-zA-ZñÑüÜ0-9-_]+$/', $value);
});

Validator::extend('alpha_num_ampersand', function($attribute, $value)
{
	return preg_match('/^[á-úÁ-Úa-zA-ZñÑüÜ0-9-&., ]+$/', $value);
});

Validator::extend('alpha_num_spaces', function($attribute, $value)
{
	return preg_match('/^[á-úÁ-Úa-zA-ZñÑüÜ0-9- _.,]+$/', $value);
});


Validator::extend('alpha_num_spaces_colon', function($attribute, $value)
{
	return preg_match('/^[á-úÁ-Úa-zA-ZñÑüÜ0-9- :_.,]+$/', $value);
});

Validator::extend('alpha_num_spaces_slash_dash', function($attribute, $value)
{
	return preg_match('/^[á-úÁ-Úa-zA-ZñÑüÜ0-9- :\/_.,]+$/', $value);
});

Validator::extend('alpha_num_spaces_slash_dash_enter', function($attribute, $value)
{
	return preg_match('/^[á-úÁ-Úa-zA-ZñÑüÜ0-9-\n- :_.,]+$/', $value);
});
