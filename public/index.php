<?php
include 'views/includes/header.php';
include '../connec.php';
$pdo = new PDO(DSN, USER, PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

// Récupérer tous les bribe depuis la base de données
$getAllBribes = "SELECT * FROM bribe ORDER BY name ASC";
try {
    $sendRequest = $pdo->query($getAllBribes);
    $bribes = $sendRequest->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = $e->getMessage();
}

// Calculate Result
if (isset($bribes) && $bribes > 0) {
    $count = 0;
    foreach ($bribes as $value) {
        $count += $value['payment'];
    }
}

// Insérer un bribe en base de données
if (isset($_POST) && isset($_POST['pay'])) {
    if (!empty($_POST['name']) && !empty($_POST['payment'])) {
        if ($_POST['name'] == "Eliott Ness") {
            $error = "Cet homme est intouchable";
        } else {
            try {
                $postBribe = $pdo->prepare("INSERT INTO bribe (name, payment) VALUES (:name, :payment);");
                $postBribe->execute([
                    'name' => $_POST['name'],
                    'payment' => $_POST['payment']
                ]);
                return header('Location: http://localhost:8000/index.php');
            } catch (PDOException $e) {
                $error = $e->getMessage();
            }
        }
    } else {
        $error = "Tous les champs sont obligatoires.";
    }
}

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $pdo = new PDO(DSN, USER, PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    // Récupérer tous les bribe depuis la base de données selon l'index alphabétique :
    try {
        $searchLike = $_GET['search'] . "%";
        $request = $pdo->prepare("SELECT * FROM bribe WHERE name LIKE :search ORDER BY name ASC");
        $request->execute([
            'search' => $searchLike
        ]);
        $bribes = $request->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error = $e->getMessage();
    }

    // Calculate Count Total Bribe from Bribe.Index (A,B,C ... Recupéré depuis $_GET['search])
    if (isset($bribes) && $bribes > 0) {
        try {
            $count = 0;
            foreach ($bribes as $value) {
                $count += $value['payment'];
            }
        } catch (PDOException $e) {
            $error = $e->getMessage();
        }
    }
}
?>
<div class="container">
    <nav>
        <ul class="pagination">
            <?php
            $index = (range('A','Z'));
            for ($i = 0; $i < count($index); $i++) {
                echo "<li class='page-item'><a href='index.php?search=" . $index[$i] . "'>" . $index[$i] . "</a></li>";
            }
            ?>
        </ul>
    </nav>

    <div class="jumbotron">
        <div class="row">
            <div class="col-4">
                <h1 class="display-4">Add Bribe :</h1>
                <form method="POST">
                    <?php if (isset($error)) echo $error; ?>
                    <div>
                        <label for="name">Name</label>
                        <br>
                        <input id="name" type="text" name="name">
                    </div>
                    <div>
                        <label for="payment">Payment</label>
                        <br>
                        <input id="payment" type="text" name="payment">
                    </div>
                    <div class="my-4">
                        <button type="submit" class="btn btn-primary" name="pay">Pay!</button>
                    </div>
                </form>
                <div>
                <div class="card">
                    <div class="card-body">
                        <p>Total :</p>
                        <p><?php if (isset($count)) echo $count ?> $</p>
                    </div>
                </div>
                </div>
            </div>

            <div class="col-1"></div>
            <div class="col-7">
                <h3>List bribe : </h3>
                <div class="calcul">
                    <?php foreach ($bribes as $bribe) { ?>
                        <div class="card my-3">
                            <div class="card-body">
                                <p>To : <?= $bribe['name'] ?></p>
                                <p><?= $bribe['payment'] ?> $</p>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div>

    <?php include('views/includes/footer.php') ?>