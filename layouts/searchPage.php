<?php
function writeSearchPage($summonerName, $regions, $content)
{
$page = "
<html>
<head>
<title>Zaun Academy</title>
<meta charset=\"utf-8\">
<link rel=\"stylesheet\" type=\"text/css\" href=\"styles/search_style.css\">
<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
<meta name=\"description\" content=\"Shows your average ingame stats to see what you need to improve on\">
<meta name=\"keywords\" content=\"lol,league of legends,statistics,stats,school,improve\">
<meta name=\"author\" content=\"William 'Wicam' Cameron\">
<script type=\"text/javascript\" src=\"scripts/searchFormScript.js\"></script>
</head>

<body>
  <div class=\"header\">
    <h2>$summonerName</h2>
    <div class=\"formContainer\">
      <form action=\"search.php\" formmethod=\"get\" onsubmit=\"return validateForm()\" name=\"querySummoner\">
        <input type=\"hidden\" name=\"region\" value=\"br\"></input>
        <div class=\"gamemodeSelection\">
          <label>Normal</label><input type=\"radio\" name=\"gamemode\" value=\"normal\" checked></input>
          <label>Ranked</label><input type=\"radio\" name=\"gamemode\" value=\"ranked\"></input>
        </div>
        <div class=\"summonerNameInput\">
          <label>Summoner Name:</label>
          <input type=\"text\" name=\"summoner\">
        </div>
        <div class=\"confirmAndRegion\">
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
          <input type=\"submit\" value=\"Search\"></input>
        </div>
      </form>
    </div>
  </div>
  <div class=\"container\">
    <div class=\"content\">
      $content
    </div>
  </div>
</body>
</html>
";
echo($page);
}
?>
