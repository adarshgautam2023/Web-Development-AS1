<?php
// Include the database connection file
require 'dbConn.php';

// Check if the function 'registerU' doesn't exist
if(!function_exists('registerU')){

// Function to register a new user with email, hashed password, and name
function registerU($email, $hashedPassword, $name) {
    $conn = db_connect();

    // Check if the email is already registered
    $checkEmailQuery = "SELECT * FROM registers WHERE email = :email";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        return false;
    }

     // Insert the new user into the 'registers' table
    $insertUserQuery = "INSERT INTO registers (email, password, name) VALUES (:email, :password, :name)";
    $stmt = $conn->prepare($insertUserQuery);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':name', $name);
    $success = $stmt->execute();
    $conn = null;
    return $success;
}
}
// Check if the function 'db_connect' doesn't exist
if(!function_exists('db_connect')){

    // Function to establish a database connection
function db_connect() {
    $pdo = new PDO('mysql:dbname=assignment1;host=mysql', 'student', 'student', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    return $pdo;
}
}

// Check if the function 'loginUser' doesn't exist
if(!function_exists('loginUser')){
    // Function to authenticate and log in a user with email and password
function loginUser($email, $password) {
    $conn = db_connect();

    // Check if the email is registered
    $checkEmailQuery = "SELECT * FROM registers WHERE email = :email";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() === 0) {
        return false;
    }

    // Retrieve user data and verify the password
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (password_verify($password, $userData['password'])) {
        return $userData; 
    } else {
        return false;
    }
}
}

// Check if the function 'getCategories' doesn't exist
if(!function_exists('getCategories')){
    // Function to retrieve all categories
function getCategories() {
    try {
        $conn = db_connect();
        $query = "SELECT * FROM categories";
        $stmt = $conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log('Error during getCategories: ' . $e->getMessage());
        return false;
    }
}
}
// Check if the function 'addAuction' doesn't exist
if(!function_exists('addAuction')){
     // Function to add a new auction with title, description, category, end date, and user ID
function addAuction($title, $description, $category, $endDate, $loggedInUserId) {
    try {
        // Connect to the database
        $conn = db_connect();

        // Insert a new auction into the 'auctions' table
        $query = "INSERT INTO auctions (title, description, category_id, endDate, register_id) VALUES (:title, :description, :category, :endDate, :loggedInUserId)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':endDate', $endDate);
        $stmt->bindParam(':loggedInUserId', $loggedInUserId);

        // Execute the query and store success status
        $success = $stmt->execute();
        // Close the database connection
        $conn = null;

        // Return the success status
        return $success;
    } catch (PDOException $e) {
        // Log an error message if an exception occurs during auction addition
        error_log('Error during addAuction: ' . $e->getMessage());
         // Return false to indicate an error
        return false;
    }
}
}

// ... (similar comments for other functions)

if(!function_exists('addCategory')){
    function addCategory($name) {
        $conn = db_connect();
        $checkCategoryQuery = "SELECT * FROM categories WHERE name = :name";
        $stmt = $conn->prepare($checkCategoryQuery);
        $stmt->bindParam(':name', $name);
        $stmt->execute();
    
        if ($stmt->rowCount() > 0) {
            return false;
        }
    
        $insertCategoryQuery = "INSERT INTO categories (name) VALUES (:name)";
        $stmt = $conn->prepare($insertCategoryQuery);
        $stmt->bindParam(':name', $name);
        $success = $stmt->execute();
        $conn = null;
    
        return $success;
    }
}
if(!function_exists('editCategory')){
    function editCategory($categoryId, $newName) {
        $conn = db_connect();
        $checkCategoryQuery = "SELECT * FROM categories WHERE category_id = :category_id";
        $stmt = $conn->prepare($checkCategoryQuery);
        $stmt->bindParam(':category_id', $categoryId);
        $stmt->execute();
    
        if ($stmt->rowCount() === 0) {
            return false;
        }
    
        $updateCategoryQuery = "UPDATE categories SET name = :new_name WHERE category_id = :category_id";
        $stmt = $conn->prepare($updateCategoryQuery);
        $stmt->bindParam(':new_name', $newName);
        $stmt->bindParam(':category_id', $categoryId);
        $success = $stmt->execute();
        $conn = null;
    
        return $success;
    }
}



if (!function_exists('getCategoryById')) {
    function getCategoryById($categoryId) {
        $conn = db_connect();

        $query = "SELECT * FROM categories WHERE category_id = :category_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':category_id', $categoryId);
        $stmt->execute();

        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        $conn = null;

        return $category;
    }
}

