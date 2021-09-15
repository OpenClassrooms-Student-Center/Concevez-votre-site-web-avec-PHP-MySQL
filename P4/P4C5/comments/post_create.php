<?php
session_start();

include_once('./../config/mysql.php');
include_once('./../config/user.php');
include_once('./../variables.php');
include_once('./../functions.php');

$postData = $_POST;

if (
    !isset($postData['comment']) &&
    !isset($postData['recipe_id']) &&
    !is_numeric($postData['recipe_id'])
    )
{
	echo('Le commentaire est invalide.');
    return;
}

if (!isset($loggedUser)) {
    echo('Vous devez être authentifié pour soumettre un commentaire');
    return;
}

$comment = $postData['comment'];
$recipeId = $postData['recipe_id'];

$insertRecipe = $mysqlClient->prepare('INSERT INTO comments(comment, recipe_id, user_id) VALUES (:comment, :recipe_id, :user_id)');
$insertRecipe->execute([
    'comment' => $comment,
    'recipe_id' => $recipeId,
    'user_id' => retrieve_id_from_user_mail($loggedUser['email'], $users),
]);

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site de Recettes - Création de commentaire</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
        rel="stylesheet"
    >
</head>
<body class="d-flex flex-column min-vh-100">
    <div class="container">

    <?php include_once($rootPath.'/header.php'); ?>
        <h1>Commentaire ajouté avec succès !</h1>
        
        <div class="card">
            
            <div class="card-body">
                <p class="card-text"><b>Votre commentaire</b> : <?php echo strip_tags($comment); ?></p>
            </div>
        </div>
    </div>
    <?php include_once($rootPath.'/footer.php'); ?>
</body>
</html>
