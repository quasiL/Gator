
<h1>Contact</h1>
<p><?= $params['name'] ?></p>
<?php if (\app\src\Application::isGuest()): ?>
    <p>Guest</p>
<?php else: ?>
    <p>Logged in</p>
<?php endif; ?>