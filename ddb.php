<?php
//session_start();
/* Connect Database */
require_once 'config.php';

$host = DB_HOST;
$user = DB_USER;
$passwd = DB_PASSWORD;
$dbn = DB_NAME;

$pdo = NULL;
$mdb = 'mysql:host=' . $host . ';dbname=' . $dbn;

$pdo = null;

try
{
    $pdo = new PDO($mdb, $user, $passwd);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    /* If there is an error, an exception is thrown. */
    echo 'Database connection failed.' . $e;
    die();
}

function getUser($b, $pdo) {
    $query = 'SELECT * FROM users WHERE (user_email = :umail)';
    $values = [':umail' => $b];
    try {
        $res = $pdo->prepare($query);
        $res->execute($values);
    }catch (PDOException $e) {
        /* Query error. */
        echo 'error' . $e;
        die();
    }

    return $res->fetch(PDO::FETCH_ASSOC);
}

function gettempUser($b, $pdo) {
    $query = 'SELECT * FROM tempaccounts WHERE (user_email = :umail)';
    $values = [':umail' => $b];
    try {
        $res = $pdo->prepare($query);
        $res->execute($values);
    }catch (PDOException $e) {
        /* Query error. */
        echo 'error' . $e;
        die();
    }
    return $res->fetch(PDO::FETCH_ASSOC);
}


function checkMail($e, $pdo){
    $row = getUser($e, $pdo);
    if(!empty($row)){
        define('USERPASS', $row['user_pass']);
        define('USEREMAIL', $row['user_email']);
        define('USERLEVEL', $row['user_level']);
        define('USERDISPLAYNAME', $row['display_name']);
        define('USERID', $row['id']);
        define('USERFNAME', $row['firstname']);
        define('USERLNAME', $row['lastname']);
        return '2';
    }
    return '0';
}

function authUser($e, $p, $pdo){
    if (checkMail($e,$pdo)=='2') {
        if (password_verify($p, USERPASS)) {
            $_SESSION['userid'] = USERID;
            $_SESSION['username'] = USERDISPLAYNAME;
            $_SESSION['userlevel'] = USERLEVEL;
            $_SESSION['userfname'] = USERFNAME;
            $_SESSION['useremail'] = USEREMAIL;
            $_SESSION['userlname'] = USERLNAME;
            return true;
        }        
    }
    return false;
}

function getUserById($b, $pdo) {
    $query = 'SELECT * FROM users WHERE (ID = :umail)';
    $values = [':umail' => $b];
    try {
        $res = $pdo->prepare($query);
        $res->execute($values);
    }catch (PDOException $e) {
        /* Query error. */
        echo 'error' . $e;
        die();
    }

    return $res->fetch(PDO::FETCH_ASSOC);
}

function putAdmin($fn, $ln, $dsp, $em, $pwd, $pdo) {
    $dsp = ($dsp==NULL) ? $fn : $dsp;
    $pwd = password_hash($pwd, PASSWORD_DEFAULT);
    $level = 2;
    $query = 'INSERT INTO users (firstname, lastname,  display_name, user_email, user_pass, user_level) VALUES (:fn, :ln, :dsp, :email, :passwd, :level)';
    $values = [
        ':level' => $level,
        ':passwd' => $pwd,
        ':fn' => $fn,
        ':dsp' => $dsp,
        ':ln' => $ln,
        ':tel' => $tel,
        ':email' => $em
    ];
    try
    {
        $res = $pdo->prepare($query);
        $res->execute($values);
    }
    catch (PDOException $e) {
        /* Query error. */
        echo 'Error, User May Already Exist' . $e;
        die();
    }


}

function putUser($fn, $ln, $dsp, $em, $pwd, $level, $pdo) {
    $dsp = ($dsp==NULL) ? $fn : $dsp;
    $pwd = password_hash($pwd, PASSWORD_DEFAULT);
    $query = 'INSERT INTO users (firstname, lastname,  display_name, user_email, user_pass, user_level) VALUES (:fn, :ln, :dsp, :email, :passwd, :level)';
    $values = [
        ':level' => $level,
        ':passwd' => $pwd,
        ':fn' => $fn,
        ':dsp' => $dsp,
        ':ln' => $ln,
        ':email' => $em
    ];
    try
    {
        $res = $pdo->prepare($query);
        $res->execute($values);
    }
    catch (PDOException $e) {
        /* Query error. */
        echo 'Error, User May Already Exist' . $e;
        die();
    }


}


