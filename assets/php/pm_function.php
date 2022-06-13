<?php
include('config.php');
session_start();
//Connect to database
include('database_connect.php');
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: ../../index.php');
	exit;
}
// Below function will convert datetime to time elapsed string
function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $now->setTimeZone(new DateTimeZone('Asia/Hong_Kong')); 
    $ago = new DateTime($datetime, new DateTimeZone('Asia/Hong_Kong'));
    $diff = $now->diff($ago);
    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;
    $string = array('y' => 'year', 'm' => 'month', 'w' => 'week', 'd' => 'day', 'h' => 'hour', 'i' => 'minute', 's' => 'second');
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }
    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
// This function will populate the comments and comments replies using a loop
function show_comments($comments, $parent_id = -1) {
    $html = '';
    if ($parent_id != -1) {
        // If the comments are replies sort them by the "submit_date" column
        array_multisort(array_column($comments, 'submit_date'), SORT_ASC, $comments);
    }
    // Iterate the comments using the foreach loop
    foreach ($comments as $comment) {
        if ($comment['parent_id'] == $parent_id) {
            // Add the comment to the $html variable
            $html .= '
            <div class="comment">
                <div>
                    <h3 class="name">' . htmlspecialchars($comment['sender_nickname'], ENT_QUOTES) . '</h3>
                    <span class="date">' . time_elapsed_string($comment['submit_date']) . '</span>
                </div>
                <p class="content">' . nl2br(htmlspecialchars($comment['content'], ENT_QUOTES)) . '</p>
                <a class="reply_comment_btn" href="#" data-comment-id="' . $comment['id'] . '">Reply</a>
                ' . show_write_comment_form($comment['id']) . '
                <div class="replies">
                ' . show_comments($comments, $comment['id']) . '
                </div>
            </div>
            ';
        }
    }
    return $html;
}
// This function is the template for the write comment form
function show_write_comment_form($parent_id = -1) {
    $html = '
    <div class="write_comment" data-comment-id="' . $parent_id . '">
        <form>
            <input name="parent_id" type="hidden" value="' . $parent_id . '">    
            <textarea name="content" placeholder="Write your comment here..." required></textarea>
            <button type="submit">Submit Message</button>
        </form>
    </div>
    ';
    return $html;
}
// Page ID needs to exist, this is used to determine which comments are for which page
if (isset($_GET['page_id'])) {
    echo $_POST['id'];
    // Check if the submitted form variables exist
    if ($_POST['content']) {
        $stmt = $con->prepare('SELECT nickname FROM accounts WHERE id = ?');
        // In this case we can use the account ID to get the account info.
        $stmt->bind_param('i', $_SESSION['id']);
        $stmt->execute();
        $stmt->bind_result($nickname);
        $stmt->fetch();
        $stmt->close();
        if ($_SESSION['id'] == $_GET['page_id']){ //reply a message
            $stmt = $con->prepare('SELECT sender_user_id FROM comments WHERE id = ?');
            $stmt->bind_param('i', $_POST['parent_id']);
            $stmt->execute();
            $stmt->bind_result($sender_user_id);
            $stmt->fetch();
            $stmt->close();
            $reply_receiver_user_id = $sender_user_id;
            //POST variables exist, insert a new comment into the MySQL comments table (user submitted form)
            $stmt = $con->prepare('INSERT INTO comments (page_id, parent_id, sender_nickname, content, submit_date, sender_user_id, receiver_user_id) VALUES (?,?,?,?,CURRENT_TIMESTAMP,?,?)');
            $stmt->bind_param('iissii', $_GET['page_id'], $_POST['parent_id'], $nickname, $_POST['content'], $_SESSION['id'] , $reply_receiver_user_id);
            $stmt->execute();
            exit('Your message has been submitted!');
        } else {
            // POST variables exist, insert a new comment into the MySQL comments table (user submitted form)
            $stmt = $con->prepare('INSERT INTO comments (page_id, parent_id, sender_nickname, content, submit_date, sender_user_id, receiver_user_id) VALUES (?,?,?,?,CURRENT_TIMESTAMP,?,?)');
            $stmt->bind_param('iissii', $_GET['page_id'], $_POST['parent_id'], $nickname, $_POST['content'], $_SESSION['id'] , $_GET['page_id']);
            $stmt->execute();
            exit('Your message has been submitted!');
        }
    }
    // Get all comments by the Page ID ordered by the submit date
    $sender_user_id = $_GET['page_id'];
    $receiver_user_id = $_SESSION['id'];
    $exchange_sender_user_id = $_SESSION['id'];
    $exchange_receiver_user_id = $_GET['page_id'];
    if ($sender_user_id == $receiver_user_id){ //view my message
        echo 'View my message';
        $stmt = $con->prepare('SELECT * FROM comments WHERE receiver_user_id = ? OR sender_user_id = ? ORDER BY submit_date DESC');
        $stmt->bind_param('ii', $receiver_user_id, $sender_user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $comments = $result->fetch_all(MYSQLI_ASSOC);
        $stmt = $con->prepare('SELECT COUNT(*) AS total_comments FROM comments WHERE receiver_user_id = ? OR sender_user_id = ?');
        $stmt->bind_param('ii', $receiver_user_id, $sender_user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $comments_info = mysqli_num_rows($result);
        $comments_info = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $stmt = $con->prepare('SELECT * FROM comments WHERE ((sender_user_id = ? AND receiver_user_id = ?) OR (sender_user_id = ? AND receiver_user_id = ?)) ORDER BY submit_date DESC');
        $stmt->bind_param('iiii',  $sender_user_id, $receiver_user_id, $exchange_sender_user_id, $exchange_receiver_user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $comments = $result->fetch_all(MYSQLI_ASSOC);
        // Get the total number of comments
        $stmt = $con->prepare('SELECT COUNT(*) AS total_comments FROM comments WHERE ((sender_user_id = ? AND receiver_user_id = ?) OR (sender_user_id = ? AND receiver_user_id = ?))');
        $stmt->bind_param('iiii',  $sender_user_id, $receiver_user_id, $exchange_sender_user_id, $exchange_receiver_user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $comments_info = mysqli_num_rows($result);
        $comments_info = $result->fetch_all(MYSQLI_ASSOC);
    }
} else {
    exit('No page ID specified!');
}
?>
<div class="comment_header">
    <span class="total"><?=$comments_info[0]['total_comments']?> messages</span>
    <?php 
        if ($_SESSION['id'] != $_GET['page_id']){
            echo '<a href="#" class="write_comment_btn" data-comment-id="-1">Write Message</a>';
        }
    ?>

</div>

<?=show_write_comment_form()?>

<?=show_comments($comments)?>