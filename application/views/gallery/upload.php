
<div class="span-24 last">
<h1>Select a photo:</h1>
<?=form_open_multipart('gallery/upload'); ?>
<div id="titleform">
<label>Title:</label><br>
<input type="text" name="title" id="title">
</div>
<div id="uploadform">
<label>Select photo:</label><br>
<input type="file" name="photo" id="photo"><br>
<p>Please do not upload a file that has full stops (.) in the file name.</p>
</div>
<input type="submit" name="submit" value="Upload">
</form>
</div>
