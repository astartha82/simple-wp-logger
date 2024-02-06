<p class="description">
To use the Simple WP Logger in your code, just type smth like
<code>
    _log('My log title', $myvar);
</code>
</p>
<p class="description">
If you don't want to output a variable, you may use it like
<code>
    _log('Just log this line of text');
</code>
</p>
<p class="description">
If you want to output several variables at once, put them into an array like this:
<code>
    _log('Several variables', array($var1, $var2, $var3, ...));
</code>
</p>
<p class="description">
If you want to log things in a separate file, you can use the third argument:
<code>
    $logfile = 'another-file';<br>
    _log('Logging to another file', $myvar, $logfile);
</code>
This file will be stored in the directory you set in the options (or "swpl_dir"), with the file prefix you also set in the options (or "swpl"):
<code>
        wp-content/uploads/swpl-logs/swpl_another-file_2024-02-06.log
</code>
</p>
