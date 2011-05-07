</div>
<div id='content'>
<div id='login_form'>
<h2 id='login'>Log in:</h2>
<?=form_open('login/submit',array('class' => 'loginform')); ?>
<label>User name:</label><br>
<input type='text' name='username' size='50'>
<br>
<label>Password:</label><br>
<input type='password' size='50' name='password'>
<br>
<input type='submit' value='Log in'>
</form>
</div>
</div>