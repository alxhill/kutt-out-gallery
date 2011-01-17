<div class="span-16 last">
<div class='content'>
<h1>Upload a photo:</h1>
<?=form_open_multipart('gallery/upload'); ?>
<div id="titleform">
<label>Title:</label><br>
<input type="text" name="title" id="title">
</div>
<div id="uploadform">
<label>Select photo:</label><br>
<input type="file" name="photo" id="photo"><br>
</div>
<input type="submit" name="submit" id="submit" value="Upload">
</form>
</div>
</div>