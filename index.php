<?php //SHOW FRIENDS LIST
    require_once '_connec.php'; 

    $pdo = new \PDO(DSN, USER, PASS);

    $query = 'SELECT * FROM friend';
    $statement = $pdo->query($query);
    $friends = $statement->fetchAll(PDO::FETCH_ASSOC);
?>


<?php //ADD NEW FRIEND AFTER VERIFICATION AND CLEANING 
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){

        $errors = [];

        $firstname = trim($_POST['firstname']); 
        $lastname = trim($_POST['lastname']);

        if (empty($firstname)) {
            $errors[] = 'Le prénom est obligatoire.';
        }

        if (empty($lastname)) {
            $errors[] = 'Le nom est obligatoire.';
        }

        $firstnameLength = 45;
        if (strlen($firstname) > $firstnameLength ) {
            $errors[] = 'La longueur du prénom ne peut dépasser ' . $firstnameLength . ' caractères.';
        }

        $lastnameLength = 45;
        if (strlen($lastname) > $lastnameLength ) {
            $errors[] = 'La longueur du prénom ne peut dépasser ' . $lastnameLength . ' caractères.';
        }

        if (empty($errors)) {
            $pdo = new \PDO(DSN, USER, PASS);
            $query = 'INSERT INTO friend(firstname, lastname) VALUES (:firstname, :lastname)';

            $statement = $pdo->prepare($query);
            $statement->bindValue(':firstname', $firstname, \PDO::PARAM_STR);
            $statement->bindValue(':lastname', $lastname, \PDO::PARAM_STR);
            $statement->execute();

            header('location:index.php');
        }
    }
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDO Quest</title>
</head>
<body>
    <h1>Liste des friends</h1>
    <?php foreach ($friends as $friend) : ?>
        <ul>
            <li><?= $friend['firstname'] . ' ' . $friend['lastname']; ?></li>
        </ul>
    <?php endforeach ?>

    <hr>

    <form action="" method="POST">
        <label for="firstname">Prénom</label>
        <input type="text" name="firstname" id="firstname" value="<?= htmlentities($firstname?? '')?>" required maxlength="45">

        <label for="lastname">Nom</label>
        <input type="text" name="lastname" id="lastname" value="<?= htmlentities($lastname?? '') ?>" required  maxlength="45">

        <button type="submit">Ajouter</button>
    </form>

    <div>
        <?php if (!empty($errors)) : ?>
            <ul>
                <?php foreach($errors as $error) :?>
                <li style="color:red"> <?= $error ?></li>
                <?php endforeach ?>
            </ul>
        <?php endif ?>
    </div>

</body>
</html>