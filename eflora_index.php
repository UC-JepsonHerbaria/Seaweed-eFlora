<?php
date_default_timezone_set('America/Los_Angeles');
$SearchTerm = htmlspecialchars($_GET["index"]);

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
       <td width="100%"><span class="pageName"><a href="http://ucjeps.berkeley.edu/seaweedflora/seaweedflora/">California Seaweeds eFlora:</a> Search page</span>
		<br>
			<!--<span class="pageAuthorLine">
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
//$SearchTerm must be a single capital letter
if (preg_match("/^[A-Z]$/", $SearchTerm)) {
	echo '<p class="bodyText">Index Page: '.$SearchTerm.'<p>';

//perform a search that returns all names that start with a letter
//this is the same query as in search_eflora.php, but with a different WHERE clause
	$stmt = $db->prepare("SELECT a.ScientificName as ScientificName, a.TaxonID as TaxonID, a.NativeStatus as NativeStatus, a.MajorGroup as MajorGroup, a.NameStatus as NameStatus, a.DescriptionDate as DescriptionDate, a.AcceptedNameTID as AcceptedNameTID, a.HasSpeciesPage as HasSpeciesPage, b.ScientificName as AcceptedName
						FROM eflora_taxa a
						LEFT OUTER JOIN eflora_taxa b on a.AcceptedNameTID = b.TaxonID
						WHERE a.ScientificName LIKE '".$SearchTerm."%' 
						AND a.HasSpeciesPage LIKE 'Y'
						ORDER BY MajorGroup, ScientificName");
	$results = $stmt->execute();

// changed the following to match the format in the search_eflora.php file (making scientific names and synonyms one column instead of 2)

//in the following "if" paragraph, the row needs to be printed before the while loop
//because when the while loop runs fetchArray, it is moving on to the second row
//so if the first row isn't printed before the while loop, the results will start with the second row.
	if ($row = $results->fetchArray()) {
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
}

else {
	echo 'Invalid index URL. Please use the index links above<br>';
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
