<?php
require_once 'sys/settings.php';
require_once 'sys/init.php';
require __DIR__ . '/vendor/autoload.php';

$data['navbar']['newItemActive'] = true;
$data['saveNextTagValue'] = "#saveNextTag!666";

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $headInsert = $mysqli->prepare("INSERT INTO `dishes`(`name`,`description`,`recipe_location`,`cooking_time`) VALUES(?,?,?,?)");
    $headInsert->bind_param("sssd", $_POST['name'], $_POST['description'], $_POST['recipeLocation'], $_POST['cookingTime']);

    $tagInsert = $mysqli->prepare("INSERT INTO `tags`(`name`) VALUES(?)");
    $tagInsert->bind_param("s", $newTagName);

    $dishTagInsert = $mysqli->prepare("INSERT INTO `dish_tag`(`dish`,`tag`) VALUES(?,?)");
    $dishTagInsert->bind_param("ii", $headID, $tagID);
    
    $headInsert->execute();
    $headID = $mysqli->insert_id;
    
    foreach ($_POST['tags'] as $tagID) {
        $dishTagInsert->execute();
    }

    $saveNext = false;
    foreach ($_POST['newTag'] as $value) 
    {        
        if ($saveNext && !empty($value)) 
        {
            $isOldTag = false;
            $newTagName = $value;
    
            $checkNewTag = $mysqli->prepare("SELECT `id` FROM `tags` WHERE lower(`name`) = lower(?)");
            $checkNewTag->bind_param("s", $newTagName);
            $checkNewTag->execute();
            $checkNewTag->bind_result($tagID);
            while($checkNewTag->fetch())
            {
                $isOldTag = true;
            }

            if(!$isOldTag)
            {
                $tagInsert->execute();
                $tagID = $mysqli->insert_id;
            }
            
            $dishTagInsert->execute();
            $saveNext = false;
        }

        if($value == $data['saveNextTagValue'])
        {
            $saveNext = true;
        }
    }
    $data['alert']['show'] = true;
    $data['alert']['type'] = "alert-success";
    $data['alert']['title'] = "Gespeichert";
    $data['alert']['message'] = "Das Gericht \"".$_POST['name']."\" wurde gespeichert";
}
$data['tags'] = $mysqli->query("SELECT `id`, `name` FROM `tags` ORDER BY `name` ASC");
$data['dishCookingTime'] = 30;
$tpl = $mustache->loadTemplate('insert');
echo $tpl->render($data);