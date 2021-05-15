<?php
require_once 'sys/settings.php';
require_once 'sys/init.php';
require __DIR__ . '/vendor/autoload.php';

$maxTags = 4;
$maxDescriptionWords = 75;

$data['navbar']['editItemActive'] = true;
function startsWith($haystack, $needle) {
    return substr_compare($haystack, $needle, 0, strlen($needle)) === 0;
}
function endsWith($haystack, $needle) {
    return substr_compare($haystack, $needle, -strlen($needle)) === 0;
}
if($_GET['action'] == 'view')
{
    $data['navbar']['editItemActive'] = false;
    $data['navbar']['homeActive'] = true;

    $dishToDeleteSelect = $mysqli->prepare("SELECT `id`, `name`, `description`, `recipe_location`, `cooking_time` from `dishes` where `id` = ?");
    $dishToDeleteSelect->bind_param("i", $_GET['id']);
    $dishToDeleteSelect->execute();
    $dishToDeleteSelect->bind_result($data['dishID'], $data['dishName'], $data['dishDescription'], $data['dishRecipeLocation'], $data['dishCookingTime']);
    $dishToDeleteSelect->fetch();
    $dishToDeleteSelect->close();

    if(startsWith($data['dishRecipeLocation'], 'http'))
    {
        $data['dishLink'] = true;
    } else 
    {
        $data['dishReference'] = true;
    }

    $tagSelect = $mysqli->prepare("SELECT `id`, `name` FROM `tags` JOIN `dish_tag` ON `dish_tag`.`tag` = `tags`.`id` WHERE `dish_tag`.`dish` = ? ORDER BY (SELECT count(*) FROM `dish_tag` where `tag` = `id`) DESC, `name` ASC");
    $tagSelect->bind_param("i", $data['dishID']);
    $tagSelect->execute();
    $tagSelect->bind_result($tagID, $tagName);
    $i = 0;
    while($tagSelect->fetch())
    {
        $data['tags'][$i]['id'] = $tagID;
        $data['tags'][$i]['name'] = $tagName;
        $i++;
    }
    if(is_array($data['tags']))
    $data['tags'] = new ArrayIterator($data['tags']);
    $tagSelect->close();

    $tpl = $mustache->loadTemplate('viewDish');
    echo $tpl->render($data);
    exit;
}

if($_GET['action'] == 'delete')
{
    $dishToDeleteSelect = $mysqli->prepare("SELECT `id`, `name`, `description`, `recipe_location`, `cooking_time` from `dishes` where `id` = ?");
    $dishToDeleteSelect->bind_param("i", $_GET['id']);
    $dishToDeleteSelect->execute();
    $dishToDeleteSelect->bind_result($dishID, $dishName, $dishDescription, $dishRecipeLocation, $dishCookingTime);
    $dishToDeleteSelect->fetch();
    $dishToDeleteSelect->close();
    $dishDelete = $mysqli->prepare("DELETE FROM `dishes` WHERE `id` = ?");
    $dishDelete->bind_param("i", $_GET['id']);
    $dishDelete->execute();
    $data['alert']['show'] = true;
    $data['alert']['type'] = "alert-success";
    $data['alert']['title'] = "Gelöscht!";
    $data['alert']['message'] = "Das Gericht \"".$dishName."\" wurde gelöscht.";
}