if (!function_exists('deleteCategory')) {
    function deleteCategory($categoryId)
    {
        $conn = db_connect();

        try {
            $deleteProductsQuery = "DELETE FROM auctions WHERE category_id = :category_id";
            $stmt = $conn->prepare($deleteProductsQuery);
            $stmt->bindParam(':category_id', $categoryId);
            $stmt->execute();

            $deleteCategoryQuery = "DELETE FROM categories WHERE category_id = :category_id";
            $stmt = $conn->prepare($deleteCategoryQuery);
            $stmt->bindParam(':category_id', $categoryId);
            $stmt->execute();
            $conn = null;

            return true;
        } catch (PDOException $e) {
            error_log('Error during deleteCategory: ' . $e->getMessage());
            return false;
        }
    }
}
if(!function_exists('getUserAuctions')){
function getUserAuctions($pdo, $userID) {
    $userAuctionsQuery = $pdo->prepare("SELECT * FROM auctions WHERE register_id = :userID");
    $userAuctionsQuery->bindParam(':userID', $userID);
    $userAuctionsQuery->execute();
    return $userAuctionsQuery->fetchAll(PDO::FETCH_ASSOC);
}
}
if(!function_exists('getAuctionById')){
function getAuctionById($pdo, $auctionID) {
    $auctionQuery = $pdo->prepare("SELECT * FROM auctions WHERE auction_id = :auctionID");
    $auctionQuery->bindParam(':auctionID', $auctionID);
    $auctionQuery->execute();
    return $auctionQuery->fetch(PDO::FETCH_ASSOC);
}
}
if(!function_exists('updateAuction')){
    function updateAuction($pdo, $auctionID, $title, $category, $description) {
        $updateAuctionQuery = $pdo->prepare("UPDATE auctions SET title = :title, category_id = :category, description = :description WHERE auction_id = :auctionID");
        $updateAuctionQuery->bindValue(':title', $title);
        $updateAuctionQuery->bindValue(':category', $category);
        $updateAuctionQuery->bindValue(':description', $description);
        $updateAuctionQuery->bindValue(':auctionID', $auctionID);
        return $updateAuctionQuery->execute();
    }
    
}
?>


































































