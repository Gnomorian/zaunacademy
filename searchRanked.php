<?php
  function getParticipantId($participantIdentities, $summonerName)
  {
    for($i = 0; $i < count($participantIdentities); $i++)
    {
      if($participantIdentities[$i]['player']['summonerName'] == $summonerName)
      {
        return $participantIdentities[$i]['participantId'];
      }
    }
  }

  function getParticipantIdOfOposingRole($participantId, $participants, $playerRole)
  {
    for($i = 0; $i < count($participants); $i++)
    {
      if($i == $participantId)
        continue;

      if(getParticipantRole($i, $participants) == $playerRole)
      {
        return $i;
      }
    }
  }

  function getParticipantRole($participantId, $participants)
  {
    for($i = 0; $i < count($participants); $i++)
    {
      if($participants[$i]['participantId'] == $participantId)
      {
        if(isset($participants[$i]['timeline']['role']) || isset($participants[$i]['timeline']['lane']))
        {
          $role = $participants[$i]['timeline']['role'];
          $lane = $participants[$i]['timeline']['lane'];

          if($lane == "BOT" || $lane == "BOTTOM")
          {
            if($role == "DUO_CARRY")
            {
              return "ADC";
            }
            else
            {
              return "SUP";
            }
          }
          else
          {
            return $lane;
          }
        }
        return null;
      }
    }
  }

  function setPlayerStats($player, $participantId, $match)
  {
    for($j = 0; $j < count($match['participants']); $j++)
    {
      if($match['participants'][$j]['participantId'] == $participantId)
      {
        if(isset($match['participants'][$j]['stats']['kills']))
          $player->kills += $match['participants'][$j]['stats']['kills'];
        if(isset($match['participants'][$j]['stats']['assists']))
          $player->assists += $match['participants'][$j]['stats']['assists'];
        if(isset($match['participants'][$j]['stats']['deaths']))
          $player->deaths += $match['participants'][$j]['stats']['deaths'];
        if(isset($match['participants'][$j]['stats']['towerKills']))
          $player->turrets += $match['participants'][$j]['stats']['towerKills'];
        if(isset($match['participants'][$j]['stats']['minionsKilled']))
          $player->creepScore += $match['participants'][$j]['stats']['minionsKilled'];
          // for whatever stupid reason, removing this screws up cs at 20 minutes, theres nothging wrong with this!
          if($j =7)
          {
            //echo($match['timeline']['frames'][20]['participantFrames'][$j]['minionsKilled'].":".$player->creepScore20m."  ");
          }
        if(isset($match['timeline']['frames'][20]['participantFrames'][$j]['minionsKilled']))
          $player->creepScore20m += $match['timeline']['frames'][20]['participantFrames'][$j]['minionsKilled'];
        if(isset($match['participants'][$j]['stats']['wardsPlaced']))
          $player->wardsPlaced += $match['participants'][$j]['stats']['wardsPlaced'];
        if(isset($match['participants'][$j]['stats']['wardsKilled']))
          $player->wardsDestroyed += $match['participants'][$j]['stats']['wardsKilled'];
        if(isset($match['participants'][$j]['stats']['winner']))
          $player->wins += $match['participants'][$j]['stats']['winner'];
        array_push($player->role, getParticipantRole($participantId, $match['participants']));
        $item = $match['participants'][$j]['stats']['item6'];
        if($item == 3361 || $item == 3362 || $item == 3341 || $item == 3363)
        {
          $player->upgradedTrinkets += 1;
        }
      }
    }
  }

  $matchHistory = \matchHistory\getMatchHistoryBySummonerId($_GET['region'], $player->id, $apiKey);
  $player->noOfGames = count($matchHistory['matches']);
  $oponent->noOfGames = count($matchHistory['matches']);
  $matchIds = array();
  // get the match ids
  for($i = 0; $i < $player->noOfGames; $i++)
  {
    $matchIds[$i] = $matchHistory['matches'][$i]['matchId'];
  }
  // fill player and oponent stats statistics
  for($i = 0; $i < count($matchIds); $i++)
  {
    $match = \match\getMatchById($_GET['region'], $matchIds[$i], $apiKey);
    $participantId = getParticipantId($match['participantIdentities'], $player->name);
    setPlayerStats($player, $participantId, $match);
    setPlayerStats($oponent, getParticipantIdOfOposingRole($participantId, $match['participants'], $player->role[count($player->role)-1]), $match);
  }
  $player->averageStatistics();
  $oponent->averageStatistics();
  $content = "
  <h3>Your Averages</h3>
  <ul>
    <li><p>Position: ".implode(array_unique($player->role), ', ')."<p></li>
    <li><p>Kills: $player->kills</p></li>
    <li><p>Assists: $player->assists</p></li>
    <li><p>Deaths: $player->deaths</p></li>
    <li><p>Turrets Destroyed: $player->turrets</p></li>
    <li><p>CS at 20 Minutes: $player->creepScore20m</p></li>
    <li><p>Total CS: $player->creepScore</p></li>
    <li><p>Wards Placed: $player->wardsPlaced</p></li>
    <li><p>wards Destroyed: $player->wardsDestroyed</p></li>
    <li><p>Win Rate: $player->wins%</p></li>
    <li><p>You upgraded your trinkets: $player->upgradedTrinkets% of the time!</p></li>
  </ul>
  <br>
  <h3>Your Oponents Averages</h3>
  <ul>
    <li><p>Kills: $oponent->kills</p></li>
    <li><p>Assists: $oponent->assists</p></li>
    <li><p>Deaths: $oponent->deaths</p></li>
    <li><p>Turrets Destroyed: $oponent->turrets</p></li>
    <li><p>CS at 20 Minutes: $oponent->creepScore20m</p></li>
    <li><p>Total CS: $oponent->creepScore</p></li>
    <li><p>Wards Placed: $oponent->wardsPlaced</p></li>
    <li><p>wards Destroyed: $oponent->wardsDestroyed</p></li>
    <li><p>You upgraded your trinkets: $oponent->upgradedTrinkets% of the time!</p></li>
  </ul>
  ";

  writeSearchPage($player->name, $regions, $gamemodes, $content);
?>