function puttempUser($fn, $mn, $ln, $em, $tel, $pwd, $code, $pdo) {
    $query = 'INSERT INTO tempaccounts (firstname,  middlename, lastname, email, phone, pwd, code) VALUES ( :fn, :mn, :ln, :email, :tel, :pwd, :code)';
    $values = [
        ':code' => $code,
        ':fn' => $fn, ':mn' => $mn, ':ln' => $ln, ':tel' => $tel,
        ':email' => $em, ':pwd'=> $pwd];
    try
    {
        $res = $pdo->prepare($query);
        $res->execute($values);
    }
    catch (PDOException $e) {
    
    $query = 'UPDATE tempaccounts SET firstname = :fn, middlename = :mn, lastname = :ln, phone = :tel, pwd = :pwd, code = :code WHERE  email = :email';
    $values = [
        ':code' => $code,
        ':fn' => $fn, ':mn' => $mn,
':ln' => $ln, ':tel' => $tel,
        ':email' => $em, ':pwd' => $pwd];
        
        try
    {
        $res = $pdo->prepare($query);
        $res->execute($values);
    }
    catch (PDOException $e) {
        echo 'Error Occurred, Please Return to homepage and try again ' ;
    die();
    }
    
    }


}

function updateUserDetails($id, $fn, $ln, $dsp, $em, $pdo) {
    
    if (!LOGDIN || (!ISADMIN && $id!=USERID)) {
        echo 'Operation Denied for this user';
        die();
    }

    $query = 'UPDATE users SET firstname = :fn, lastname = :ln, display_name = :dsp, user_email = :email WHERE id = :id';

    $values = [
        ':fn' => $fn,
        ':dsp' => $dsp,
        ':ln' => $ln,
        ':email' => $em,
        ':id' => $id
    ];
    try
    {
        $res = $pdo->prepare($query);
        $res->execute($values);
        checkMail(USEREMAIL, $pdo);
        $_SESSION['userid'] = USERID;
        $_SESSION['username'] = USERDISPLAYNAME;
        $_SESSION['userlevel'] = USERLEVEL;
        $_SESSION['userfname'] = USERFNAME;
        $_SESSION['useremail'] = USEREMAIL;
        $_SESSION['userlname'] = USERLNAME;
    }
    catch (PDOException $e) {
        /* Query error. */
        echo 'Query error.' . $e;
        die();
    }
}

function updatePassword($id, $pwd, $pdo) {
    if (!LOGDIN || (!ISADMIN && $id!=USERID)) {
        echo 'Operation Denied for this user';
        die();
    }
    $pwd = password_hash($pwd, PASSWORD_DEFAULT);
    $query = 'UPDATE users SET user_pass = :passwd WHERE id = :uid';

    $values = [':uid' => $id,
        ':passwd' => $pwd];
    try
    {
        $res = $pdo->prepare($query);
        $res->execute($values);
    }
    catch (PDOException $e) {
        /* Query error. */
        echo 'Query error.' . $e;
        die();
    }


}


function putBuilding($name, $desc, $lat, $lng, $image, $pdo){

    $query = 'INSERT INTO buildings ( building_name, building_desc, lat, lng, building_image ) VALUES (:name, :desc, :lat, :lng, :image)';
    
    $values = [
        ':desc' => $desc,
        ':name' => $name,
        ':lat' => $lat,
        ':lng' => $lng,
        ':image' => $image
        ];
    try
    {
        $res = $pdo->prepare($query);
        $res->execute($values);
    }
    catch (PDOException $e) {
        /* Query error. */
        echo 'Query error.' . $e;
        die();
    }

}

function updateBuilding($id, $name, $desc, $lat, $lng, $image, $pdo) {
    
    if (!LOGDIN || USERLEVEL!=2) {
        echo 'Operation Denied for this user';
        die();
    }
    $query = 'UPDATE buildings SET building_name = :name, building_desc = :desc, lat = :lat, lng = :lng, building_image = :image WHERE id = :id';

    $values = [
        ':desc' => $desc,
        ':name' => $name,
        ':lat' => $lat,
        ':lng' => $lng,
        ':image' => $image,
        ':id' => $id
        ];
    try
    {
        $res = $pdo->prepare($query);
        $res->execute($values);
    }
    catch (PDOException $e) {
        /* Query error. */
        echo 'Query error.' . $e;
        die();
    }

}

