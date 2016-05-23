This menu was adapted from a menu on http://cssmenumaker.com
Specifically: http://cssmenumaker.com/menu/cherry-responsive-menu

I heavily edited the CSS file to match the ucjeps style
and also renamed the file
The index.html file in this folder contains a version of our menu

I am currently running the menu on herbaria4 in http://herbaria4.herb.berkeley.edu/IJM.php
 pulling from a separate PHP file in common/php
When I actually implement it, I should have the images in common/images
and the stylesheet in common/styles
and the js script wherever js scripts go/should go

refer to the local index.html or the header information in the IJM.php example to see what is needed

Right now, the viewport configuration is messing up the page if you view it on mobile.
Because only the menu is optimized for mobile right now.
We need a separate CSS sheet for mobile.

I'm going to refer to these links:
https://perishablepress.com/the-5-minute-css-mobile-makeover/
http://www.htmlgoodies.com/beyond/css/targeting-specific-devices-in-your-style-sheets.html
http://www.smashingmagazine.com/2010/07/19/how-to-use-css3-media-queries-to-create-a-mobile-version-of-your-website/
http://getfirebug.com

http://manas.tungare.name/software/css-compression-in-php/

apparently media="handheld" is deprecated, so you do it by screen size. See:
http://stackoverflow.com/questions/12043983/how-to-make-style-sheet-for-tablet-and-iphone


Having one big minified CSS file is faster than multiple manageable readable ones, but obviously harder to maintain.
For us, ease of maintenance is more important than speed