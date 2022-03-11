<?php 

/**
 * Valide les champs du formulaire
 */
function validateSignupForm(string $lastname, string $firstname, string $email, string $password, string $passwordConfirm): array
{
    $errors = [];

    // LASTNAME
    if (!$lastname) { 
        $errors['lastname'] = 'Le champ "Nom" est obligatoire';
    }

    // FIRSTNAME
    if (!$firstname) { 
        $errors['firstname'] = 'Le champ "Prénom" est obligatoire';
    }

    // VALIDATION EMAIL
    if (!$email) { // ou bien if (empty($email)) { ou if (strlen($email) == 0) { ou if ($email == '') { 
        $errors['email'] = 'Le champ "Email" est obligatoire';
    }

    // Si le champ est bien rempli, on fait les autres tests
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // Si l'email n'est pas valide...  
        $errors['email'] = "Le format de l'email n'est pas correct";
    } 
    
    // Vérification de l'existence de l'email
    elseif(getUserByEmail($email)) { 
        $errors['email'] = "Vous êtes déjà enregistré";
    }

}