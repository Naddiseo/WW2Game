<? include "gzheader.php";
include "scripts/vsys.php";
class User {
	var $id;
	var $username;
	var $race;
	var $alliance;
	var $weapons = array('mercs' => 0, 'trained' => 0, 'untrained' => 0);
	var $noweapons = array('mercs' => 0, 'trained' => 0, 'untrained' => 0);
	var $deaths = 0;
	var $SA = 0;
	var $DA = 0;
	var $SADamage = 0.0;
	var $DADamage = 0.0;
	var $effectiveness = 0.0;
	var $expgain = 0;
	var $powgain = 0;
	function __construct($id) {
		$q = mysql_query("SELECT username,race,alliance FROM UserDetails WHERE id=$id") or die(mysql_error());
		$a = mysql_fetch_object($q);
		mysql_free_result($q);
		if ($a->alliance > 0) {
			$q = mysql_query("SELECT name FROM alliances WHERE ID={$a->alliance}") or die(mysql_error());
			$f = mysql_fetch_object($q);
			mysql_free_result($q);
			$a->alliance = $f->name;
		}
		$this->id = $id;
		$this->username = $a->username;
		$this->race = $a->race;
		$this->alliance = $a->alliance;
	}
	function husername($col) {
		$this->husername = "<span style=\"color:$col;\">{$this->username}</span>";
	}
	function do_alloc($alloc_array) {
		$this->weapons['mercs'] = $alloc_array['mercW'];
		$this->weapons['trained'] = $alloc_array['trainedW'];
		$this->weapons['untrained'] = $alloc_array['untrainedW'];
		$this->noweapons['mercs'] = $alloc_array['mercUnW'];
		$this->noweapons['trained'] = $alloc_array['trainedUnW'];
		$this->noweapons['untrained'] = $alloc_array['untrainedUnW'];
	}
}
class Attack {
	var $attacker;
	var $defender;
	var $success = 0;
	var $gold = 0;
	var $percent_steal = 0;
	var $type = 0;
	var $raeff = 0;
	function __construct($battle) {
		$this->attacker = new User($battle->userID);
		$this->defender = new User($battle->toUserID);
		$this->attacker->husername("#2F7BFF");
		$this->defender->husername("orange");
		$this->attacker->do_alloc(getWeaponAllocation(0, $battle->attackWeaponCount, $battle->attackTrained, $battle->attackMercs, $battle->attackUnTrained));
		$this->defender->do_alloc(getWeaponAllocation(0, $battle->defWeaponCount, $battle->defTrained, $battle->defMercs, $battle->defUnTrained));
		$this->attacker->SA = $battle->attackStrength;
		$this->defender->DA = $battle->defStrength;
		$atweapons = explode(':', $battle->attackWeapons);
		$this->attacker->SADamage = $atweapons[0];
		$this->attacker->DADamage = $atweapons[1];
		$defweapons = explode(':', $battle->defWeapons);
		$this->defender->DADamage = $defweapons[0];
		$this->defender->SADamage = $defweapons[1];
		$this->attacker->effectiveness = $battle->attper;
		$this->defender->effectiveness = $battle->defper;
		$this->attacker->deaths = $battle->attackUsersKilled;
		$this->defender->deaths = $battle->defUsersKilled;
		$this->attacker->expgain = $battle->attexp;
		$this->defender->expgain = $battle->defexp;
		$this->attacker->powgain = $battle->userhost;
		$this->defender->powgain = $battle->defuserhost;
		if ($battle->attackStrength <= $battle->defStrength) {
			$success = 1;
		}
		$this->gold = $battle->gold;
		$this->percent_steal = $battle->pergold;
		$this->type = $battle->type;
		$this->raeff = $battle->raeff;
	}
	function ShowLog() {
		if ($this->type == 1) {
			$this->NukeTable();
		} else {
			$this->ShowStats();
			echo "<br /><b>{$this->attacker->husername}'s Army</b>";
			$this->ShowAttackerAlloc();
			echo "<b>{$this->defender->husername}'s Army</b>";
			$this->ShowDefenderAlloc();
		}
	}
	function ShowStats() {
		if ($this->success == 0) {
			$winner = $this->attacker;
			$loser = $this->defender;
		} else {
			$winner = $this->defender;
			$loser = $this->attacker;
		}
?><p>
				The forces of <?=$winner->husername ?> defeated those of <?=$loser->husername ?><br /><br />
				
				<?=$winner->husername
?> striked at <font color="green"><?=$winner->effectiveness
?>%</font> effectiveness with <font color="red"><? numecho($winner->SA) ?></font> strike action and caused <font color="green"><?=$loser->DADamage ?>%</font> to <?=$loser->husername ?>'s defence weapons<br />
				<?=$loser->husername
?> defended at <font color="green"><?=$loser->effectiveness
?>%</font> effectiveness with <font color="red"><? numecho($loser->DA) ?></font> defence action and caused <font color="green"><?=$winner->SADamage ?>%</font> to <?=$winner->husername ?>'s attack weapons<br />
				
				<p style="width:90%;position:relative;">Retaliation caused <font color="green"><?=$winner->DADamage ?>%</font> damage to <?=$winner->husername ?>'s defence weapons,<br /> and caused <font color="green"><?=$loser->SADamage ?>%</font> damage to <?=$loser->husername ?>'s attack weapons.</p>
				
				Retaliation added <font color="green"><?=$this->raeff ?>%</font> more damage to the weapons.
		</p><?
		if ($winner->powgain > 0) {
?>
				<?=$winner->husername ?> took <? numecho($winner->powgain) ?> prisoners of war<br />
			<?
		}
		if ($loser->powgain > 0) {
?>
				<?=$loser->husername ?> took <? numecho($loser->powgain) ?> prisoners of war<br />
			<?
		}
		if ($this->success == 0) {
			echo $winner->husername . " had a <font color=\"green\">{$this->percent_steal}%</font> steal and took <font color=\"red\">" . number_format($this->gold) . "</font> gold from {$loser->husername}<br />";
		}
?><br />
			<?=$winner->husername
?> gained <font color="red"><?=$winner->expgain
?></font> experience<br />
			<?=$loser->husername
?> gained <font color="red"><?=$loser->expgain
?></font> experience<br />
			
			<?=$winner->husername
?> lost <font color="red"><? numecho($winner->deaths) ?></font> soldiers<br />
			<?=$loser->husername
?> lost <font color="red"><? numecho($loser->deaths) ?></font> soldiers<br />
		<?
	}
	function ShowAttackerAlloc() {
?>
		<table class="table_lines">
			<tr>
				<th class="subh">Soldier</th>
				<th class="subh">With Weapons</th>
				<th class="subh">Without Weapons</th>
				<th class="subh">Total</th>
			</tr>
			<tr>
				<td>Trained Attack Soldiers</td>
				<td><?=$this->attacker->weapons['trained'] ?></td>
				<td><?=$this->attacker->noweapons['trained'] ?></td>
				<td><?=$this->attacker->noweapons['trained'] + $this->attacker->weapons['trained'] ?></td>
			</tr>
			<tr>
				<td>Trained Attack Mercenaries</td>
				<td><?=$this->attacker->weapons['mercs'] ?></td>
				<td><?=$this->attacker->noweapons['mercs'] ?></td>
				<td><?=$this->attacker->noweapons['mercs'] + $this->attacker->weapons['mercs'] ?></td>
			</tr>
			<tr>
				<td>Untrained Soldiers</td>
				<td><?=$this->attacker->weapons['untrained'] ?></td>
				<td><?=$this->attacker->noweapons['untrained'] ?></td>
				<td><?=$this->attacker->noweapons['untrained'] + $this->attacker->weapons['untrained'] ?></td>
			</tr>
		</table>
	<?
	}
	function ShowDefenderAlloc() {
?>
		<table class="table_lines">
			<tr>
				<th class="subh">Soldier</th>
				<th class="subh">With Weapons</th>
				<th class="subh">Without Weapons</th>
				<th class="subh">Total</th>
			</tr>
			<tr>
				<td>Trained Defence Soldiers</td>
				<td><?=$this->defender->weapons['trained'] ?></td>
				<td><?=$this->defender->noweapons['trained'] ?></td>
				<td><?=$this->defender->noweapons['trained'] + $this->defender->weapons['trained'] ?></td>
			</tr>
			<tr>
				<td>Trained Defence Mercenaries</td>
				<td><?=$this->defender->weapons['mercs'] ?></td>
				<td><?=$this->defender->noweapons['mercs'] ?></td>
				<td><?=$this->defender->noweapons['mercs'] + $this->defender->weapons['mercs'] ?></td>
			</tr>
			<tr>
				<td>Untrained Soldiers</td>
				<td><?=$this->defender->weapons['untrained'] ?></td>
				<td><?=$this->defender->noweapons['untrained'] ?></td>
				<td><?=$this->defender->noweapons['untrained'] + $this->defender->weapons['untrained'] ?></td>
			</tr>
		</table>
	<?
	}
	function NukeTable() {
?>
		<p>
			<?=$this->attacker->alliance
?>'s alliance unleashed a nuclear bomb upon <?=$this->defender->username ?>'s forces!<Br />
			<? numecho($this->defender->deaths) ?> of <?=$this->defender->username ?>'s forces were killed in the explosion and resulting radiation. 
			Many of&nbsp;<?=$this->defender->username ?>'s weapons were damaged or destroyed!
		</p>
	<?
	}
}
$attack = new Attack(getAttack($cgi['id']));
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" 
>
<HTML>
  <HEAD>
    <TITLE>
		<?=$conf["sitename"] ?> :: Battle Log
	</TITLE>
    <META http-equiv=Content-Type content="text/html; charset=iso-8859-1">
    <LINK href="css/common.css" type=text/css rel=stylesheet>
    <SCRIPT language=javascript type=text/javascript>
		<!--
		function checkCR(evt) {
		var evt = (evt) ? evt : ((event) ? event : null);
		var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
		if ((evt.keyCode == 13) && (node.type=="text")) {return false;}
		}
		document.onkeypress = checkCR;
		//-->
		</SCRIPT>
  </HEAD>
  <BODY text=#ffffff bgColor=#000000 leftMargin=0 topMargin=0 marginheight="0" marginwidth="0">
    <?
include "top.php";
?>
    <TABLE cellSpacing=0 cellPadding=5 width="100%" border=0>
      <TBODY>
        <TR>
          <TD class=menu_cell_repeater style="PADDING-LEFT: 15px" vAlign=top width=140>
            <?
include ("left.php");
?>
          </TD>
          <TD style="PADDING-RIGHT: 15px; PADDING-LEFT: 15px; PADDING-TOP: 12px" vAlign=top align=left>
            <BR />
            <center>
            <?
include "islogined.php";
if ($_SESSION['isLogined'] != $attack->attacker->id AND $_SESSION['isLogined'] != $attack->defender->id) {
	die("Cannot view other's Attack Logs.");
}
//print_r($attack);
$attack->ShowLog();
if (!$cgi['isview']) {
?>
												<FORM action=attack.php method=get>
												  <INPUT type=hidden value=<?=$attack->defender->id
?>
												   name=id><INPUT name="submit" type=submit value="Attack / Spy Again">
												</FORM>
												<?
} ?>
            </center>
            <P>
				<? include ("bottom.php"); ?>
			</P>
          </th>
        </TR>
      </TBODY>
    </TABLE>
  </BODY>
  <? include "gzfooter.php";
?>
