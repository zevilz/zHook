<?php

/**
 * Add filter to hooks array
 *
 * @param string $name Hook name
 * @param string $function Function name for hook
 * @param int $priority Function priority
 * @param int $accepted_args Num of accepted args for function
 *
 * @return bool Result
 */
function add_filter($name, $function, $priority = 10, $accepted_args = 1) {
	global $hooks;
	$function_isset = false;
	if (isset($hooks[$name])) {
		foreach ($hooks[$name] as $hook) {
			if ($hook['function'] == $function) {
				$function_isset = true;
			}
		}
	}
	if (!$function_isset) {
		if (!preg_match("|^[\d]+$|", $priority)) {
			$priority = 10;
		}
		if (!preg_match("|^[\d]+$|", $accepted_args)) {
			$accepted_args = 1;
		}
		$hooks[$name][] = [
			'function' => $function,
			'priority' => $priority,
			'accepted_args' => $accepted_args
		];
		return true;
	}
	else {
		return false;
	}
}

/**
 * Add action to hooks array
 *
 * @param string $name Hook name
 * @param string $function Function name for hook
 * @param int $priority Function priority
 * @param int $accepted_args Num of accepted args for function
 *
 * @return bool Result
 */
function add_action($name, $function, $priority = 10, $accepted_args = 0) {
	return add_filter($name, $function, $priority, $accepted_args);
}

/**
 * Apply hook filters
 *
 * @param string $name Hook name
 * @param mixed $data Input data
 *
 * @return mixed Filtered input data
 */
function apply_filters($name, $data) {
	global $hooks;
	if (isset($hooks[$name])) {
		usort($hooks[$name], function($a, $b){
			return ($a['priority'] - $b['priority']);
		});
		$args = func_get_args();
		array_shift($args);
		$num_args = count($args);
		foreach ($hooks[$name] as $hook) {
			if ($hook['accepted_args'] == 0) {
				$data = call_user_func_array($hook['function'], []);
			}
			elseif ($hook['accepted_args'] >= $num_args) {
				$data = call_user_func_array($hook['function'], $args);
			}
			else {
				$data = call_user_func_array($hook['function'], array_slice($args, 0, (int)$hook['accepted_args']));
			}
		}
	}
	return $data;
}

/**
 * Do hook actions
 *
 * @param string $name Hook name
 *
 * @return mixed Action output
 */
function do_action($name) {
	$args = func_get_args();
	if (count($args) == 1) {
		$args[] = '';
	}
	return call_user_func_array('apply_filters', $args);
}

/**
 * Remove filter by hook name and function
 *
 * @param string $name Hook name
 * @param string $function Function name for hook
 *
 * @return bool Result
 */
function remove_filter($name, $function) {
	$result = false;
	global $hooks;
	if (isset($hooks[$name])) {
		foreach ($hooks[$name] as $k=>$hook_item) {
			if ($hook_item['function'] == $function) {
				unset($hooks[$name][$k]);
				$result = true;
			}
		}
		if (count($hooks[$name]) == 0) {
			unset($hooks[$name]);
		}
	}
	return $result;
}

/**
 * Remove action by hook name and function
 *
 * @param string $name Hook name
 * @param string $function Function name for hook
 *
 * @return bool Result
 */
function remove_action($name, $function) {
	return remove_filter($name, $function);
}
