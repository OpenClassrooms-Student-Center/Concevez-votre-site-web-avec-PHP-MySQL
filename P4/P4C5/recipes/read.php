<?php
session_start();

include_once('./../config/mysql.php');
include_once('./../config/user.php');
include_once('./../variables.php');

$getData = $_GET;
if (!isset($getData['id']) && is_numeric($getData['id']))
{
	echo('La recette n\'existe pas');
    return;
}	

$recipeId = $getData['id'];

$retrieveRecipeWithCommentsStatement = $mysqlClient->prepare('SELECT * FROM recipes r LEFT JOIN comments c on r.recipe_id = c.recipe_id WHERE r.recipe_id = :id ');
$retrieveRecipeWithCommentsStatement->execute([
    'id' => $recipeId,
]);

$recipeWithComments = $retrieveRecipeWithCommentsStatement->fetchAll(PDO::FETCH_ASSOC);

$recipe = [
    'recipe_id' => $recipeWithComments[0]['recipe_id'],
    'title' => $recipeWithComments[0]['title'],
    'recipe' => $recipeWithComments[0]['recipe'],
    'author' => $recipeWithComments[0]['author'],
    'comments' => [],
];

foreach($recipeWithComments as $comment) {
    if (!is_null($comment['comment_id'])) {
        $recipe['comments'][] = [
            'comment_id' => $comment['comment_id'],
            'comment' => $comment['comment'],
            'user_id' => (int) $comment['user_id'],
        ];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site de Recettes - <?php echo($recipe['title']); ?></title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
        rel="stylesheet"
    >
</head>
<body class="d-flex flex-column min-vh-100">
    <div class="container">

    <?php include_once($rootPath.'/header.php'); ?>
        <h1><?php echo($recipe['title']); ?></h1>
        <div class="row">
            <article class="col">
                <?php echo($recipe['recipe']); ?>
            </article>
            <aside class="col">
                <p><i>Contribuée par <?php echo($recipe['author']); ?></i></p>
            </aside>
        </div>

        <?php if(count($recipe['comments']) > 0): ?>
        <hr />
        <h2>Commentaires</h2>
        <div class="row">
            <?php foreach($recipe['comments'] as $comment): ?>
                <div class="comment">
                    <p><?php echo($comment['comment']); ?></p>
                    <i>(<?php echo(display_user($comment['user_id'], $users)); ?>)</i>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <hr />
        <?php if (isset($loggedUser)) : ?>
            <?php include_once($rootPath.'/comments/create.php'); ?>
        <?php endif; ?>
    </div>
    <?php include_once($rootPath.'/footer.php'); ?>
</body>
</html>
