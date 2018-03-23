# zHook

Simple lightweight (WordPress-like) php functions for working with hooks.

This functions provide work with two types of hooks:
- filters - for changing the transferred value;
- actions - for performing actions at a certain moment.

Hooks stored in array **$hooks** with structure as below:

```
array {
  'hook_name' => 
    array {
      0 => 
        array {
          'function' => string 'some_function'
          'priority' => int 10
          'accepted_args' => int 0
        }
    }
  'other_hook' => 
    array {
      0 => 
        array {
          'function' => string 'another_function'
          'priority' => int 0
          'accepted_args' => int 1
        }
      1 => 
        array {
          'function' => string 'yet_another_function'
          'priority' => int 50
          'accepted_args' => int 1
        }
    }
}
```

## Filters functions

### add_filter()

`add_filter($name, $function, $priority, $accepted_args)`

Attaches the specified PHP function to the specified filter hook. So, when the filter is triggered, the value will be processed by the specified PHP function.

**Args:**

- `$name (string | required)` - hook name;
- `$function (string | required)` - function name for hook;
- `$priority (integer | default: 10)` - function priority;
- `$accepted_args (integer | default: 1)` - num of accepted args for function.

**Returns:** `true` if filter added, `false` if filter not added.

**Usage:**

```php
function my_function($data, $some_var) {
	$data .= 'some text' . $some_var;
	return $data;
}
add_filter('hook_name', 'my_function', 10, 2);
```

### apply_filters()

`apply_filters($name, $data, $some_var_1, $some_var_2 ... $some_var_n)`

Applies the function attached to the specified PHP filter. The function is attached using **add_filter()**. Used when you need to change the value of a variable (for example text).

**Args:**

- `$name (string | required)` - hook name;
- `$data (mixed | required)` - input data;
- `$some_var_* (mixed)` - additional custom vars.

**Returns:** filtered data.

**Usage:**

```php
echo apply_filters('hook_name', $text, $some_var);
```

### remove_filter()

`remove_filter($name, $function)`

Removes the specified function attached to the specified filter.

**Args:**

- `$name (string | required)` - hook name;
- `$function (string | required)` - function name for hook.

**Returns:** `true` if filter deleted, `false` if filter not deleted (not isset in hooks array).

**Usage:**

```php
remove_filter('hook_name', 'my_function');
```

Notice: You can't delete a filter before it was added!

## Actions functions

### add_action()

`add_action($name, $function, $priority, $accepted_args)`

Registers a hook event. When registering, a PHP function is specified that will be triggered at the time of the event, which is called using **do_action()**.

**Args:**

- `$name (string | required)` - hook name;
- `$function (string | required)` - function name for hook;
- `$priority (integer | default: 10)` - function priority;
- `$accepted_args (integer | default: 0)` - num of accepted args for function

**Returns:** `true` if action added, `false` if action not added.

**Usage:**

```php
function my_function($some_var) {
	echo 'Some text' . $some_var;
}
add_action('hook_name', 'my_function', 10, 1);
```

### do_action()

`do_action($name, $some_var_1, $some_var_2 ... $some_var_n)`

Creates an event (hook for an arbitrary function). For the function to work at the time of the event, it must be connected to this event using the **add_action()** function.

**Args:**

- `$name (string | required)` - hook name;
- `$some_var_* (mixed)` - additional custom vars.

**Returns:** nothing or result of actions.

**Usage:**

```php
do_action('hook_name', $some_var);
```

### remove_action()

`remove_action($name, $function)`

Removes the specified function attached to the specified action.

**Args:**

- `$name (string | required)` - hook name;
- `$function (string | required)` - function name for hook.

**Returns:** `true` if action deleted, `false` if action not deleted (not isset in hooks array).

**Usage:**

```php
remove_action('hook_name', 'my_function');
```

Notice: You can't delete action before it was added!
