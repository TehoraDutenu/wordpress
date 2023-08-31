<?php
// récup du tableau de lando info
$landoInfo =
    json_decode(getenv('LANDO_INFO'), true);

// récup du web root
$landoWebroot = getenv('LANDO_WEBROOT');

// récup url définit par lando
$url = $landoInfo['appserver']['urls'][0];

// récupérer les creds de l'accès base
$dbServer =
    $landoInfo['database']['internal_connection']['host'];
$dbName = $landoInfo['database']['creds']['database'];
$dbUser = $landoInfo['database']['creds']['user'];
$dbPassword = $landoInfo['database']['creds']['password'];

// tableau de commande à executer pour le téléchargement de WP
$cmd = [
    "cd $landoWebroot;",
    "wp core download"
];

$installScript = implode(' ', $cmd);
shell_exec($installScript);

// tableau de commande configuration de WP
$cmd = [
    "cd $landoWebroot;",
    "wp core config",
    "--dbprefix=wp_",
    "--dbhost=$dbServer",
    "--dbname=$dbName",
    "--dbuser=$dbUser",
    "--dbpass=$dbPassword",
];
$installScript = implode(' ', $cmd);
shell_exec($installScript);

// tableau de commande pour finir la config WP
$cmd = [
    "cd $landoWebroot;",
    "wp core install",
    "--url='$url'",
    "--title='Mon TP'",
    "--admin_user='admin'",
    "--admin_password='admin'",
    "--admin_email='tehoradutenu@gmail.com'",
];
$installScript = implode(' ', $cmd);
shell_exec($installScript);
