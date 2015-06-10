<html>
<head>
<title>Zaun Academy</title>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="styles/search_style.css">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Shows your average ingame stats to see what you need to improve on">
<meta name="keywords" content="lol,league of legends,statistics,stats,school,improve">
<meta name="author" content="William 'Wicam' Cameron">
<script type="text/javascript" src="scripts/searchScripts.js"></script>
</head>

<body>
  <div class="header">
    <div class="formContainer">
      <form action="search.php" formmethod="get" onsubmit="return validateForm()" name="querySummoner">
        <input type="hidden" name="region" value="br"></input>
        <div class="gamemodeSelection">
          <label>Normal</label><input type="radio" name="gamemode" value="normal" checked></input>
          <label>Ranked</label><input type="radio" name="gamemode" value="ranked"></input>
        </div>
        <div class="summonerNameInput">
          <label>Summoner Name:</label>
          <input type="text" name="summoner">
        </div>
        <div class="confirmAndRegion">
          <?php
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
          echo(
          "
          <select id=\"selectRegion\">
            <option {$regions['br']} value=\"br\">BR</option>
            <option {$regions['eune']} value=\"eune\">EUNE</option>
            <option {$regions['euw']} value=\"euw\">EUW</option>
            <option {$regions['kr']} value=\"kr\">KR</option>
            <option {$regions['lan']} value=\"lan\">LAN</option>
            <option {$regions['las']} value=\"las\">LAS</option>
            <option {$regions['na']} value=\"na\">NA</option>
            <option {$regions['oce']} value=\"oce\">OCE</option>
            <option {$regions['ru']} value=\"ru\">RU</option>
            <option {$regions['tr']} value=\"tr\">TR</option>
          </select>
          "
        );
          ?>
          <input type="submit" value="Search"></input>
        </div>
      </form>
    </div>
  </div>
  <div class="container">
    <div class="content">
      <?php
      $apiKey = "201a3c6c-87f0-4a3a-a65f-910fa3570316";
        require(dirname(__DIR__)."/zaunacademy/PHPriotAPI/riotAPI.php");
        $summoner = \summoner\getSummonersByName($_GET['region'], $_GET['summoner'], $apiKey);
        $summonerId = $summoner[$_GET['summoner']]['id'];
        $matchHistory = \game\getRecentGameBySummonerId($_GET['region'], $summonerId, $apiKey);
        $noOfGames = count($matchHistory['games']);
        //number of games upgraded trinket has been found
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

            if(isset($matchHistory['games'][$i]['stats']['item6']))
            {
              $item = $matchHistory['games'][$i]['stats']['item6'];
              if($item == 3361 || $item == 3362 || $item == 3341 || $item == 3363)
              {
                $upgradedTrinkets += 1;
              }
            }
        }
        if($assists != 0)
          $assists /= $noOfGames;
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


        echo("
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
        </li>"
      );
      ?>
    </div>
  </div>
</body>
</html>
