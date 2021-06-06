<?php

    if(isset($_FILES['picture']))
    { 
        $folder = 'upload/';
        $file = basename($_FILES['picture']['name']);
        if(move_uploaded_file($_FILES['picture']['tmp_name'], $folder . $file))
        {
            echo 'Upload effectué avec succès !';
        }
        else
        {
            echo 'Echec de l\'upload !';
        }
    }

?>