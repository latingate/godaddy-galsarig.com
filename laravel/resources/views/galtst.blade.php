hello gal
<br/>
from: resources/views/galtst.blade.php
<br/>
change the route in /routes/web.php
<br/>

<?php
echo('QueryString from URL - $request / id = ' . $request::input('id') . '  name = ' . $request::input('name'))
?>

