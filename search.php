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
          <select id="selectRegion">
            <option value="br">BR</option>
            <option value="eune">EUNE</option>
            <option value="euw">EUW</option>
            <option value="kr">KR</option>
            <option value="lan">LAN</option>
            <option value="las">LAS</option>
            <option value="na">NA</option>
            <option value="oce">OCE</option>
            <option value="ru">RU</option>
            <option value="tr">TR</option>
          </select>
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
        }

        $assists /= $noOfGames;
        $kills /= $noOfGames;
        $turrets /= $noOfGames;
        $deaths /= $noOfGames;
        $wardsPlaced /= $noOfGames;
        $wardsDestroyed /= $noOfGames;
        $creepScore /= $noOfGames;
        $wins = ($wins / $noOfGames) * 100;


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
        </li>"
      );
      ?>
    </div>
  </div>
</body>
</html>
