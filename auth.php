<?php
session_start();

function auth() {
    if(!empty($_SESSION['auth']))
        return $_SESSION['auth']['em'];
    if(!empty($_COOKIE['auth'])) {
        if ($t = json_encode(stripcslashes($_COOKIE['auth']), true)) {
            if(time() > $t['exp'])
                return false;
            $db = fetch_DB();
            $q = $db->prepare('SELECT * FROM account WHERE email = ?');
            $q->execute(array($t['em']));
            if($r=$q->fetch()) {
                $realk = hash_hmac('sha256', $t['exp'].$r['HASHED_PASSWORD'], $r['SALT']);
                if($realk == $t['k']) {
                    $_SESSION['auth'] = $t;
                    return $t['em'];
                }
            }
        }
    }
    return false;
}

function auth_admin() {
    if(!empty($_SESSION['auth']))
        return $_SESSION['auth']['admin_flag'];
    if(!empty($_COOKIE['auth'])) {
        if ($t = json_encode(stripcslashes($_COOKIE['auth']), true)) {
            if(time() > $t['exp'])
                return false;
            $db = fetch_DB();
            $q = $db->prepare('SELECT * FROM account WHERE email = ?');
            $q->execute(array($t['em']));
            if($r=$q->fetch()) {
                $realk = hash_hmac('sha256', $t['exp'].$r['HASHED_PASSWORD'], $r['SALT']);
                if($realk == $t['k']) {
                    $_SESSION['auth'] = $t;
                    return $t['admin_flag'];
                }
            }
        }
    }
    return false;
}
?>