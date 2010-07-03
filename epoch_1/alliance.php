<?
/** DONE: progressive price increase done
 TODO: limit bonuses to 50% done, to 10%
 TODO: show prices on alliance_upgrade page
 *
 */
$statcap = 0.10;
include ("scripts/vsys.php");
function echoURL($url, $name) {
	$url = str_replace('http://', '', $url);
	echo "<a href=\"http://" . stripslashes($url) . "\" target=\"_blank\" title=\"Opens in new window\">$name</a>\n";
}
//if($user->ID!=1){ header("Location: base.php");exit; }
$alliances = array();
$q = mysql_query("SELECT * FROM alliances ORDER BY ID ASC") or die(mysql_error());
while ($a = mysql_fetch_object($q)) {
	$alliances[$a->ID] = $a;
	if (($a->leaderid1 == $user->ID OR $a->leaderid2 == $user->ID OR $a->leaderid3 == $user->ID) AND $a->ID != 0 AND !defined("IS_LEADER")) {
		define("IS_LEADER", $a->ID);
	}
}
if (!defined("IS_LEADER")) {
	define("IS_LEADER", 0);
}
if (count($alliances) == 0) {
	$alliances = array();
}
//ar_dump($alliances);
if ($user->alliance == 0) {
	define("NO_ALLIANCE", true);
} else {
	define("NO_ALLIANCE", false);
	define("MEMBER", $user->alliance);
}
switch ($cgi['leader']) {
	case 'convert':
		if (IS_LEADER == MEMBER) {
			if ($cgi['sbmt_ge']) {
				$amount = intval($cgi['gold-exp']);
				if ($alliances[MEMBER]->gold >= $amount) {
					$exc = ($amount / 10000000) * 3333; //1,000,000,000/333,300
					mysql_query("UPDATE alliances SET gold=gold-$amount,exp=exp+$exc WHERE id=" . MEMBER) or die(mysql_error());
					header("Location: alliance.php?strErr=Conversion successful");
					exit;
				} else {
					header("Location: alliance.php?strErr=Not enough gold");
					exit;
				}
			} elseif ($cgi['sbmt_eg']) {
				$amount = intval($cgi['exp-gold']);
				if ($alliances[MEMBER]->exp >= $amount) {
					$exc = ($amount / 3333) * 10000000; //333,300/1,000,000,000
					mysql_query("UPDATE alliances SET exp=exp-$amount,gold=gold+$exc WHERE id=" . MEMBER) or die(mysql_error());
					header("Location: alliance.php?strErr=Conversion successful");
					exit;
				} else {
					header("Location: alliance.php?strErr=Not enough Experience");
					exit;
				}
			} elseif ($cgi['sbmt_ag']) {
				$amount = intval($cgi['ap-gold']);
				if ($alliances[MEMBER]->ap >= $amount) {
					$exc = $amount * 1000000000; //1,000,000,000/333,300
					mysql_query("UPDATE alliances SET ap=ap-$amount,gold=gold+$exc,usedap=usedap+$amount WHERE id=" . MEMBER) or die(mysql_error());
					header("Location: alliance.php?strErr=Conversion successful");
					exit;
				} else {
					header("Location: alliance.php?strErr=Not enough alliance points");
					exit;
				}
			} elseif ($cgi['sbmt_ae']) {
				$amount = intval($cgi['ap-exp']);
				if ($alliances[MEMBER]->ap >= $amount) {
					$exc = $amount * 333300; //1,000,000,000/333,300
					mysql_query("UPDATE alliances SET ap=ap-$amount,exp=exp+$exc,usedap=usedap+$amount WHERE id=" . MEMBER) or die(mysql_error());
					header("Location: alliance.php?strErr=Conversion successful");
					exit;
				} else {
					header("Location: alliance.php?strErr=Not enough alliance points");
					exit;
				}
			} elseif ($cgi['sbmt_da']) {
				$amount = intval($cgi['d-ap']);
				if ($alliances[MEMBER]->donated >= $amount) {
					$exc = $amount * 2; //1,000,000,000/333,300
					mysql_query("UPDATE alliances SET donated=donated-$amount,ap=ap+$exc,usedcash=usedcash+$amount WHERE id=" . MEMBER) or die(mysql_error());
					header("Location: alliance.php?strErr=Conversion successful");
					exit;
				} else {
					header("Location: alliance.php?strErr=Not enough dollars");
					exit;
				}
			}
		}
	break;
	case 'alliance_upgrade':
		if (!$cgi['what']) {
			header("Location: alliance.php?leaderpage=alliance_upgrade&strErr=No upgrade selected");
			exit;
		}
		switch ($cgi['what']) {
			case 'sa':
				$what = 'SA';
			break;
			case 'da':
				$what = 'DA';
			break;
			case 'ca':
				$what = 'CA';
			break;
			case 'up':
				$what = 'UP';
			break;
			default:
			case 'ra':
				$what = 'RA';
			break;
		}
		if (($what == 'UP' AND $alliances[MEMBER]->UP >= 10) OR ($what != 'UP' && $alliances[MEMBER]->$what >= $statcap)) {
			header('Location: alliance.php?strErr=Your alliance has enough upgrades');
			exit;
		}
		if ($what == 'UP' AND $cgi['sbm1']) {
			$upam = '5';
			switch ($cgi['pay']) {
				case 'ap':
					$pay = 'ap';
					if ($what == 'UP') {
						$pris = 10 + ($alliances[MEMBER]->UP / 5);
					} else {
						$pris = 10;
					}
					if ($alliances[MEMBER]->ap >= 10) {
						mysql_query("UPDATE alliances SET $what=$what+$upam,$pay=$pay-10,usedap=usedap+10 WHERE id=" . MEMBER) or die(mysql_error());
						header('Location: alliance.php?strErr=Upgrade Successful1');
						exit;
					} else {
						header('Location: alliance.php?strErr=Not enough alliance points');
						exit;
					}
				break;
				case 'gold':
					$pay = 'gold';
					if ($what == 'UP') {
						$pris = 10000000000 + (1000000000 * ($alliances[MEMBER]->UP / 5));
					} else {
						$pris = 10000000000;
					}
					if ($alliances[MEMBER]->gold >= 10000000000) {
						mysql_query("UPDATE alliances SET $what=$what+$upam,$pay=$pay-10000000000 WHERE id=" . MEMBER) or die(mysql_error());
						header('Location: alliance.php?strErr=Upgrade Successful');
						exit;
					} else {
						header('Location: alliance.php?strErr=Not enough gold');
						exit;
					}
				break;
			}
		}
		if ($what == 'UP') {
			header("Location: alliance.php?leaderpage=alliance_upgrade&strErr=No upgrade selected");
			exit;
		}
		switch ($cgi['pay']) {
			case 'exp':
				$pay = 'exp';
				$pris = 100000000 + (100000000 * ($alliances[MEMBER]->$what));
				if ($alliances[MEMBER]->exp >= 100000000) {
					mysql_query("UPDATE alliances SET $what=$what+0.005,$pay=$pay-100000000 WHERE id=" . MEMBER) or die(mysql_error());
					header('Location: alliance.php?strErr=Upgrade Successful');
					exit;
				} else {
					header('Location: alliance.php?strErr=Not enough experience');
					exit;
				}
			break;
			case 'ap':
				$pay = 'ap';
				$pris = 10 + (($alliances[MEMBER]->$what / 0.005));
				if ($alliances[MEMBER]->ap >= 10) {
					mysql_query("UPDATE alliances SET $what=$what+0.005,$pay=$pay-10,usedap=usedap+10 WHERE id=" . MEMBER) or die(mysql_error());
					header('Location: alliance.php?strErr=Upgrade Successful');
					exit;
				} else {
					header('Location: alliance.php?strErr=Not enough alliance points');
					exit;
				}
			break;
			case 'gold':
				$pay = 'gold';
				$pris = 500000000 + (500000000 * ($alliances[MEMBER]->$what));
				if ($alliances[MEMBER]->gold >= 500000000) {
					mysql_query("UPDATE alliances SET $what=$what+0.005,$pay=$pay-500000000 WHERE id=" . MEMBER) or die(mysql_error());
					header('Location: alliance.php?strErr=Upgrade Successful ');
					exit;
				} else {
					header('Location: alliance.php?strErr=Not enough gold');
					exit;
				}
			break;
		}
	break;
	case 'disband':
		if (IS_LEADER == MEMBER) {
			if ($cgi['sbm_Yes']) {
				mysql_query("UPDATE UserDetails SET alliance=0,aaccepted=0 WHERE alliance=" . MEMBER) or die(mysql_error());
				mysql_query("DELETE FROM alliances WHERE id=" . MEMBER) or die(mysql_error());
				header("Location: alliance.php?strErr=Alliance disbanded");
				exit;
			} else {
				header("Location: alliance.php");
				exit;
			}
		} else {
			header("Location: alliance.php?strErr=You are not the leader");
			exit;
		}
	break;
	case 'edit':
		$name = mysql_escape_string($cgi['alliance_name']);
		$tag = mysql_escape_string($cgi['alliance_tag']);
		$url = mysql_escape_string($cgi['alliance_url']);
		$irc = mysql_escape_string($cgi['alliance_irc']);
		$tax = floatval($cgi['alliance_tax'] / 100);
		$msg = mysql_escape_string($cgi['alliance_message']);
		if (strlen($name) <= 30 AND strlen($name) > 0 AND strlen($tag) <= 8 AND strlen($tag) > 0 AND strlen($irc) <= 10 AND strlen($msg) <= 500) {
			if ($tax > 0.05) {
				$tax = 0.05;
			}
			$q = mysql_query("SELECT count(*) FROM alliances WHERE (name LIKE \"%$name%\" OR tag LIKE \"%$tag%\" OR irc LIKE \"%$irc%\" OR url LIKE \"%$url%\") AND id!=" . MEMBER) or die(mysql_error() . '1');
			$a = mysql_fetch_array($q);
			if ($a[0] == 0) {
				mysql_query("UPDATE alliances SET name=\"$name\",url=\"$url\",tag=\"$tag\",irc=\"$irc\",tax=\"$tax\",message=\"$msg\" WHERE id=" . MEMBER) OR die(mysql_error());
				header("Location: alliance.php?strErr=Alliance Updated");
				exit;
			} else {
				header("Location: alliance.php?strErr=That alliance already exists");
				exit;
			}
		} else {
			header("Location: alliance.php?strErr=Some values were too long or too short");
			exit;
		}
	break;
	case 'create':
		if ($user->alliance == 0) {
			$name = mysql_escape_string($cgi['alliance_name']);
			$tag = mysql_escape_string($cgi['alliance_tag']);
			$url = mysql_escape_string($cgi['alliance_url']);
			$irc = mysql_escape_string($cgi['alliance_irc']);
			$tax = floatval($cgi['alliance_tax'] / 100);
			$msg = mysql_escape_string($cgi['alliance_message']);
			if ($tax > 0.05) {
				$tax = 0.05;
			}
			if (strlen($name) <= 30 AND strlen($name) > 0 AND strlen($tag) <= 8 AND strlen($tag) > 0 AND strlen($irc) <= 10 AND strlen($msg) <= 500) {
				$q = mysql_query("SELECT count(*) FROM alliances WHERE name LIKE \"%$name%\" OR irc LIKE \"%$irc%\" OR url LIKE \"%$url%\"") or die(mysql_error() . '1');
				$a = mysql_fetch_array($q);
				if ($a[0] == 0) {
					$q = mysql_query("INSERT INTO alliances (name,tag,leaderid1,url,irc,creationdate,message) VALUES (\"$name\",\"$tag\",{$user->ID},\"$url\",\"$irc\"," . (string)time() . ",\"$msg\")") or die(mysql_error() . '2');
					$q = mysql_query("SELECT ID FROM alliances WHERE name=\"$name\"") or die(mysql_error() . '3');
					$a = mysql_fetch_object($q);
					mysql_query("UPDATE UserDetails SET alliance={$a->ID},aaccepted=1 WHERE id={$user->ID}") or die(mysql_error() . '4');
					header("Location: alliance.php?strErr=Alliance Created");
					exit;
				} else {
					header("Location: alliance.php?strErr=That alliance already exists");
					exit;
				}
			} else {
				header("Location: alliance.php?strErr=Some values were too long or too short");
				exit;
			}
		} else {
			header("Location: alliance.php?strErr=You are already part of an alliance");
			exit;
		}
	break;
	case 'aaccept':
		if ($cgi['aaccept_deny'] AND IS_LEADER == MEMBER) {
			if (is_array($cgi['ids'])) {
				$i = '(0';
				foreach ($cgi['ids'] as $id) {
					$i.= ',' . intval($id);
				}
				$i.= ')';
				$q = mysql_query("UPDATE UserDetails SET alliance=0 WHERE alliance=" . MEMBER . " AND id in $i") or die(mysql_error());
			} else {
				$q = mysql_query("UPDATE UserDetails SET alliance=0 WHERE alliance=" . MEMBER . " AND id =" . intval($cgi['ids'])) or die(mysql_error());
			}
		} elseif ($cgi['aaccept_allow'] AND IS_LEADER == MEMBER) {
			if (is_array($cgi['ids'])) {
				$i = '(0';
				foreach ($cgi['ids'] as $id) {
					$i.= ',' . intval($id);
				}
				$i.= ')';
				$q = mysql_query("UPDATE UserDetails SET aaccepted=1 WHERE alliance=" . MEMBER . " AND id in $i") or die(mysql_error());
			} else {
				$q = mysql_query("UPDATE UserDetails SET aaccepted=1 WHERE alliance=" . MEMBER . " AND id =" . intval($cgi['ids'])) or die(mysql_error());
			}
		}
	break;
	case 'akick':
		if (IS_LEADER == MEMBER) {
			$i = '(0';
			foreach ($cgi['ids'] as $id) {
				$i.= ',' . intval($id);
			}
			$i.= ')';
			$q1 = mysql_query("SELECT ID FROM UserDetails WHERE alliance=" . MEMBER . " AND id in $i") or die(mysql_error());
			mysql_query("UPDATE alliances, UserDetails set alliances.leaderid1=0 WHERE 
			alliances.leaderid1=UserDetails.ID AND 
			UserDetails.alliance = " . MEMBER . " AND UserDetails.id in $i") or die(mysql_error());
			mysql_query("UPDATE alliances, UserDetails set alliances.leaderid2=0 WHERE 
			alliances.leaderid2=UserDetails.ID AND 
			UserDetails.alliance = " . MEMBER . " AND UserDetails.id in $i") or die(mysql_error());
			mysql_query("UPDATE alliances, UserDetails set alliances.leaderid3=0 WHERE 
			alliances.leaderid3=UserDetails.ID AND 
			UserDetails.alliance = " . MEMBER . " AND UserDetails.id in $i") or die(mysql_error());
			mysql_query("UPDATE UserDetails SET alliance=0,aaccepted=0 where id in $i");
			while ($a1 = mysql_fetch_object($q1)) {
				//mysql_query("UPDATE alliances set leaderid1=0 WHERE leaderid1={$a1->ID}") or die(mysql_error());
				//mysql_query("UPDATE alliances set leaderid2=0 WHERE leaderid2={$a1->ID}") or die(mysql_error());
				//mysql_query("UPDATE alliances set leaderid3=0 WHERE leaderid3={$a1->ID}") or die(mysql_error());
				$q = mysql_query("SELECT id FROM alliances WHERE leaderid1=0 AND leaderid2=0 AND leaderid3=0") or die(mysql_error());
				while ($a = mysql_fetch_object($q)) {
					mysql_query("UPDATE UserDetails SET alliance=0,aaccepted=0 WHERE alliance={$a->id}") or die(mysql_error());
					mysql_query("DELETE FROM alliances where id={$a->id}") or die(mysql_error());
				}
			}
		}
	break;
	case 'promote':
		//print_r($cgi);
		$q = mysql_query("SELECT alliance FROM UserDetails WHERE id=\"" . intval($cgi['ids']) . "\"") or die(mysql_error());
		$a = mysql_fetch_object($q);
		if ($a->alliance == MEMBER AND IS_LEADER == MEMBER) {
			$q = mysql_query("SELECT leaderid2,leaderid3 FROM alliances WHERE id=" . MEMBER) or die(mysql_error());
			$a = mysql_fetch_object($q);
			if ($a->leaderid2 == 0) {
				$q = mysql_query("UPDATE alliances SET leaderid2=" . intval($cgi['ids']) . " WHERE id=" . MEMBER) or die(mysql_error());
			} elseif ($a->leaderid3 == 0) {
				$q = mysql_query("UPDATE alliances SET leaderid3=" . intval($cgi['ids']) . " WHERE id=" . MEMBER) or die(mysql_error());
			} else {
				header("Location: alliance.php?strErr=Your alliance has enough leaders");
				exit;
			}
		}
	break;
	case 'demote':
		//$q=mysql_query("UPDATE alliances SET subleader=0 WHERE id=".MEMBER) or die(mysql_error());
		
	break;
		/*case 'memberbuy':
		$pid=intval($cgi['pwho']);
		$q=mysql_query("SELECT alliance FROM UserDetails WHERE id=$pid") or die(mysql_error().' 1');
		$a=mysql_fetch_object($q);
		if($a->alliance==MEMBER AND IS_LEADER==MEMBER){//see if user is in the alliance
			switch($cgi['pwhat']){			
				case 'da': $pw= 1;break;
				case 'ca': $pw= 2;break;
				case 'ra': $pw= 3;break;
				case 'bank': $pw=  4;break;
				case 'nukerlevel': $pw=  5;break;
				case 'h2h': $pw= 6;break;
				case 'bunker': $pw= 7;break;
				default:
				case 'sa':	$pw=  0;break;
			}
			
			$q=mysql_query("SELECT * FROM alliance_vote WHERE aid=".MEMBER." AND votetype=$pw AND mid=$pid") or die(mysql_error().' 2');
			if(mysql_num_rows($q)==0){//see if there is already a vote of this type
				$time=10*24*3;
				//$varray=addslashes(serialize(array($pid=>array(0,array()))));
				mysql_query("INSERT INTO alliance_vote (aid,mid,votetype,votes,time) VALUES (".MEMBER.",$pid,$pw,\"\",$time)") or die(mysql_error().' 3');
			}else{
				header("Location: alliance.php?strErr=That user has already been nominated for that reward");exit;
			
			}
		}
		break;*/
	default:
	break;
}
if ($cgi['leader'] AND IS_LEADER > 0) {
	header("Location: alliance.php?leaderpage=$cgi[leaderpage]");
}
if ($cgi['join']) {
	if ($user->alliance == 0) {
		$a = intval($cgi['join']);
		mysql_query("UPDATE UserDetails SET alliance=$a where id={$user->ID}");
	} else {
		header("Location: alliance.php?strErr=You must leave your alliance first");
		exit;
	}
} elseif ($cgi['leave']) {
	mysql_query("UPDATE alliances set leaderid1=0 WHERE leaderid1={$user->ID}") or die(mysql_error());
	mysql_query("UPDATE alliances set leaderid2=0 WHERE leaderid2={$user->ID}") or die(mysql_error());
	mysql_query("UPDATE alliances set leaderid3=0 WHERE leaderid3={$user->ID}") or die(mysql_error());
	$q = mysql_query("SELECT id FROM alliances WHERE leaderid1=0 AND leaderid2=0 AND leaderid3=0") or die(mysql_error());
	while ($a = mysql_fetch_object($q)) {
		mysql_query("UPDATE UserDetails SET alliance=0,aaccepted=0 WHERE alliance={$a->id}") or die(mysql_error());
		mysql_query("DELETE FROM alliances where id={$a->id}") or die(mysql_error());
	}
	mysql_query("UPDATE UserDetails SET alliance=0,aaccepted=0 where id={$user->ID}");
}
if ($cgi['do']) {
	switch ($cgi['do']) {
		case 'donate':
			$gold = abs(intval($cgi['dgold']));
			$exp = abs(intval($cgi['dexp']));
			if ($gold > 0) {
				if ($gold <= $user->gold and $gold > 0) {
					mysql_query("UPDATE UserDetails u,alliances a SET u.gold=u.gold-$gold,a.gold=a.gold+$gold WHERE u.alliance=a.id AND u.id={$user->ID}") or die(mysql_error());
				} else {
					header('Location: alliance.php?strErr=You do not have enough gold');
					exit;
				}
			}
			if ($exp > 0) {
				if ($exp <= $user->exp AND $exp > 0) {
					mysql_query("UPDATE UserDetails u,alliances a SET u.exp=u.exp-$exp,a.exp=a.exp+$exp WHERE u.alliance=a.id AND u.id={$user->ID}") or die(mysql_error());
				} else {
					header('Location: alliance.php?strErr=You do not have enough experience');
					exit;
				}
			}
			header('Location: alliance.php?strErr=Successful');
			exit;
		break;
		case 'create':
			//	include('alliance/create.php');
			
		break;
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" 
>
<HTML>
<HEAD>
<TITLE><? echo $conf["sitename"]; ?>:: Alliances</TITLE>
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
<BODY text=#ffffff bgColor=#000000 leftMargin=0 topMargin=0 marginheight="0" 
				marginwidth="0">
<?
include "top.php";
?>
<TABLE cellSpacing=0 cellPadding=5 width="100%" border=0>
  <TBODY>
    <TR>
      <TD class=menu_cell_repeater style="PADDING-LEFT: 15px" vAlign=top width=140><?
include ("left.php");
?>
      </TD>
      <TD style="PADDING-RIGHT: 15px; PADDING-LEFT: 15px; PADDING-TOP: 12px" 
				vAlign=top align=left><H3> Alliances </H3>
        <P> <strong>
          <center>
            <font color=red><? echo $cgi["strErr"]; ?></font>
          </center>
          </strong> </p>
        <? include "islogined.php";
if ($cgi['view'] == 'list') {
	include ('alliance/list.php');
} elseif (isset($cgi['leaderpage']) AND IS_LEADER > 0) {
	echo "<a href=\"?leaderpage=main\">Alliance Leader Panel</a><br />";
	switch ($cgi['leaderpage']) {
		case 'convert':
			include ('alliance/admin/convert.php');
		break;
		case 'members':
			include ('alliance/admin/members.php');
		break;
		case 'nominate_bonus':
			include ('alliance/admin/nominate_bonus.php');
		break;
		case 'edit':
			include ('alliance/admin/edit.php');
		break;
		case 'disband':
			include ('alliance/admin/disband.php');
		break;
		case 'alliance_upgrades':
			include ('alliance/admin/alliance_upgrades.php');
		break;
		case 'main':
		default:
			include ('alliance/leader.php');
	}
} elseif ($cgi['do'] == 'create') {
	include ('alliance/create.php');
} else {
	if (NO_ALLIANCE) {
		include ('alliance/list.php');
	} elseif (IS_LEADER > 0) {
		include ('alliance/leader.php');
	} elseif (MEMBER AND $user->aaccepted) {
		//echo "debug: ISLEADER=".IS_LEADER;
		include ('alliance/member.php');
	} else {
		include ('alliance/list.php');
	}
}
//include('alliance/list.php');
include ("bottom.php");
?>
      </TD>
    </TR>
  </TBODY>
</TABLE>
</BODY>
</HTML>
<? include "gzfooter.php";
?>
