<?php
include('config.php');

$sql = "SELECT * FROM posts ORDER BY post_id DESC";
$result = mysqli_query($connection, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    echo '<div class="post-container">';
    echo '  <div class="post">';
    echo '    <img src="blank-profile-picture-gb4f5f9059_640.png" alt="Avatar" class="avatar">';
    echo '    <h3 class="username"><a href="profile.html" style="text-decoration: none; color: #000000;">User Name</a></h3>';
    echo '  </div>';
    echo '  <div class="post-content">';
    echo '    <p class="post-text">' . $row["post_text"] . '</p>';
    echo '  </div>';
    echo '  <div class="post-image">';
    echo '    <img src="' . $row["post_image"] . '" alt="">';
    echo '  </div>';
    echo '  <div class="post-footer">';
    echo '    <button class="like-btn">Like</button>';
    echo '    <button class="unlike-btn">Unlike</button>';
    echo '    <div class="like-count">0 Likes</div>';
    echo '  </div>';
    echo '  <div class="comment-section">';
    echo '    <h4 class="comment-heading">Comments:</h4>';
    echo '    <ul class="comment-list">';
    echo '      <!-- Comments will be dynamically added here -->';
    echo '    </ul>';
    echo '    <form class="comment-form">';
    echo '      <input type="text" class="comment-input" placeholder="Write a comment...">';
    echo '      <button type="submit" class="comment-btn">Comment</button>';
    echo '    </form>';
    echo '  </div>';
    echo '</div>';
}

mysqli_close($connection);
?>
