<?php 

require_once('Connections/hawaiiDB.php');

session_start();



if (!function_exists("uniqueFields")) {
function uniqueFields($rs,$col) {
// rs: an sql recordset 
// col: the column in the recordset(containing rows of strings, sometimes with elements separated by commas)
// loops over all rows
// e.g. [optics] [environment] [environment, model]
// returns a sorted array of distinct elements
// e.g. [optics] [environment] [model]

//rewind
mysql_data_seek($rs, 0);
$row = mysql_fetch_assoc($rs);

// main loop
$elements=array();
do { 
  $pieces=explode(", ",$row[$col]);
  $elements = array_merge($elements,$pieces);
}
while ($row = mysql_fetch_assoc($rs));

// rewind
$rows = mysql_num_rows($rs);
if($rows > 0) {
   mysql_data_seek($rs, 0);
   $row = mysql_fetch_assoc($rs);
}

$unique=array_unique($elements);
sort($unique);
return $unique;

} //end function
} // end if function exists


// add POST variables to SESSION (only relevant if not the first time on this page)
foreach ($_POST as $key=>$val) {
    $_SESSION[$key]=$val;
}

// SESSION variables (only relevant if not the first time on this page)
extract($_SESSION);



// search section

mysql_select_db($database_hawaiiDB, $hawaiiDB);

$query_rsCruise = "SELECT Acronym FROM Cruises ORDER BY Date ASC";
$rsCruise = mysql_query($query_rsCruise, $hawaiiDB) or die(mysql_error());
$row_rsCruise = mysql_fetch_assoc($rsCruise);
$totalRows_rsCruise = mysql_num_rows($rsCruise);

$query_rsPI = "SELECT DISTINCT PI FROM ResearchGroups ORDER BY PI ASC";
$rsPI = mysql_query($query_rsPI, $hawaiiDB) or die(mysql_error());
$row_rsPI = mysql_fetch_assoc($rsPI);
$totalRows_rsPI = mysql_num_rows($rsPI);

$query_rsName = "SELECT DISTINCT Name FROM Researchers ORDER BY Name ASC";
$rsName = mysql_query($query_rsName, $hawaiiDB) or die(mysql_error());
$row_rsName = mysql_fetch_assoc($rsName);
$totalRows_rsName = mysql_num_rows($rsName);

$query_rsGroup = "SELECT DISTINCT Name FROM ResearchGroups ORDER BY Name ASC";
$rsGroup = mysql_query($query_rsGroup, $hawaiiDB) or die(mysql_error());
$row_rsGroup = mysql_fetch_assoc($rsGroup);
$totalRows_rsGroup = mysql_num_rows($rsGroup);

$query_rsData_Type = "SELECT DISTINCT Data_Type FROM Data where Data_Type IS NOT NULL ORDER BY Data_Type ASC";
$rsData_Type = mysql_query($query_rsData_Type, $hawaiiDB) or die(mysql_error());
$row_rsData_Type = mysql_fetch_assoc($rsData_Type);
$totalRows_rsData_Type = mysql_num_rows($rsData_Type);

$query_rsInstrument = "SELECT DISTINCT Instrument FROM Data where Instrument IS NOT NULL ORDER BY Instrument ASC";
$rsInstrument = mysql_query($query_rsInstrument, $hawaiiDB) or die(mysql_error());
$row_rsInstrument = mysql_fetch_assoc($rsInstrument);
$totalRows_rsInstrument = mysql_num_rows($rsInstrument);

$rsShortName = mysql_query("SELECT DISTINCT Dataset FROM Data where Dataset IS NOT NULL ORDER BY Dataset ASC", $hawaiiDB) or die(mysql_error());
$row_rsShortName = mysql_fetch_assoc($rsShortName);

$query_rsFunding = "SELECT DISTINCT(CONCAT_WS(' ',Agency,Award_Number)) as Award FROM Funding where Agency IS NOT NULL ORDER BY Agency ASC";
$rsFunding = mysql_query($query_rsFunding, $hawaiiDB) or die(mysql_error());
$row_rsFunding = mysql_fetch_assoc($rsFunding);
$totalRows_rsFunding = mysql_num_rows($rsFunding);


if (!isset($showmore1)) {$showmore1 = "n";}
if (isset($_POST['showmore1b_x'])) {$showmore1 = "y";}
if (isset($_POST['hidemore1b_x'])) {$showmore1 = "n";}
if (!isset($showmore2)) {$showmore2 = "n";}
if (isset($_POST['showmore2b_x'])) {$showmore2 = "y";}
if (isset($_POST['hidemore2b_x'])) {$showmore2 = "n";}
if (!isset($showmore3)) {$showmore3 = "n";}
if (isset($_POST['showmore3b_x'])) {$showmore3 = "y";}
if (isset($_POST['hidemore3b_x'])) {$showmore3 = "n";}
if (isset($_POST['hidemore2b_x'])) {$showmore3 = "n";}
if (!isset($tabular)) {$tabular = "n";}
if (isset($_POST['tabularyes_x'])) {$tabular = "y";}
if (isset($_POST['tabularno_x'])) {$tabular = "n";}



$page=1; 
if (isset($_POST['Next1']) | isset($_POST['Back3']) | isset($_POST['tabularyes_x']) | isset($_POST['tabularno_x'])) { $page=2; }
if (isset($_POST['Next2'])) { $page=3; }

?>

