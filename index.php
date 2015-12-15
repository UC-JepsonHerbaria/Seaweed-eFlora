<html xmlns="http://www.w3.org/1999/xhtml">

<head><title>University Herbarium: Postelsia palmaeformis</title> 
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
<link href="http://ucjeps.berkeley.edu/common/styles/dropdowns.css" rel="stylesheet" type="text/css" />
<link href="common/css/seaweed.css" rel="stylesheet" type="text/css" />

<!--JQueryUI stuff-->
<meta charset="utf-8">
<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
  <script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>  
<!--JQueryUI-->
<!-- fancybox files -->
	<link rel="stylesheet" href="fancybox/source/jquery.fancybox.css" type="text/css" media="screen" />
	<script type="text/javascript" src="fancybox/source/jquery.fancybox.pack.js"></script>
<!-- end fancybox -->


<!-- google maps -->
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
function initialize() {
  var myOptions = {
    mapTypeId: google.maps.MapTypeId.ROADMAP
  }

  var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

  var pmlLayer = new google.maps.KmlLayer("http://herbaria4.herb.berkeley.edu/SW_coords/Postelsia_palmaeformis.kml?66054", { });
  pmlLayer.setMap(map);

}

function JumpToIt(list) {
    var newPage = list.options[list.selectedIndex].value
    if (newPage != "None") {
        location=newPage
    }
}
</script>


<!--JQ Tabs-->
<script>
  $(function() {
    $( "#tabs" ).tabs();
  });
  </script>
<!-- end JQ tabs -->

<!-- JQ accordion -->
<!--  <script>
  $(function() {
    $( "#accordion" ).accordion()({autoHeight: true});
  });
  </script> -->
<!-- end accordion -->

