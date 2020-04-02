 <!-- BONUS -->
 <!doctype html>
 <?php
    include 'header.php';
    include '../connec.php';

  
    ?>
 <main class="container">
     <section class="desktop">
         <div class="glass">
             <img src="image/whisky.png" alt="a whisky glass" class="whisky" />
             <img src="image/empty_whisky.png" alt="an empty whisky glass" class="empty-whisky" />
         </div>

         <div class="pages">
             <!-- Afficher le resultat selon l'index selectionné -->
             <div class="page leftpage">
                 <h3>- <?= $_GET['search'] ?> -</h3>
                 <hr>
                 <div class="calcul">
                     <?php foreach ($results as $result) { ?>
                         <p><?= $result['name'] ?></p>
                         <p><?= $result['payment'] ?> $</p>
                     <?php } ?>
                 </div>
                 <hr class="line-result">
                 <!-- Afficher le total selon l'index selectionné -->
                 <div class="result">
                     <p>Total :</p>
                     <p><?php if (isset($count)) echo $count ?> $</p>
                 </div>
             </div>

             <div class="page rightpage">

             </div>
         </div>
         <img src="image/inkpen.png" alt="an ink pen" class="inkpen" />
     </section>
 </main>
 <?php include('views/includes/footer.php')?>