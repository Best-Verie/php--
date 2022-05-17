<?php
// headers

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');

include_once '../../config/Database.php';
include_once '../../models/Post.php';


// instantiate DB & connect

$database  = new Database();
$db        = $database->connect();

// instantiate blog post object

$post = new Post($db);

// get raw posted data

$result = $post->read();
$num = $result->rowCount();

// check if any posts
if($num > 0) {
    // post array
    $posts_arr = array();
    $posts_arr['data'] = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $post_item = array(
            'id' => $id,
            'category_id' => $category_id,
            'category_name' => $category_name,
            'title' => $title,
            'body' => html_entity_decode($body),
            'author' => $author,
            'created_at' => $created_at
        );

        // push to "data"
        array_push($posts_arr['data'], $post_item);
    }

    // turn to json & output
    echo json_encode($posts_arr);
} else {
    // no posts
    echo json_encode(
        array('message' => 'No Posts Found')
    );
}

?>