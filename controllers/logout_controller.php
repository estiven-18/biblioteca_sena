<?php
if(session_status()!==PHP_SESSION_ACTIVE){
session_start();
}
session_unset();
session_destroy();
//* logout funcional 
//? impontante tener en cuenta el status success 
echo json_encode(["status"=>"success"]);


exit();
?>