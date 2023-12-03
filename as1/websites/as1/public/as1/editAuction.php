<?php
require 'Header.php';
require 'db.php';

if (isset($_SESSION['user_id'])) {
    $userID = $_SESSION['user_id'];

    if (isset($_GET['id'])) {
        $auctionID = $_GET['id'];
        $auctionDetails = getAuctionById($pdo, $auctionID);

        if ($auctionDetails && $auctionDetails['register_id'] == $userID) {

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $title = $_POST['title'];
                $category = $_POST['category'];
                $description = $_POST['description'];

                $success = updateAuction($pdo, $auctionID, $title, $category, $description);

                if ($success) {
                    header('Location: userAuction.php');
                    exit();
                } else {
                    $updateError = 'Failed to update auction. Please try again.';
                }
            }

            ?>
            <main>
                <h1>Edit Auction</h1>
                <form method="POST" action="">
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" value="<?= $auctionDetails['title']; ?>" required>
                    <label for="category">Category:</label>
                    <select id="category" name="category" required>
            <?php foreach ($categories as $category) : ?>
                <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
            <?php endforeach; ?>
        </select>

                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required><?= $auctionDetails['description']; ?></textarea>

                    <input type="submit" value="Update Auction">
                </form>
            </main>
            <?php
        } else {
            ?>
            <main>
                <h1>Error</h1>
                <p>You do not have permission to edit this auction.</p>
            </main>
            <?php
        }
    } else {
        ?>
        <main>
            <h1>Error</h1>
            <p>Auction ID not provided.</p>
        </main>
        <?php
    }
} else {
    header('Location: login.php');
    exit();
}

require 'footer.php';
?>




























