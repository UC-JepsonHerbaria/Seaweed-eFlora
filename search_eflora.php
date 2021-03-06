<?php
date_default_timezone_set('America/Los_Angeles');

//get user input from POST
if (isset($_POST['query'])){
	$SearchTerm = $_POST['query'];
	$SearchTermPrintStatement = "Scientific Name = ".$SearchTerm."; ";
}
//Commented out the last in-development changes made by David Baxter and Jason Alexander, these will be fixed and re-vised in the future in the second grant
//if (isset($_GET['majgrp'])){
//	$URLMajorGroup = $_GET['majgrp'];
//	$MajGrpAndStatement = "AND a.MajorGroup LIKE '".$URLMajorGroup."'";
//	$MajGrpPrintStatement = "Major Group = ".$URLMajorGroup."; ";
//}
//if (isset($_GET['MacAdd'])){
//	$MacAddition = $_GET['MacAdd'];
//	$MacAddAndStatement = "AND a.Additions = IS NOT NULL";//value is an "x" in this field, otherwise field is blank
//	$MacAddPrintStatement = "Additions = "x"; ";
//} 
//if (isset($_GET['SpPage'])){
//	$HasSpeciesPage = $_GET['SpPage'];
//	$SpPageAndStatement = "AND a.HasSpeciesPage = IS NOT NULL";//What value is expressed here?, this column not yet added
//	$SpPagePrintStatement = "Species Page = "x"; ";//I assume Kathy Ann has a value of "x" in this field, otherwise field is blank
//}
//connect to the database
require 'config/config.php';
$db = new SQLite3($database_location);
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>Jepson Herbarium: Jepson Flora Project: Jepson eFlora Search</title>
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW"> 
<link href="http://ucjeps.berkeley.edu/common/styles/dropdowns.css" rel="stylesheet" type="text/css" />

<!-- Google analytics -->
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-27128382-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>

<body onload="initialize()">
<!-- Begin banner -->
<?php include($_SERVER['DOCUMENT_ROOT'].'/common/php/header_uc.php'); ?>
<!-- End banner -->

<!-- Beginning of horizontal menu -->
<?php include($_SERVER['DOCUMENT_ROOT'].'/common/php/globalnav.php'); ?> 
<!-- End of horizontal menu -->

<table border="0">
   <tr>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td width="100%"><span class="pageName"><a href="http://ucjeps.berkeley.edu/seaweedflora/">California Seaweeds eFlora:</a> Search page</span>
		<br>
<!--			<span class="pageAuthorLine">
				<a href="http://ucjeps.berkeley.edu/IJM_fam_key.html">Key to families</a>
				<a href="http://ucjeps.berkeley.edu/IJM_toc.html">Table of families and genera</a>
			</span>	  -->
		</td>
	</tr>
</table>

<table align="center" width="100%"><tr><td>
&nbsp;
</td>
<td width=90%">
<!--eFlora index-->
<?php include('common/php/eflora_index_bar.php'); ?>
<hr width="10%">
</td>

<td align="right">
&nbsp;
</td></tr>
</table>

<br>

<table width="100%">
<tr>
<td width="100%" valign="top">

<form action="search_eflora.php" method="POST">
<b>Scientific Name<br></b>		<input type="text" id="query_text" name="query"></input>
		<input type="submit" value="Submit Name" />
</form>

