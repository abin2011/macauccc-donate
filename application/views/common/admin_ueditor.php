<script type="text/javascript" src="<?=base_url('themes/admin/js/ueditor1_4_3/ueditor.config.js')?>"></script>
<script type="text/javascript" src="<?=base_url('themes/admin/js/ueditor1_4_3/ueditor.all.min.js')?>"></script>
<script src="<?=base_url('themes/admin/vendor/multi-language-sync.js')?>"></script>
<script type="text/javascript">
$(document).ready(function(){

  // 全局：使用UEDITOR
  $('textarea[editer="uediter"]').each(function(){
    var obj_id=$(this).attr('id');
    var obj_name='ue_'+obj_id;
    obj_name = UE.getEditor(obj_id);
  });

});  
</script>