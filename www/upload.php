<?php

    if(isset($_FILES['picture']))
    { 
        $folder = 'upload/';
        if(!file_exists($folder)){
            mkdir($folder);
            $fileName = basename($_FILES['picture']['name']);
            if(move_uploaded_file($_FILES['picture']['tmp_name'], $folder . $fileName))
            {
                echo 'Upload effectué avec succès !';
            }
            else
            {
                echo 'Echec de l\'upload !';
            }
        }
    }

?>