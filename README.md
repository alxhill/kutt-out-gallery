Kutt Out Gallery
===============

This is a gallery system for kutt-out.co.uk. Currently supports uploading, editing and deleting images, one user login and multiple galleries (including video galleries) within an updated version of the kutt-out.co.uk design.

To do:
------

* GENERAL & SYSTEM FIXES:
	* Error handling on db entries - error codes/exceptions or something
	* Add WAY better handling of the initial setup phase - at the very least when there's no galleries, better when there's no database
	* Remove all hardcoded /gallery/ links in place of dynamically generated ones, in both PHP, CSS and JS.
	* Add a way to have separators between galleries.
	* Minify & combine CSS and JS files!
	* Reduce the repetition of JS for photos and video views. It's 90% identical.
	
* TOBYELWES.COM FIXES & ADDITIONS
	* Improve readability of galleries along bar.
	* Add JavaScript to do everything it needs to do. *in progress*
	* Add edit functionality to video admin pages. *partially done*
	* Add gallery reordering.
	* Sort out the CSS of gallery edit pages, because they're freaking ugly.
	* Make the scroller dynamic and AJAXy. *partially complete*