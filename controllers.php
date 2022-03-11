<?php 

/**
 * Contrôleur de la page d'accueil
 */


function genArticle()
{   

    //Valider le paramètre idArticle 
    if(!array_key_exists('idArticle',$_GET) || !$_GET['idArticle'] || !ctype_digit($_GET['idArticle'])){
        echo '<p> Erreur : Id Article manquant ou incorrect</p>';
        exit;
    }
    
    $idArticle = (int)$_GET['idArticle'];
    

    // Selection de l'article
    $ArticleModel = new ArticleModel();
    $article = $ArticleModel->getOneArticle($idArticle);

    if (!$article){
        echo 'erreur : aucun article ne possède l\'id' . $idArticle;
        exit;
    }

    // Traitement des données du formulaire d'ajout de commentaires
    if (!empty($_POST)){
    
    // Récupération des données
    $content = trim($_POST['content']);
    
    // Validation
    $errors = [];

    // Si le champ "content" est vide => message d'erreur
    if(empty($content)){
        $errors['content'] = 'Le champ doit etre obligatoire';
    }
    
    // Si pas d'erreurs
    if (empty($errors)) {

        $UserModel = new Usermodel();
        $idUser = $USerModel->getUserId();
        
        $CommentModel = new CommentModel(); 
        $CommentModel->insertComment($content, $idArticle, $idUser);

        header('Location: index.php?action=article&idArticle=' . $idArticle);
        exit;
    }


    }
    $CommentModel = new Commentmodel();
    $comments = $CommentModel->getCommentByArticleId($idArticle);
    
    // Affichage : inclusion du fichier de template
    $template = 'article';
    include TEMPLATE_DIR .'/base.phtml';
}

function genContact()
{   
    $template = 'contact';
    include TEMPLATE_DIR .'/base.phtml';
}

function genMentions()
{
    $template = 'mentions';
    include TEMPLATE_DIR .'/base.phtml';
}


function genSignUP() {

// Initialisation des variables

$firstname ='';
$lastname ='';
$email ='';
$password = '';
$passwordconfirm = '';
$msgErrors = [];

// Traitement du formulaire en cas de soumission
if(!empty($_POST)) {

// 1 : On récupère les données du formulaire en leur appliquant un traitement si besoin 
$firstname = trim($_POST['firstname']);
$lastname = trim($_POST['lastname']);
$email = trim($_POST['email']);
$password = trim($_POST['password']);
$passwordconfirm = trim($_POST['passwordconfirm']);

$msgErrors = valideForm($firstname,$lastname,$email,$password,$passwordconfirm);


// si pas d'erreurs
if(empty($msgErrors)) {  
    
    $UserModel = new UserModel();
    $UserModel->insertUser($firstname,$lastname,$email,$password);

    addFlashMessage('Votre compte est créé BRAVO !');
        
    header('location: index.php?action=home');

    exit;
    
}

}
    $template = 'signup';
    include TEMPLATE_DIR .'/base.phtml';
    
}

function genLogin(){

    if(!empty($_POST)){
        $email = $_POST['email'];
        $password = $_POST['password']; 
    

    // On vérifie les identifiants 
    $UserModel = new UserModel();
    $user = $UserModel->checkCredentials($email,$password);
   

    // Si les identifiants sont corrects
    if($user){
        
        // On enregistre les données de l'utilisateur en session ( fonction userRegister() )
        
        userRegister($user['firstname'],$user['lastname'],$user['email'],$user['idUser']); 

        // On ajoute un message flash ( fonction addFlashMessage() )
        addFlashMessage('Vous etes connecté ' .$user['firstname']  .' ' .$user['lastname']);

        // On redirige l'internaute vers la page d'accueil
        header('location: index.php?action=home');
        exit;
    } 

    // Si les identifiants sont incorrects, on stocke un message d'erreur dans une variable
    $error = 'Identifiants incorrects';
    

}

    $template = 'login';
    include TEMPLATE_DIR .'/base.phtml';

}


function genLogout(){

    $UserModel = new UserModel();
    if($UserModel->isConnected()){

        // On efface les données de la session
        $_SESSION['user'] = null;
    
        // Je détruis la session
        session_destroy();

        addFlashMessage('Vous êtes bien déconnecté');
    
        }
        
        header('location: index.php');
        exit ;
        
        
}