<script type="text/javascript">
function doScroll()
{
  if (window.name) window.scrollTo(0,window.name);
}
function myunload()
{
  if (navigator.appName == "Microsoft Internet Explorer")
    {
        window.name=document.body.scrollTop;
    }
    else
    {
        window.name=window.pageYOffset;
    }
}
window.onload=doScroll;
window.onunload=myunload;
</script>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="EN" xmlns:m="http://schemas.microsoft.com/office/2004/12/omml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<meta name="htdig-keywords" content="Center for Microbial Oceanography: Research and Education, C-MORE, CMORE, c-more, cmore, University of Hawaii, School of Ocean and Earth Schience and Technology, SOEST, Hawaii, 
archaea, allelopathic compounds, autecology, bacteria, biogeochemistry, bioinformatics, carbon cycle, climate change, C-N-P cycles, community genomics, 
cultural dimensions of the genomics, dissolved organic carbon, DNA arrays, ecological dynamics, ecosystem dynamics, energy flux, gene expression, genome, 
genomics, benomic inventories, food webs, food-web structure, instrumentation development, in-water sensors ligands, maritime anthropology; marine biology, marine microbiology,
marine microbe, metabolism, metagenomics, metabolic pathways, microbial consortia, microbial food webs micorbial genomics molecular probes, nitrogen cycling, nitrogen fixation, 
numerical models, oceanography, organic matter transformation, phenotypic repertoire, photoheterotrophs, photoheterotrophy, plankton metabolism, primary and secondary production, 
protein arrays, proteomics, proteorhodopsin, physiology; science, technology, and society; symbiosismicrobial activity, synecology" />

