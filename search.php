<?php
require(dirname(__DIR__)."/zaunacademy/PHPriotAPI/riotAPI.php");
require(dirname(__DIR__)."/zaunacademy/layouts/searchPage.php");

$apiKey = "201a3c6c-87f0-4a3a-a65f-910fa3570316";

/*
  makes the selected region the same as the previously
  selected region for ease of use.
*/
$regions = array();
$regions['br'] = "";
$regions['eune'] = "";
$regions['euw'] = "";
$regions['kr'] = "";
$regions['lan'] = "";
$regions['las'] = "";
$regions['na'] = "";
$regions['oce'] = "";
$regions['ru'] = "";
$regions['tr'] = "";
$regions[$_GET['region']] = 'selected';

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

  $errorMessage =
  $content = "
    <p>$errorMessage</p>
  ";
  writeSearchPage($_GET['summoner'], $regions, $content);
}
else
{
  $summoner = $summoners[$_GET['summoner']];
  $summonerId = $summoner['id'];
  // get the summoners recent 10 games
  $matchHistory = \game\getRecentGameBySummonerId($_GET['region'], $summonerId, $apiKey);

  $noOfGames = count($matchHistory['games']);

  $upgradedTrinkets = 0;
  $assists = 0;
  $kills = 0;
  $turrets = 0;
  $deaths = 0;
  $wardsPlaced = 0;
  $wardsDestroyed = 0;
  $creepScore = 0;
  $wins = 0;

  for($i = 0; $i < $noOfGames; $i++)
  {
    if(isset($matchHistory['games'][$i]['stats']['assists']))
      $assists += $matchHistory['games'][$i]['stats']['assists'];
    if(isset($matchHistory['games'][$i]['stats']['numDeaths']))
      $deaths += $matchHistory['games'][$i]['stats']['numDeaths'];
    if(isset($matchHistory['games'][$i]['stats']['championsKilled']))
      $kills += $matchHistory['games'][$i]['stats']['championsKilled'];
    if(isset($matchHistory['games'][$i]['stats']['turretsKilled']))
      $turrets += $matchHistory['games'][$i]['stats']['turretsKilled'];
    if(isset($matchHistory['games'][$i]['stats']['wardPlaced']))
      $wardsPlaced += $matchHistory['games'][$i]['stats']['wardPlaced'];
    if(isset($matchHistory['games'][$i]['stats']['wardKilled']))
      $wardsDestroyed += $matchHistory['games'][$i]['stats']['wardKilled'];
    if(isset($matchHistory['games'][$i]['stats']['win']))
      $wins += $matchHistory['games'][$i]['stats']['win'];
    if(isset($matchHistory['games'][$i]['stats']['minionsKilled']))
      $creepScore += $matchHistory['games'][$i]['stats']['minionsKilled'];
      // trinket slot
      if(isset($matchHistory['games'][$i]['stats']['item6']))
      {
        $item = $matchHistory['games'][$i]['stats']['item6'];
        if($item == 3361 || $item == 3362 || $item == 3341 || $item == 3363)
        {
          $upgradedTrinkets += 1;
        }
      }
  }
  // stop devision by 0 errors
  if($assists != 0)
    $assists /= $noOfGames;
    if($kills != 0)
    $kills /= $noOfGames;
  if($turrets != 0)
    $turrets /= $noOfGames;
  if($deaths != 0)
    $deaths /= $noOfGames;
  if($wardsPlaced != 0)
    $wardsPlaced /= $noOfGames;
  if($wardsDestroyed != 0)
    $wardsDestroyed /= $noOfGames;
  if($creepScore != 0)
    $creepScore /= $noOfGames;
  if($wins != 0)
    $wins = ($wins / $noOfGames) * 100;
  if($upgradedTrinkets != 0)
    $upgradedTrinkets = ($upgradedTrinkets / $noOfGames) * 100;

  // page layout to be inserted into the document
  $content = "
  <ul>
    <li><p>Kills: $kills</p></li>
    <li><p>Assists: $assists</p></li>
    <li><p>Deaths: $deaths</p></li>
    <li><p>Turrets Destroyed: $turrets</p></li>
    <li><p>CS: $creepScore</p></li>
    <li><p>Wards Placed: $wardsPlaced</p></li>
    <li><p>wards Destroyed: $wardsDestroyed</p></li>
    <li><p>Win Rate: $wins%</p></li>
    <li><p>You upgraded your trinkets: $upgradedTrinkets% of the time!</p></li>
  </li>
  ";

  writeSearchPage($summoner['name'], $regions, $content);
}


?>
