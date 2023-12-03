<?php
// Include the common header file that provides consistent HTML structure and styles
require 'Header.php';
// Include the database connection file
require 'dbConn.php';

// Check if an auction ID is provided in the URL
if (isset($_GET['id'])) {
    $auctionIDFromURL = $_GET['id'];
}

// Retrieve details of the auction with the specified ID
$productDetailsQuery = $pdo->prepare("SELECT * FROM `auctions` WHERE auction_id = :auctionID");
$productDetailsQuery->bindParam(':auctionID', $auctionIDFromURL);
$productDetailsQuery->execute();
$auctionDetails = $productDetailsQuery->fetchAll(PDO::FETCH_ASSOC);

foreach ($auctionDetails as $auction) {
    $categoryID = $auction['category_id'];
    $uploaderID = $auction['register_id'];

    // Retrieve uploader's name from the 'registers' table
    $uploaderNameQuery = $pdo->prepare("SELECT name FROM `registers` WHERE register_id = :uploaderID");
    $uploaderNameQuery->bindParam(':uploaderID', $uploaderID);
    $uploaderNameQuery->execute();
    $uploaderDetails = $uploaderNameQuery->fetch(PDO::FETCH_ASSOC);
    $uploaderName = $uploaderDetails['name'];

    $productName = $auction['title'];

    // Retrieve category name from the 'categories' table
    $categoryNameQuery = $pdo->prepare("SELECT name FROM `categories` WHERE category_id = :categoryID");
    $categoryNameQuery->bindParam(':categoryID', $categoryID);
    $categoryNameQuery->execute();
    $categoryDetails = $categoryNameQuery->fetch(PDO::FETCH_ASSOC);
    $productCategory = $categoryDetails['name'];

    $productDescription = $auction['description'];
    $auctionEndDate = $auction['endDate'];
    $auctionEndDateTime = new DateTime($auctionEndDate);
}

// Retrieve the highest bid for the auction
$highestBidQuery = $pdo->prepare("SELECT MAX(bid) as maxBid FROM `bids` WHERE auction_id = :auctionID");
$highestBidQuery->bindParam(':auctionID', $auctionIDFromURL);
$highestBidQuery->execute();
$highestBidData = $highestBidQuery->fetch(PDO::FETCH_ASSOC);
$highestBidAmount = ($highestBidData['maxBid'] !== null) ? intval($highestBidData['maxBid']) : 0;
?>

<main>
    <h1>Product Page</h1>
    <article class="product">
        <img src="product.png" alt="product name">
        <section class="details">
            <h2><?= $productName ?></h2>
            <h3><?= $productCategory ?></h3>
            <p>Auction created by <a href="#"><?= $uploaderName ?></a></p>
            <p class="price"><?= 'Current bid: £' . $highestBidAmount ?></p>
            <time><?php
    $currentDateTime = new DateTime();
    $timeDifference = $currentDateTime->diff($auctionEndDateTime);
    echo $timeDifference->format('%d day %H hour %i minute') . ' remaining';
    ?></time>
            <form class="bid" action="#" method="POST">
                <input type="text" name="bidAmount" placeholder="Enter your amount" />
                <input type="submit" name="submitBid" value="Place bid" />
            </form>
        </section>
        <section class="description">
            <p><?= $productDescription ?></p>
        </section>
        <section class="reviews">
            <h2>Reviews of <?= $uploaderName ?></h2>
            <ul>
                <?php
                $reviewsQuery = $pdo->prepare("SELECT * FROM `reviews` WHERE auction_id = :auctionID");
                $reviewsQuery->bindParam(':auctionID', $auctionIDFromURL);
                $reviewsQuery->execute();
                $reviewDetails = $reviewsQuery->fetchAll(PDO::FETCH_ASSOC);

                foreach ($reviewDetails as $review) {
                    $reviewerID = $review['register_id'];
                    $reviewText = $review['writeReview'];
                    $reviewDate = $review['date'];

                     // Retrieve reviewer's name from the 'registers' table
                    $reviewerNameQuery = $pdo->prepare("SELECT name FROM `registers` WHERE register_id  = :reviewerID");
                    $reviewerNameQuery->bindParam(':reviewerID', $reviewerID);
                    $reviewerNameQuery->execute();
                    $reviewerDetails = $reviewerNameQuery->fetch(PDO::FETCH_ASSOC);
                    $reviewerName = $reviewerDetails['name'];

                      // Display each review in a list item
                    echo '<li><strong>' . $reviewerName . ' said </strong>' . $reviewText . '<em>' . $reviewDate . '</em></li>';
                }
                ?>
            </ul>
            <form action="#" method="POST">
                <label>Add your review</label> <textarea name="userReview" required></textarea>
                <input type="submit" name="submitReview" value="Add Review">
            </form>
        </section>
    </article>
    <?php
    require 'footer.php';
    ?>
