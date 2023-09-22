<?php
?>
<h1>Home</h1>
<?php $form = \app\src\form\Form::begin('', 'post'); ?>
    <?php echo $form->field($model, 'firstname'); ?>
    <?php echo $form->field($model, 'lastname'); ?>
    <?php echo $form->field($model, 'email'); ?>
    <?php echo $form->field($model, 'password')->passwordField(); ?>
    <?php echo $form->field($model, 'confirmPassword'); ?>
    <button type="submit">Send</button>
<?php \app\src\form\Form::end(); ?>
<!--<form action="" method="post" style="display: flex; flex-direction: column; gap: 1vh; width: 20%">-->
<!--    <label for="firstName">Name</label>-->
<!--    <input name="firstName" type="text">-->
<!--    <label for="lastName">Surname</label>-->
<!--    <input name="lastName" type="text">-->
<!--    <label for="email">Email</label>-->
<!--    <input name="email" type="text">-->
<!--    <label for="password">Password</label>-->
<!--    <input name="password" type="password">-->
<!--    <label for="confirmPassword">Confirm Password</label>-->
<!--    <input name="confirmPassword" type="password">-->
<!--    <button type="submit">Send</button>-->
<!--</form>-->