<meta name="Original Author" content="Jasmine Nahorniak" />
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<style type="text/css">
<!--
.style35 {color: #FF0000}
.style36 {color: #FFFFFF}
.style37 {color: #003366}
.style38 {font-size: 85%}
-->
</style>
<head>
<title>C-MORE | Data</title>
<link href="http://cmore.soest.hawaii.edu/css/cmore_style.css" rel="stylesheet" type="text/css" media="all" />
<link rel="shortcut icon" href="http://cmore.soest.hawaii.edu/images/favicon.ico" type="image/x-icon" />
</head>


<body onLoad="rotate()">

 <!-- Begin Container -->
 <div id="Container">
<a name="top"></a>

 <!-- Begin Banner -->
<div id="WideBanner">
<a href="/index.htm" tppabs="/index.htm"><img src="http://cmore.soest.hawaii.edu/images/cmore_banner3.jpg" alt="C-MORE Wide Banner." width="880" height="130" border="0" /></a><!-- Begin MainContent -->
</div>
 <!-- End Banner -->

<!--topmenu.ssi inserts the main left hand menu-->
<!-- Begin Menu -->
<div id="Menu">
<ul>
		<li><a title="C-MORE Member Login" href="/members/index.htm">Member Login</a></li>
		<li><a title="C-MORE Home Page" href="/index.htm">C-MORE Home</a></li>
		<li><a title="What is Microbial Oceanography?" href="/microbes.htm">What is Microbial Oceanography?</a></li> 
		<li><a title="Research" href="/research.htm">Research</a></li>
		<li><a title="Data" href="/data.htm">Data</a></li>
		<li><a title="Education" href="/education.htm">Education &amp; Outreach</a></li> 
		<li><a title="Events" href="/events.htm">Events</a></li> 
		<li><a title="More Information" href="/information.htm">More About C-MORE</a></li>
		<li><a title="The C-MORE Team" href="/team.htm">The C-MORE Team</a></li>
		<li><a title="News Releases" href="/press.htm">News Releases</a></li>
		<li><a title="Image Library" href="/photopost/">Image Library</a></li>
		<li><a title="Sponsoring Organizations" href="/sponsors.htm">Sponsors</a></li>
		<li><a title="Contact Information" href="/contact.htm">Contact Us</a></li>
	</ul>
<div align="center"><img src="http://cmore.soest.hawaii.edu/images/white_pixel.gif" alt="white spacer pixel." width="1" height="1" hspace="0" vspace="30" border="0" /></a></div>	

</div>
<!-- End Menu -->



<!-- Begin Breadcrumbs -->
<div id="Breadcrumbs">
<p><a href="/index.htm">Home</a> | <a class="youarehere" href="/data.htm">Data</a></p>
</div>
<!-- End Breadcrumbs -->
<div id="MainContent" style="background-image:url(http://cmore.soest.hawaii.edu/images/Scholin_bit_pieces_BG_600px.gif); background-position:top; background-repeat:no-repeat;">

<h1>Data Submission</h1>
<p class="style35">THIS PAGE IS UNDER DEVELOPMENT!<br /> 
  PLEASE DO NOT SUBMIT METADATA USING THIS FORM. <br />
  An announcement will be made to all CMORE members when this page is ready for use.<br />
- Jasmine Nahorniak 6/26/2015</p>
<p>Metadata are a very important component of any data set. Please fill out the form below with as much information as possible about the data set you are submitting. All fields are required unless otherwise noted<span class="style35"></span>. If a needed option doesn't appear in the drop-down lists, please enter them in the &quot;Other&quot; text field. Multiple &quot;Other&quot; entries should be separated with commas.</p>
<p>Metadata questions, corrections, additions, and updates may be directed by email to <a href="mailto:jasmine@coas.oregonstate.edu">Jasmine Nahorniak</a>.</p>
<p>Data files should be submitted via email or ftp to <a href="mailto:jasmine@coas.oregonstate.edu">Jasmine Nahorniak</a>. Data files may be in any format (ASCII, Matlab, Excel, binary, ...). Please include the date, GMT time, station, and lat/lon information in each file if relevant.</p>
<p> Data will be posted in two locations: the C-MORE data webpage and <a href="http://www.bco-dmo.org/project/2093">BCO-DMO</a>. 
  Data will be provided in their original format on the C-MORE data webpage. Prior to submission to BCO-DMO, tabular data will be reformatted according to BCO-DMO requirements (non-tabular data will be provided in their original format). Genomics data will be accessible by links to NCBI from both the C-MORE data webpage and BCO-DMO.</p>
<p>The <a href="data-policy.htm">C-MORE data policy</a> provides guidelines for the submission of C-MORE data.</p>
<p>&nbsp;</p>
<form action="data-submission.php" method="post" name="myform" target="_self" id="myform">


<?php if ($page==1) { ?>

<table width="100%" border="0" cellpadding="5" cellspacing="3" bgcolor="#6298A6">
  
  <tr>
    <td colspan="2" nowrap="nowrap" bgcolor="#003366" class="style36"> PERSONNEL</td>
  </tr>
  <tr>
    <td nowrap="nowrap" bgcolor="#9CBEC7"><span class="style37">Your name</span></td>
    <td bgcolor="#E7F1F3"><em><span class="style38">Name of the person entering the metadata on this form.</span></em><br />
        <select name="Name" id="Name" title="Select your name">
          <option value="None">Please select your name.</option>
          <?php
do {  
?>
          <option value="<?php echo $row_rsName['Name']?>" <?php if (isset($Name)) {if ($row_rsName['Name']==$Name) {echo "selected=\"selected\"";}} ?>><?php echo $row_rsName['Name']?></option>
          <?php
} while ($row_rsName = mysql_fetch_assoc($rsName));
  $rows = mysql_num_rows($rsName);
  if($rows > 0) {
      mysql_data_seek($rsName, 0);
	  $row_rsName = mysql_fetch_assoc($rsName);
  }
?>
        </select>
        <br />
        <?php if ($showmore1=='y') 
	             {echo "<input name=\"hidemore1b\" type=\"image\" id=\"hidemore1b\" src=\"checkbox_yes.png\" />";}
	        else {echo "<input name=\"showmore1b\" type=\"image\" id=\"showmore1b\" src=\"checkbox_no.png\" />";} ?> <span class="style38">other</span>         <br />
		<?php if ($showmore1=='y') { ?>
            
        <br />
        
        <table width="100%" border="0" cellpadding="0">
          <tr>
            <td nowrap="nowrap" class="style38">Name</td>
            <td class="style38"><input name="new_name" type="text" id="new_name" size="40" value="<?php if (isset($new_name)) {echo $new_name;} else {echo "Last, First";} ?>"/></td>
          </tr>
          <tr>
            <td nowrap="nowrap" class="style38">Email</td>
            <td class="style38"><input name="new_email" type="text" id="new_email" size="40" value="<?php if (isset($new_email)) {echo $new_email;} ?>"/></td>
          </tr>
          <tr>
            <td nowrap="nowrap" class="style38">Lab</td>
            <td class="style38"><select name="new_group" id="new_group">
                <option value="None">Please select your lab group.</option>
                <?php
do {  
?>
                <option value="<?php echo $row_rsGroup['Name']?>" <?php if (isset($new_group)) {if ($row_rsGroup['Name']==$new_group) {echo "selected=\"selected\"";}} ?>><?php echo $row_rsGroup['Name']?></option>
                <?php
} while ($row_rsGroup = mysql_fetch_assoc($rsGroup));
  $rows = mysql_num_rows($rsGroup);
  if($rows > 0) {
      mysql_data_seek($rsGroup, 0);
	  $row_rsGroup = mysql_fetch_assoc($rsGroup);
  }
?>
            </select></td>
          </tr>
        </table>   
        
        <?php } ?>        </td>
  </tr>
  <tr>
    <td nowrap="nowrap" bgcolor="#9CBEC7"><span class="style37">Data contact</span></td>
    <td bgcolor="#E7F1F3"><span class="style38"><em>The contact for queries about the data from end-users. Contact information for this person will be posted online and included within the metadata for the dataset.</em><br />
      
        <?php if ($showmore2=='y' | $showmore3=='y') 
	             {echo "<input name=\"hidemore2b\" type=\"image\" id=\"hidemore2b\" src=\"checkbox_no.png\" />";}
	        else {echo "<input name=\"showmore2b\" type=\"image\" id=\"showmore2b\" src=\"checkbox_yes.png\" />";} ?> 
        same as above
         <br />
		 <?php if ($showmore2=='y' | $showmore3=='y') { ?>      
         <select name="Contact" id="Contact">
           <option value="None">Please select the data contact name.</option>
           <?php
do {  
?>
           <option value="<?php echo $row_rsName['Name']?>" <?php if (isset($Contact)) {if ($row_rsName['Name']==$Contact) {echo "selected=\"selected\"";}} ?>><?php echo $row_rsName['Name']?></option>
           <?php
} while ($row_rsName = mysql_fetch_assoc($rsName));
  $rows = mysql_num_rows($rsName);
  if($rows > 0) {
      mysql_data_seek($rsName, 0);
	  $row_rsName = mysql_fetch_assoc($rsName);
  }
?>
          </select>
        
         <br />
        
         <?php if ($showmore3=='y') 
	             {echo "<input name=\"hidemore3b\" type=\"image\" id=\"hidemore3b\" src=\"checkbox_yes.png\" />";}
	        else {echo "<input name=\"showmore3b\" type=\"image\" id=\"showmore3b\" src=\"checkbox_no.png\" />";} ?> 
         other
         <br />
		 <?php if ($showmore3=='y') { ?>  
        
      </span>
      <table width="100%" border="0" cellpadding="0">
        <tr>
          <td nowrap="nowrap" class="style38">Name</td>
          <td class="style38"><input name="contact_new_name" type="text" id="contact_new_name" size="40" value="<?php if (isset($contact_new_name)) {echo $contact_new_name;} else {echo "Last, First";} ?>"/></td>
        </tr>
        <tr>
          <td nowrap="nowrap" class="style38">Email</td>
          <td class="style38"><input name="contact_new_email" type="text" id="contact_new_email" size="40" value="<?php if (isset($contact_new_email)) {echo $contact_new_email;} ?>"/></td>
        </tr>
        <tr>
          <td nowrap="nowrap" class="style38">Lab</td>
          <td class="style38"><select name="contact_new_group" id="contact_new_group">
            <option value="None" selected="selected">Please select his/her lab group.</option>
            <?php
do {  
?>
            <option value="<?php echo $row_rsGroup['Name']?>" <?php if (isset($contact_new_group)) {if ($row_rsGroup['Name']==$contact_new_group) {echo "selected=\"selected\"";}} ?>><?php echo $row_rsGroup['Name']?></option>
            <?php
} while ($row_rsGroup = mysql_fetch_assoc($rsGroup));
  $rows = mysql_num_rows($rsGroup);
  if($rows > 0) {
      mysql_data_seek($rsGroup, 0);
	  $row_rsGroup = mysql_fetch_assoc($rsGroup);
  }
?>
                                        </select></td>
        </tr>
      </table>
      <?php } ?>
      <?php } ?>    </td>
  </tr>
 
 
  
  
  <tr>
    <td nowrap="nowrap" bgcolor="#9CBEC7"><span class="style37">PI</span></td>
    <td bgcolor="#E7F1F3">
      <em class="style38">Name of the main research lead responsible for this data set.      </em>      
      <select name="PI" id="PI">
        <option value="None">Please select the main research lead (PI).</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rsPI['PI']?>" <?php if (isset($PI)) {if ($row_rsPI['PI']==$PI) {echo "selected=\"selected\"";}} ?>><?php echo $row_rsPI['PI']?></option>
        <?php
} while ($row_rsPI = mysql_fetch_assoc($rsPI));
  $rows = mysql_num_rows($rsPI);
  if($rows > 0) {
      mysql_data_seek($rsPI, 0);
	  $row_rsPI = mysql_fetch_assoc($rsPI);
  }
?>
      </select></td>
  </tr>
  <tr>
    <td nowrap="nowrap" bgcolor="#9CBEC7"><span class="style37">Co-PI(s)</span></td>
    <td bgcolor="#E7F1F3"><span class="style38"><em>Name(s) of all co-PIs responsible for this data set.</em></span><em><br />
</em>
        <select name="coPI[]" size="7" multiple="multiple" id="coPI[]" title="Select a PI">
          <option value="None">None</option>
          <?php
do {  
?>
          <option value="<?php echo $row_rsPI['PI']?>" <?php if (isset($coPI)) {if (in_array($row_rsPI['PI'],$coPI)) {echo "selected=\"selected\"";}} ?>><?php echo $row_rsPI['PI']?></option>
          <?php
} while ($row_rsPI = mysql_fetch_assoc($rsPI));
  $rows = mysql_num_rows($rsPI);
  if($rows > 0) {
      mysql_data_seek($rsPI, 0);
	  $row_rsPI = mysql_fetch_assoc($rsPI);
  }
?>
        </select></td>
  </tr>
  <tr>
    <td nowrap="nowrap" bgcolor="#9CBEC7" class="style37">Project(s)</td>
    <td bgcolor="#E7F1F3"><span class="style38"><em>Project(s) this data set is associated with. </em></span><br />
        <select name="Project[]" size="4" multiple="multiple" id="Project[]">
          <option value="C-MORE" <?php if (isset($Project)) {if (in_array("C-MORE",$Project)) {echo "selected=\"selected\"";}} else {echo "selected=\"selected\"";} ?>>C-MORE</option>
          <option value="HOT" <?php if (isset($Project)) {if (in_array("HOT",$Project)) {echo "selected=\"selected\"";}} ?>>HOT</option>
          <option value="MI-LOCO" <?php if (isset($Project)) {if (in_array("MI-LOCO",$Project)) {echo "selected=\"selected\"";}} ?>>MI-LOCO</option>
          <option value="CANON" <?php if (isset($Project)) {if (in_array("CANON",$Project)) {echo "selected=\"selected\"";}} ?>>CANON</option>
        </select>
          <br />
          <em class="style38">Other (please provide the full project name in addition to the acronym):</em> <br />
          <input name="project_other" type="text" id="project_other" value="<?php echo $project_other; ?>" size="70" /></td>
  </tr>
  <tr>
    <td nowrap="nowrap" bgcolor="#9CBEC7" class="style37">Funding</td>
    <td bgcolor="#E7F1F3"><span class="style38"><em>Major funding source(s) for this project.</em></span><br />
      <select name="Funding[]" size="5" multiple="multiple" id="Funding[]">
        <?php
		  $funding=uniqueFields($rsFunding,"Award");
          for ($i=0; $i<count($funding); $i++) {  
             echo "<option value=\"".$funding[$i]."\"" ;
		     if (isset($Funding)) {if (in_array($funding[$i],$Funding)) {echo "selected=\"selected\"";}}
		     echo ">".$funding[$i]."</option>";
          }
?>
      </select>
      <br />
      <em class="style38">Other (Agency, Award Number): </em><br />
        <input name="funding_other" type="text" id="funding_other" value="<?php echo $funding_other; ?>" size="40" />
        <br />        </td>
  </tr>
  <tr>
      <td bgcolor="#DC9054" colspan="2"><div align="center">
          <input name="Next1" type="submit" id="Next1" value="Next" />
      </div>      </td>
  </tr>
  
  </table>

<input type="hidden" name="showmore1" id="showmore1" value="<?php echo $showmore1; ?>" />
<input type="hidden" name="showmore2" id="showmore2" value="<?php echo $showmore2; ?>" />
<input type="hidden" name="showmore3" id="showmore3" value="<?php echo $showmore3; ?>" />


<?php } ?>




