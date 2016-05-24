
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<head>
<title>California Seaweed eFlora: Front Page</title> 
<meta name="keywords">
<link href="http://ucjeps.berkeley.edu/common/styles/dropdowns.css" rel="stylesheet" type="text/css">

   <meta charset='utf-8'>
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="common/css/globalmenu.css">
   <script src="common/js/globalmenu.js"></script>
   <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>

</head>

<!-- jQuery UI stuff-->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="http://ucjeps.berkeley.edu/common/styles/sw-custom-jquery-ui.css">

 <script>
  $(function() {
    $( "#accordion" ).accordion({
      heightStyle: "fill"
    });
  });
  </script>

<style>
#accordion .ui-accordion-content {
    max-height: 250px;
}
</style>
<!-- end jQuery -->

<!-- JS popups -->
<script>
function openwindowmap()
{
	window.open ("images/frontpage/california_coast.png", "California Coast Map", "resizable=1,scrollbars=1,toolbar=0,height=800,width=700")
}

function openwindowphylo()
{
	window.open ("pages/about_phylogeny.html", "About the algal phylogeny", "resizable=1,scrollbars=1,toolbar=0")
}
</script>
<!-- end JS-->


<body onLoad="initialize()">

<!-- Begin banner -->
<?php include($_SERVER['DOCUMENT_ROOT'].'/common/php/header_uc.php'); ?>  
<!-- End banner -->

<!-- Beginning of horizontal menu -->
<?php include($_SERVER['DOCUMENT_ROOT'].'/common/php/globalnav.php'); ?>
<!-- End of horizontal menu -->

<table align="center" width="100%" border=0>
<tr>
<td align="center"><img height="120px" src="images/frontpage/sw1.png" alt=""></td>
<td align="center"><img height="120px" src="images/frontpage/sw2.png" alt="Arctostaphylos ohloneana, Neal Kramer"></td>
<td align="center"><center><span class="pageName"><font size="5">California Seaweeds eFlora</font></span></center></td>
<td align="center"><img height="120px" src="images/frontpage/sw3.png"></td>
<td align="center"><img height="120px" src="images/frontpage/sw4.png"></td>
</tr>
<tr>
<td colspan=5>
<!-- empty row we may or may not use -->
</td>
</tr>
</table>



<!-- Being seaweed link bar -->
<?php include($_SERVER['DOCUMENT_ROOT'].'/seaweedflora/common/php/seaweed_menu.php'); ?>
<!-- end link bar-->

<br>
<span class="bodyText">The California Seaweeds eFlora includes a current list of seaweeds (multicellular red, green and brown algae) reported to occur in California, including current names, additions to the flora since Abbott & Hollenberg (1976), and non-native species.  For ~300 species that can be identified without a microscope, a non-technical portal provides photographs, interactive visual keys, and information about identification, nomenclature, distribution, and life history. </span>
<br><br>

<table align="center" width="100%">
<tbody><tr>
<td width="50%" height="" valign="top">
<form action="search_eflora.php" method="POST">
<b>Scientific Name<br></b>		<input type="text" id="query_text" name="query"></input>
		<input type="submit" value="Current and former names for California seaweeds" />

</form>

<td width="25%" align="center" valign="top">
<form action="pages/non_native_list.html">
        <input type="submit" value="Non-native species">
        
</form>
</td>
<td width="25%" align="center" valign="top">
<form action="pages/new_species.html">
        <input type="submit" value="Species reported since 1976">
        
</form>


</td>
</tr>

<tr>
<td>
<?php include('common/php/eflora_index_bar.php'); ?>  
</td>
<td colspan="2" align="center">
<FORM>Voucher-based species lists: <SELECT WIDTH=20 onChange="JumpToIt(this)"><OPTION VALUE="None">Select a county
<OPTION VALUE="#">Del Norte
<OPTION VALUE="#">Humboldt
<OPTION VALUE="#">Mendocino
<OPTION VALUE="#">Sonoma
<OPTION VALUE="#">Marin
<OPTION VALUE="#">San Francisco
<OPTION VALUE="#">San Mateo
<OPTION VALUE="#">Santa Cruz
<OPTION VALUE="#">Monterey
<OPTION VALUE="#">San Luis Obispo
<OPTION VALUE="#">Santa Barbara
<OPTION VALUE="#">Ventura
<OPTION VALUE="#">Los Angeles
<OPTION VALUE="#">Orange
<OPTION VALUE="#">San Diego
</SELECT>
<br><a href="javascript: openwindowmap()">map of coastal counties</a></FORM>