<!-- fancybox -->
    <script>
        $(document).ready(function() {
   $(".fancybox").fancybox({
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
<?php include('common/php/globanav.php'); ?>
<!-- End of horizontal menu -->
<div id="content">
<div id="content-left">
		<h2><a href="http://ucjeps.berkeley.edu/cgi-bin/porp_cgi.pl?139507"><i>Postelsia palmaeformis</i> Ruprecht</a></h2>
		<img width="210px" src="images/postelsia1.jpg">
		<h3>Key Characteristics</h3>
		<ul>
			<li>Sporophyte with single, hollow, cylindrical stipe topped by a nodding cluster of narrow, grooved blades. </li>
			<li>Grows in upper-mid intertidal zone, exposed to heavy surf</li>
			<li>Annual</li>
		</ul>

	<h3>Image Gallery (click for more)</h3>
	<a href="images/Postelsia3.jpg" class="fancybox" rel="gallery" title="Postelsia3 caption"><img width=210px src="images/Postelsia3.jpg" /></a>
	<div class="hidden"> <!-- other images are hidden, but are still in the "gallery" slide show-->
		<a href="images/Postelsia4.jpg" class="fancybox" rel="gallery" title="Postelsia4 caption"><img height=200px src="images/Postelsia4.jpg" /></a>
		<a href="images/Postelsia5.jpg" class="fancybox" rel="gallery" title="Postelsia5 caption"><img height=200px src="images/Postelsia5.jpg" /></a>
	</div>

	<h3>Database links</h3>
	<ul>
		<li><a href="https://webapps.cspace.berkeley.edu/ucjeps/publicsearch/publicsearch/?country=USA&determination=Postelsia%20palmaeformis&displayType=grid&maxresults=10000">UC Specimens</a></li>
		<li><a href="http://collections.si.edu/search/results.htm?q=Postelsia+palmaeformis&tag.cstype=all">Smithsonian Institution</a></li>
		<li><a href="http://macroalgae.org/">Macroalgal Herbarium Portal</a></li>
	</ul>

</div>

	<div id="content-main">
		<div id="tabs">
		  <ul>
		    <li><a href="#tabs-1">Distribution</a></li>
		    <li><a href="#tabs-2">Notes</a></li>
		    <li><a href="#tabs-3">Illustrations</a></li>
		    <li><a href="#tabs-4">Description</a></li>
		    <li><a href="#tabs-5">Similar Species</a></li>
	<!--	    <li><a href="#tabs-6">Phenology</a></li> -->
		  </ul>
		  <div id="tabs-1">
		  
			<p><div id="map_canvas"></div></p>
		  <p>UC specimens and range limits for Postelsia palmaeformis</p>
		  <p><a class="internal" href="http://www.pnwherbaria.org/data/results.php?DisplayAs=WebPage&ExcludeCultivated=Y&GroupBy=ungrouped&SortBy=Year&SortOrder=DESC&SearchAllHerbaria=Y&QueryCount=1&IncludeSynonyms1=Y&Genus1=Postelsia&Species1=palmaeformis&Zoom=4&Lat=55&Lng=-135&PolygonCount=0">Map from Pacific Northwest Herbaria</a>
		  <p><b>Notes: </b> Before the 1983-84 El Niño, the southern limit of Postelsia was at the headland north of Diablo Cove, San Luis Obisbo Co., California (35.213018,-120.861111). The population was lost and never recovered, probably because the Diablo Canyon Nuclear Power Plant began discharging warm water in 1985. Currently, the southernmost population is on the first headland south of Point Buchon (35.251192,-120.897245) (S. Kimura, pers. comm.).  The published northern limit is Hope Island, British Columbia, Canada.</p>
		  </div>
		  <div id="tabs-2">
		    <p><b>Status:</b> Field identification is unambiguous.</p>
		    <p><b>Habitat:</b> Restricted to wave-exposed, intertidal zone rocky shores, often, but not always, associated with Mytilus californianus Conrad, 1837, the California mussel (Dayton 1973; Paine 1979, 1988). Physiological stresses associated with emersion limit the upper distribution of Postelsia through declines in growth, survivorship, and reproductive output; both physiological and ecological performance, expressed as reductions in growth and reproductive output, but not survivorship, decline at the lower edges of its vertical range (Nielsen et al. 2006). </p>
		    <p><b>Life History:</b> Alternation of heteromorphic phases, with a macroscopic, annual sporophyte and dioecious microscopic gametophytes (Myers 1925). </p>
		    <p>Dispersal distances are short, resulting in closely related individuals in local populations (Coyer et al. 1997). In fact, Postelsia gametophytes frequently self-fertilize, with little cost in fitness (Barner et al. 2011). </p>
		    <p><b>Conservation:</b> Postesia is a protected species in California, and its harvest is regulated by the California Department of Fish and Wildlife.  Closed to non-commercial harvest. Commercial kelp harvest: <a href="http://www.leginfo.ca.gov/cgi-bin/displaycode?section=fgc&group=06001-07000&file=6650-6657">http://www.leginfo.ca.gov/cgi-bin/displaycode? section=fgc&group=06001-07000&file=6650-6657</a></p>
		    <p>A precautionary approach to management of the commercial Postelsia harvest should: (1) mandate frond trimming rather than taking the entire individual, (2) limit collection to once a year and (3) close the commercial season before the onset of reproduction (Thompson et al. 2010).</p>
		    <p><b>Associated Taxa:</b> California mussel (Mytilus californianus), goose neck barnacles (Pollicipes polymerus), Endocladia muricata, corallines (e.g., Corallina vancouveriensis, Bossiella plumosa, B. frondifera)</p>
		    <p><b>Epiphytes:</b> Pylaiella gardneri, Ectocarpus commensalis, Pyropia gardneri</p>
		    <p><b><a class="internal" href="http://www.ncbi.nlm.nih.gov/nuccore/?term=%22Postelsia+palmaeformis%22">Sequences on GenBank</a></b> </p>
		  </div>
		  <div id="tabs-3">
		  <img width="300px" src="images/Postelsia_Smith_1944.jpg">
		  <p>Caption: Marine Algae of California</p>
		  <img width="300px" src="images/Postelsia2.jpg">
		  <p>Caption?</p>
		  <img width="300px" src="http://ucjeps.berkeley.edu/guide/P-81.gif">
		  <p>Caption: From Decew's Guide
		  
		    <p><b></b></p>
		  </div>
		  <div id="tabs-4">
		    <img width=525px src="images/MAC_Postelsia.jpg">
		    <p>Excerpt from Abbott, I. A., & Hollenberg, G. J. (1976). <i><a href="http://www.sup.org/books/title/?id=2401">Marine algae of California</a></i>. Stanford University Press, Stanford, California. xii [xiii] + 827 pp., 701 figs.<p>
		    <p><b>Notes:</b> The southern limit on the first headland south of Point Buchon (35.251192,-120.897245)</p>
		  </div>
		  <div id="tabs-5">
		    <p><b></b>No similar species</p>
		  </div>
	<!--	  <div id="tabs-6">
		    <img src="images/Postesia_phenology.tiff">
		  </div> -->
		</div>
		
		
	</div>
		<div id="content-right">
		
			<p><a href="http://www.algaebase.org/search/?species=Postelsia%20palmaeformis"><b>Classification & Synonyms: Algaebase</b></a></p>
			<p><b>Native</b></p>
			<p><b>Vertical Distribution:</b> Upper-mid intertidal zone, exposed to heavy surf</p>
			<p><b>Frequency:</b> Patchy Populations of a few to 100s of individuals</p>
			<p><b>Substrate:</b> Grows on rock or mussels (Mytilus californianus)</p>
			<p><b>Type locality (first collection site):</b> <a href="https://maps.google.com/maps/@38.30529,-123.05117,8z/data=!4m2!3m1!1s0x0:0x0">Bodega Bay, CA</a>
			<p><b><a href="Postesia_phenology.tiff">Phenology</a></b></p>

	<h3>Specimen Gallery (click for more)</h3>
<!--	<a href="https://webapps.cspace.berkeley.edu/ucjeps/imageserver/blobs/49907a78-fba0-49dd-84dc/derivatives/Medium/content" class="fancybox" rel="gallery2" title="UC981493"><img width=134px src="https://webapps.cspace.berkeley.edu/ucjeps/imageserver/blobs/49907a78-fba0-49dd-84dc/derivatives/Medium/content" /></a> -->
	<a href="images/content-1.jpeg" class="fancybox" rel="gallery2" title="UC981493"><img width=134px src="images/content-1.jpeg" /></a>
	<div class="hidden"> <!-- other images are hidden, but are still in the "gallery" slide show-->
<!--	<a href="https://webapps.cspace.berkeley.edu/ucjeps/imageserver/blobs/f33d928d-7efd-4f42-8cd4/derivatives/Medium/content" class="fancybox" rel="gallery2" title="UC1718108"><img width=134px src="https://webapps.cspace.berkeley.edu/ucjeps/imageserver/blobs/f33d928d-7efd-4f42-8cd4/derivatives/Medium/content" /></a>
	<a href="https://webapps.cspace.berkeley.edu/ucjeps/imageserver/blobs/44b15b2e-18e0-4b6c-939e/derivatives/Medium/content" class="fancybox" rel="gallery2" title="UC1934361"><img width=134px src="https://webapps.cspace.berkeley.edu/ucjeps/imageserver/blobs/44b15b2e-18e0-4b6c-939e/derivatives/OriginalJpeg/content" /></a> -->
	<a href="images/content-2.jpeg" class="fancybox" rel="gallery2" title="UC1718108"><img width=134px src="images/content-2.jpeg" /></a>
	<a href="images/content-3.jpeg" class="fancybox" rel="gallery2" title="UC1934361"><img width=134px src="images/content-3.jpeg" /></a>
	</div>


		</div>		
</div>
<div id="footer">

</div>
	<div id="bottom"></div>
</div>