<?php if ($page==2) { ?>

  <table width="100%" border="0" cellpadding="5" cellspacing="3" bgcolor="#6298A6">
  <tr>
    <td colspan="2" nowrap="nowrap" bgcolor="#003366"><span class="style36">DATA SET</span></td>
    </tr>
  <tr>
    <td nowrap="nowrap" bgcolor="#9CBEC7"><span class="style37">Brief description</span></td>
    <td bgcolor="#E7F1F3"><span class="style38"><em>A paragraph or two describing this data set. This description will appear with the data set on BCO-DMO. Please include the location and a general date (e.g. Monterey Bay, May 2012) in your description.</em></span><em><br />
    </em>
      <textarea name="description" cols="70" rows="5" id="description"><?php echo $description; ?></textarea>    </td>
  </tr>
  <tr>
    <td nowrap="nowrap" bgcolor="#9CBEC7"><span class="style37">Data short name</span></td>
    <td bgcolor="#E7F1F3">
      <em class="style38">A short name describing the data set (e.g. HPLC pigment, single cell genomics, nutrients). This is the text that will be displayed for the data set on the C-MORE data webpage. This name does not need to be unique - please use the same name for similar datasets on multiple cruises. </em>      
      <select name="data_shortname" id="data_shortname">
        <option value="None">Please select a short name for the data set.</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rsShortName['Dataset']?>" <?php if (isset($data_shortname)) {if ($row_rsShortName['Dataset']==$data_shortname) {echo "selected=\"selected\"";}} ?>><?php echo $row_rsShortName['Dataset']?></option>
        <?php
} while ($row_rsShortName = mysql_fetch_assoc($rsShortName));
  $rows = mysql_num_rows($rsShortName);
  if($rows > 0) {
      mysql_data_seek($rsShortName, 0);
	  $row_rsShortName = mysql_fetch_assoc($rsShortName);
  }