if($_GET{'action'} == 'edit')
{
    if($_SERVER['REQUEST_METHOD'] == 'GET')
    {
        $dishToDeleteSelect = $mysqli->prepare("SELECT `id`, `name`, `description`, `recipe_location`, `cooking_time` from `dishes` where `id` = ?");
        $dishToDeleteSelect->bind_param("i", $_GET['id']);
        $dishToDeleteSelect->execute();
        $dishToDeleteSelect->bind_result($data['dishID'], $data['dishName'], $data['dishDescription'], $data['dishRecipeLocation'], $data['dishCookingTime']);
        $dishToDeleteSelect->fetch();
        $dishToDeleteSelect->close();

        $tagsSelect = $mysqli->prepare("SELECT `id`, `name`, `dish` is not null as HasTag
        FROM `tags`
            LEFT JOIN `dish_tag`
                ON `tags`.`id` = `dish_tag`.`tag`
                AND `dish_tag`.`dish` = ?");
        $tagsSelect->bind_param("i", $data['dishID']);
        $tagsSelect->execute();
        $tagsSelect->bind_result($tagID, $tagName, $tagIsSelected);
        $i = 0;
        while($tagsSelect->fetch())
        {
            $data['tags'][$i]['id'] = $tagID;
            $data['tags'][$i]['name'] = $tagName;
            $data['tags'][$i]['isSelected'] = $tagIsSelected;
            if($tagIsSelected)
            {
                $data['tags'][$i]['checkedState'] = 'checked';
            } else 
            {
                $data['tags'][$i]['checkedState'] = '';
            } 
            $i++;
        }

        $tpl = $mustache->loadTemplate('editDish');
        echo $tpl->render($data);
        exit;
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $dishUpdate = $mysqli->prepare("UPDATE `dishes` SET
        `name` = ?,
        `description` = ?,
        `recipe_location` = ?,
        `cooking_time` = ?
        WHERE `id` = ?");
        $dishUpdate->bind_param("sssdi", $_POST['name'], $_POST['description'], $_POST['recipeLocation'], $_POST['cookingTime'], $_GET['id']);
        $dishUpdate->execute();

        $tagsDelete = $mysqli->prepare("DELETE FROM `dish_tag` WHERE `dish` = ?");
        $tagsDelete->bind_param("i", $_GET['id']);
        $tagsDelete->execute();

        $dishTagInsert = $mysqli->prepare("INSERT INTO `dish_tag`(`dish`,`tag`) VALUES(?,?)");
        $dishTagInsert->bind_param("ii", $_GET['id'], $tagID);
        
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
}

// Get Data
$dishSelect = $mysqli->prepare("SELECT `id`, `name`, `description`, `recipe_location`, `cooking_time` from `dishes`");
$dishSelect->execute();
$dishSelect->bind_result($dishID, $dishName, $dishDescription, $dishRecipeLocation, $dishCookingTime);
while($dishSelect->fetch())
{
    $data['dishes'][$dishID]['id'] = $dishID;
    $data['dishes'][$dishID]['name'] = $dishName;
    $data['dishes'][$dishID]['description'] = $dishDescription;
    $data['dishes'][$dishID]['recipe_location'] = $dishRecipeLocation;
    $data['dishes'][$dishID]['cooking_time'] = $dishCookingTime;
    $data['dishes'][$dishID]['action_delete'] = "?action=delete&id=".$dishID;
    $data['dishes'][$dishID]['action_edit'] = "?action=edit&id=".$dishID;
    $data['dishes'][$dishID]['action_view'] = "?action=view&id=".$dishID;

    // shrink description to max words
    $descParts = explode(' ', $data['dishes'][$dishID]['description'], $maxDescriptionWords + 1);
    if(count($descParts) > $maxDescriptionWords)
    {
        $descParts[$maxDescriptionWords] = '(...)';
        $data['dishes'][$dishID]['description'] = implode(' ', $descParts);
    }
}
$dishSelect->close();
$tagSelect = $mysqli->prepare("SELECT `id`, `name` FROM `tags` JOIN `dish_tag` ON `dish_tag`.`tag` = `tags`.`id` WHERE `dish_tag`.`dish` = ? ORDER BY (SELECT count(*) FROM `dish_tag` where `tag` = `id`) DESC, `name` ASC");
$tagSelect->bind_param("i", $dishID);
foreach ($data['dishes'] as $dishID => $dish) 
{
    $tagSelect->execute();
    $tagSelect->bind_result($tagID, $tagName);
    $i = 0;
    while($tagSelect->fetch() && $i < $maxTags)
    {
        $data['dishes'][$dishID]['tags'][$tagID] = $tagName;
        $i ++;
    }
    if($i == $maxTags)
    {
        $data['dishes'][$dishID]['tags'][0] = '(...)';
    }
    if(is_array($data['dishes'][$dishID]['tags']))
    $data['dishes'][$dishID]['tags'] = new ArrayIterator($data['dishes'][$dishID]['tags']);
}
$tagSelect->close();
$data['dishes'] = new ArrayIterator($data['dishes']);

if(empty($_GET['view'])) $view = 'editOverviewTable';
else $view = $_GET['view'];
$tpl = $mustache->loadTemplate($view);
echo $tpl->render($data);