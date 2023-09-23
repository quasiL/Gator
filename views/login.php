<?php
/** @var $model User */
use app\models\User;
?>
<h1>Login</h1>
<?php $form = \app\src\form\Form::begin('', 'post'); ?>
<lablel>email</lablel>
<?php echo $form->field($model, 'email'); ?>
<lablel>password</lablel>
<?php echo $form->field($model, 'password')->passwordField(); ?>
<button type="submit">Login</button>
<?php \app\src\form\Form::end(); ?>