</td>
</tr>
<tr><td colspan="3">&nbsp;</td></tr>
<tr>
<td valign="top" width="50%">
<h4 class="pageMajorHeading">Evolutionary history of the three great algal phyla</h4>
<img height="500px" src="images/frontpage/phylogeny.png"><br>
<a href="javascript: openwindowphylo()">more about the algae phylogeny</a>

</td>

<!-- <td></td> -->
<td colspan="2" valign="top">
<h4 class="pageMajorHeading">About the Project</h4> 
  <div id="accordion">
    <h3>Goals for this website</h3>
    <div>
    <p>Here I will present a consensus of our knowledge of California seaweeds.  I have not attempted to solve taxonomic and nomenclatural problems for the nearly 800 species reported for California.  Rather, I want to point out what we know &mdash; and what we need to find out &mdash; to understand the California seaweeds and their relationships in time and space, much of which can only be understood as historical and cultural, and much of which will change with new information.</p>
<p>My goal is to present information on the seaweeds of California, based on the authority of publications from around the world and herbarium specimens in UC and other herbaria.  Biogeography provides invaluable clues to species' identities.  The ranges of seaweeds included here are based on herbarium collections rather than published endpoints; thus, the basis for this critical information can be checked or tested by looking at the scanned specimen and label information, or by visiting herbaria to ground truth a geographic assertion.  Maps derived from UC's database of 50,000 California specimens are provided, as are links to other seaweed databases.  I hope this approach encourages people who discover range extensions to contribute vouchers to UC or other herbaria.</p>
<p>This eFlora will provide species pages for the 250 more or less conspicuous, common species in California, accessed from intuitive, visual keys.  Rare and microscopic species will be treated in a second wave.</p>
<p>I draw on my own experience and on what I've learned from my many teachers and colleagues, to offer an appreciation of these fabulous marine creatures for everyone &mdash; casual observer, coastal ecologist or manager, dedicated professional &mdash; whose life or work intersects with California's coast.  </p>
    </div>

    <h3>How to use this eFlora</h3>
    <div>
