<div class="step-form-footer text-center mt-3">
  <span>&copy; <?php echo date('Y');?> <?=lang('donate_page_title');?></span>
</div>
</div><!-- wrapper -->

<script src="<?=base_url('themes/front/vendor/bootstrap3/js/bootstrap.min.js')?>"></script>
<script src="<?=base_url('themes/front/js/application.js')?>"></script>

<?php if(isset($front_google_analytics) && !empty($front_google_analytics)):?>
<script type="text/javascript" charset="utf-8">
(function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
e=o.createElement(i);r=o.getElementsByTagName(i)[0];
e.src='//www.google-analytics.com/analytics.js';
r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
ga('create',"<?php echo $front_google_analytics;?>");ga('send','pageview');
</script>
<?php endif;?>
</body>
</html>