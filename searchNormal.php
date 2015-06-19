<?php
  $matchHistory = \game\getRecentGameBySummonerId($_GET['region'], $player->id, $apiKey);
  $player->noOfGames = count($matchHistory['games']);

  for($i = 0; $i < $player->noOfGames; $i++)
  {
    if(isset($matchHistory['games'][$i]['stats']['assists']))
      $player->assists += $matchHistory['games'][$i]['stats']['assists'];
    if(isset($matchHistory['games'][$i]['stats']['numDeaths']))
      $player->deaths += $matchHistory['games'][$i]['stats']['numDeaths'];
    if(isset($matchHistory['games'][$i]['stats']['championsKilled']))
      $player->kills += $matchHistory['games'][$i]['stats']['championsKilled'];
    if(isset($matchHistory['games'][$i]['stats']['turretsKilled']))
      $player->turrets += $matchHistory['games'][$i]['stats']['turretsKilled'];
    if(isset($matchHistory['games'][$i]['stats']['wardPlaced']))
      $player->wardsPlaced += $matchHistory['games'][$i]['stats']['wardPlaced'];
    if(isset($matchHistory['games'][$i]['stats']['wardKilled']))
      $player->wardsDestroyed += $matchHistory['games'][$i]['stats']['wardKilled'];
    if(isset($matchHistory['games'][$i]['stats']['win']))
      $player->wins += $matchHistory['games'][$i]['stats']['win'];
    if(isset($matchHistory['games'][$i]['stats']['minionsKilled']))
      $player->creepScore += $matchHistory['games'][$i]['stats']['minionsKilled'];
      // trinket slot
      if(isset($matchHistory['games'][$i]['stats']['item6']))
      {
        $item = $matchHistory['games'][$i]['stats']['item6'];
        if($item == 3361 || $item == 3362 || $item == 3341 || $item == 3363)
        {
          $player->upgradedTrinkets += 1;
        }
      }
  }

  $player->averageStatistics();
  $content = "
  <h3>Your Averages</h3>
  <ul>
    <li><p>Kills: $player->kills</p></li>
    <li><p>Assists: $player->assists</p></li>
    <li><p>Deaths: $player->deaths</p></li>
    <li><p>Turrets Destroyed: $player->turrets</p></li>
    <li><p>CS: $player->creepScore</p></li>
    <li><p>Wards Placed: $player->wardsPlaced</p></li>
    <li><p>wards Destroyed: $player->wardsDestroyed</p></li>
    <li><p>Win Rate: $player->wins%</p></li>
    <li><p>You upgraded your trinkets: $player->upgradedTrinkets% of the time!</p></li>
  </ul>
  ";

  writeSearchPage($player->name, $regions, $gamemodes, $content);
?>
