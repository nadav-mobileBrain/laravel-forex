<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Softwarezon Script Installer</title>
    <link rel="apple-touch-icon-precomposed" sizes="144x144"
        href="http://www.softwarezon.com/assets/images/favicon.png">
    <link rel="shortcut icon" href="http://www.softwarezon.com/assets/images/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/style.css">
    <script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
</head>

<body>

    <!-- First Section Start -->
    <section class="section-padding" id="section-first">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <header>
                        <div class="section-header d-flex align-items-center justify-content-between">
                            <div class="logo">
                                <img src="https://softwarezon.com/assets/images/logo.png" alt="">
                            </div>
                            <div class="support">
                                <button type="button" class="btn btn-primary btn-lg text-uppercase" data-toggle="modal"
                                    data-target="#supportModal"><i class="fa fa-handshake"></i> Get Support</button>
                            </div>
                        </div>
                    </header>
                    <?php
error_reporting(0);
include 'helpers.php';

if (isset($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = "";
}

if ($action == 'install') {
    ?>
                    <div class="step-installer first-installer second-installer third-installer">
                        <div class="installer-header">
                            <h1 class="text-center text-uppercase">Result</h1>
                        </div>
                        <div class="installer-content">
                            <?php
if ($_POST) {
        try {
            $user = $_POST['user'];
            $code = $_POST['code'];
            $db_name = $_POST['db_name'];
            $db_host = $_POST['db_host'];
            $db_port = $_POST['db_port'];
            $db_user = $_POST['db_user'];
            $db_pass = $_POST['db_pass'];
            $dbh = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://softwarezon.com/purchase-verify?code=$code&name=$user&url=$base_url");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
            curl_setopt($ch, CURLOPT_USERAGENT, $agent);
            $rr = curl_exec($ch);
            curl_close($ch);

            $status = json_decode($rr, true);
            if ($status['error'] == 1) {
                echo "<h2 class='text-center' style='color:red;'>{$status['message']}<h2>";
            } else {
                $query = file_get_contents("database.sql");
                $stmt = $dbh->prepare($query);
                $stmt->execute();
                echo '<div style="text-align:center; text-transform:uppercase;">
                                              <h1>Successfully Installed</h1><br>
                                              <a href="' . $base_url . '" class="btn btn-success btn-sm">Go to Website</a> <br>
                                              <br><br><h4 class="text-center text-danger">Now Delete The Installer Folder.</h4><br><br><br></div>';
                $key = 'base64:' . base64_encode(random_bytes(32));
                changeEnv([
                    'APP_ENV'        => 'production',
                    'APP_KEY'        => $key,
                    'APP_URL'        => $base_url,
                    'APP_DEBUG'      => "false",
                    'APP_INSTALL'    => "true",
                    'DB_HOST'        => $db_host,
                    'DB_PORT'        => $db_port,
                    'DB_DATABASE'    => $db_name,
                    'DB_USERNAME'    => $db_user,
                    'DB_PASSWORD'    => $db_pass,
                    'BUYER_USERNAME' => $user,
                    'BUYER_USERNAME' => $code,
                ]);
            }

        } catch (PDOException $ex) {
            echo "<h2 class='text-center' style='color:red;'>Please Check Your Database Credential!<h2>";
        }
    }
    ?>
                        </div>
                    </div>
                    <?php
} elseif ($action == 'config') {
    ?>
                    <div class="step-installer first-installer second-installer third-installer">
                        <div class="installer-header">
                            <h1 class="text-center text-uppercase">Installation</h1>
                        </div>
                        <div class="installer-content">
                            <form action="?action=install" method="post">
                                <div class="form-group">
                                    <input class="form-control" name="app_url" value="<?php echo $base_url; ?>"
                                        type="text">
                                </div>
                                <h5 class="text-center mb-3">Database Connection</h5>
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <input type="text" name="db_name" id="db_name" value=""
                                            placeholder="Database Name" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <input type="text" name="db_host" id="db_host" value=""
                                            placeholder="Database Host" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <input type="number" name="db_port" id="db_port" value=""
                                            placeholder="Database Port" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <input type="text" name="db_user" id="db_username" value=""
                                            placeholder="Database Username" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input type="text" name="db_pass" id="db_password" value=""
                                            placeholder="Database Password" class="form-control">
                                    </div>
                                </div>
                                <h5 class="text-center mb-3">Purchase Verification</h5>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <div class="input-group">
                                            <input type="text" name="client_name" id="client_name" value=""
                                                placeholder="Full Name" class="form-control" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="basic-addon2"><i
                                                        class="far fa-id-badge"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <div class="input-group">
                                            <input type="email" name="client_email" id="client_email" value=""
                                                placeholder="Email Address" class="form-control" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="basic-addon2"><i
                                                        class="fas fa-at"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <div class="input-group">
                                            <input type="text" name="user" id="client_username" value=""
                                                placeholder="License Username" class="form-control" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="basic-addon2"><i
                                                        class="fas fa-underline"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <div class="input-group">
                                            <input type="type" name="code" id="client_code" value=""
                                                placeholder="Purchase Code" class="form-control" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="basic-addon2"><i
                                                        class="fas fa-code"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary btn-lg btn-block" type="submit">INSTALL NOW</button>
                            </form>
                        </div>
                    </div>
                    <?php
} elseif ($action == 'requirements') {
    ?>
                    <div class="step-installer first-installer second-installer">
                        <div class="installer-header">
                            <h1 class="text-center text-uppercase">Server Requirements</h1>
                        </div>
                        <div class="installer-content table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Requirement</th>
                                        <th>Message</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
$error = 0;
    $phpversion = version_compare(PHP_VERSION, '8.0', '>=');
    if ($phpversion == true) {
        $error = $error + 0;
        createTable("PHP", "Required PHP version 8.0 or higher", 1);
    } else {
        $error = $error + 1;
        createTable("PHP", "Required PHP version 8.0 or higher", 0);
    }
    foreach ($extensions as $key) {
        $extension = extension_check($key);
        if ($extension == true) {
            $error = $error + 0;
            createTable($key, "Required " . strtoupper($key) . " PHP Extension", 1);
        } else {
            $error = $error + 1;
            createTable($key, "Required " . strtoupper($key) . " PHP Extension", 0);
        }
    }
    foreach ($folders as $key) {
        $folder_perm = folder_permission($key);
        if ($folder_perm == true) {
            $error = $error + 0;
            createTable(str_replace("../", "", $key), " Required permission: 0755 ", 1);
        } else {
            $error = $error + 1;
            createTable(str_replace("../", "", $key), " Required permission: 0755 ", 0);
        }
    }
    $envCheck = is_writable('../../.env');
    //$envCheck = true;
    if ($envCheck == true) {
        $error = $error + 0;
        createTable('env', " Required .env to be writable", 1);
    } else {
        $error = $error + 1;
        createTable('env', " Required .env to be writable", 0);
    }
    $database = file_exists('database.sql');
    if ($database == true) {
        $error = $error + 0;
        createTable('Database', "  Required database.sql available", 1);
    } else {
        $error = $error + 1;
        createTable('Database', " Required database.sql available", 0);
    }
    echo '</tbody></table><div class="button float-right">';
    if ($error == 0) {
        echo '<a class="btn btn-primary anchor" href="?action=config">Next Step <i class="fa fa-angle-double-right"></i></a>';
    } else {
        echo '<a class="btn btn-info anchor" href="?action=requirements">Check Again <i class="fa fa-sync-alt"></i></a>';
    }
    ?>
                        </div>
                    </div>
                </div>
                <?php
} else {
    ?>
                <div class="step-installer first-installer">
                    <div class="installer-header text-center text-uppercase">
                        <h1> Terms of use</h1>
                    </div>
                    <div class="installer-content">
                        <h5>Regular License to be used on one (1) domain only !</h5>
                        <h5>If you want to use it on multiple websites / domains you have to purchase more licenses</h5>
                        <div class="mt-4">
                            <h4>YOU CAN:</h4>
                            <div class="">
                                <i class="fa fa-check"></i> Use on one (1) domain only.<br>
                                <i class="fa fa-check"></i> Modify or edit as you want.<br>
                                <i class="fa fa-check"></i> Translate language as you want.<br>
                            </div>
                        </div>
                        <div class="mt-4">
                            <h4>YOU CAN NOT:</h4>
                            <div class="">
                                <i class="fa fa-times" style="color:red;"></i> Resell, distribute, give away or trade by
                                any
                                means to any third party or individual without permission.<br>
                                <i class="fa fa-times" style="color:red;"></i> Include this product into other products
                                sold
                                on Envato market and its affiliate websites.<br>
                                <i class="fa fa-times" style="color:red;"></i> Use on more than one (1) domain.<br>
                            </div>
                        </div>
                        <p class="mt-4">
                            For more information, Please Check <a href="https://codecanyon.net/licenses/terms/regular"
                                target="_blank">Envato License FAQ </a>.
                        </p>
                        <div class="button float-right">
                            <a class="btn btn-primary anchor" href="?action=requirements">I agreed. Next Step <i
                                    class="fa fa-angle-double-right"></i></a>
                        </div>
                    </div>
                </div>
                <?php
}
?>
            </div>
        </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="supportModal" tabindex="-1" role="dialog" aria-labelledby="supportModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content text-success">
                <div class="modal-header">
                    <h5 class="modal-title" id="supportModalLabel"><i class="fas fa-handshake"></i> Support Center</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-success">
                    <h5 class="text-center text-success mt-2">
                        We always try to provide premium support to our buyers. <br>
                        Just knock us on the below medium we will try to reply to you ASAP.
                    </h5>
                    <table class="table table-bordered table-striped mt-4">
                        <thead>
                            <tr>
                                <th width="35%" class="text-right">Medium</th>
                                <th>Communication Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-right">Whatsapp</td>
                                <td>
                                    <a
                                        href="https://api.whatsapp.com/send?phone=8801571118839&amp;text=Hi%20Softwarezon%20support!">+8801571118839</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right">Telegram</td>
                                <td>
                                    <a href="https://t.me/softwarezon">@softwarezon</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right">Skype ID</td>
                                <td>
                                    <a href="skype:softwarezon?chat">live:softwarezon</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right">Discord</td>
                                <td>
                                    softwarezon#3326
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right">Email Address</td>
                                <td>
                                    <a href="mailto:softwarezon@hotmail.com">softwarezon@hotmail.com</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"
        integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous">
    </script>
</body>

</html>