?>
      </select>
         
      <br />
      Other: <br />
     <input name="new_data_shortname" type="text" id="new_data_shortname" size="70" value="<?php if (isset($new_data_shortname)) {echo $new_data_shortname;} ?>"/>     </td>
  </tr>
  <tr>
    <td nowrap="nowrap" bgcolor="#9CBEC7"><span class="style37">Data filename(s)<br />
        <em>(if relevant)</em></span></td>
    <td bgcolor="#E7F1F3">
      <em class="style38">The data file(s) that these metadata refer to. These data files must be sent via email or ftp to Jasmine Nahorniak. Please enter multiple filenames on separate lines. Wildcards (*) may be used as needed.</em><br />
      <textarea name="data_filename" cols="70" rows="2" id="data_filename"><?php echo $data_filename; ?></textarea>    </td>
  </tr>
  <tr>
    <td nowrap="nowrap" bgcolor="#9CBEC7"><span class="style37">URL(s)</span><br />
      <span class="style37"> <em>(if relevant)</em></span></td>
    <td bgcolor="#E7F1F3"><span class="style38"><em>Any existing URLs for the data set. These could include: BCO-DMO, NCBI, etc.</em> <em>Please enter multiple URLs on separate lines.</em></span><br />
        <em>
        <textarea name="data_URL" cols="70" rows="2" id="data_URL"><?php echo $data_URL; ?></textarea>
        <br />
      </em></td>
  </tr>
  <tr>
    <td nowrap="nowrap" bgcolor="#9CBEC7"><span class="style37">Document(s)</span><br />
      <span class="style37"> <em>(if available)</em></span></td>
    <td bgcolor="#E7F1F3"><em class="style38">A list of documents (protocols, processing details, publications, data formats, etc.) to accompany the data file. These documents may be sent via email or ftp to Jasmine Nahorniak. Please enter multiple filenames on separate lines. Wildcards (*) may be used as needed. URLs may also be provided here.</em><br />
        <textarea name="document" cols="70" rows="2" id="document"><?php echo $document; ?></textarea>    </td>
  </tr>
  <tr>
    <td nowrap="nowrap" bgcolor="#9CBEC7"><span class="style37">Data format</span></td>
    <td bgcolor="#E7F1F3" class="style38"><em>Does the data set consist of data in tabular format (a series of columns with unique headings)? Examples include CSV, tab-delimited, and Excel tables.</em><br />
    
        <?php if ($tabular=='y') { ?>
	           <input name="tabularno" type="image" id="tabularno" src="checkbox_yes.png" />
        <?php } else { ?>
               <input name="tabularyes" type="image" id="tabularyes" src="checkbox_no.png" />
        <?php } ?> 
		<span class="style38"> tabular </span><br />
        <em>Tabular data will be reformatted to standard BCO-DMO format prior to ingestion.</em></td>
  </tr>
  <tr>
    <td nowrap="nowrap" bgcolor="#9CBEC7"><span class="style37">Data type(s)</span></td>
    <td bgcolor="#E7F1F3"><span class="style38"><em>The data types this data set represents. These selections help end-users search for data sets of interest.</em> <em>To select multiple items, hold down the shift, control, or alt keys while selecting.</em></span><br />
        <select name="DataType[]" size="5" multiple="multiple" id="DataType[]" title="Select a data type">
          <option value="None">Please select the data type(s).</option>
          <?php
		  $datatypes=uniqueFields($rsData_Type,"Data_Type");
          for ($i=0; $i<count($datatypes); $i++) {  
             echo "<option value=\"".$datatypes[$i]."\"" ;
		     if (isset($DataType)) {if (in_array($datatypes[$i],$DataType)) {echo "selected=\"selected\"";}}
		     echo ">".$datatypes[$i]."</option>";
          }
?>
        </select>
        <br />
        <em class="style38">Other (comma-separated):</em> <br />
      <input name="datatype_other" type="text" id="datatype_other" size="40" value="<?php echo $datatype_other; ?>"/></td>
  </tr>
  <tr>
    <td nowrap="nowrap" bgcolor="#9CBEC7"><span class="style37">Instrument(s)</span></td>
    <td bgcolor="#E7F1F3" class="style38"><em>The main instrument(s) used to collect this data set.</em> <em>To select multiple items, hold down the shift, control, or alt keys while selecting.</em><br />
        <select name="Instrument[]" size="5" multiple="multiple" id="Instrument[]" title="Select an instrument">
          <option value="None">Please select the instrument(s) used.</option>
          <?php
		  $instruments=uniqueFields($rsInstrument,"Instrument");
          for ($i=0; $i<count($instruments); $i++) {  
             echo "<option value=\"".$instruments[$i]."\"" ;
		     if (isset($Instrument)) {if (in_array($instruments[$i],$Instrument)) {echo "selected=\"selected\"";}}
		     echo ">".$instruments[$i]."</option>";
          }
