<?php $this->load->view('common/front_header');?>

<div class="container">
  
  <div class="page-404 text-center mt-10 mb-10">
    <div class="icon">
      <i class="fas fa-ban"></i>
    </div>
    <div class="main">
      <h3><?=lang('page_404_title')?></h3>
      <div class="content">
        <p><?=lang('page_404_content')?></p>
        <p><span id="countdown">8</span><?=lang('page_404_words')?></p>
      </div>
      <div class="btn-area">
        <a href="<?=site_url('home')?>" class="btn btn-primary"><?=lang('donate_back_home');?></a>
      </div>
    </div>
  </div>

</div>
<!--container-->
<script type="text/javascript">
  // count down and redirect to home page
  var seconds = 8;
  function countdown() {
    seconds = seconds - 1;
    if (seconds < 0) {
      window.location = "<?=base_url()?>";
    } else {
      document.getElementById("countdown").innerHTML = seconds;
      window.setTimeout("countdown()", 1000);
    }
  }
  countdown();
</script>
<?php $this->load->view('common/front_footer');?>