</main>

</body>
</html>

<?php
if (isset($_POST['submitReview'])) {
    if ($_SESSION['user_id'] !== NULL) {
        $userEmail = $_SESSION['user_email'];
        $currentDate = date("Y.m.d");
        $userRegIDQuery = $pdo->prepare("SELECT register_id FROM `registers` WHERE email = :userEmail");
        $userRegIDQuery->bindParam(':userEmail', $userEmail);
        $userRegIDQuery->execute();
        $userRegDetails = $userRegIDQuery->fetch(PDO::FETCH_ASSOC);

        $userRegID = $userRegDetails['register_id'];
        $userReview = $_POST['userReview'];

        $insertReviewQuery = $pdo->prepare("INSERT INTO `reviews`( `writeReview`, `auction_id`, `register_id`, `date`) VALUES (:userReview, :auctionID, :userRegID, :currentDate)");
        $insertReviewQuery->bindParam(':userReview', $userReview);
        $insertReviewQuery->bindParam(':auctionID', $auctionIDFromURL);
        $insertReviewQuery->bindParam(':userRegID', $userRegID);
        $insertReviewQuery->bindParam(':currentDate', $currentDate);
        $insertReviewQuery->execute();

        // Redirect to the same auction page after adding the review
        header("Location: auctionPages.php?id=$auctionIDFromURL");
        exit();
    } else {
         // Redirect to the login page if the user is not logged in
        header('Location:login.php');
        exit();
    }
}

if (isset($_POST['submitBid'])) {
    if ($_SESSION['user_id'] != NULL) {
        $userEmail = $_SESSION['user_email'];

         // Retrieve user registration ID based on their email
        $userRegIDQuery = $pdo->prepare("SELECT register_id FROM `registers` WHERE email = :userEmail");
        $userRegIDQuery->bindParam(':userEmail', $userEmail);
        $userRegIDQuery->execute();
        $userRegDetails = $userRegIDQuery->fetch(PDO::FETCH_ASSOC);

        $userRegID = $userRegDetails['register_id'];
        $bidAmount = $_POST['bidAmount'];

        $insertBidQuery = $pdo->prepare("INSERT INTO `bids`( `bid`, `auction_id`, `register_id`) VALUES (:bidAmount, :auctionID, :userRegID)");
        $insertBidQuery->bindParam(':bidAmount', $bidAmount);
        $insertBidQuery->bindParam(':auctionID', $auctionIDFromURL);
        $insertBidQuery->bindParam(':userRegID', $userRegID);
        $insertBidQuery->execute();

        
        //header("Location: auctionPages.php?id=$auctionIDFromURL");
        //exit();
    } else {
        header('Location:login.php');
        exit();
    }
}

require 'footer.php';
?>



























