<?php
require(dirname(__DIR__)."/zaunacademy/PHPriotAPI/riotAPI.php");
require(dirname(__DIR__)."/zaunacademy/layouts/searchPage.php");
require(dirname(__DIR__)."/zaunacademy/zaunlib.php");

$apiKey = "201a3c6c-87f0-4a3a-a65f-910fa3570316";

/*
  makes the selected region the same as the previously
  selected region for ease of use.
*/
$regions = array();
$regions['br']    = "";
$regions['eune']  = "";
$regions['euw']   = "";
$regions['kr']    = "";
$regions['lan']   = "";
$regions['las']   = "";
$regions['na']    = "";
$regions['oce']   = "";
$regions['ru']    = "";
$regions['tr']    = "";
$regions[$_GET['region']] = 'selected';

/*
  makes the selected gamemode the same as the previously
  selected gamemode for ease of use.
*/
$gamemodes = array();
$gamemodes['normal'] = "";
$gamemodes['ranked'] = "";
$gamemodes[$_GET['gamemode']] = "checked";

// get the summoner id and locolized name
$summoners = \summoner\getSummonersByName($_GET['region'], $_GET['summoner'], $apiKey);
// if query to riotapi fails do alternative page
if(!is_array($summoners))
{
  if(strpos($summoners, "400"))
  {
    $errorMessage = "Error 400: Bad Request";
  }
  else if(strpos($summoners, "401"))
  {
    $errorMessage = "Error 401: Website is currently down, there are issues with the API Keys.";
  }
  else if(strpos($summoners, "404"))
  {
    $errorMessage = "Error 404: No Summoner by that name, are you sure you selected the right server?";
  }
  else if(strpos($summoners, "422"))
  {
    $errorMessage = "Error 422: Found summoner information, but they havent played since 2013 so no data is saved.";
  }
  else if(strpos($summoners, "429"))
  {
    $errorMessage = "Error 429: Site is Overloaded, Try again soon.";
  }
  else if(strpos($summoners, "500"))
  {
    $errorMessage = "Error 500: Riots Servers are havin a bit of a tiff, come back soon.";
  }
  else if(strpos($summoners, "503"))
  {
    $errorMessage = "Error 503: Riot has currently disabled a feature that breaks the site, try again later.";
  }
  else if(strpos($summoners, "1Million"))
  {
    $errorMessage = "Error 1Million dollars!: No Internet Connection";
  }

  $errorMessage =
  $content = "
    <p>$errorMessage</p>
  ";
  writeSearchPage($_GET['summoner'], $regions, $gamemodes, $content);
}
else
{
  $player = new SummonerStats();
  $summoner = $summoners[$_GET['summoner']];
  $player->name = $summoner['name'];
  $player->id = $summoner['id'];

  $oponent = new SummonerStats();
  if($_GET['gamemode'] == "normal")
  {
    require(dirname(__DIR__)."/zaunacademy/searchNormal.php");
  }
  else
  {
    require(dirname(__DIR__)."/zaunacademy/searchRanked.php");
  }
}
?>
