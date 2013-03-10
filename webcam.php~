<script type="text/javascript" src="webcam.js"></script>
<script language="JavaScript">
        webcam.set_api_url( 'save_from_webcam.php' );
        webcam.set_quality( 100 ); // JPEG quality (1 - 100)
        webcam.set_shutter_sound( true ); // play shutter click sound
	document.write(webcam.get_html(320, 240));
</script>
<br/><form>
        <input type=button value="Configure..." onClick="webcam.configure()">
        &nbsp;&nbsp;&nbsp;
        <input type=button value="Take Snapshot" onClick="webcam.snap()">
	
</form>
<script language="JavaScript">
        webcam.set_hook( 'onComplete', 'my_callback_function' );
        function my_callback_function(response) {
               //alert("Success! PHP returned: " + response);
		window.open('search.php'+response);
        }
</script>