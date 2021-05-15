<?php
require_once 'sys/settings.php';
require_once 'sys/init.php';
require __DIR__ . '/vendor/autoload.php';

$data['navbar']['homeActive'] = true;

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $dishes = array();

    $dishIdQuery = $mysqli->prepare("SELECT `id` FROM `dishes` WHERE `cooking_time` <= ?");
    $dishIdQuery->bind_param("d", $_POST['maxCookingTime']);
    $dishIdQuery->execute();
    $dishIdQuery->bind_result($dishID);
    $i = 0;
    while($dishIdQuery->fetch())
    {
        $dishes[$i]['id'] = $dishID;
        $i++;
    }
    unset($dishID);
    unset($i);

    $dishTagsQuery = $mysqli->prepare("SELECT `tag` FROM `dish_tag` WHERE `dish` = ?");
    $dishTagsQuery->bind_param("i", $dishID);
    $dishTagsQuery->bind_result($tagID);
    foreach ($dishes as $key => $value) 
    {
        $dishID = $value['id'];
        $dishTagsQuery->execute();
        $i = 0;
        while($dishTagsQuery->fetch())
        {
            $dishes[$key]['tags'][$i] = $tagID;
            $i++;
        }
    }

    if(is_array($_POST['tags']))
    {
        foreach ($dishes as $key => $value) 
        {
            foreach ($_POST['tags'] as $wantedTag) 
            {
                if(!in_array($wantedTag, $value['tags']))
                {
                    unset($dishes[$key]);
                }
            }
        }
    }

    if(count($dishes) > 0) 
    {
        header("Location: edit.php?action=view&id=".array_values($dishes)[random_int(0, count($dishes)-1)]['id']);
        exit();
    }

    $tagInfoSelect = $mysqli->prepare("SELECT `name` FROM `tags` WHERE `id` = ?");
    $tagInfoSelect->bind_param("i", $tagID);
    $tagInfoSelect->bind_result($tagName);
    $wantedTags = array();
    foreach ($_POST['tags'] as $tagKey => $tagID) 
    {
        $tagInfoSelect->execute();
        $tagInfoSelect->fetch();
        $wantedTags[$tagKey] = $tagName;
    }
    $tagInfoSelect->close();

    $data['alert']['show'] = true;
    $data['alert']['type'] = "alert-info";
    $data['alert']['title'] = "Kein Gericht gefunden";
    $data['alert']['message'] = "Es wurde kein Gericht gefunden welches unter ".$_POST['maxCookingTime']." Minuten Kochzeit benötigt und die Tags \"".implode("\", \"", $wantedTags)."\" enthält";
}

$data['tags'] = $mysqli->query("SELECT `id`, `name` FROM `tags` WHERE `id` in (SELECT DISTINCT `tag` FROM `dish_tag`) ORDER BY `name` ASC");
$maxCookingTimeQuery = $mysqli->query("SELECT max(`cooking_time`) as cooking_time FROM `dishes`");
$maxCookingTimeObj = $maxCookingTimeQuery->fetch_object();
$data['maxCookingTime'] = $maxCookingTimeObj->cooking_time;


$tpl = $mustache->loadTemplate('index');
echo $tpl->render($data);