?>
        </select>
        <br />
        <em>Other (comma-separated):</em> <br />
        <input name="instrument_other" type="text" id="instrument_other" size="40" value="<?php echo $instrument_other; ?>" />    </td>
  </tr>
  
  <tr>
    <td nowrap="nowrap" bgcolor="#9CBEC7"><span class="style37">Cruise(s)</span></td>
    <td bgcolor="#E7F1F3"><span class="style38"><em>The cruise(s) covered by the data set.</em>
        <em>To select multiple items, hold down the shift, control, or alt keys while selecting.</em><br />
        <select name="Cruise[]" size="5" multiple="multiple" id="Cruise[]">
          <option value="None">Please select the cruise(s).</option>
          <?php
do {  
?>
          <option value="<?php echo $row_rsCruise['Acronym']; ?>" <?php if (isset($Cruise)) {if (in_array($row_rsCruise['Acronym'],$Cruise)) {echo "selected=\"selected\"";}} ?>><?php echo $row_rsCruise['Acronym']?></option>
          <?php
} while ($row_rsCruise = mysql_fetch_assoc($rsCruise));
  $rows = mysql_num_rows($rsCruise);
  if($rows > 0) {
      mysql_data_seek($rsCruise, 0);
	  $row_rsCruise = mysql_fetch_assoc($rsCruise);
  }
?>
        </select>
        <br />
        <em>Other (comma-separated)</em>:<br />
        <input name="cruise_other" type="text" id="cruise_other" size="70" value="<?php echo $cruise_other; ?>"/>
        <br />
    </span> </td>
  </tr>
  <tr>
    <td nowrap="nowrap" bgcolor="#9CBEC7"><span class="style37">Time Zone</span></td>
    <td bgcolor="#E7F1F3" class="style38"><em>The time zone used for any times and dates within the data file (e.g. UTC, HST, ...).</em>
      <br />
        <input name="timezone" type="text" id="timezone" size="5" value="<?php echo $timezone; ?>" />
      </td>
  </tr>
  <tr>
    <td nowrap="nowrap" bgcolor="#9CBEC7"><span class="style37">Access type</span></td>
    <td bgcolor="#E7F1F3" class="style38">
        <input name="accesstype" type="radio" id="accesstype" value="public" <?php if ($accesstype=='public') { echo "checked=\"checked\"";} ?> /> public 
        <input name="accesstype" type="radio" id="accesstype" value="members" <?php if ($accesstype=='members') { echo "checked=\"checked\"";} ?>/> C-MORE members only    </td>
  </tr>
  <tr>
    <td nowrap="nowrap" bgcolor="#9CBEC7"><span class="style37">Data status</span></td>
    <td bgcolor="#E7F1F3" class="style38"><input name="datastatus" type="radio" id="datastatus" value="preliminary" <?php if ($datastatus=='preliminary') { echo "checked=\"checked\"";} ?> />
        preliminary
        <input name="datastatus" type="radio" id="datastatus" value="final" <?php if ($datastatus=='final') { echo "checked=\"checked\"";} ?>/>
        final</td>
  </tr>
  <tr>
    <td nowrap="nowrap" bgcolor="#9CBEC7"><span class="style37">Additional comments <br />
      for the data manager<br />
      <em>(optional)</em></span></td>
    <td bgcolor="#E7F1F3" class="style38"><textarea name="comments" cols="70" rows="4" id="comments"><?php echo $comments; ?></textarea></td>
  </tr>
  <tr>
      <td bgcolor="#DC9054" colspan="2"><div align="center">
          <input name="Back2" type="submit" id="Back2" value="Back" />
        <?php if ($tabular=='n') { ?>
           <input name="Submit" type="submit" id="Submit" value="Submit" />
        <?php } else { ?>
           <input name="Next2" type="submit" id="Next2" value="Next" />          
        <?php } ?>
		   
      </div>      </td>
    </tr>
  </table>
  
  <input type="hidden" name="tabular" id="tabular" value="<?php echo $tabular; ?>" />

<?php } ?>





