<?php
?>

<h1>Profile</h1>
<?php if (\app\src\Application::isGuest()): ?>
    <p>Guest</p>
<?php else: ?>
    <p>Logged in</p>
<?php endif; ?>
<?php
 echo $id;
 echo $username;
?>