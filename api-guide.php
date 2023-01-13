<?php

    /*
    * Database credentials. Assuming you are running MySQL server on a localhost with default
    * setting (user 'root' with no password)
    */
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME', 'YourDatabaseName');
    
    // Attempt to connect to MySQL database
    $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    
    // Check if connection is established else kill the process
    if ($connection === false) {

        die("ERROR: Could not connect. " . mysqli_connect_error());

    }



    // Create a "try catch" block to handle any exception
    try {

        // Declare and initialize these variables with null values
        $id = null;
        $firstName = null;
        $lastName = null;
        $phone  = null;
        $email = null;
        $returnError = true;    // This will be used later to determine if query returned any data

        
        
        /**
         * This section will query the "Users" table in the database using a prepared statement 
         * which is safe against any sql injections
         */
        $sql = 'SELECT * FROM Users';
        $statement = $connection->prepare($sql);
        $statement->execute();
        $statement->bind_result(
            $id,
            $firstName,
            $lastName,
            $phone,
            $email
        );



        /*
         * This section loops through the query results in this case it loops through
         * each row retrieved from the "Users" table
         */
        while ($statement->fetch()) {

            $returnError = false;   // Set this variable to "false" meaning that it fetched at least one row from "Users" table

            // Store each field in "Users" table in an array with key/value pair
            $data[] = array(
        
                "id" => $id,
                "firstName" => $firstName,
                "lastName" => $lastName,
                "phone" => $phone,
                "email" => $email
        
            );
        
        }

        return $res = $returnError ? $returnError : $data;

    } catch (\Throwable $th) {
        echo $th;
    }
    
?>

<!DOCTYPE html>
<html lang="en">
   <!-- head -->
   <head>
      <title>php API</title>
   </head>
   <!-- body -->
   <body>
        <!-- In this section, we are going to populate a table with the query results -->
        <table>
            <tbody>
                <?php
                    try {
                        //  If there was any data retrieved the code in the "if" block will be executed
                        if ($res !== true) {
                            //  initialize counter for the "while" loop
                            $i = 0;
                            /**
                             * This "while" loop will iterate through the number of the rows fetched
                             * which we added to the array of key/value pairs earlier
                             */
                            while ($i < count($res)) {
                                echo '
                                    <tr>
                                        <td>'.$res[$i]['id'].'</td>
                                        <td>'.$res[$i]['firstName'].'</td>
                                        <td>'.$res[$i]['lastName'].'</td>
                                        <td>'.$res[$i]['phone'].'</td>
                                        <td>'.$res[$i]['email'].'</td>
                                    </tr>
                                ';
                                $i++;
                            }
                        } else {
                            echo 'No Data Found!';
                        }
                    } catch (\Throwable $th) {
                        echo $th;
                    }
                ?>
            </tbody>
        </table>
   </body>
   <!-- footer -->
   <footer></footer>
</html>
    