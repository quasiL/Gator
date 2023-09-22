<?php
/** @var $model User */
use app\models\User;
?>
<h1>Login</h1>
<?php $form = \app\src\form\Form::begin('', 'post'); ?>
<?php echo $form->field($model, 'email'); ?>
<?php echo $form->field($model, 'password')->passwordField(); ?>
<button type="submit">Login</button>
<?php \app\src\form\Form::end(); ?>
