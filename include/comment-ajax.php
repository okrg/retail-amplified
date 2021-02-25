<?php
/* [INIT] */
session_start();
require 'db.php';
require 'comment-mysql.php';

$pdo = new DB();

switch ($_POST['req']) {
  /* [INVALID REQUEST] */
  default:
    echo "Invalid request";
    break;

  /* [SHOW COMMENTS] */
  case "show":
    $comments = $pdo->get($_POST['project_id']);    
    if (is_array($comments)) { 
      foreach ($comments as $c) {
      //show($c['comment_id'],$c['author_id'],$c['message'], $c['type'], $c['time']);    
      $comment_author_fullname = get_user_fullname_by_id($c['author_id']);
      $comment_timestamp = date ("M d, Y h:i A", strtotime($c['timestamp']));
      switch($c['type']) {
        case "real_estate":
        $comment_type = "Real Estate";
        break;
        case "construction":
        $comment_type = "Construction";
        break;
        case "design":
        $comment_type = "Design";
        break;        
      }
      ?>
      <div class="comment-container" data-comment-type="<?=$c['type']?>">
      <div class="comment-author"><?=$comment_author_fullname?></div>
      <div class="comment-meta"><?=$comment_type?> &bull; <?=$comment_timestamp?></div>
      <div class="comment-message"><?=$c['message']?></div>      
      </div>
      <?php
      }
    }
    break;

  /* [ADD COMMENT] */
  case "add":
    echo $pdo->add($_POST['project_id'], $_POST['author_id'], $_POST['message'], $_POST['type']) ? "OK" : "ERR";
    break;

  /* [EDIT COMMENT] */
  case "edit":
    if ($_SESSION['admin']) {
      echo $pdo->edit($_POST['comment_id'], $_POST['name'], $_POST['message']) ? "OK" : "ERR";
    }
    break;

  /* [DELETE COMMENT] */
  case "del":
    echo $pdo->delete($_POST['comment_id']) ? "OK" : "ERR";    
    break;
}
?>