<?php if ($page==3) { ?>

<table width="100%" border="0" cellpadding="5" cellspacing="3" bgcolor="#6298A6">
  <tr>
    <td colspan="3" nowrap="nowrap" bgcolor="#003366"><span class="style36">PARAMETERS</span></td>
  </tr>
  <tr>
    <td colspan="3" bgcolor="#6298A6"><span class="style36">Sampling attributes</span></td>
    </tr>
  <tr>
    <td bgcolor="#9CBEC7" class="style38"><span class="style37">Your parameter name <br />
or column number</span></td>
    <td bgcolor="#E7F1F3" class="style38"><span class="style37">BCO-DMO <br />parameter name</span></td>
    <td bgcolor="#E7F1F3" class="style38"><span class="style37">BCO-DMO <br />parameter description</span></td>
  </tr>
  <tr>
    <td bgcolor="#9CBEC7"><input name="cruise_id" type="text" id="cruise_id" maxlength="30" /></td>
    <td bgcolor="#E7F1F3" class="style38">cruise_id</td>
    <td bgcolor="#E7F1F3" class="style38">cruise name</td>
  </tr>
  <tr>
    <td bgcolor="#9CBEC7"><input name="date" type="text" id="date" maxlength="30" /></td>
    <td bgcolor="#E7F1F3" class="style38">date</td>
    <td bgcolor="#E7F1F3" class="style38">date (if sample from a single day)</td>
  </tr>
  <tr>
    <td bgcolor="#9CBEC7"><input name="date_begin" type="text" id="date_begin" maxlength="30" /></td>
    <td bgcolor="#E7F1F3" class="style38">date_begin</td>
    <td bgcolor="#E7F1F3" class="style38">date sampling begins (if sample covers multiple days)</td>
  </tr>
  <tr>
    <td bgcolor="#9CBEC7"><input name="date_end" type="text" id="date_end" maxlength="30" /></td>
    <td bgcolor="#E7F1F3" class="style38">date_end</td>
    <td bgcolor="#E7F1F3" class="style38">date sampling ends (if sample covers multiple days)</td>
  </tr>
  <tr>
    <td bgcolor="#9CBEC7"><input name="year" type="text" id="year" maxlength="30" /></td>
    <td bgcolor="#E7F1F3" class="style38">year</td>
    <td bgcolor="#E7F1F3" class="style38">year</td>
  </tr>
  <tr>
    <td bgcolor="#9CBEC7"><input name="month" type="text" id="month" maxlength="30" /></td>
    <td bgcolor="#E7F1F3" class="style38">month</td>
    <td bgcolor="#E7F1F3" class="style38">month</td>
  </tr>
  <tr>
    <td bgcolor="#9CBEC7"><input name="day" type="text" id="day" maxlength="30" /></td>
    <td bgcolor="#E7F1F3" class="style38">day</td>
    <td bgcolor="#E7F1F3" class="style38">day of month</td>
  </tr>
  <tr>
    <td bgcolor="#9CBEC7"><input name="time" type="text" id="time" maxlength="30" /></td>
    <td bgcolor="#E7F1F3" class="style38">time</td>
    <td bgcolor="#E7F1F3" class="style38">time (if measurement at a single time)</td>
  </tr>
  <tr>
    <td bgcolor="#9CBEC7"><input name="time_begin" type="text" id="time_begin" maxlength="30" /></td>
    <td bgcolor="#E7F1F3" class="style38">time_begin</td>
    <td bgcolor="#E7F1F3" class="style38">time at start of measurement (if longer measurement)</td>
  </tr>
  <tr>
    <td bgcolor="#9CBEC7"><input name="time_end" type="text" id="time_end" maxlength="30" /></td>
    <td bgcolor="#E7F1F3" class="style38">time_end</td>
    <td bgcolor="#E7F1F3" class="style38">time at end of measurement (if longer measurement)</td>
  </tr>
  <tr>
    <td bgcolor="#9CBEC7"><input name="sta" type="text" id="sta" maxlength="30" /></td>
    <td bgcolor="#E7F1F3" class="style38">sta</td>
    <td bgcolor="#E7F1F3" class="style38">station number</td>
  </tr>
  <tr>
    <td bgcolor="#9CBEC7"><input name="sta_name" type="text" id="sta_name" maxlength="30" /></td>
    <td bgcolor="#E7F1F3" class="style38">sta_name</td>
    <td bgcolor="#E7F1F3" class="style38">station name</td>
  </tr>
  <tr>
    <td bgcolor="#9CBEC7"><input name="cast" type="text" id="cast" maxlength="30" /></td>
    <td bgcolor="#E7F1F3" class="style38">cast</td>
    <td bgcolor="#E7F1F3" class="style38">cast or profile number</td>
  </tr>
  <tr>
    <td bgcolor="#9CBEC7"><input name="lon" type="text" id="lon" maxlength="30" /></td>
    <td bgcolor="#E7F1F3" class="style38">lon</td>
    <td bgcolor="#E7F1F3" class="style38">longitude</td>
  </tr>
  <tr>
    <td bgcolor="#9CBEC7"><input name="lon_begin" type="text" id="lon_begin" maxlength="30" /></td>
    <td bgcolor="#E7F1F3" class="style38">lon_begin</td>
    <td bgcolor="#E7F1F3" class="style38">longitude at start of measurement</td>
  </tr>
  <tr>
    <td bgcolor="#9CBEC7"><input name="lon_end" type="text" id="lon_end" maxlength="30" /></td>
    <td bgcolor="#E7F1F3" class="style38">lon_end</td>
    <td bgcolor="#E7F1F3" class="style38">longitude at end of measurement</td>
  </tr>
  <tr>
    <td bgcolor="#9CBEC7"><input name="lat" type="text" id="lat" maxlength="30" /></td>
    <td bgcolor="#E7F1F3" class="style38">lat</td>
    <td bgcolor="#E7F1F3" class="style38">latitude</td>
  </tr>
  <tr>
    <td bgcolor="#9CBEC7"><input name="lat_begin" type="text" id="lat_begin" maxlength="30" /></td>
    <td bgcolor="#E7F1F3" class="style38">lat_begin</td>
    <td bgcolor="#E7F1F3" class="style38">latitude at start of measurement</td>
  </tr>
  <tr>
    <td bgcolor="#9CBEC7"><input name="lat_end" type="text" id="lat_end" maxlength="30" /></td>
    <td bgcolor="#E7F1F3" class="style38">lat_end</td>
    <td bgcolor="#E7F1F3" class="style38">latitude at end of measurement</td>
  </tr>
  <tr>
    <td bgcolor="#9CBEC7"><input name="bot" type="text" id="bot" maxlength="30" /></td>
    <td bgcolor="#E7F1F3" class="style38">bot</td>
    <td bgcolor="#E7F1F3" class="style38">rosette bottle number</td>
  </tr>
  <tr>
    <td bgcolor="#9CBEC7"><input name="bots" type="text" id="bots" maxlength="30" /></td>
    <td bgcolor="#E7F1F3" class="style38">bots</td>
    <td bgcolor="#E7F1F3" class="style38">rosette bottle numbers for composite sample</td>
  </tr>
  <tr>
    <td bgcolor="#9CBEC7"><input name="depth" type="text" id="depth" maxlength="30" /></td>
    <td bgcolor="#E7F1F3" class="style38">depth</td>
    <td bgcolor="#E7F1F3" class="style38">depth (or pressure value) of sample</td>
  </tr>
  <tr>
    <td bgcolor="#9CBEC7"><input name="depth_begin" type="text" id="depth_begin" maxlength="30" /></td>
    <td bgcolor="#E7F1F3" class="style38">depth_begin</td>
    <td bgcolor="#E7F1F3" class="style38">depth (or pressure value) at which measurement began</td>
  </tr>
  <tr>
    <td bgcolor="#9CBEC7"><input name="depth_end" type="text" id="depth_end" maxlength="30" /></td>
    <td bgcolor="#E7F1F3" class="style38">depth_end</td>
    <td bgcolor="#E7F1F3" class="style38">depth (or pressure value) at which measurement ended</td>
  </tr>
  <tr>
    <td bgcolor="#9CBEC7"><input name="depth_trap" type="text" id="depth_trap" maxlength="30" /></td>
    <td bgcolor="#E7F1F3" class="style38">depth_trap</td>
    <td bgcolor="#E7F1F3" class="style38">depth of sediment trap</td>
  </tr>
  <tr>
    <td bgcolor="#9CBEC7"><input name="sample" type="text" id="sample" maxlength="30" /></td>
    <td bgcolor="#E7F1F3" class="style38">sample</td>
    <td bgcolor="#E7F1F3" class="style38">sample ID</td>
  </tr>
  <tr>
    <td bgcolor="#9CBEC7"><input name="activity_and_comments" type="text" id="activity_and_comments" maxlength="30" /></td>
    <td bgcolor="#E7F1F3" class="style38">activity_and_comments</td>
    <td bgcolor="#E7F1F3" class="style38">operation performed and/or sampling method for given sampling event, and comments</td>
  </tr>
  <tr>
    <td bgcolor="#9CBEC7"><input name="vol_filt" type="text" id="vol_filt" maxlength="30" /></td>
    <td bgcolor="#E7F1F3" class="style38">vol_filt</td>
    <td bgcolor="#E7F1F3" class="style38">volume of water filtered</td>
  </tr>
  <tr>
    <td bgcolor="#9CBEC7"><input name="qflag" type="text" id="qflag" maxlength="30" /></td>
    <td bgcolor="#E7F1F3" class="style38">qflag</td>
    <td bgcolor="#E7F1F3" class="style38">quality flag, identifies a problem/quality issue</td>
  </tr>
  <tr>
    <td colspan="3" bgcolor="#6298A6"><span class="style36">Physical Properties</span></td>
  </tr>
  <tr>
    <td bgcolor="#9CBEC7" class="style38"><span class="style37">Your parameter name <br />
      or column number</span></td>
    <td bgcolor="#E7F1F3" class="style38"><span class="style37">BCO-DMO <br />
      parameter name</span></td>
    <td bgcolor="#E7F1F3" class="style38"><span class="style37">BCO-DMO <br />
      parameter description</span></td>
  </tr>
  
  <tr>
    <td bgcolor="#9CBEC7"><input name="cond" type="text" id="cond" maxlength="30" /></td>
    <td bgcolor="#E7F1F3" class="style38">cond</td>
    <td bgcolor="#E7F1F3" class="style38">conductivity from CTD</td>
  </tr>
  <tr>
      <td bgcolor="#DC9054" colspan="3">
        <div align="center">
          <input name="Back3" type="submit" id="Back3" value="Back" />
          <input name="Submit" type="submit" id="Submit" value="Submit" />
        </div>      </td>
    </tr>
</table>

<?php } ?>

  




 

