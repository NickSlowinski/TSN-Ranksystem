<?PHP
$job_check = $mysqlcon->query("SELECT * FROM `$dbname`.`job_check`")->fetchAll(PDO::FETCH_UNIQUE|PDO::FETCH_ASSOC);
if((time() - $job_check['last_update']['timestamp']) < 259200 && !isset($_SESSION[$rspathhex.'upinfomsg'])) {
	if(!isset($err_msg)) {
		$err_msg = '<i class="fas fa-info-circle"></i>&nbsp;'.sprintf($lang['upinf2'], date("Y-m-d H:i",$job_check['last_update']['timestamp']), '<i class="fas fa-book"></i>&nbsp;<a href="//ts-ranksystem.com/?changelog" target="_blank">', '</a>'); $err_lvl = 1;
		$_SESSION[$rspathhex.'upinfomsg'] = 1;
	}
}

if(!isset($_POST['start']) && !isset($_POST['stop']) && !isset($_POST['restart']) && isset($_SESSION[$rspathhex.'username']) && $_SESSION[$rspathhex.'username'] == $cfg['webinterface_user'] && $_SESSION[$rspathhex.'password'] == $cfg['webinterface_pass']) {
	if (substr(php_uname(), 0, 7) == "Windows") {
		if (file_exists(substr(__DIR__,0,-12).'logs\pid')) {
			$pid = str_replace(array("\r", "\n"), '', file_get_contents(substr(__DIR__,0,-12).'logs\pid'));
			exec("wmic process where \"processid=".$pid."\" get processid 2>nul", $result);
			if(isset($result[1]) && is_numeric($result[1])) {
				$botstatus = 1;
			} else {
				$botstatus = 0;
			}
		} else {
			$botstatus = 0;
		}
	} else {
		if (file_exists(substr(__DIR__,0,-12).'logs/pid')) {
			$check_pid = str_replace(array("\r", "\n"), '', file_get_contents(substr(__DIR__,0,-12).'logs/pid'));
			$result = str_replace(array("\r", "\n"), '', shell_exec("ps ".$check_pid));
			if (strstr($result, $check_pid)) {
				$botstatus = 1;
			} else {
				$botstatus = 0;
			}
		} else {
			$botstatus = 0;
		}
	}
}
?>
<!DOCTYPE html>
<html lang="<?PHP echo $cfg['default_language']; ?>">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="version" content="<?PHP echo $cfg['version_current_using']; ?>">
	<link rel="icon" href="../tsicons/rs.png">
	<title>TSN Ranksystem - ts-ranksystem.com</title>
	<link href="../libs/combined_wi.css?v=<?PHP echo $cfg['version_current_using']; ?>" rel="stylesheet">
	<script src="../libs/combined_wi.js?v=<?PHP echo $cfg['version_current_using']; ?>"></script>
	<script>
	$(function() {
		$("ul.dropdown-menu").on("click", "[data-keepOpenOnClick]", function(e) {
			e.stopPropagation();
		});
	});
	</script>