<!-- Lorem ipsum dolor sit amet consectetur adipisicing elit. Asperiores, quidem! Quasi, molestiae. Quis deleniti id voluptatum omnis eveniet non dignissimos, dolorum itaque reprehenderit asperiores! Ullam expedita nemo minus dolore tempora. Eaque, adipisci velit accusantium, fuga quam laudantium, beatae itaque corrupti alias sed sunt quod hic sint eos veritatis. Facilis est voluptatum pariatur vitae quo. Delectus sequi ut sunt eius ex nulla dolore velit placeat pariatur voluptas consequatur vitae voluptatibus perspiciatis sapiente, rem reiciendis repellendus dignissimos ipsum id debitis laudantium voluptatum similique. Velit eos cupiditate tempore, beatae impedit dolores mollitia voluptates nostrum delectus itaque. Exercitationem sunt ullam et animi omnis eum aspernatur, ab accusantium rem perspiciatis atque veniam a praesentium qui optio possimus sequi inventore asperiores quo quia ipsam ad earum suscipit? Repellat, provident soluta recusandae quo qui quaerat possimus magnam sapiente cumque laborum magni, sequi atque nam culpa quisquam nisi repellendus. A eius molestiae sequi harum molestias ut laboriosam natus sit assumenda mollitia odio ad, corporis aspernatur voluptas. Explicabo, nostrum! Dolores nesciunt quisquam explicabo consectetur totam laboriosam alias sapiente veniam corrupti, aliquid qui asperiores porro id iste repudiandae perspiciatis? Sint adipisci doloribus minus at quo vitae tempora, nostrum magni praesentium aliquid dicta quod exercitationem esse pariatur qui necessitatibus accusantium, eaque labore beatae, natus quos mollitia velit? Aliquam impedit amet inventore rerum, labore odit nesciunt repudiandae excepturi architecto? Laboriosam amet quia suscipit. Perferendis a pariatur unde minus nisi beatae. Provident blanditiis tempora asperiores ducimus quasi reprehenderit alias vero ut, cum non eum dolorum delectus earum laudantium, ab dolore corporis ex quaerat vitae quia pariatur quod cupiditate? Eveniet, amet! Odit amet suscipit reprehenderit eum explicabo, voluptas rerum dicta doloribus rem quo? Ipsum, magni? Deleniti, minus libero! Quasi eveniet id velit expedita voluptates corporis facilis reiciendis dolorum, eos nemo labore molestias adipisci illo. Obcaecati libero ex pariatur accusantium cumque eum ipsum, recusandae, repellat ab rem fugiat quo perferendis nisi animi. Saepe, dolor. Non, ipsa temporibus. Asperiores iusto harum eveniet laborum beatae corrupti vel voluptatem esse. Magni numquam laudantium nihil saepe dolorum explicabo earum a quod, illo odio praesentium odit repellendus hic, enim quam iste, nesciunt vitae laboriosam? Suscipit dolore explicabo consequuntur pariatur nisi ducimus omnis magnam commodi, temporibus impedit, veritatis et corporis mollitia fugit voluptatibus! Nulla suscipit nisi totam praesentium atque. Saepe nobis nam impedit veniam ut soluta obcaecati iusto necessitatibus dolorum amet nulla repellendus, a unde eos ipsum. Debitis atque placeat fugit a fugiat, facere omnis unde neque consequatur aliquid explicabo? Distinctio minus fuga tenetur dicta? Provident velit, sed sequi laudantium cupiditate et quo eveniet? Corporis id tempore cum, veniam expedita voluptates. Enim, optio aut minus placeat iste alias voluptatibus. Vel repudiandae in quisquam maxime sapiente tenetur, eaque totam, ipsa voluptas commodi consequuntur quod. Molestias ducimus quaerat eligendi numquam non. Ab, vitae a, voluptates cupiditate possimus officia id eius eum minus, optio temporibus esse provident blanditiis! Aliquid hic ullam sunt esse libero. Repudiandae odit facere molestiae mollitia sequi. Esse dolorum sapiente dicta natus, optio architecto iste iusto quia recusandae, praesentium magnam enim tenetur quae officia nihil voluptatum saepe odit doloribus fugit deserunt necessitatibus dolore ex fugiat. Corporis ratione facere voluptatibus quam quo nihil, ea repudiandae debitis? Aspernatur ex voluptas ad commodi doloremque cum eum in labore debitis non optio accusantium illo neque reiciendis modi, distinctio tempore error eaque. At quo sapiente dolore repellat expedita eaque temporibus nam iure fugiat non recusandae, repudiandae assumenda veritatis tempora dolores, aliquam aut explicabo maiores sit modi reiciendis eveniet? Ipsum, sapiente inventore reiciendis maiores rerum quo nihil. Dolorum velit doloribus hic, ipsum ipsa laborum reprehenderit qui, magni placeat adipisci voluptatibus ratione quo dicta porro deserunt. Consectetur ipsum laudantium vel nam sequi nemo, odit, illo nisi recusandae reprehenderit non expedita et velit est quidem neque accusamus aliquid quae debitis excepturi veniam nulla dolore quod placeat! Ipsum perspiciatis adipisci est omnis veritatis? Vel eveniet laudantium rerum totam quam commodi recusandae atque, adipisci praesentium delectus consectetur accusantium doloribus nam ab vero suscipit autem sint? At sequi voluptatibus pariatur atque quaerat accusamus repudiandae soluta possimus accusantium dolorem quis ratione, aperiam odit perspiciatis aliquid harum impedit mollitia debitis assumenda eius libero quidem dolores. Veritatis aut necessitatibus ipsa libero reiciendis! Modi officia repellendus hic molestias beatae illo distinctio perspiciatis fuga, dignissimos reprehenderit non rem sit. Nulla veniam vero assumenda non tempore quia qui, modi recusandae alias? Delectus, cupiditate velit officiis corrupti iste beatae modi ad nobis illo quae, deserunt eos enim doloribus eligendi? Rerum natus dignissimos autem debitis sint animi. Sunt dolorem, fugit beatae tempore possimus autem eligendi cum provident aliquid quo itaque rerum, assumenda consequatur perferendis magnam ipsam minus, mollitia laborum quod cupiditate ipsum id veritatis quidem? Qui repellendus beatae sunt quia eum dolor libero inventore distinctio, deleniti asperiores eius. Consequuntur maxime animi odit quos earum eum atque iure incidunt quaerat vel fugit debitis, ex impedit iusto voluptates. Deserunt quae vero facere repellendus aliquid nam minima quidem. Perferendis, molestias rerum culpa modi maxime ipsum ex aspernatur est. Atque ipsam adipisci corporis reprehenderit ullam placeat, excepturi veniam ratione odio neque, voluptatibus incidunt unde ipsum consequuntur facilis modi rerum quod fugiat quam minus? Nisi animi dolores quae quidem distinctio ullam accusantium, non blanditiis error iure asperiores praesentium sed inventore fuga tempore dolorem, quaerat cum id quo ratione. Cupiditate sed ea esse maiores iure accusantium quidem ex eveniet, est totam libero voluptates voluptate quisquam vel incidunt porro eaque repellendus, blanditiis veritatis minus harum. Nisi quidem fugiat excepturi dicta iusto nam. Dolorem non tenetur quo voluptate praesentium, iusto neque quia tempora, omnis ipsa culpa provident. Nemo dolor magnam dignissimos! Eaque vero nam et? Assumenda, fugit sed, sint ratione laborum saepe quibusdam expedita tempora magnam dolorem quae eaque iure fugiat cumque accusantium optio distinctio veritatis aperiam! Veniam inventore et, eos laboriosam mollitia doloremque laudantium reprehenderit! Neque quibusdam error reiciendis sunt eius officia quae id fuga exercitationem cumque! Porro voluptatum unde nisi modi, exercitationem perferendis quibusdam, quasi, sapiente distinctio ea eaque? Nihil sequi eaque voluptatibus? Expedita quas maxime aliquam eum repellendus odit fugiat adipisci itaque incidunt quis esse eaque libero excepturi voluptatum, modi est assumenda fugit quos, doloremque odio corrupti quibusdam tempora nemo? Ipsam molestiae earum fuga dolorum. -->