<!-- Lorem ipsum dolor sit amet consectetur adipisicing elit. Asperiores, quidem! Quasi, molestiae. Quis deleniti id voluptatum omnis eveniet non dignissimos, dolorum itaque reprehenderit asperiores! Ullam expedita nemo minus dolore tempora. Eaque, adipisci velit accusantium, fuga quam laudantium, beatae itaque corrupti alias sed sunt quod hic sint eos veritatis. Facilis est voluptatum pariatur vitae quo. Delectus sequi ut sunt eius ex nulla dolore velit placeat pariatur voluptas consequatur vitae voluptatibus perspiciatis sapiente, rem reiciendis repellendus dignissimos ipsum id debitis laudantium voluptatum similique. Velit eos cupiditate tempore, beatae impedit dolores mollitia voluptates nostrum delectus itaque. Exercitationem sunt ullam et animi omnis eum aspernatur, ab accusantium rem perspiciatis atque veniam a praesentium qui optio possimus sequi inventore asperiores quo quia ipsam ad earum suscipit? Repellat, provident soluta recusandae quo qui quaerat possimus magnam sapiente cumque laborum magni, sequi atque nam culpa quisquam nisi repellendus. A eius molestiae sequi harum molestias ut laboriosam natus sit assumenda mollitia odio ad, corporis aspernatur voluptas. Explicabo, nostrum! Dolores nesciunt quisquam explicabo consectetur totam laboriosam alias sapiente veniam corrupti, aliquid qui asperiores porro id iste repudiandae perspiciatis? Sint adipisci doloribus minus at quo vitae tempora, nostrum magni praesentium aliquid dicta quod exercitationem esse pariatur qui necessitatibus accusantium, eaque labore beatae, natus quos mollitia velit? Aliquam impedit amet inventore rerum, labore odit nesciunt repudiandae excepturi architecto? Laboriosam amet quia suscipit. Perferendis a pariatur unde minus nisi beatae. Provident blanditiis tempora asperiores ducimus quasi reprehenderit alias vero ut, cum non eum dolorum delectus earum laudantium, ab dolore corporis ex quaerat vitae quia pariatur quod cupiditate? Eveniet, amet! Odit amet suscipit reprehenderit eum explicabo, voluptas rerum dicta doloribus rem quo? Ipsum, magni? Deleniti, minus libero! Quasi eveniet id velit expedita voluptates corporis facilis reiciendis dolorum, eos nemo labore molestias adipisci illo. Obcaecati libero ex pariatur accusantium cumque eum ipsum, recusandae, repellat ab rem fugiat quo perferendis nisi animi. Saepe, dolor. Non, ipsa temporibus. Asperiores iusto harum eveniet laborum beatae corrupti vel voluptatem esse. Magni numquam laudantium nihil saepe dolorum explicabo earum a quod, illo odio praesentium odit repellendus hic, enim quam iste, nesciunt vitae laboriosam? Suscipit dolore explicabo consequuntur pariatur nisi ducimus omnis magnam commodi, temporibus impedit, veritatis et corporis mollitia fugit voluptatibus! Nulla suscipit nisi totam praesentium atque. Saepe nobis nam impedit veniam ut soluta obcaecati iusto necessitatibus dolorum amet nulla repellendus, a unde eos ipsum. Debitis atque placeat fugit a fugiat, facere omnis unde neque consequatur aliquid explicabo? Distinctio minus fuga tenetur dicta? Provident velit, sed sequi laudantium cupiditate et quo eveniet? Corporis id tempore cum, veniam expedita voluptates. Enim, optio aut minus placeat iste alias voluptatibus. Vel repudiandae in quisquam maxime sapiente tenetur, eaque totam, ipsa voluptas commodi consequuntur quod. Molestias ducimus quaerat eligendi numquam non. Ab, vitae a, voluptates cupiditate possimus officia id eius eum minus, optio temporibus esse provident blanditiis! Aliquid hic ullam sunt esse libero. Repudiandae odit facere molestiae mollitia sequi. Esse dolorum sapiente dicta natus, optio architecto iste iusto quia recusandae, praesentium magnam enim tenetur quae officia nihil voluptatum saepe odit doloribus fugit deserunt necessitatibus dolore ex fugiat. Corporis ratione facere voluptatibus quam quo nihil, ea repudiandae debitis? Aspernatur ex voluptas ad commodi doloremque cum eum in labore debitis non optio accusantium illo neque reiciendis modi, distinctio tempore error eaque. At quo sapiente dolore repellat expedita eaque temporibus nam iure fugiat non recusandae, repudiandae assumenda veritatis tempora dolores, aliquam aut explicabo maiores sit modi reiciendis eveniet? Ipsum, sapiente inventore reiciendis maiores rerum quo nihil. Dolorum velit doloribus hic, ipsum ipsa laborum reprehenderit qui, magni placeat adipisci voluptatibus ratione quo dicta porro deserunt. Consectetur ipsum laudantium vel nam sequi nemo, odit, illo nisi recusandae reprehenderit non expedita et velit est quidem neque accusamus aliquid quae debitis excepturi veniam nulla dolore quod placeat! Ipsum perspiciatis adipisci est omnis veritatis? Vel eveniet laudantium rerum totam quam commodi recusandae atque, adipisci praesentium delectus consectetur accusantium doloribus nam ab vero suscipit autem sint? At sequi voluptatibus pariatur atque quaerat accusamus repudiandae soluta possimus accusantium dolorem quis ratione, aperiam odit perspiciatis aliquid harum impedit mollitia debitis assumenda eius libero quidem dolores. Veritatis aut necessitatibus ipsa libero reiciendis! Modi officia repellendus hic molestias beatae illo distinctio perspiciatis fuga, dignissimos reprehenderit non rem sit. Nulla veniam vero assumenda non tempore quia qui, modi recusandae alias? Delectus, cupiditate velit officiis corrupti iste beatae modi ad nobis illo quae, deserunt eos enim doloribus eligendi? Rerum natus dignissimos autem debitis sint animi. Sunt dolorem, fugit beatae tempore possimus autem eligendi cum provident aliquid quo itaque rerum, assumenda consequatur perferendis magnam ipsam minus, mollitia laborum quod cupiditate ipsum id veritatis quidem? Qui repellendus beatae sunt quia eum dolor libero inventore distinctio, deleniti asperiores eius. Consequuntur maxime animi odit quos earum eum atque iure incidunt quaerat vel fugit debitis, ex impedit iusto voluptates. Deserunt quae vero facere repellendus aliquid nam minima quidem. Perferendis, molestias rerum culpa modi maxime ipsum ex aspernatur est. Atque ipsam adipisci corporis reprehenderit ullam placeat, excepturi veniam ratione odio neque, voluptatibus incidunt unde ipsum consequuntur facilis modi rerum quod fugiat quam minus? Nisi animi dolores quae quidem distinctio ullam accusantium, non blanditiis error iure asperiores praesentium sed inventore fuga tempore dolorem, quaerat cum id quo ratione. Cupiditate sed ea esse maiores iure accusantium quidem ex eveniet, est totam libero voluptates voluptate quisquam vel incidunt porro eaque repellendus, blanditiis veritatis minus harum. Nisi quidem fugiat excepturi dicta iusto nam. Dolorem non tenetur quo voluptate praesentium, iusto neque quia tempora, omnis ipsa culpa provident. Nemo dolor magnam dignissimos! Eaque vero nam et? Assumenda, fugit sed, sint ratione laborum saepe quibusdam expedita tempora magnam dolorem quae eaque iure fugiat cumque accusantium optio distinctio veritatis aperiam! Veniam inventore et, eos laboriosam mollitia doloremque laudantium reprehenderit! Neque quibusdam error reiciendis sunt eius officia quae id fuga exercitationem cumque! Porro voluptatum unde nisi modi, exercitationem perferendis quibusdam, quasi, sapiente distinctio ea eaque? Nihil sequi eaque voluptatibus? Expedita quas maxime aliquam eum repellendus odit fugiat adipisci itaque incidunt quis esse eaque libero excepturi voluptatum, modi est assumenda fugit quos, doloremque odio corrupti quibusdam tempora nemo? Ipsam molestiae earum fuga dolorum. -->