<?php
if (isset($SearchTerm)){// OR isset($URLMajorGroup)){  Commented this small section out also.  It is in-devlopment code for filtering major group
	$SearchTerm = trim( $SearchTerm, $character_mask = " \t\n\r\0\x0B");
	echo '<p class="bodyText">You searched for: '.$SearchTermPrintStatement;
	echo $MajGrpPrintStatement;
	echo '</p>';
	if (strpos($SearchTerm, " ssp. ") !== false) { #in case someone uses ssp.
		$SearchTerm = str_replace(" ssp. ", " subsp. ", $SearchTerm);
	}
	$SearchTerm = str_replace(" ", "%", $SearchTerm); //replace space with wildcard, so e.g. "Art cal" returns "Artemisia californica"

//Commented out the last in-development changes made by David Baxter and Jason Alexander, these will be exapnded and re-vised in the future in the second grant

//put in a query that gets the ScientificName, TID, maybe other stuff FROM eflora_taxa WHERE ScientificName LIKE ScientificName%
//preparing a query and then executing it, as done here, is supposed to be faster than running the query directly
//The WHERE statement is set up so that the $SearchTerm needs to match the start of the name (i.e. they start with the genus)
//OR their search term matches anywhere in the ScientificName as long as it's preceded by a space (i.e. they start with the specific or infra epithet)
//One problem with this is if someone searches "var" or "subsp", and in general it may be too permissible
//$stmt = $db->prepare("SELECT a.ScientificName as ScientificName, a.TaxonID as TaxonID, a.NativeStatus as NativeStatus, a.MajorGroup as MajorGroup, a.NameStatus as NameStatus, a.DescriptionDate as DescriptionDate, a.Additions as Additions, a.HasSpeciesPage as HasSpeciesPage, a.AcceptedNameTID as AcceptedNameTID, b.ScientificName as AcceptedName
//						FROM eflora_taxa a
//						LEFT OUTER JOIN eflora_taxa b on a.AcceptedNameTID = b.TaxonID
//						WHERE (a.ScientificName LIKE '".$SearchTerm."%' OR a.ScientificName LIKE '% ".$SearchTerm."%')
//							".$MajGrpAndStatement."
//							".$MacAddAndStatement."
//							".$SpPageAndStatement."					
//						ORDER BY ScientificName");
//$results = $stmt->execute();
//						".$MajGrpAndStatement."
//in the following "if" paragraph, the row needs to be printed before the while loop
//because when the while loop runs fetchArray, it is moving on to the second row
//so if the first row isn't printed before the while loop, the results will start with the second row.
//	if ($row = $results->fetchArray()) {
//		//expand major group
//		if ($row['MajorGroup'] == "C") { $row['MajorGroup'] = str_replace("C", "Chlorophyceae (green)", $row['MajorGroup']); }
//		if ($row['MajorGroup'] == "P") { $row['MajorGroup'] = str_replace("P", "Phaeophyceae (brown)", $row['MajorGroup']); }
//		if ($row['MajorGroup'] == "R") { $row['MajorGroup'] = str_replace("R", "Rhodophyceae (red)", $row['MajorGroup']); }			
//		//print row
//		echo '<div class="eFloraTable"><table border="1">';
//		echo '<tr><td>Major Group</td><td>Scientific Name</td><td>Year</td><td>Name Status</td><td>Accepted Name</td><td>Native Status</td></tr>';
//		if ($row['AcceptedNameTID']){ //if it has an AcceptedNameTID, then it's a synonym, so print the synonym line
//			echo '<tr><td>'.ucfirst(strtolower($row['MajorGroup'])).'</td>';
//			echo '<td>'.$row['ScientificName'].'</td>';
//			echo '<td>'.$row['DescriptionDate'].'</td>';
//			echo '<td>'.$row['NameStatus'].'</td>';
//			echo '<td><a href="eflora_display.php?tid='.$row['AcceptedNameTID'].'">'.$row['AcceptedName'].'</td>';
//			echo '<td>'.ucfirst(strtolower($row['NativeStatus'])).'</td>';
//			echo '</tr>';
//		}
//		else { //else it is an accepted name, so print the full line
//			echo '<tr><td>'.ucfirst(strtolower($row['MajorGroup'])).'</td>';
//			echo '<td><a href="eflora_display.php?tid='.$row['TaxonID'].'">'.$row['ScientificName'].'</td>';
//			echo '<td>'.$row['DescriptionDate'].'</td>';
//			echo '<td>'.$row['NameStatus'].'</td>';
//			echo '<td></td>'; // accepted name column is blank for accepted names
//			echo '<td>'.ucfirst(strtolower($row['NativeStatus'])).'</td>';
//			echo '</tr>';
//		}
//		while ($row = $results->fetchArray()) {
//			//expand major group
//			if ($row['MajorGroup'] == "C") { $row['MajorGroup'] = str_replace("C", "Chlorophyceae (green)", $row['MajorGroup']); }
//			if ($row['MajorGroup'] == "P") { $row['MajorGroup'] = str_replace("P", "Phaeophyceae (brown)", $row['MajorGroup']); }
//			if ($row['MajorGroup'] == "R") { $row['MajorGroup'] = str_replace("R", "Rhodophyceae (red)", $row['MajorGroup']); }			
//			//print row
//			if ($row['AcceptedNameTID']){ //if it has an AcceptedNameTID, then it's a synonym, so print the synonym line
//				echo '<tr><td>'.ucfirst(strtolower($row['MajorGroup'])).'</td>';
//				echo '<td>'.$row['ScientificName'].'</td>';
//				echo '<td>'.$row['DescriptionDate'].'</td>';
//				echo '<td>'.$row['NameStatus'].'</td>';
//				echo '<td><a href="eflora_display.php?tid='.$row['AcceptedNameTID'].'">'.$row['AcceptedName'].'</td>';
//				echo '<td>'.ucfirst(strtolower($row['NativeStatus'])).'</td>';
//				echo '</tr>';
//			}
//			else { //else it is an accepted name, so print the full line
//				echo '<tr><td>'.ucfirst(strtolower($row['MajorGroup'])).'</td>';
//				echo '<td><a href="eflora_display.php?tid='.$row['TaxonID'].'">'.$row['ScientificName'].'</td>';
//				echo '<td>'.$row['DescriptionDate'].'</td>';
//				echo '<td>'.$row['NameStatus'].'</td>';
//				echo '<td></td>'; // accepted name column is blank for accepted names
//				echo '<td>'.ucfirst(strtolower($row['NativeStatus'])).'</td>';
//
//   The edited and revised search code begins here.  This was added to make a simple more or less clone of the Jepson eFlora search_eflora.php.  The above in-development search code could not be finished or debugged before the December 2016 deadline

//put in a query that gets the ScientificName, TID, maybe other stuff FROM eflora_taxa WHERE ScientificName LIKE ScientificName%
//preparing a query and then executing it, as done here, is supposed to be faster than running the query directly
//The WHERE statement is set up so that the $SearchTerm needs to match the start of the name (i.e. they start with the genus)
//OR their search term matches anywhere in the ScientificName as long as it's preceded by a space (i.e. they start with the specific or infra epithet)
//One problem with this is if someone searches "var" or "subsp", and in general it may be too permissible
	$stmt = $db->prepare("SELECT a.ScientificName as ScientificName, a.TaxonID as TaxonID, a.NativeStatus as NativeStatus, a.MajorGroup as MajorGroup, a.NameStatus as NameStatus, a.DescriptionDate as DescriptionDate, a.AcceptedNameTID as AcceptedNameTID, b.ScientificName as AcceptedName
						FROM eflora_taxa a
						LEFT OUTER JOIN eflora_taxa b on a.AcceptedNameTID = b.TaxonID
						WHERE (a.ScientificName LIKE '".$SearchTerm."%' OR a.ScientificName LIKE '% ".$SearchTerm."%')				
						ORDER BY MajorGroup, ScientificName");
	$results = $stmt->execute();

//in the following "if" paragraph, the row needs to be printed before the while loop
//because when the while loop runs fetchArray, it is moving on to the second row
//so if the first row isn't printed before the while loop, the results will start with the second row.
	if ($row = $results->fetchArray()) {
		//expand major group
		if ($row['MajorGroup'] == "C") { $row['MajorGroup'] = str_replace("C", "Chlorophyceae (green)", $row['MajorGroup']); }
		if ($row['MajorGroup'] == "P") { $row['MajorGroup'] = str_replace("P", "Phaeophyceae (brown)", $row['MajorGroup']); }
		if ($row['MajorGroup'] == "R") { $row['MajorGroup'] = str_replace("R", "Rhodophyceae (red)", $row['MajorGroup']); }			
		//print row
		echo '<div class="eFloraTable"><table border="1">';
		echo '<tr><td>Major Group</td><td>Scientific Name</td><td>Year</td><td>Name Status</td><td>Native Status</td></tr>';
		if ($row['AcceptedNameTID']){ //if it has an AcceptedNameTID, then it's a synonym, so print the synonym line
			echo '<tr><td>'.ucfirst(strtolower($row['MajorGroup'])).'</td>';
			echo '<td><a href="eflora_display.php?tid='.$row['AcceptedNameTID'].'">'.$row['ScientificName'].'</a><br>(Under '.$row['AcceptedName'].')</td>';
			echo '<td>'.$row['DescriptionDate'].'</td>';
			echo '<td>'.$row['NameStatus'].'</td>';
			echo '<td>'.ucfirst(strtolower($row['NativeStatus'])).'</td>';
			echo '</tr>';
		}
		else { //else it is an accepted name, so print the full line
			echo '<tr><td>'.ucfirst(strtolower($row['MajorGroup'])).'</td>';
			echo '<td><a href="eflora_display.php?tid='.$row['TaxonID'].'">'.$row['ScientificName'].'</td>';
			echo '<td>'.$row['DescriptionDate'].'</td>';
			echo '<td>'.$row['NameStatus'].'</td>';
			echo '<td>'.ucfirst(strtolower($row['NativeStatus'])).'</td>';
			echo '</tr>';
		}
		while ($row = $results->fetchArray()) {
			//expand major group
			if ($row['MajorGroup'] == "C") { $row['MajorGroup'] = str_replace("C", "Chlorophyceae (green)", $row['MajorGroup']); }
			if ($row['MajorGroup'] == "P") { $row['MajorGroup'] = str_replace("P", "Phaeophyceae (brown)", $row['MajorGroup']); }
			if ($row['MajorGroup'] == "R") { $row['MajorGroup'] = str_replace("R", "Rhodophyceae (red)", $row['MajorGroup']); }			
			//print row
			if ($row['AcceptedNameTID']){ //if it has an AcceptedNameTID, then it's a synonym, so print the synonym line
				echo '<tr><td>'.ucfirst(strtolower($row['MajorGroup'])).'</td>';
				echo '<td><a href="eflora_display.php?tid='.$row['AcceptedNameTID'].'">'.$row['ScientificName'].'</a><br>(Under '.$row['AcceptedName'].')</td>';
				echo '<td>'.$row['DescriptionDate'].'</td>';
				echo '<td>'.$row['NameStatus'].'</td>';
				echo '<td>'.ucfirst(strtolower($row['NativeStatus'])).'</td>';
				echo '</tr>';
			}
			else { //else it is an accepted name, so print the full line
				echo '<tr><td>'.ucfirst(strtolower($row['MajorGroup'])).'</td>';
				echo '<td><a href="eflora_display.php?tid='.$row['TaxonID'].'">'.$row['ScientificName'].'</td>';
				echo '<td>'.$row['DescriptionDate'].'</td>';
				echo '<td>'.$row['NameStatus'].'</td>';
				echo '<td>'.ucfirst(strtolower($row['NativeStatus'])).'</td>';
				echo '</tr>';
			}	
		}
		echo '</table></div>';
	}
	else {
		echo '<i>'.$SearchTerm.'</i> was not found in the eFlora name list.<br>
		Try another search, or try searching the <a href="http://ucjeps.berkeley.edu/interchange/">Jepson Online Interchange</a>.<br>';
	}	
}

else {
	echo 'No search term entered. Please enter a search term above<br>';
}

?>
<br>
<a href="mailto:kathyannmiller@berkeley.edu"><span class="feedbackLink">Feedback/Contact<span></a><br>
</td>
</tr>
</table>



<!--Begin footer-->
<?php include('common/php/seaweed_footer.php'); ?>
<!--End footer-->