<p>There are many resources available to you in the California Seaweed eFlora.
<ol>
	<li>If you use <i>Marine Algae of California</i> (Abbott & Hollenberg 1976) and need to know a current name, click on Current Names for a list of seaweeds currently reported in California, including the names in <i>Marine Algae of California</i> (Abbott & Hollenberg 1976).</li>
	<li>Search for a species by typing in the search box.  This list contains current names and both nomenclatural and taxonomic synonyms.  As you type, a list of names will appear.  When you see the name you seek, click on it.  It will take you to a species page (if available) that is filed under the current name.</li>
	<li>Search for a genus name by clicking on its first letter (a-z).  This will take you table of names that begin with that letter, with both current names and synonyms identified.</li>
	<li>The simple interactive visual keys to ~300 species are multi-entry.  Check the boxes corresponding to the information you have (county, habitat, features of the seaweed) and look at the photographs.  If you see a photo that looks like your specimen, you can go to its species page to confirm your identification.</li>
	<li>Species pages provide notes on the status of the species (recent studies, reliability of identification, need for further studies). They contain key field characteristics, photographs, images of specimens, distribution maps, species descriptions, habitat information, and links to specimen databases and other websites (e.g., <i>Index Nominum Algarum</i>, AlgaeBase, GenBank, and DeCew's Guide).  Click on the species name for the original publication and collector, basionym, and nomenclatural synonyms in the <i>INA</i>.  The photos and images of specimens are high resolution; right click or control click to place then in another tab for close inspection.</li>
</ol>
Explore and enjoy!
</p>
<p><b>Native, Non-Native, and Cryptogenic</b></p>
<p>NATIVE species evolved on the coast of North America; NON-NATIVE species were introduced to California, by aquaculture, shipping or recreational boating; CRYPTOGENIC species have "hidden origins" - they are broadly distributed and their place of origin is unknown.</p>
    </div>

    <h3>The Setting: Coastal California</h3>
    <div>
<p>The magnificent diversity and abundance of seaweed populations along the coast of California reflect the dramatic sweep of this rich coastal environment, embracing rocky shores and reefs, sandy beaches, and offshore islands.  Only the floras of Australia and Japan (both with significantly longer coastlines) are more diverse.  </p>
<p>California straddles the junction of two great biogeographic provinces: the cold temperate Oregonian Province roughly to the north of the northern Channel Islands and Point Conception, and the warmer temperate to subtropical Panamanian Province to the south.  </p>
<p>The cold California Current, a branch of the Kuroshio Current, runs south along the coast with temperatures from ~9-16&deg; C , shifting offshore near Point Conception, where the coastline veers abruptly east.  In the southern California Bight, the warm Davidson Current flows northward in the late summer and autumn, bringing warmer temperatures of ~20&deg; C, but is depressed during spring and early summer months when upwelling brings cold nutrient-rich water to the surface at headlands throughout California and Baja California, Mexico. The iconic kelps of California, those huge, brown, forest-forming seaweeds so unique to our coast, could not survive as far south without these upwelled subsidies.  The eight California Channel Islands, in the southern California Bight, are particularly interesting because they are relatively free of coastal development and lie in this mixture of warm and cold currents. </p>
	</div>
    <h3>History and Herbaria: Our knowledge of California Seaweeds</h3>
    <div>
<p>Our scientific knowledge of California seaweeds began with the expeditions of Alessandro Malaspina (Spain) and George Vancouver (Britain) in the 1790s, grew through the long-distance taxonomic efforts of European (e.g., D. Turner, C.A. Agardh, J.G. Agardh, W.H. Harvey, F.J. Ruprecht) and American botanists on the east coast (e.g., A. Gray, W.G. Farlow), and came of age, at the beginning of the 20th century, with the collected works of W.A. Setchell and N.L. Gardner at the University of California at Berkeley (UC).  </p>
<p>The heart of this story of discovery lies in the herbaria of the world &mdash; museums that preserve plant, fungal and algal specimens for botanical research.  Herbaria grew out of the "cabinets of curiosities" of earlier centuries, where collectors assembled enduring, beautiful and compelling specimens of natural history &mdash; rocks, plants, shells and bones.  </p>
<p>Today, the world's herbaria house millions of plant specimens, representing the history of our environment and the collective efforts of botanists (and students of botany) to understand and order the plant universe.  Particularly valuable are the type specimens.  Every time a plant is described, a preserved specimen is designated as the exemplar of that species, and the name is forever associated with that specimen.</p>
<p>But because a single specimen cannot encompass the spectrum of expression of that species throughout its life and its geographic range, additional specimens are essential for understanding a species.  Herbaria are treasure houses of information, not just about plants, but about people, too &mdash; when and where they explored, what they found, and with whom they collaborated.  </p>
    </div>
    <h3>Benchmarks for the California Flora</h3>
    <div>
<p>Marine Algae of California (Abbott & Hollenberg 1976) based on and expanding the range of G.M. Smith's Marine Algae of the Monterey Peninsula (1944), was the first complete seaweed flora for California. MAC, a collaboration between two great students of the flora, Isabella A. Abbott and George J. Hollenberg, with contributions by others, including William Johansen, Susan Loiseaux and Elise Wollaston, drew upon the previous works of W.A. Setchell, N.L. Gardner, E.Y. Dawson and R.F. Scagel, among many others.  </p>
<p>MAC lists 669 species of red, brown and green seaweeds, of which 15% are endemic to California (found nowhere else), 45% are restricted to the Pacific coast of North America, 20% are restricted to southern California and adjacent Baja California and 20% are so-called "cosmopolitan" species, with global distributions, that were described from other parts of the world. </p>
<p>Since the publication of this landmark book, we've added more than 100 species to California's seaweed flora.  ~Twenty-three are non-native.</p>
<p>T.C. DeCew and P.C. Silva assembled information about species from Washington, Oregon and northern California, with special reference to phenology (patterns of reproduction) as a function of latitudinal range.  Their treatments of green and brown algae are online, while their studies of red algae are part of the archives at UC.  For this website, I have drawn on the field and nomenclatural wisdom embodied in these treatments and now present their illustrations of red algae for the first time.</p>
    </div>
    <h3>Biogeography and Species Ranges</h3>
    <div>
<p>Because the native ranges of many of the "cosmopolitan" species in California are unknown, they are known as "cryptogenic", with hidden origins. Many may be ancient introductions to California. The ships that brought early explorers to our coast (and eager gold miners in the 1840s) were wooden reefs supporting organisms from many ports visited over multi-year voyages, and were probably significant sources of introductions, long before scientists established a baseline for what is indigenous. Organisms from our coast have been introduced to other oceans by the same mechanism. </p>
<p>That said, there is evidence that we share genera and species with the western Pacific, especially Japan and Korea, and others with the Atlantic Ocean. At the last glacial maximum, when ice reached as far south as Cape Mendocino, ocean temperature, sea levels and current patterns were very different than they are today, and the Arctic Ocean has opened and closed repeatedly, allowing species to "travel" along the boreal "north coast".  The geographic distributions of our seaweed species through time are clues to where they evolved, how they have dispersed (naturally or via human intervention) and whether their ranges are currently changing as ocean temperatures, sea levels and current patterns continue to shift.  </p>
    </div>
    <h3>New Frontiers in Seaweed Taxonomy</h3>
    <div>
<p>Seaweed systematists are critically revisiting nomenclatural history, species identity and geographic ranges.  Trends and paradigms in science have determined our interpretations of what we see &mdash; and these, like climate, shift.  We began in an age of "splitting" during which diversity was described in all its expressions.  Later, during the exciting explosion of information during the 1960s and 1970s, we entered a time of "lumping", when we realized that individuals could look different due to ecological interactions or life history phase, but were in fact the same species.  This trend dominates MAC.</p>
<p>The well-known propensity of seaweeds to change their form depending on the environment &mdash; temperature, wave action, interactions with herbivores &mdash; is one of the reasons that it is difficult to distinguish species identity and relationships from form alone.  Advanced microscopy has allowed us to look deep into cells and to use minute, internal structures as characters to distinguish algal groups.  Growing spores in culture to trace the life history of a species &mdash; its mode of reproduction and transition between diploid and haploid phases &mdash; has been a powerful method for understanding how a species occupies time and space and has provided clues to relationships.  </p>
<p>But the revolution in species concepts ignited by using molecular methods to "read" DNA as a set of characters independent of form and environment has opened up a world of new information about species and uncovered "cryptic" species (those that look like others but are genetically distinct) that have never before been recognized.  Analyses of DNA sequences from type specimens, housed in the world's herbaria, permit the assignment of names and identities with explicit reference to nomenclatural history &mdash; and link contemporary collections to archival specimens.</p>
<p>Still, the delineation of species within our most common genera poses daunting challenges to phycologists, who continue to pursue field, culture and molecular studies to determine relationships at every taxonomic level.  </p>
    </div>

    
    <h3>Acknowledgments</h3>
    <div>
<p>I'm deeply grateful to my mentor Paul C. Silva and my many colleagues who joined and assisted me in the exploration of the California seaweed flora.  David Baxter, Tim Jones and Richard Moe provided expert IT assistance.  Margriet Weatherwax Downing and Ga Hun Boo helped immeasurably with the assembly of information.  I am indebted to photographers who contributed wonderful photos, especially my friends Jesse Alstatt, Dan Richards, Steve Lee, Steve Lonhart, Joe Wible, and Bob Case. The Tatman Foundation and Jack Engle gave me the opportunity to participate on more than 40 collecting cruises in the California Channel Islands. Alan Harvey, director of Stanford Press, allowed us to use text from <i>Marine Algae of California</i> (Abbott & Hollenberg 1976), my close and valued companion for the last 40 years.</p>
  </div>



</td>
</tr>

</tbody></table>
<hr>
<table>
<tr >
    <td valign="top">
    
</td>

<td valign="top" width="50%" style="padding-left:25px">

</td>


</tr>
</table>

<center><p class="bodyText"><a href="http://ucjeps.berkeley.edu/CPD/algal_research.html">I dedicate this work to Paul C. Silva, with love</a></p></center>

<!-- Begin footer --> 
<?php include('common/php/seaweed_footer.php'); ?>  
<!-- End footer -->
</body></html>

