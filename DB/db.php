<?php
   $host        = "host = 127.0.0.1";
   $port        = "port = 5432";
   $dbname      = "dbname = Recipes";
   $credentials = "user =[user] password=[password]";

   $conn = pg_connect( "$host $port $dbname $credentials"  );
   if(!$conn) {
      echo "Error : Unable to connect to database ❌\n";
   }
?>