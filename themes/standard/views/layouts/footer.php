
<div style="width: 30px; height: 104px; position: fixed; right: 0; top: 240px; border-left: 3px #444 solid; border-bottom: 3px #444 solid; border-top: 3px #444 solid;">
	<a href="http://geoloqi.com/help/" target="_blank"><img src="/images/feedback-tab.png" width="30" height="104"</a>	
</div>

<?php 
if(GEOLOQI_GA_ID){
?>
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '<?=GEOLOQI_GA_ID?>']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
<?php 
}
?>
</body>
</html>