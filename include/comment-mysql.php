<?php
class DB {
  private $pdo = null;
  private $stmt = null;

  function __construct(){
    try {
      $this->pdo = new PDO(
        "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET, 
        DB_USER, DB_PASSWORD, [
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
          PDO::ATTR_EMULATE_PREPARES => false,
        ]
      );
    } catch (Exception $ex) { die($ex->getMessage()); }
  }

  function __destruct(){
    if ($this->stmt!==null) { $this->stmt = null; }
    if ($this->pdo!==null) { $this->pdo = null; }
  }

  function query($sql, $data=null){
    try {
      $this->stmt = $this->pdo->prepare($sql);
      $this->stmt->execute($data);
    } catch (Exception $ex) { die($ex->getMessage()); }
    $this->stmt = null;
    return true;
  }

  function get($pid=0){
    $sql = "SELECT * FROM `comments` WHERE `project_id`=? ORDER BY timestamp ASC";
    $this->stmt = $this->pdo->prepare($sql);
    $this->stmt->execute([$pid]);
    $comments = [];
    while ($row = $this->stmt->fetch(PDO::FETCH_NAMED)) {
      $comments[$row['comment_id']] = $row;
    }
    return count($comments)>0 ? $comments : false ;
  }

  function add($pid, $author, $message, $type){
    $fields = "`project_id`,`author_id`,`message`,`type`";
    $values = "?,?,?,?";
    $cond = [$pid, $author, $message, $type];
    $sql = "INSERT INTO `comments` ($fields) VALUES ($values);";

    return $this->query($sql, $cond);
  }

  function edit($cid, $name, $message){
    $sql = "UPDATE `comments` SET `name`=?, `message`=? WHERE `comment_id`=?;";
    return $this->query($sql, [$name,$message,$cid]);
  }

  function delete($cid){
    $pass = $this->query("DELETE FROM `comments` WHERE `comment_id`=?;", [$cid]);
    if ($pass) {
      $this->query("DELETE FROM `comments` WHERE `reply_id`=?;", [$cid]);
    }
    return $pass;
  }
}
?>