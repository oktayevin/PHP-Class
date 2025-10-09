<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <title>file copy</title>
</head>
<body>
  <h2>file copy interface</h2>
  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" >  
    <p>
      <label for="source">source file:</label>
      <input type="text" name="source" id="source"/>
    </p>
    <p>
      <label for="destination">destination file:</label>
      <input type="text" name="destination" id="destination"/>
    </p>
     <p><input type ="submit" name="send" value="send" /> </p>
  </form>
  <hr />
  <?php
  
  ?>
</body>
</html>