</form>

<p>&nbsp;</p>

 
  
<p><br />
</p>

<p>[ <a href="#top">Top of Page</a> ]</p>

</div>
<!-- End MainContent -->

<BR><BR><BR><BR><BR><BR><BR><BR>


<!--footer.ssi inserts the SOEST footer -->
<!-- Begin Footer -->
<div id="Footer">
<p>
<a href="http://manoa.hawaii.edu/" target="_blank"><img src="http://www.soest.hawaii.edu/soest_web/images/ManoaSeal_in_75pix.gif" alt="UH Manoa" width="75" height="75" hspace="6" border="0" align="right" /></a>
<a href="http://www.soest.hawaii.edu"><img src="http://www.soest.hawaii.edu/soest_web/images/soest_oval_logo_web.gif" alt="SOEST" width="103" height="74" border="0" align="right" /></a>
<a href="http://www.soest.hawaii.edu/index.htm">SOEST Home</a> | <a href="http://www.soest.hawaii.edu/soest_web/soest.search.htm">Search</a> | <a href="http://www.soest.hawaii.edu/soest_web/soest.directory.php">Directory</a> | <a href="http://www.soest.hawaii.edu/soest_web/soest.news.htm">News</a> | <a href="http://www.soest.hawaii.edu/soest_web/soest.departments.htm">Academic Departments</a> | <a href="http://www.soest.hawaii.edu/soest_web/soest.research.htm">Research at the School</a> | <a href="http://www.soest.hawaii.edu/soest_web/soest.academics.htm">Education</a> | <a href="http://www.soest.hawaii.edu/soest_web/soest.resources.htm">Resources</a> |
<a href="http://www.soest.hawaii.edu/soest_web/soest.outreach.htm">Public Outreach</a> | <a href="http://www.soest.hawaii.edu/soest_web/soest.school.htm">The School</a> | <a href="http://www.soest.hawaii.edu/soest_web/soest.sitemap.htm">Site Map</a> | <a href="http://www.soest.hawaii.edu/soest_web/soest.contact.htm">Contact SOEST</a></p>

<p><em>Comments or questions about this page go to <a href="mailto:illust@soest.hawaii.edu">illust@soest.hawaii.edu</a>.</em></p>
</div>
<BR><BR><BR>
<!-- End Footer -->


</div>
<!-- End Container -->

</body>

</html>
<?php

mysql_free_result($rsCruise);

mysql_free_result($rsPI);

mysql_free_result($rsName);

mysql_free_result($rsGroup);

mysql_free_result($rsData_Type);

mysql_free_result($rsInstrument);

mysql_free_result($rsFunding);

?>
