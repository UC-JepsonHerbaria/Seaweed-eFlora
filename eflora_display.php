<?php
date_default_timezone_set('America/Los_Angeles');
$URL_TaxonID = htmlspecialchars($_GET["tid"]);

require 'config/config.php';
$db = new SQLite3($database_location);

if (preg_match('/^[0-9]+/', $URL_TaxonID)) {
	$results = $db->query('SELECT ID, TaxonID, ScientificName, TaxonAuthor, NativeStatus, KeyCharacteristics, Status, Habitat, LifeHistory, Conservation, DistributionNotes, MACDescription, MACNotes, VerticalDistribution, Frequency, Substrate, Associates, Epiphytes, TypeLocality, SimilarSpecies 
							FROM eflora_taxa
							WHERE TaxonID='.$URL_TaxonID.';'); //10 is the TaxonID for Lessoniopsis littoralis, for example
}
else { //Else, process URL_TaxonID as a string and check if there's a matching name
	$URL_Name = ucwords($URL_TaxonID);
	$URL_Name = str_replace("-", " ", $URL_Name);
	$URL_Name = str_replace("_", " ", $URL_Name);
	$results = $db->query('SELECT ID, TaxonID, ScientificName, TaxonAuthor, NativeStatus, KeyCharacteristics, Status, Habitat, LifeHistory, Conservation, DistributionNotes, MACDescription, MACNotes, VerticalDistribution, Frequency, Substrate, Associates, Epiphytes, TypeLocality, SimilarSpecies 
							FROM eflora_taxa
							WHERE ScientificName IN ("'.$URL_Name.'");');
}


while ($row = $results->fetchArray()) {

$ID = $row['ID'];
$URL_TaxonID = $row['TaxonID']; //reassign as TaxonID if name was used in URL, for queries to media table
$ScientificName = $row['ScientificName'];
$TaxonAuthor = $row['TaxonAuthor'];
$NativeStatus = $row['NativeStatus'];
$KeyCharacteristics = $row['KeyCharacteristics'];
$Status = $row['Status'];
$Habitat = $row['Habitat'];
$LifeHistory = $row['LifeHistory'];
$Conservation = $row['Conservation'];
$DistributionNotes = $row['DistributionNotes'];
$MACDescription = $row['MACDescription'];
$MACNotes = $row['MACNotes'];
$VerticalDistribution = $row['VerticalDistribution'];
$Frequency = $row['Frequency'];
$Substrate = $row['Substrate'];
$Associates = $row['Associates'];
$Epiphytes = $row['Epiphytes'];
$TypeLocality = $row['TypeLocality'];
$SimilarSpecies = $row['SimilarSpecies'];
//var_dump($row); 
/*var_dump prints the whole row in a databased format
which is useful for seeing what you're getting
but is pretty ugly
*/
}

//get previous and next info based on ID
$previousID = $ID-1;
$nextID = $ID+1;

$results = $db->query('SELECT TaxonID, ScientificName
						FROM eflora_taxa
						WHERE ID='.$previousID.'');

while ($row = $results->fetchArray()) {
	$previousTaxonID = $row['TaxonID'];
	$previousName = $row['ScientificName'];
}

$results = $db->query('SELECT TaxonID, ScientificName
						FROM eflora_taxa
						WHERE ID='.$nextID.'');

while ($row = $results->fetchArray()) {
	$nextTaxonID = $row['TaxonID'];
	$nextName = $row['ScientificName'];
}

//get the audio clip from the media table
$results = $db->query('SELECT ID, FileName 
						FROM eflora_media
						WHERE TaxonID='.$URL_TaxonID.' AND MediaType LIKE "Audio";'); //10 is the TaxonID for Lessoniopsis littoralis, for example

while ($row = $results->fetchArray()) {
	$AudioFile= $row['FileName'];
}

//get illustration and photo results
$illustration_results = $db->query('SELECT FileName, IsDecew
									from eflora_media
									WHERE TaxonID='.$URL_TaxonID.' AND MediaType LIKE "Illustration";');
$photo_results = $db->query('SELECT FileName, MediaURL, Creator, ThumbURL, Locality
							from eflora_media
							WHERE TaxonID='.$URL_TaxonID.' AND MediaType LIKE "Photo";');
$specimen_results = $db->query('SELECT FileName, MediaURL, Creator, ThumbURL
								from eflora_media
								WHERE TaxonID='.$URL_TaxonID.' AND MediaType LIKE "Specimen";');


//Before doing anything, if the TID isn't recognized, give a plain error screen
if (!$ID){ //if TaxonID (pulled from URL) did not match a line in the database...
	echo "<html xmlns='http://www.w3.org/1999/xhtml'>";
	echo "Taxon not recognized TID=".$TaxonID.":  <a href='http://ucjeps.berkeley.edu/seaweedflora/'>Return to the front page</a>";
	echo "</html>";
	die();
}

?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>University Herbarium: California Seaweed eFlora: <?php echo "$ScientificName"?></title> 
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
<link href="http://ucjeps.berkeley.edu/common/styles/dropdowns.css" rel="stylesheet" type="text/css" />
<link href="common/css/seaweed.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="common/css/globalmenu.css">
<script src="common/js/globalmenu.js"></script>

<!--jquery/jquery-ui script files-->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<!-- fancybox files -->
<link rel="stylesheet" href="fancybox/source/jquery.fancybox.css" type="text/css" media="screen" />
<script type="text/javascript" src="fancybox/source/jquery.fancybox.pack.js"></script>

<!-- google maps files and scripts 
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>-->

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

<?php 
// TID is passed to JS by using PHP to echo the value of $TaxonID to a JS variable TaxID 
// Apparently there are better ways to do this using AJAX
$name4GoogleMap = str_replace(" ", '_', $ScientificName);
?>
<!--<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>-->
<script type="text/javascript"> 

	var map; //If we want access "map" outside of initialize(), this must be a global variable
	var kml;
	
     function ZoomControl(controlDiv, map) {

        // Set CSS for the controlDiv border.
        var controlUI = document.createElement('div');
        controlUI.style.backgroundColor = '#fff';
        controlUI.style.border = '2px solid #fff';
        controlUI.style.borderRadius = '3px';
        controlUI.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
        controlUI.style.cursor = 'pointer';
        controlUI.style.marginBottom = '10px';
        controlUI.style.textAlign = 'center';
        controlUI.title = 'Click to Zoom';
        controlDiv.appendChild(controlUI);

   
        
        // Set CSS for the zoomIn
  var zoomInButton = document.createElement('div');
  zoomInButton.style.color = 'rgb(25,25,25)';
        zoomInButton.style.fontFamily = 'Roboto,Arial,sans-serif';
        zoomInButton.style.fontSize = '12px';
        zoomInButton.style.lineHeight = '5px';
        zoomInButton.style.paddingLeft = '2px';
        zoomInButton.style.paddingRight = '2px';
        zoomInButton.innerHTML = 'IN + ';
  controlUI.appendChild(zoomInButton);
        
      // Set CSS for the zoomOut
  var zoomOutButton = document.createElement('div');
  zoomOutButton.style.color = 'rgb(25,25,25)';
        zoomOutButton.style.fontFamily = 'Roboto,Arial,sans-serif';
        zoomOutButton.style.fontSize = '12px';
        zoomOutButton.style.lineHeight = '5px';
        zoomOutButton.style.paddingLeft = '2px';
        zoomOutButton.style.paddingRight = '2px';
        zoomOutButton.innerHTML = ' - OUT';
  controlUI.appendChild(zoomOutButton);  
        
        

  // Setup the click event listener - zoomIn
  google.maps.event.addDomListener(zoomInButton, 'click', function() {
    map.setZoom(map.getZoom() + 1);
  });
    
  // Setup the click event listener - zoomOut
  google.maps.event.addDomListener(zoomOutButton, 'click', function() {
    map.setZoom(map.getZoom() - 1);
  });  
    
}
	
//    function initMap() {
    
  //  map = new google.maps.Map(document.getElementById("map_canvas"); //this can't have var
    
    //var mapOptions = {
//          minzoom: 2,
 //         zoomControl: false,
  //        mapTypeId: google.maps.MapTypeId.ROADMAP
//	}

//	var MapName = "<?php echo $name4GoogleMap;?>";
//		
//		kml = new google.maps.KmlLayer("http://ucjeps.berkeley.edu/seaweedflora/SW_coords/"+MapName+".kml?122", {  });
  //			kml.setMap(map);

  //      });

        

function initialize() {
  var mapDiv = document.getElementById('map_canvas');
    
  var mapOptions = {
    zoomControl: false,
    streetViewControl: false, //disable pegman/street view
//    fullscreenControl: true,  
    scrollwheel: false, //disable mouse zoom
    mapTypeId: google.maps.MapTypeId.TERRAIN
  }
  
  map = new google.maps.Map(mapDiv, mapOptions);
  
    
  var MapName = "<?php echo $name4GoogleMap;?>";
  
  kml = new google.maps.KmlLayer("http://ucjeps.berkeley.edu/seaweedflora/SW_coords/"+MapName+".kml?122", {  });
  
  kml.setMap(map);

  // Create the DIV to hold the control and call the ZoomControl() constructor
  // passing in this DIV.
  var zoomControlDiv = document.createElement('div');
  var zoomControl = new ZoomControl(zoomControlDiv, map);

  zoomControlDiv.index = 1;
  map.controls[google.maps.ControlPosition.LEFT_BOTTOM].push(zoomControlDiv);
}

                               
 
initialize();	
	
	
	//	function initialize() {
	//		var mapOptions = {
	//			mapTypeId: google.maps.MapTypeId.ROADMAP
	//		};
	//		map = new google.maps.Map(document.getElementById("map_canvas"),mapOptions); //this can't have var
	//		
	//		var MapName = "<?php echo $name4GoogleMap;?>";
	//		
	//		kml = new google.maps.KmlLayer("http://ucjeps.berkeley.edu/seaweedflora/SW_coords/"+MapName+".kml?122", {  });
  	//		kml.setMap(map);
  		  			

 //}
 

	 
		
//		$(document).ready(function(){

			/* set tab change */
//			$('.tab-content').not(':first').hide();
//			$('.tabs').click(function(e){
//				e.preventDefault();
//				$('.tab-content').hide();
//				$($(this).attr('href')).show();
//				if($(this).attr('href')=='#tabs-1'){
//					//we have to set center for map after resize, but we need to know center BEFORE we resize it
//					var centerx = map.getCenter();
//					google.maps.event.trigger(map, 'resize'); //this fix the problem with not completely map
//					map.setCenter(centerx);
//				
//				}
				
///			});
	//	});




function JumpToIt(list) {
    var newPage = list.options[list.selectedIndex].value
    if (newPage != "None") {
        location=newPage
    }
}
</script>

<script async defer src="https://maps.googleapis.com/maps/api/js?callback=initMap">
    </script>

<?php //for inside the jquery ui tabs. For some reason it is not working from this style in seaweed.css?>
<style> 
a.internal {
	color: #CC6600;
	text-decoration: none;
	}
a.internal:hover {
	color: #3366CC;
	text-decoration: underline;
}
</style>

<!-- Google analytics -->
<!-- goes here, once it is made -->

<!-- jquery functions -->
<!--JQ Tabs-->
<script>
  $(function() {
    $( "#tabs" ).tabs();
  });


</script>


<!-- fancybox -->
<script>
   $(document).ready(function() {
	   $('.fancybox').fancybox({
		    type        : 'image',
	    	openEffect  : 'none',
	    	closeEffect : 'none'
		});
    });
    $(document).ready(function() {
		$('.fancybox2').fancybox();
    });
    
   
    
</script>
<!-- end fancybox-->
</head>

<body onload="initialize()">
<div id="wrapper">
<!-- Begin banner -->
<?php include('common/php/seaweed_header.php'); ?>
<!-- End banner -->

<!-- Beginning of horizontal menu -->
<?php include('common/php/globalnav.php'); ?> 
<!-- End of horizontal menu -->

<!-- Top matter -->
<table align="center" width="100%"><tr><td>
<?php echo '<a href="eflora_display.php?tid='.$previousTaxonID.'"><IMG SRC="http://ucjeps.berkeley.edu/icons/left.gif" BORDER=2 ALT="Previous taxon"></a>'?>
</td>
<td width=40%>
<span class="pageName"><a href="http://ucjeps.berkeley.edu/seaweedflora/">California Seaweeds eFlora</a></span>
</td>
<td width=55%>
<!--eFlora index-->

<?php include('common/php/eflora_index_bar.php'); ?>
<!--end eFlora index-->
</td>
<td align="right">
<?php echo '<a href="eflora_display.php?tid='.$nextTaxonID.'"><IMG SRC="http://ucjeps.berkeley.edu/icons/right.gif" BORDER=2 ALT="Next taxon"></a>'?>
</td></tr>
</table>


<?php include('common/php/seaweed_menu.php'); ?>
<!-- end top matter-->

<div id="content">
<div id="content-left">


	<?php
	$Name4INA = str_replace(" ", "+", $ScientificName);
	echo "<h2><a href='http://ucjeps.berkeley.edu/cgi-bin/search_ina.pl?lookfor=".$Name4INA."'><i><b>".$ScientificName."</b></i></h2> <b>".$TaxonAuthor."</b></a>";
	if (isset($AudioFile)){ // AudioFile is included as part of the heading
		echo '<audio controls>';
		echo '<source src="http://ucjeps.berkeley.edu/seaweedflora/audio/'.$AudioFile.'" type="audio/ogg">';
		echo 'Your browser does not support the audio element.';
		echo '</audio>';
	}
	echo "</h2>";
	if ($KeyCharacteristics){ //print KeyCharacteristics block, if any
		$KeyCharacteristics = str_replace("|",'</li><li>',$KeyCharacteristics); //process lists by turning the pipe delimiter into list tags
		echo "<h3>Key Characteristics</h3>";
		echo '<ul><li>'.$KeyCharacteristics.'</li></ul>';
	}
	
	//load image gallery, if any
	if ($row = $photo_results->fetchArray()) { //this if statement loads the first image, which is not hidden
		echo '<h3>Image Gallery (click for more)</h3>';
		$photo_name = $row['FileName'];
		$photo_URL = $row['MediaURL'];
		$photo_creator = $row['Creator'];
		$thumb_URL = $row['ThumbURL'];
		$photo_locality = $row['Locality'];
		if (isset($photo_locality)){
			$photo_locality = $photo_locality.': ';
		}		
#		echo '<a href="images/'.$photo_name.'" class="fancybox" rel="gallery"><img width=210px src="images/'.$photo_name.'" /></a>';
		echo '<a href="'.$photo_URL.'" class="fancybox" rel="gallery" title="'.$photo_locality.'Photo by '.$photo_creator.', &copy; UC Regents"><img width=210px src="'.$thumb_URL.'" /></a>';
	}
	echo '<div class="hidden">';
	while ($row = $photo_results->fetchArray()) { //this while statement loops through the remaining images, which are hidden
		$photo_name = $row['FileName'];
		$photo_URL = $row['MediaURL'];
		$photo_creator = $row['Creator'];
		$thumb_URL = $row['ThumbURL'];
		$photo_locality = $row['Locality'];
		if (isset($photo_locality)){
			$photo_locality = $photo_locality.': ';
		}	
#		echo '<a href="images/'.$photo_name.'" class="fancybox" rel="gallery"><img width=210px src="images/'.$photo_name.'" /></a>';
		echo '<a href="'.$photo_URL.'" class="fancybox" rel="gallery" title="'.$photo_locality.'Photo by '.$photo_creator.', &copy; UC Regents"><img width=210px src="'.$thumb_URL.'" /></a>';
	}
	echo '</div>';
	?>
	
	<h3>Database links</h3>
	<ul>
		<?php $name4CSpace = str_replace(" ", '%20', $ScientificName);
		echo '<li><a href="https://webapps.cspace.berkeley.edu/ucjeps/publicsearch/publicsearch/?country=USA&determination='.$name4CSpace.'&displayType=grid&maxresults=10000">UC Specimens</a></li>';
		$name4Smithso = str_replace(" ", '+', $ScientificName);
		echo '<li><a href="http://collections.si.edu/search/results.htm?q='.$name4Smithso.'&tag.cstype=all">Smithsonian Institution</a></li>';
		echo '<li><a href="http://macroalgae.org/">Macroalgal Herbarium Portal</a></li>';
		?>
	</ul>
</div>


<div id="content-main">
	<div id="tabs">
		  <ul>
		    <li><a href="#tabs-1">Distribution</a></li>
		    <li><a href="#tabs-2">Notes</a></li>
		    <li><a href="#tabs-3">Illustrations</a></li>
		    <li><a href="#tabs-4">Description</a></li>
		    <!-- <li><a href="#tabs-5">Similar Species</a></li> --> <!-- SIMILAR SPECIES not in CALIFORNIA_SEAWEEDS.txt yet -->
		  </ul>
		<div id="tabs-1" class="tab-content"> <!-- map tab -->
			<p><div id="map_canvas"></div></p>
			<p>UC specimens and range limits for <i><?php echo "$ScientificName"; ?></i></p>
			<UL>
<li>Blue markers: specimen records
<li>Yellow marker: type locality, if present
<li>Red markers: endpoints of range from literature
</UL>
			<?php 
				$name_for_pnw_link = str_replace(" ", '&Species1=', $ScientificName);
    			echo '<p><a class="internal" href="http://www.pnwherbaria.org/data/results.php?DisplayAs=WebPage&ExcludeCultivated=Y&GroupBy=ungrouped&SortBy=Year&SortOrder=DESC&SearchAllHerbaria=Y&QueryCount=1&IncludeSynonyms1=Y&Genus1='.$name_for_pnw_link.'&Zoom=4&Lat=55&Lng=-135&PolygonCount=0">View map from the Consortium of Pacific Northwest Herbaria</a>';
				if($DistributionNotes){
					$DistributionNotes = str_replace("|",'</p><p>',$DistributionNotes);
					echo '<p><b>Notes: </b>'.$DistributionNotes.'</p>';
				}
			?>
		</div>
		<div id="tabs-2" class="tab-content"> <!-- notes tab -->
			<?php
				if ($Status) {
					$Status = str_replace("|",'</p><p>',$Status);
					echo '<p><b>Status:</b> '.$Status.'</p>'; 
				}
				if ($Habitat) {
					$Habitat = str_replace("|",'</p><p>',$Habitat);
					echo '<p><b>Habitat:</b> '.$Habitat.'</p>';
				}
				if ($LifeHistory) {
					$LifeHistory = str_replace("|",'</p><p>',$LifeHistory);
					echo '<p><b>Life History:</b> '.$LifeHistory.'</p>';
				}
				if ($Conservation) {
					$Conservation = str_replace("|",'</p><p>',$Conservation);
					echo '<p><b>Conservation:</b> '.$Conservation.'</p>';
				}
				if ($Associates) {
					$Associates = str_replace("|",'</p><p>',$Associates);
					echo '<p><b>Associated Taxa:</b> '.$Associates.'</p>';
				}
				if ($Epiphytes) {
					$Epiphytes = str_replace("|",'</p><p>',$Epiphytes);
					echo '<p><b>Epiphytes:</b> '.$Epiphytes.'</p>';
				}
				if ($SimilarSpecies) {
					$SimilarSpecies = str_replace("|",'</p><p>',$SimilarSpecies);
					echo '<p><b>Similar Species:</b> '.$SimilarSpecies.'</p>';
				}
				echo '<p><b><a class="internal" href="http://www.ncbi.nlm.nih.gov/nuccore/?term='.$name4Smithso.'">Search Sequences in GenBank</a></b></p>'; // GENBANK LINK to be imported from Dick Moe's SW table
			?>
		</div>
		<div id="tabs-3" class="tab-content"> <!-- illustrations tab -->
			<!--<p>Illustrations are from <a href="http://ucjeps.berkeley.edu/guide/">DeCew's Guide to the Seaweeds of British Columbia, Washington, Oregon, and Northern California</a>, unless otherwise stated.</p>-->
			<?php
		    	while ($row = $illustration_results->fetchArray()) {
					$illu_name = $row['FileName'];
					$IsDecew = $row['IsDecew'];
					if ($IsDecew) {//Add image and citation if from Decew
					    echo '<img style="max-width:500px;" src="http://ucjeps.berkeley.edu/guide/'.$illu_name.'"><br>';
					    echo "<p>Illustration from <a href='http://ucjeps.berkeley.edu/guide/'>DeCew's Guide to the Seaweeds of British Columbia, Washington, Oregon, and Northern California</a></p><br>";				
					} else {
					//list other non-Decew  images
    					echo '<img style="max-width:500px;" src="/seaweedflora/images/'.$illu_name.'"><br>';
					} 
				
					//echo '<br>';
				}
			?>
		</div>
		<div id="tabs-4" class="tab-content"> <!-- MAC Tab -->
			<?php
				if ($MACDescription) {
					$MACDescription = str_replace("|",'</p><p>',$MACDescription);
					echo '<p>'.$MACDescription.'</p>';
					echo '<p>Excerpt from Abbott, I. A., & Hollenberg, G. J. (1976). <i><a href="http://www.sup.org/books/title/?id=2401">Marine algae of California</a></i>. Stanford University Press, Stanford, California. xii [xiii] + 827 pp., 701 figs.<p>';
				}
				if ($MACNotes) {
					$MACNotes = str_replace("|",'</p><p>',$MACNotes);
					echo '<p><b>Notes:</b> '.$MACNotes.'</p>';
				}
			?>
		</div>
		<!-- </div> id="tabs-5" class="tab-content"></div> --> <!-- similar species tab -->
	</div>	
</div>


<div id="content-right">
	<?php
		echo '<p><a href="http://www.algaebase.org/search/?species='.$name4CSpace.'"><b>Classification: Algaebase</b></a></p>';
		echo '<p><b>'.$NativeStatus.'</b></p>';
		if ($VerticalDistribution) { echo '<p><b>Vertical Distribution:</b> '.$VerticalDistribution.'</p>'; }
		if ($Frequency) { echo '<p><b>Frequency:</b> '.$Frequency.'</p>'; }
		if ($Substrate) { echo '<p><b>Substrate:</b> '.$Substrate.'</p>'; }
		if ($TypeLocality) { 
			$TypeLocality = str_replace("|", '</p><p>', $TypeLocality);
			echo '<p><b>Type locality:</b> '.$TypeLocality.'</p>'; 
		}
		// Phenology: Link to DeCew's guide
		
		//load image gallery, if any
		if ($row = $specimen_results->fetchArray()) { //this if statement loads the first image, which is not hidden
			echo '<h3>Specimen Gallery (click for more)</h3>';
			$specimen_name = $row['FileName'];
			$specimen_image_URL = $row['MediaURL'];
			$specimen_thumb_URL = $row['ThumbURL'];
			echo '<a href="'.$specimen_image_URL.'" class="fancybox" rel="gallery2" title="'.$specimen_name.'"><img width=134px src="'.$specimen_thumb_URL.'" /></a>';
		}
		echo '<div class="hidden">';
		while ($row = $specimen_results->fetchArray()) { //this while statement loops through the remaining images, which are hidden
			$specimen_name = $row['FileName'];
			$specimen_image_URL = $row['MediaURL'];
			echo '<a href="'.$specimen_image_URL.'" class="fancybox" rel="gallery2" title="'.$specimen_name.'"><img width=134px src="'.$specimen_thumb_URL.'" /></a>';
		}
		echo '</div>';
	?>
</div>
</div>
<!--<div id="footer">
</div>-->
<div id="bottom"><?php include('common/php/treatment_footer.php'); ?></div>
</div>
</body></html>