<body>
	<div id="wrapper">
		<nav class="navbar navbar-inverse navbar-fixed-top">
			<div class="navbar-header">
				<a class="navbar-brand" href="index.php">TSN Ranksystem - Webinterface <?PHP echo $cfg['version_current_using'];?></a>
				<?PHP if(isset($_SESSION[$rspathhex.'newversion']) && version_compare($_SESSION[$rspathhex.'newversion'], $cfg['version_current_using'], '>') && $_SESSION[$rspathhex.'newversion'] != '') {
					echo '<a class="navbar-brand" href="//ts-ranksystem.com/?changelog" target="_blank">'.$lang['winav9'].' ['.$_SESSION[$rspathhex.'newversion'].']</a>';
				} ?>
			</div>
			<?PHP if(basename($_SERVER['SCRIPT_NAME']) == "stats.php") { ?>
			<ul class="nav navbar-left top-nav">
				<li class="navbar-form navbar-right">
					<button onclick="window.open('../stats/list_rankup.php?admin=true','_blank'); return false;" class="btn btn-primary" name="adminlist">
						<i class="fas fa-list"></i>&nbsp;<?PHP echo $lang['wihladm']; ?>
					</button>
				</li>
			</ul>
			<?PHP } ?>
			<ul class="nav navbar-right top-nav">
				<?PHP
				if($_SERVER['SERVER_PORT'] == 443 || $_SERVER['SERVER_PORT'] == 80) {
					echo '<li><a href="//',$_SERVER['SERVER_NAME'],substr(dirname($_SERVER['SCRIPT_NAME']),0,-12),'stats/"><i class="fas fa-chart-bar"></i>&nbsp;',$lang['winav6'],'</a></li>';
				} else {
					echo '<li><a href="//',$_SERVER['SERVER_NAME'],':',$_SERVER['SERVER_PORT'],substr(dirname($_SERVER['SCRIPT_NAME']),0,-12),'stats/"><i class="fas fa-chart-bar"></i>&nbsp;',$lang['winav6'],'</a></li>';
				}
				if(isset($_SESSION[$rspathhex.'username']) && $_SESSION[$rspathhex.'username'] == $cfg['webinterface_user'] && $_SESSION[$rspathhex.'password'] == $cfg['webinterface_pass']) { ?>
				<li>
					<a href="changepassword.php"><i class="fas fa-lock"></i>&nbsp;<?PHP echo $lang['pass2']; ?></a>
				</li>
				<li>
					<form class="navbar-form navbar-center" method="post">
						<div class="form-group">
							<button type="submit" name="logout" class="btn btn-primary"><?PHP echo $lang['wilogout']; ?>&nbsp;<span class="fas fa-sign-out-alt" aria-hidden="true"></span></button>
						</div>
					</form>
				</li>
				<?PHP } ?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-globe-europe"></i>&nbsp;<b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li>
							<a href="?lang=ar"><span class="flag-icon flag-icon-arab"></span>&nbsp;&nbsp;AR - العربية</a>
						</li>
						<li>
							<a href="?lang=cz"><span class="flag-icon flag-icon-cz"></span>&nbsp;&nbsp;CZ - Čeština</a>
						</li>
						<li>
							<a href="?lang=de"><span class="flag-icon flag-icon-de"></span>&nbsp;&nbsp;DE - Deutsch</a>
						</li>
						<li>
							<a href="?lang=en"><span class="flag-icon flag-icon-gb"></span>&nbsp;&nbsp;EN - English</a>
						</li>
						<li>
							<a href="?lang=es"><span class="flag-icon flag-icon-es"></span>&nbsp;&nbsp;ES - español</a>
						</li>
						<li>
							<a href="?lang=fr"><span class="flag-icon flag-icon-fr"></span>&nbsp;&nbsp;FR - français</a>
						</li>
						<li>
							<a href="?lang=it"><span class="flag-icon flag-icon-it"></span>&nbsp;&nbsp;IT - Italiano</a>
						</li>
						<li>
							<a href="?lang=nl"><span class="flag-icon flag-icon-nl"></span>&nbsp;&nbsp;NL - Nederlands</a>
						</li>
						<li>
							<a href="?lang=pl"><span class="flag-icon flag-icon-pl"></span>&nbsp;&nbsp;PL - polski</a>
						</li>
						<li>
							<a href="?lang=ro"><span class="flag-icon flag-icon-ro"></span>&nbsp;&nbsp;RO - Română</a>
						</li>
						<li>
							<a href="?lang=ru"><span class="flag-icon flag-icon-ru"></span>&nbsp;&nbsp;RU - Pусский</a>
						</li>
						<li>
							<a href="?lang=pt"><span class="flag-icon flag-icon-ptbr"></span>&nbsp;&nbsp;PT - Português</a>
						</li>
					</ul>
				</li>
			</ul>
			<div class="collapse navbar-collapse">
				<ul class="nav navbar-nav side-nav">
					<?PHP echo '<li'.(basename($_SERVER['SCRIPT_NAME']) == "ts.php" ? ' class="active">' : '>'); ?>
						<a href="ts.php"><i class="fas fa-headset"></i>&nbsp;<?PHP echo $lang['winav1']; ?></a>
					</li>
					<?PHP echo '<li'.(basename($_SERVER['SCRIPT_NAME']) == "db.php" ? ' class="active">' : '>'); ?>
						<a href="db.php"><i class="fas fa-database"></i>&nbsp;<?PHP echo $lang['winav2']; ?></a>
					</li>
					<?PHP echo '<li'.(basename($_SERVER['SCRIPT_NAME']) == "core.php" ? ' class="active">' : '>'); ?>
						<a href="core.php"><i class="fas fa-cogs"></i>&nbsp;<?PHP echo $lang['winav3']; ?></a>
					</li>
					<?PHP echo '<li'.(basename($_SERVER['SCRIPT_NAME']) == "other.php" ? ' class="active">' : '>'); ?>
						<a href="other.php"><i class="fas fa-wrench"></i>&nbsp;<?PHP echo $lang['winav4']; ?></a>
					</li>
					<?PHP echo '<li'.(basename($_SERVER['SCRIPT_NAME']) == "msg.php" ? ' class="active">' : '>'); ?>
						<a href="msg.php"><i class="fas fa-envelope"></i>&nbsp;<?PHP echo $lang['winav5']; ?></a>
					</li>
					<?PHP echo '<li'.(basename($_SERVER['SCRIPT_NAME']) == "stats.php" ? ' class="active">' : '>'); ?>
						<a href="stats.php"><i class="fas fa-chart-bar"></i>&nbsp;<?PHP echo $lang['winav6']; ?></a>
					</li>
					<li class="divider"></li>
					<li>
						<a href="javascript:;" data-toggle="collapse" data-target="#addons"><i class="fas fa-puzzle-piece"></i>&nbsp;<?PHP echo $lang['winav12']; ?>&nbsp;<i class="fas fa-caret-down"></i></a>
						<?PHP echo '<ul id="addons" class="'.(basename($_SERVER['SCRIPT_NAME']) == "addon_assign_groups.php" ? 'in collapse">' : 'collapse">'); ?>
							<?PHP echo '<li'.(basename($_SERVER['SCRIPT_NAME']) == "addon_assign_groups.php" ? ' class="active">' : '>'); ?>
								<a href="addon_assign_groups.php" class="active"><?PHP echo $lang['stag0001']; ?></a>
							</li>
						</ul>
					</li>
					<li class="divider"></li>
					<li>
						<a href="javascript:;" data-toggle="collapse" data-target="#admin"><i class="fas fa-users"></i>&nbsp;<?PHP echo $lang['winav7']; ?>&nbsp;<i class="fas fa-caret-down"></i></a>
						<?PHP echo '<ul id="admin" class="'.(basename($_SERVER['SCRIPT_NAME']) == "admin_addtime.php" || basename($_SERVER['SCRIPT_NAME']) == "admin_remtime.php" ? 'in collapse">' : 'collapse">'); ?>
							<?PHP echo '<li'.(basename($_SERVER['SCRIPT_NAME']) == "admin_addtime.php" ? ' class="active">' : '>'); ?>
								<a href="admin_addtime.php"><?PHP echo $lang['wihladm1']; ?></a>
							</li>
							<?PHP echo '<li'.(basename($_SERVER['SCRIPT_NAME']) == "admin_remtime.php" ? ' class="active">' : '>'); ?>
								<a href="admin_remtime.php"><?PHP echo $lang['wihladm2']; ?></a>
							</li>
						</ul>
					</li>
					<li class="divider"></li>
					<?PHP echo '<li'.(basename($_SERVER['SCRIPT_NAME']) == "bot.php" ? ' class="active">' : '>'); ?>
						<a href="bot.php"><i class="fas fa-power-off"></i>&nbsp;<?PHP echo $lang['winav8']; ?></a>
					</li>
					<?PHP
					if(isset($botstatus)) {
						echo '<li class="divider"></li>';
						if($botstatus == 1) {
							echo '<li><div class="btn-group-justified alertbot alert-success" style="width:100%;"><i class="fas fa-check"></i>&nbsp;&nbsp;'.$lang['boton'].'</div></li>';
						} else {
							echo '<li><div class="btn-group-justified alertbot alert-info" style="width:100%;"><i class="fas fa-times"></i>&nbsp;&nbsp;'.$lang['botoff'].'</div></li>';
						}
					}
					?>
				</ul>
			</div>
		</nav>
<?PHP
if($cfg['webinterface_admin_client_unique_id_list'] == NULL && isset($_SESSION[$rspathhex.'username']) && $_SESSION[$rspathhex.'username'] == $cfg['webinterface_user'] && !isset($err_msg)) {
	$err_msg = $lang['winav11']; $err_lvl = 3;
}

if(!isset($_SERVER['HTTPS']) && !isset($err_msg) || isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "on" && !isset($err_msg)) {
	$host = "<a href=\"https://".$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['PHP_SELF']), '/\\')."\">";
	$err_msg = sprintf($lang['winav10'], $host,'</a>!<br>', '<br>'); $err_lvl = 2;
}

function error_handling($msg,$type = NULL) {
	switch ($type) {
		case NULL: echo '<div class="alert alert-success alert-dismissible">'; break;
		case 1: echo '<div class="alert alert-info alert-dismissible">'; break;
		case 2: echo '<div class="alert alert-warning alert-dismissible">'; break;
		case 3: echo '<div class="alert alert-danger alert-dismissible">'; break;
	}
	echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>',$msg,'</div>';
}
?>