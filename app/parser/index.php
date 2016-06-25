<?php
header('Content-Type: text/html; charset=utf-8');

// Display PHP errors
error_reporting(E_ALL | E_STRICT);
ini_set('display_startup_errors',1);
ini_set('display_errors', 1);

// Display informations in real time (flush HTML)
ob_implicit_flush(true);
ob_end_flush();


///////////////////////////////////////////////////////
// Logger

require_once("../php/logManager.class.php");
$logger         = LogManager::getLogger();
$logger->level  = "debug"; // debug < info < warn < error < fatal
$logger->html   = true;    // HTML format
$logger->output = false;   // Output log in the end (see $logger->flush() below)


///////////////////////////////////////////////////////
// Loader

//require_once("php/loader.class.php");
require_once("../php/krumo/class.krumo.php");
require_once("../php/csvparser/csvtableformater.class.php");
require_once("../php/csvparser/csvreader.class.php");
require_once("../php/csvparser/csvconverter.class.php");

///////////////////////////////////////////////////////
// Code

try {

	$aData = array(
		"doc"  => "https://docs.google.com/spreadsheets/d/16-CJ_b6nkpj2BL_zsV0lezuWEF1JetD8X0IqN0AwFA8/edit#gid=0",
		"csv"  => "https://docs.google.com/spreadsheets/d/16-CJ_b6nkpj2BL_zsV0lezuWEF1JetD8X0IqN0AwFA8/export?gid=0&format=csv",
		"json" => "../data/data.json",
		"jsonPrettyPrint" => true
	);

	$logger->info("Doc : <a href='{$aData["doc"]}' target='_blank'>" . $aData["doc"] . "</a>");
	$logger->info("CSV : <a href='{$aData["csv"]}' target='_blank'>" . $aData["csv"] . "</a>");
	$logger->info("Data: <a href='{$aData["json"]}' target='_blank'>" . $aData["json"] . "</a>");
	$logger->info("App : <a href='../app/index.html' target='_blank'>app folder</a>");
	$logger->info("Option <b>jsonPrettyPrint</b>: " . ($aData["jsonPrettyPrint"] ? "1" : "0"));
	$logger->breakLine("info");

	$logger->info("Jeu de données : Fichier Google Doc en ligne (tableau simple)");
	$logger->info("Résultat (traitement complet du fichier)");

	$cf = new CsvParser\CsvTableFormater();
	$cf->addColumn("A", "commission"  , CsvParser\CsvFormaterType::RAW);
	$cf->addColumn("B", "temps_individuel"  , CsvParser\CsvFormaterType::RAW);
	$cf->addColumn("C", "temps_global"  , CsvParser\CsvFormaterType::RAW);
	$cf->addColumn("D", "action"  , CsvParser\CsvFormaterType::RAW);
	$cf->addColumn("E", "nbr personnes"  , CsvParser\CsvFormaterType::RAW);
        $cf->addColumn("F", "competences"  , CsvParser\CsvFormaterType::RAW);
        $cf->addColumn("G", "habitue"  , CsvParser\CsvFormaterType::RAW);


	$cr = new CsvParser\CsvReader();
	$aResult  = $cr->read($aData["csv"], $cf, 1);
	$aResultG = $aResult;
	//$cr->groupBy("id", false);

	// Dump json
	$cr->saveToJson($aData["json"], $aResultG, $aData["jsonPrettyPrint"]);


} catch (Exception $e) {
	echo 'Exception : ',  $e->getMessage(), "\n";
}

///////////////////////////////////////////////////////

?>
<?php 
	$cc = new CsvParser\CsvConverter();
	//$cc->toHtmlTable($cr);
	/*
	krumo($aResult);
	krumo($aResultG);
	krumo($aResult); 
	*/
