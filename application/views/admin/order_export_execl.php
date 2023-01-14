X<html>
<head>
<meta charset="utf-8">
<meta http-equiv="Content-Language" content="en" />
<meta name="GENERATOR" content="PHPEclipse 1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>order export execl</title>
</head>
<body>
<div class="content">
<table width="100%" border="1" cellpadding="5" cellspacing="5">
  <thead>
    <tr>
      <th>ID</th>
      <th>訂單編號</th>
      <th>捐款人稱呼</th>
      <th>捐款人姓</th>
      <th>捐款人名</th>
      <th>捐款金額</th>
      <th>捐款項目</th>
      <th>捐款項目其他</th>
      <th>聯絡電郵</th>
      <th>國家/地區</th>
      <th>聯絡電話</th>
      <th>聯絡地址</th>
      <th>支付方式</th>
      <th>支付狀態</th>
  
      <th>需要捐款收據</th>
      <th>收據寄送方式</th>
      <th>收據寄送補充</th>

      <th>需要訂閱信息</th>
      <th>訂閱信息方式</th>
      <th>訂閱信息補充</th>

      <th>狀態</th>
      <th>編輯於</th>
      <th>創建於</th>
    </tr>
  </thead>
  <tbody>
    <?php if(isset($export) && !empty($export) && is_array($export)):?>
    <?php foreach ($export as $list):?>
    <?php 
      $donate_item_array = !empty($list['donate_item'])?json_decode($list['donate_item'],TRUE):array();
      $donate_item_format =!empty($donate_item_array)?implode('，',$donate_item_array):'';
    ?>
    <tr>
      <td><?php echo $list['id'];?></td>
      <td><?php echo $list['number']?></td>
      <td><?php echo $list['donate_gender']?></td>
      <td><?php echo $list['donate_firstname']?></td>
      <td><?php echo $list['donate_lastname']?></td>
      <td><?php echo $list['donate_money'];?></td>

      <td><?php echo $donate_item_format;?></td>
      <td><?php echo $list['donate_item_other'];?></td>
      <td><?php echo $list['donate_email'];?></td>
      <td><?php echo $list['donate_country'];?></td>
      <td><?php echo $list['donate_phone'];?></td>
      <td><?php echo $list['donate_address'];?></td>

      <td><?php echo $list['payment_method'];?></td>
      <td><?php echo helper_type_parameter('order_status_option',$list['order_status_id']);?></td>
      <?php if($list['need_receipt']==1):?>
      <td><?php echo $list['need_receipt']==1?'是':''?></td>
      <td><?php echo $list['payment_receipt_type']==1?'電郵':'郵寄地址';?></td>
      <td><?php echo $list['payment_receipt_note'];?></td>
      <?php else:?>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <?php endif;?>

      <?php if($list['need_subscribe']==1):?>
      <td><?php echo $list['need_subscribe']==1?'是':''?></td>
      <td><?php echo $list['subscribe_type']==1?'電郵':'郵寄地址';?></td>
      <td><?php echo $list['subscribe_note'];?></td>
      <?php else:?>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <?php endif;?>

      <td><?php echo $list['status_format']?></td>
      <td><?php echo $list['updated_at'];?></td>
      <td><?php echo $list['created_at'];?></td>
    </tr>
    <?php endforeach;?>
    <?php endif;?>
    </tbody>
  </table>
</div><!--content_right end-->
</body>
</html>