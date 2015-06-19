<?php
  /*
  exists to clean out the search.php file,
  as it was getting long.
  */

  /* stores the statistics for one summoner */
  class SummonerStats {
    public $id = 0;
    public $name = 0;
    public $upgradedTrinkets = 0;
    public $assists = 0;
    public $kills = 0;
    public $turrets = 0;
    public $deaths = 0;
    public $wardsPlaced = 0;
    public $wardsDestroyed = 0;
    public $creepScore = 0;
    public $creepScore20m = 0;
    public $league = "Unranked";
    public $wins = 0;
    public $role = array();
    public $noOfGames = 0;

    public function averageStatistics()
    {
      // stops devision by 0 error
      if($this->assists != 0)
        $this->assists /= $this->noOfGames;
      if($this->kills != 0)
        $this->kills /= $this->noOfGames;
      if($this->turrets != 0)
        $this->turrets /= $this->noOfGames;
      if($this->deaths != 0)
        $this->deaths /= $this->noOfGames;
      if($this->wardsPlaced != 0)
        $this->wardsPlaced /= $this->noOfGames;
      if($this->wardsDestroyed != 0)
        $this->wardsDestroyed /= $this->noOfGames;
      if($this->creepScore != 0)
        $this->creepScore /= $this->noOfGames;
      if($this->creepScore20m != 0)
        $this->creepScore20m /= $this->noOfGames;
      if($this->wins != 0)
        $this->wins = ($this->wins / $this->noOfGames) * 100;
      if($this->upgradedTrinkets != 0)
        $this->upgradedTrinkets = ($this->upgradedTrinkets / $this->noOfGames) * 100;
    }
  }
?>