?>
<!DOCTYPE html>
<html lang='fr'>
	<head>
	<title></title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta charset='utf-8' />
	<style>

		* { margin: 0; padding: 0; box-sizing: border-box; }
		html, body{ height:100%; }

		#wrapper {
			position:relative;
			width:100%;
			min-height: 100%;
			padding: 10px;
			height: auto!important;
			height:100%;
			background:#FFF;
			color: #343434;
			font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
		}


		/* ---------------------- */
		/* Separator              */

		.sep {
			position:relative;
			margin:10px 0; width:100%;
		}

		.sep hr {
			overflow:hidden;
			height:20px; 
			border: none;
		}

		.sep hr:after {
			content:'';
			display:block; 
			margin:-28px auto 0;
			width:100%; 
			height:25px;
			border-radius:125px / 12px;
			box-shadow:0 0 8px black;
		}

		::selection {
			background-color: hsla(24, 20%, 50%,.5);
			color: white;
			text-shadow: 0 -1px 1px hsl(24, 20%, 50%);
		}


		/* ---------------------- */
		/* Console                */

		#console {
			width: 100%;
			margin: 10px 0 30px 0;
			padding: 10px;
			border: 1px solid #ddd;
			border-radius: 4px;
			background-color: #f9f9f9;
			/*line-height: 9px;*/
			overflow: auto;
		}


		/* ---------------------- */
		/* Table                  */

		table {
			width: 100%;
			border: 1px solid #ddd;
			border-collapse: separate;
			border-spacing: 0;
			border-color: #ddd;
			border-left: 0;
			border-radius: 4px;
			font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
			font-size: 14px;
			line-height: 20px;
			color: #333;
		}

		table.responsive {
			table-layout: fixed;
		}

		table.responsive td {
			overflow: hidden;
			white-space: nowrap;
			text-overflow: ellipsis;
		}

		table thead tr th {
			padding: 8px;
			line-height: 20px;
			font-weight: bold;
			vertical-align: middle;
			border-left: 1px solid #ddd;
			border-bottom: 2px solid #ddd;
		}

		table tbody tr td {
			padding: 8px;
			line-height: 20px;
			text-align: left;
			vertical-align: top;
			border-top: 1px solid #ddd;
			border-left: 1px solid #ddd;
		}

		table tbody tr:hover td {
			background-color: #f9f2f4 !important;
		}

		table tbody tr:nth-child(odd) td {
			background-color: #f9f9f9;
		}

		table tbody tr td strong {
			font-family: Monaco, Menlo, Consolas, 'Courier New', monospace;
			font-weight: normal;
			padding: 2px 4px;
			font-size: 90%;
			color: #c7254e;
			background-color: #CFE5FF;
			color: #136DD4;
			color: #960f4a;
			background-color: #fcf0f7;
		}

	</style>
	<script>
		// Expand au démarrage (tout sauf 4ème où on déplie que le 1er niveau)
		document.addEventListener('DOMContentLoaded',function(){
			//krumo.expandAll();
			//krumo.expand(document.querySelectorAll(".krumo-root")[4], true);
		});
	</script>
	</head>
	<body>
		<div id="wrapper">

			<pre id="console">
<b>Console :</b>
<p>-------</p>
<?php $logger->flush(); ?>
			</pre>

			<div class="sep"><hr /></div>
			
			<h1>Result</h1>
			<div id="result" class="responsive">
				<?php krumo($aResult); ?>
				<table border="0" width="100%">
					<thead>
						<tr>
							<?php foreach ($cf->cols as $col): ?>
								<th><?php echo $col["key"] ?></th>
							<?php endforeach; ?>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($aResult as $line): ?>
						<tr>
							<?php foreach ($cf->cols as $col): ?>
								<td>
									<?php if ($col["key"] === "INT"): ?><strong><?php endif; ?>
									<?php // echo mb_strimwidth(filter_var($line[$col["key"]], FILTER_SANITIZE_SPECIAL_CHARS), 0, 50, "...", "UTF-8") ?>
									<?php echo filter_var($line[$col["key"]], FILTER_SANITIZE_SPECIAL_CHARS); ?>
									<?php if ($col["key"] === "INT"): ?></strong><?php endif; ?>
								</td>
							<?php endforeach; ?>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>

				
			</div>

		</div>
	</body>
</html>