<!-- Lorem ipsum dolor sit amet consectetur adipisicing elit. Asperiores, quidem! Quasi, molestiae. Quis deleniti id voluptatum omnis eveniet non dignissimos, dolorum itaque reprehenderit asperiores! Ullam expedita nemo minus dolore tempora. Eaque, adipisci velit accusantium, fuga quam laudantium, beatae itaque corrupti alias sed sunt quod hic sint eos veritatis. Facilis est voluptatum pariatur vitae quo. Delectus sequi ut sunt eius ex nulla dolore velit placeat pariatur voluptas consequatur vitae voluptatibus perspiciatis sapiente, rem reiciendis repellendus dignissimos ipsum id debitis laudantium voluptatum similique. Velit eos cupiditate tempore, beatae impedit dolores mollitia voluptates nostrum delectus itaque. Exercitationem sunt ullam et animi omnis eum aspernatur, ab accusantium rem perspiciatis atque veniam a praesentium qui optio possimus sequi inventore asperiores quo quia ipsam ad earum suscipit? Repellat, provident soluta recusandae quo qui quaerat possimus magnam sapiente cumque laborum magni, sequi atque nam culpa quisquam nisi repellendus. A eius molestiae sequi harum molestias ut laboriosam natus sit assumenda mollitia odio ad, corporis aspernatur voluptas. Explicabo, nostrum! Dolores nesciunt quisquam explicabo consectetur totam laboriosam alias sapiente veniam corrupti, aliquid qui asperiores porro id iste repudiandae perspiciatis? Sint adipisci doloribus minus at quo vitae tempora, nostrum magni praesentium aliquid dicta quod exercitationem esse pariatur qui necessitatibus accusantium, eaque labore beatae, natus quos mollitia velit? Aliquam impedit amet inventore rerum, labore odit nesciunt repudiandae excepturi architecto? Laboriosam amet quia suscipit. Perferendis a pariatur unde minus nisi beatae. Provident blanditiis tempora asperiores ducimus quasi reprehenderit alias vero ut, cum non eum dolorum delectus earum laudantium, ab dolore corporis ex quaerat vitae quia pariatur quod cupiditate? Eveniet, amet! Odit amet suscipit reprehenderit eum explicabo, voluptas rerum dicta doloribus rem quo? Ipsum, magni? Deleniti, minus libero! Quasi eveniet id velit expedita voluptates corporis facilis reiciendis dolorum, eos nemo labore molestias adipisci illo. Obcaecati libero ex pariatur accusantium cumque eum ipsum, recusandae, repellat ab rem fugiat quo perferendis nisi animi. Saepe, dolor. Non, ipsa temporibus. Asperiores iusto harum eveniet laborum beatae corrupti vel voluptatem esse. Magni numquam laudantium nihil saepe dolorum explicabo earum a quod, illo odio praesentium odit repellendus hic, enim quam iste, nesciunt vitae laboriosam? Suscipit dolore explicabo consequuntur pariatur nisi ducimus omnis magnam commodi, temporibus impedit, veritatis et corporis mollitia fugit voluptatibus! Nulla suscipit nisi totam praesentium atque. Saepe nobis nam impedit veniam ut soluta obcaecati iusto necessitatibus dolorum amet nulla repellendus, a unde eos ipsum. Debitis atque placeat fugit a fugiat, facere omnis unde neque consequatur aliquid explicabo? Distinctio minus fuga tenetur dicta? Provident velit, sed sequi laudantium cupiditate et quo eveniet? Corporis id tempore cum, veniam expedita voluptates. Enim, optio aut minus placeat iste alias voluptatibus. Vel repudiandae in quisquam maxime sapiente tenetur, eaque totam, ipsa voluptas commodi consequuntur quod. Molestias ducimus quaerat eligendi numquam non. Ab, vitae a, voluptates cupiditate possimus officia id eius eum minus, optio temporibus esse provident blanditiis! Aliquid hic ullam sunt esse libero. Repudiandae odit facere molestiae mollitia sequi. Esse dolorum sapiente dicta natus, optio architecto iste iusto quia recusandae, praesentium magnam enim tenetur quae officia nihil voluptatum saepe odit doloribus fugit deserunt necessitatibus dolore ex fugiat. Corporis ratione facere voluptatibus quam quo nihil, ea repudiandae debitis? Aspernatur ex voluptas ad commodi doloremque cum eum in labore debitis non optio accusantium illo neque reiciendis modi, distinctio tempore error eaque. At quo sapiente dolore repellat expedita eaque temporibus nam iure fugiat non recusandae, repudiandae assumenda veritatis tempora dolores, aliquam aut explicabo maiores sit modi reiciendis eveniet? Ipsum, sapiente inventore reiciendis maiores rerum quo nihil. Dolorum velit doloribus hic, ipsum ipsa laborum reprehenderit qui, magni placeat adipisci voluptatibus ratione quo dicta porro deserunt. Consectetur ipsum laudantium vel nam sequi nemo, odit, illo nisi recusandae reprehenderit non expedita et velit est quidem neque accusamus aliquid quae debitis excepturi veniam nulla dolore quod placeat! Ipsum perspiciatis adipisci est omnis veritatis? Vel eveniet laudantium rerum totam quam commodi recusandae atque, adipisci praesentium delectus consectetur accusantium doloribus nam ab vero suscipit autem sint? At sequi voluptatibus pariatur atque quaerat accusamus repudiandae soluta possimus accusantium dolorem quis ratione, aperiam odit perspiciatis aliquid harum impedit mollitia debitis assumenda eius libero quidem dolores. Veritatis aut necessitatibus ipsa libero reiciendis! Modi officia repellendus hic molestias beatae illo distinctio perspiciatis fuga, dignissimos reprehenderit non rem sit. Nulla veniam vero assumenda non tempore quia qui, modi recusandae alias? Delectus, cupiditate velit officiis corrupti iste beatae modi ad nobis illo quae, deserunt eos enim doloribus eligendi? Rerum natus dignissimos autem debitis sint animi. Sunt dolorem, fugit beatae tempore possimus autem eligendi cum provident aliquid quo itaque rerum, assumenda consequatur perferendis magnam ipsam minus, mollitia laborum quod cupiditate ipsum id veritatis quidem? Qui repellendus beatae sunt quia eum dolor libero inventore distinctio, deleniti asperiores eius. Consequuntur maxime animi odit quos earum eum atque iure incidunt quaerat vel fugit debitis, ex impedit iusto voluptates. Deserunt quae vero facere repellendus aliquid nam minima quidem. Perferendis, molestias rerum culpa modi maxime ipsum ex aspernatur est. Atque ipsam adipisci corporis reprehenderit ullam placeat, excepturi veniam ratione odio neque, voluptatibus incidunt unde ipsum consequuntur facilis modi rerum quod fugiat quam minus? Nisi animi dolores quae quidem distinctio ullam accusantium, non blanditiis error iure asperiores praesentium sed inventore fuga tempore dolorem, quaerat cum id quo ratione. Cupiditate sed ea esse maiores iure accusantium quidem ex eveniet, est totam libero voluptates voluptate quisquam vel incidunt porro eaque repellendus, blanditiis veritatis minus harum. Nisi quidem fugiat excepturi dicta iusto nam. Dolorem non tenetur quo voluptate praesentium, iusto neque quia tempora, omnis ipsa culpa provident. Nemo dolor magnam dignissimos! Eaque vero nam et? Assumenda, fugit sed, sint ratione laborum saepe quibusdam expedita tempora magnam dolorem quae eaque iure fugiat cumque accusantium optio distinctio veritatis aperiam! Veniam inventore et, eos laboriosam mollitia doloremque laudantium reprehenderit! Neque quibusdam error reiciendis sunt eius officia quae id fuga exercitationem cumque! Porro voluptatum unde nisi modi, exercitationem perferendis quibusdam, quasi, sapiente distinctio ea eaque? Nihil sequi eaque voluptatibus? Expedita quas maxime aliquam eum repellendus odit fugiat adipisci itaque incidunt quis esse eaque libero excepturi voluptatum, modi est assumenda fugit quos, doloremque odio corrupti quibusdam tempora nemo? Ipsam molestiae earum fuga dolorum. -->