function removeBuilding($id, $pdo) {
    if (!LOGDIN || USERLEVEL!=2 ) {
        echo 'Operation Denied for this user';
        die();
    }

    $query = 'DELETE FROM buildings WHERE id = :id';

    $values = [
        ':id' => $id
        ];
    try
    {
        $res = $pdo->prepare($query);
        $res->execute($values);
    }
    catch (PDOException $e) {
        /* Query error. */
        echo 'Query error.' . $e;
        die();
    }

}

function getAllBuildings($pdo){
    $query = 'SELECT id, building_name, building_desc, lat, lng, building_image FROM buildings ORDER BY building_name ASC';
    try {
        $res = $pdo->prepare($query);
        $res->execute();
    }catch (PDOException $e) {
        /* Query error. */
        echo 'error' . $e;
        die();
    }

    return $res->fetchAll(PDO::FETCH_ASSOC);

}

function putOffice($name, $desc, $lat, $lng, $building, $guide, $image, $pdo){

    $query = 'INSERT INTO offices ( office_name, office_desc, lat, lng, building, guide, office_image ) VALUES (:name, :desc, :lat, :lng, :building, :guide,:image)';
    
    $values = [
        ':desc' => $desc,
        ':name' => $name,
        ':lat' => $lat,
        ':lng' => $lng,
        ':building' => $building,
        ':guide' => $guide,
        ':image' => $image
        ];
    try
    {
        $res = $pdo->prepare($query);
        $res->execute($values);
    }
    catch (PDOException $e) {
        /* Query error. */
        echo 'Query error.' . $e;
        die();
    }

}

function updateOffice($id, $name, $desc, $lat, $lng, $building, $guide, $image, $pdo) {
    
    if (!LOGDIN || USERLEVEL!=2) {
        echo 'Operation Denied for this user';
        die();
    }
    $query = 'UPDATE offices SET office_name = :name, office_desc = :desc, lat = :lat, lng = :lng, building = :building, guide = :guide, office_image = :image WHERE id = :id';

    $values = [
        ':desc' => $desc,
        ':name' => $name,
        ':lat' => $lat,
        ':lng' => $lng,
        ':building' => $building,
        ':guide' => $guide,
        ':image' => $image,
        ':id' => $id
        ];
    try
    {
        $res = $pdo->prepare($query);
        $res->execute($values);
    }
    catch (PDOException $e) {
        /* Query error. */
        echo 'Query error.' . $e;
        die();
    }

}

function removeOffice($id, $pdo) {
    if (!LOGDIN || USERLEVEL!=2 ) {
        echo 'Operation Denied for this user';
        die();
    }

    $query = 'DELETE FROM offices WHERE id = :id';

    $values = [
        ':id' => $id
        ];
    try
    {
        $res = $pdo->prepare($query);
        $res->execute($values);
    }
    catch (PDOException $e) {
        /* Query error. */
        echo 'Query error.' . $e;
        die();
    }

}

function getAllOffices($pdo){
    $query = 'SELECT id, office_name, office_desc, lat, lng, building, guide, office_image FROM offices ORDER BY office_name ASC';
    try {
        $res = $pdo->prepare($query);
        $res->execute();
    }catch (PDOException $e) {
        /* Query error. */
        echo 'error' . $e;
        die();
    }

    return $res->fetchAll(PDO::FETCH_ASSOC);

}



/*
function createtables($pdo){
    $query = 'CREATE TABLE account` ( `userID` VARCHAR(65) NOT NULL , `firstname` VARCHAR(65) NOT NULL , `middlename` VARCHAR(65) NOT NULL , `lastname` VARCHAR(65) NOT NULL , `email` VARCHAR(65) NOT NULL , `phone` VARCHAR(65) NOT NULL , `pwd` VARCHAR(65) NOT NULL , `null1` INT NULL , `null2` INT NULL , `null3` INT NULL ) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_bin;

}
*/


?>