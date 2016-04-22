<?php
$bodyTag = "results";
require_once "../Locale/locale.php";
require '../lib/infospacerequestsigner.php';
$headlinks = '<link rel="dns-prefetch" href="//csr.inspsearchapi.com" />
<link rel="dns-prefetch" href="//config.inspsearchapi.com" />
<link rel="dns-prefetch" href="//appapi.inspsearchapi.com" />
<link rel="dns-prefetch" href="//eventlog.inspsearchapi.com" />';
$headscripts = '<script type="text/javascript" src="//csr.inspsearchapi.com/lib/infospace.search.js"></script>';
$pageTitle = _("Search Results");
$pageDescription = _("Vivaldi Search");
$pageKeywords = _("search,vivaldi");
$searchbar = true;
$query = htmlspecialchars($_GET["s"]);
$infospace_signer = new InfoSpaceRequestSigner('nmatXupew0-5K1uD5KdDxQ2');
$signature = $infospace_signer->getSignature($query);
$page = isset($_GET["page"]) ? $_GET["page"] : '';
include "../header.php";
?>
<div id="main">
  <div class="section" id="content" style="display:none">
    <div id="leftCol">
      <div id="resultWrapper">
          <div id="pageLevelDiv"></div>
          <div id="topResults"><span class="resultLabel"><p>Ad</p></span></div>
          <div id="mainResults"><span class="resultLabel"><p>Web Results</p></span></div>
          <div id="bottomResults"></div>
          <div id="spellSuggestResults"></div>
          <div id="relatedWrapper">
            <p>Searches related to <?php echo $query; ?></p>
            <div id="relatedResults"></div>
          </div>
      </div>
    </div>
    <div id="rightCol">
      <div id="rightResults"><span class="resultLabel"><p>Ads</p></span></div>
    </div>
    <div id="pagination"></div>
  </div>
</div>

<script type="text/javascript">

   insp.search.doSearch({
     query: '<?php echo $query;?>',  // take care to encode the query term properly, refer to Best Practices Tip #7
     accessId: 'vivaldi.vivaldi',
     signature: '<?php echo $signature;?>',
     page: '<?php echo (isset($page) ? $page : 1);?>',
     containers: {
       'top': {id:'topResults'},
       'main': {id:'mainResults'},
       'bottom': {id:'bottomResults'},
       'right': {id:'rightResults'},
       'related': {id:'relatedResults'},
       'spelling': {id:'spellSuggestResults'}
     },
    onComplete: function(details) {
      if (details) {
        if (details.maxAlgoResultCount === 0) {
          var pageLevelDiv = document.getElementById('pageLevelDiv');
          var noResultsDiv = document.createElement('div');
          $("#relatedWrapper p").remove();
          $("#pagination").remove();
          noResultsDiv.innerHTML = '<span>No results returned for the provided query.</span>';
          pageLevelDiv.appendChild(noResultsDiv);
        }
        $('#content').fadeIn(150);
      }

      var maxPagesToShow = 10;
      var paginationHtml = "";
      var currentPage = parseInt('<?php echo (isset($page) ? $page : 1);?>');
      var links = [];
      var startPosition = 1;
      if (currentPage > 6) {
        startPosition = currentPage - 5;
        maxPagesToShow = currentPage + 4;
      }
      for (var i = startPosition; i <= Math.min(maxPagesToShow, details.maxAlgoPage); ++i) {
          var active = "";
          if(i == currentPage) {
            active = "active";
          }
          var link = ' <a class="' + active + '" href="/results/?s=' + '<?php echo $query?>' + '&page=' + i + '&lang=' +'<?php echo substr($lang, 0, 2)?>' + '">' + i + '</a>'; 
          links.push(link);
      }
      paginationHtml = links.join('\n');
      $('#pagination').html(paginationHtml);
    }
   });
</script>
<?php include "./footer.php";?>