<?php
/**
 * @param $name
 * @return mixed
 */
function extension_check($name)
{
    if (!extension_loaded($name)) {
        $response = false;
    } else {
        $response = true;
    }
    return $response;
}

/**
 * @param $name
 * @return mixed
 */
function folder_permission($name)
{
    $perm = substr(sprintf('%o', fileperms($name)), -4);
    if ($perm >= '0755') {
        $response = true;
    } else {
        $response = false;
    }
    return $response;
}

/**
 * @param $mysql_host
 * @param $mysql_database
 * @param $mysql_user
 * @param $mysql_password
 */
function importDatabase($mysql_host, $mysql_database, $mysql_user, $mysql_password)
{
    $db = new PDO("mysql:host=$mysql_host;dbname=$mysql_database", $mysql_user, $mysql_password);

    $query = file_get_contents("database.sql");
    $stmt = $db->prepare($query);
    if ($stmt->execute()) {
        echo "Done";
    } else {
        echo "Not";
    }
}

/**
 * @return mixed
 */
function home_base_url()
{
    $base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
    $tmpURL = dirname(__FILE__);
    $tmpURL = str_replace(chr(92), '/', $tmpURL);
    $tmpURL = str_replace($_SERVER['DOCUMENT_ROOT'], '', $tmpURL);
    $tmpURL = ltrim($tmpURL, '/');
    $tmpURL = rtrim($tmpURL, '/');
    $tmpURL = str_replace('installer', '', $tmpURL);
    $base_url .= $_SERVER['HTTP_HOST'] . '/' . $tmpURL;
    return $base_url;
}

/**
 * @param $name
 * @param $details
 * @param $status
 */
function createTable($name, $details, $status)
{
    if ($status == '1') {
        $pr = '<i class="fa fa-check"><i>';
    } else {
        $pr = '<i class="fa fa-times" style="color:red;"><i>';
    }
    echo "<tr><td>$name</td><td>$details</td><td>$pr</td></tr>";
}

/**
 * @param array $data
 */
function changeEnv($data = [])
{
    if (count($data) > 0) {
        $path = '../../.env';
        $env = file_get_contents($path);
        $env = preg_split('/(\r\n|\r|\n)/', $env);

        foreach ((array) $data as $key => $value) {
            if (preg_match('/\s/', $value) || preg_match('/(#)/', $value)) {
                $value = '"' . $value . '"';
            }
            foreach ($env as $env_key => $env_value) {
                $entry = explode("=", $env_value, 2);
                if ($entry[0] == $key) {
                    $env[$env_key] = $key . "=" . $value;
                } else {
                    $env[$env_key] = $env_value;
                }
            }
        }
        $env = implode("\n", $env);
        file_put_contents($path, $env);
    }
}

$base_url = home_base_url();
if (substr("$base_url", -1 == "/")) {
    $base_url = substr("$base_url", 0, -1);
}

$extensions = [
    'BCMath', 'Ctype', 'cURL', 'DOM', 'Fileinfo', 'JSON', 'Mbstring', 'OpenSSL', 'PCRE', 'PDO', 'Tokenizer', 'pdo_mysql', 'XML', 'GD', 'gmp', 'soap',
];

$folders = [
    '../../public/assets/images',
    '../../bootstrap/cache',
    '../../storage/framework',
    '../../storage/framework/cache',
    '../../storage/framework/sessions',
    '../../storage/framework/views',
    '../../storage/logs',
    '../../storage/app',
    '../../lang',
];
