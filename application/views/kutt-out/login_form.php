<div class="padding">
<h3 id='login'>Log in:</h3>
<?=form_open('login/submit',array('class' => 'login_form')); ?>
<label>User name:</label><br>
<input type='text' name='username'>
<br>
<label>Password:</label><br>
<input type='password' name='password'>
<br>
<input type='submit' value='Log in'>
</form>
</div>