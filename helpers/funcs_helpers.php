<?php

/**
 * View helper & utility functions
 */

/**
 * Returns true if the $user is allowed to access the $resource
 * $resource is a string that matches an application relative URI path
 * If $user is null, currently logged in user is tested
 * @param string $resource
 * @param mixed $user
 * @return mixed
 */
function allowed($resource, $user = null)
{
    $acl = app('acl');
    return $acl->allowed($resource, $user);
}

/**
 * @param string|null $uwnetid
 * @return \App\Auth\User
 */
function user($uwnetid = null)
{
    return app('UserProvider')->getUser($uwnetid);
}

/**
 * User information transformed for Rollbar.com error logging
 * @see config/logging.php config('logging.channels.rollbar.person_fn')
 * @return array
 */
function user_rollbar()
{
    $u = user();
    return [
        'id' => $u->person_id,
        'username' => $u->uwnetid,
    ];
}

/**
 * True if Carbon instance represents an empty date value
 * @param mixed $carbon
 * @return boolean
 */
function carbonEmpty($carbon)
{
    if ($carbon === null) {
        return true;
    }
    if (!$carbon instanceof \Carbon\Carbon) {
        return ($carbon == 0);
    }
    return ($carbon->year < 1200);
}

/**
 * Escape a value to be safely included in a Comma Separated Variable
 * text file. Values that include commas are enclosed in double quotes.
 * When a quoted value contains a double quote character it is escaped
 * by changing it to two sequential double quote characters.
 * @param $value
 * @return string
 */
function csvQuote($value)
{
    // strip new line characters
    if (strpos($value, "\n") !== false) {
        $value = str_replace("\r", '', $value);
        $value = str_replace("\n", ' ', $value);
    }
    // if it has a comma in it, it needs help
    if (strpos($value,',') !== false) {
        // first escape " with and extra "
        $value = str_replace('"', '""', $value);
        return '"' . $value . '"';
    }
    // the string is safe as is
    return $value;
}

function downloadHref()
{
    $out = $_SERVER['REQUEST_URI'];
    if (strpos($out, 'format=csv')) {
        return $out;
    }
    if (empty($_SERVER['QUERY_STRING'])) {
        return $_SERVER['REQUEST_URI'].'?format=csv';
    }
    return $_SERVER['REQUEST_URI'].'&format=csv';
}

/**
 * Return a budget number with a hyphen
 * @param $value
 * @return string
 */
function eBudget($value)
{
    if (strpos($value, '-') === false) {
        $parts = str_split($value, 2);
        return array_shift($parts).'-'.implode('', $parts);
    }
    return $value;
}

/**
 * Format dates
 * @param $value
 * @param string $format
 * @return bool|string
 */
function eDate($value, $format = 'n/j/Y')
{
    if (empty($value)) {
        return '';
    }
    if ($value instanceof \Carbon\Carbon) {
        if (carbonEmpty($value)) {
            return '';
        }
        return $value->format($format);
    }
    if (is_numeric($value)) {
        return date($format, (int)$value);
    }
    $ts = strtotime($value);
    if ($ts) {
        return date($format, $ts);
    }
    return $value;
}

function eFirstLast($person)
{
    if (empty($person)) {
        return '';
    }

    $person = personLookup($person);
    $out = trim("{$person->getFirst()} {$person->getLast()}");
    return ($out) ?: $person->getIdentifier();
}

function eLastFirst($person)
{
    if (empty($person)) {
        return '';
    }

    $person = personLookup($person);
    $out = trim("{$person->getLast()}, {$person->getFirst()}", " \t\n\r\0\x0B,");
    return ($out === ',') ? $person->getIdentifier() : $out;
}

/**
 * Return escaped $value, if $value is empty return $emptyMsg in .empty HTML markup
 * @param string $value
 * @param string $emptyMsg
 * @return string
 */
function eOrEmpty($value, $emptyMsg = 'missing')
{
    if ($value === null || $value === '') {
        return '<span class="empty">' . e($emptyMsg) . '</span>';
    }
    return e($value);
}

/**
 * Returns true if the $user has or inherits the $role
 * If $user is null, currently logged in user is tested
 * @param string $role
 * @param mixed $user
 * @return mixed
 */
function hasRole($role, $user = null)
{
    $acl = App::make('acl');
    return $acl->hasRole($role, $user);
}

/**
 * Return the rendered input element only
 * @param \App\Core\Forms\Input $input
 * @return string
 */
function input($input)
{
    return app('InputBuilder')->build($input);
}

function checked($value, $optionValue)
{
    if (is_array($value)) {
        return in_array($optionValue, $value) ? ' checked="checked" ' : '';
    }
    return $optionValue == $value ? ' checked="checked" ' : '';
}

function selected($value, $optionValue)
{
    if (is_array($value)) {
        return in_array($optionValue, $value) ? ' selected="selected" ' : '';
    }
    return $optionValue == $value ? ' selected="selected" ' : '';
}

function optionId($id, $value)
{
    return \Illuminate\Support\Str::snake("{$id}_{$value}");
}

/**
 * @param mixed $value
 * @return App\Contracts\HasNames
 */
function personLookup($value)
{
    return app('PersonLookup')->toPerson($value);
}

/**
 * String SQL statement from include file
 * If $substitutions is an associative array [ KEY => VALUE ]
 * each instance of KEY in SQL will be replaced with VALUE
 * @param string $filename
 * @param array $substitutions
 * @return string
 */
function sqlInclude($filename, $substitutions = [])
{
    $sql = file_get_contents($filename);
    foreach ($substitutions as $placeholder => $value) {
        $sql = str_replace($placeholder, $value, $sql);
    }
    return $sql;
}

/**
 * Return a View Response object with "text/csv" header set
 * Use in Controller actions instead of Laravel view() helper for csv downloads for proper handling on MacOS
 * @param $view
 * @param array $data
 * @param int $status
 * @param array $headers
 * @return \Illuminate\Http\Response
 */
function viewCsv($view, $data = [], $status = 200, array $headers = [])
{
    return response()->view($view, $data, $status, $headers)->header('Content-Type', 'text/csv');
}

/**
 * Output an array of data as a comma separated variable row
 * @param array $data
 */
function echoCsvRow(array $data)
{
    $first = true;
    foreach ($data as $value) {
        if (!$first) {
            echo ',';
        }
        $first = false;
        echo csvQuote($value);
    }
    echo PHP_EOL;
}

/**
 * True if HTTP request contains format=csv
 * This is our application standard for request report view download as spreadsheet
 * @return boolean
 */
function wantsCsv()
{
    return request('format') == 'csv';
}

/**
 * Output a report as a CSV text file download
 * @param string $filename
 * @param array|Traversable $report
 * @param \App\Core\RowBuilder\AbstractRowBuilder $builder
 * @throws Exception
 */
function renderCsv($filename, $report, \App\Core\RowBuilder\AbstractRowBuilder $builder)
{
    if (!is_array($report) && !$report instanceof \Traversable) {
        throw new \Exception('Report must be an array or Traversable');
    }
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Expires: 0');
    header('Pragma: public');
    header('Content-type: text/csv');
    header('Content-Disposition: attachment; filename='.$filename);
    echoCsvRow($builder->headers());
    foreach ($report as $row) {
        echoCsvRow($builder->build($row));
    }
}

function clamp($val, $min, $max)
{
    if ($val < $min) {
        return $min;
    } elseif ($val > $max) {
        return $max;
    }

    return $val;
}

function setting($name)
{
    return app('App\Utilities\SettingsCache')->get($name);
}
