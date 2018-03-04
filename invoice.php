<?php 
  include 'rsrv_server.php';
  
  if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
  
    $db = mysqli_connect('localhost', 'root', '', 'ppdo_iams');

?>

<!DOCTYPE html>
<html>
<head>
  <title>Invoice</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="css/or.css">
</head>
<body>

    <?php 
      if (isset($_GET['view'])) {
        $order_number = $_GET['view'];
      }
      else {
        $result = mysqli_query($db, "SELECT order_number FROM menu_reservation ORDER BY order_number DESC LIMIT 1");
        $row_order = mysqli_fetch_array($result);
        $order_number = $row_order['order_number'];  
      }
      $results_event = mysqli_query($db, "SELECT * FROM menu_reservation WHERE order_number=$order_number");
      while ($row_event = mysqli_fetch_array($results_event)) {  
    ?>

<div id="invoiceholder">

  <div id="headerimage"></div>
  <div id="invoice" class="effect2">
    
    <div id="invoice-top">
      <div class="logo"></div>
      <div class="info">
        <h2>Sherwin's Catering</h2>
        <p> sherwin_modesto_catering@yahoo.com </br>
            (+63)9152146092
        </p>
      </div><!--End Info-->
      <div class="title">
        <h1>Reservation #<?php echo $order_number;?> </h1>
        <p>Date: <?php echo $row_event['rsrv_date']; ?> </br>
            Call Time: <?php echo $row_event['rsrv_calltime'];?>
        </p>
      </div><!--End Title-->
    </div><!--End InvoiceTop-->
    <?php 
      $lastname = $row_event['rsrv_lname'];
      $firstname = $row_event['rsrv_fname']; 
      $id = $row_event['id'];
    }
    ?>

    <div id="invoice-mid">  
      <div class="clientlogo"></div>
      <div class="info">
        <h2>Client Name: <?php echo $firstname." ".$lastname; ?></h2>
        <?php
          
          $results_event2 = mysqli_query($db, "SELECT * FROM users WHERE id=$id"); 
          while ($row_event = mysqli_fetch_array($results_event2)) { 
        ?>
        <p><?php echo $row_event['email']; ?></br>
           <?php echo $row_event['contact']; ?></br></p>
      </div>
      <?php } ?>

      <?php 
      $results_event = mysqli_query($db, "SELECT * FROM menu_reservation WHERE order_number=$order_number"); 
      while ($row_event = mysqli_fetch_array($results_event)) {  
      ?>

      <div id="project">
        <h2>Event Name: <?php echo $row_event['rsrv_eventname'];?></h2>
        <?php if ($row_event['rsrv_headcount']!=0): ?>
          <p>Headcount: <?php echo $row_event['rsrv_headcount'];?></p>
        <?php endif ?>  
      </div>   
      <?php } ?>
    </div><!--End Invoice Mid-->
  
    <div id="invoice-bot">
      
      <div id="table">
    <table>
      <tr>
        <td>Breakfast:</td>
      </tr>
      <tr>
        <td><?php 

        $bf_order = "";

        $results_bs = mysqli_query($db, "SELECT * FROM bs_reservation WHERE order_number=$order_number");
        $j=0;
        while ($row_bs = mysqli_fetch_array($results_bs)){
          for ($i=1;$i<=12;$i++){
            if ($row_bs['bs_s'.$i]>0 && $row_bs['bs_s'.$i.'name']!="None" && $row_bs['bs_o'.$i]=="Breakfast"){
              $bf_order = $bf_order.$row_bs['bs_s'.$i.'name']." (".$row_bs['bs_s'.$i].") ";
            }
          } 
        }

        $results_bf = mysqli_query($db, "SELECT * FROM bf_reservation WHERE order_number=$order_number");
        $j=0;
        while ($row_bf = mysqli_fetch_array($results_bf)){
          for ($i=1;$i<=20;$i++){
            if ($row_bf['bf_s'.$i]>0 && $row_bf['bf_s'.$i.'name']!="None"){
              $bf_order = $bf_order.$row_bf['bf_s'.$i.'name']." (".$row_bf['bf_s'.$i].") ";
            }
          } 
        }
        
        echo $bf_order;

        ?>  
        </td>
      </tr>
      <tr>
        <td>AM Snack:</td>
      </tr>
      <tr>
        <td><?php 

        $as_order = "";

        $results_bs = mysqli_query($db, "SELECT * FROM bs_reservation WHERE order_number=$order_number");
        $j=0;
        while ($row_bs = mysqli_fetch_array($results_bs)){
          for ($i=1;$i<=12;$i++){
            if ($row_bs['bs_s'.$i]>0 && $row_bs['bs_s'.$i.'name']!="None" && $row_bs['bs_o'.$i]=="AM Snack"){
              $as_order = $as_order.$row_bs['bs_s'.$i.'name']." (".$row_bs['bs_s'.$i].") ";
            }
          } 
        }

       

        $results_sn = mysqli_query($db, "SELECT * FROM sn_reservation WHERE order_number=$order_number");
        $j=0;
        while ($row_sn = mysqli_fetch_array($results_sn)){
          for ($i=1;$i<=23;$i++){
            if ($row_sn['sn_s'.$i]>0 && $row_sn['sn_s'.$i.'name']!="None" && $row_sn['sn_o'.$i]=="AM Snack"){
              $as_order = $as_order.$row_sn['sn_s'.$i.'name']." (".$row_sn['sn_s'.$i].") ";
            }
          } 
        }

        echo $as_order;
         ?> 
        </td>
      </tr>
      <tr>
        <td>Lunch:</td>
      </tr>
      <tr>
        <td><?php 
        $ln_order = "";
        $results_bs = mysqli_query($db, "SELECT * FROM bs_reservation WHERE order_number=$order_number");
        $j=0;
        while ($row_bs = mysqli_fetch_array($results_bs)){
          for ($i=1;$i<=12;$i++){
            if ($row_bs['bs_s'.$i]>0 && $row_bs['bs_s'.$i.'name']!="None" && $row_bs['bs_o'.$i]=="Lunch"){
              $ln_order = $ln_order.$row_bs['bs_s'.$i.'name']." (".$row_bs['bs_s'.$i].") ";
            }
          } 
        }


        $results_ch = mysqli_query($db, "SELECT * FROM ch_reservation WHERE order_number=$order_number");
        $j=0;
        while ($row_ch = mysqli_fetch_array($results_ch)){
          for ($i=1;$i<=18;$i++){
            if ($row_ch['ch_s'.$i]>0 && $row_ch['ch_s'.$i.'name']!="None" && $row_ch['ch_o'.$i]=="Lunch"){
              $ln_order = $ln_order.$row_ch['ch_s'.$i.'name']." (".$row_ch['ch_s'.$i].") ";
            }
          } 
        }

        $results_p = mysqli_query($db, "SELECT * FROM p_reservation WHERE order_number=$order_number");
        $j=0;
        while ($row_p = mysqli_fetch_array($results_p)){
          for ($i=1;$i<=17;$i++){
            if ($row_p['p_s'.$i]>0 && $row_p['p_s'.$i.'name']!="None" && $row_p['p_o'.$i]=="Lunch"){
              $ln_order = $ln_order.$row_p['p_s'.$i.'name']." (".$row_p['p_s'.$i].") ";
            }
          } 
        }

        $results_f = mysqli_query($db, "SELECT * FROM f_reservation WHERE order_number=$order_number");
        $j=0;
        while ($row_f = mysqli_fetch_array($results_f)){
          for ($i=1;$i<=10;$i++){
            if ($row_f['f_s'.$i]>0 && $row_f['f_s'.$i.'name']!="None" && $row_f['f_o'.$i]=="Lunch"){
              $ln_order = $ln_order.$row_f['f_s'.$i.'name']." (".$row_f['f_s'.$i].") ";
            }
          } 
        }

        $results_v = mysqli_query($db, "SELECT * FROM v_reservation WHERE order_number=$order_number");
        $j=0;
        while ($row_v = mysqli_fetch_array($results_v)){
          for ($i=1;$i<=14;$i++){
            if ($row_v['v_s'.$i]>0 && $row_v['v_s'.$i.'name']!="None" && $row_v['v_o'.$i]=="Lunch"){
              $ln_order = $ln_order.$row_v['v_s'.$i.'name']." (".$row_v['v_s'.$i].") ";
            }
          } 
        }

        $results_d = mysqli_query($db, "SELECT * FROM d_reservation WHERE order_number=$order_number");
        $j=0;
        while ($row_d = mysqli_fetch_array($results_d)){
          for ($i=1;$i<=10;$i++){
            if ($row_d['d_s'.$i]>0 && $row_d['d_s'.$i.'name']!="None" && $row_d['d_o'.$i]=="Lunch"){
              $ln_order = $ln_order.$row_d['d_s'.$i.'name']." (".$row_d['d_s'.$i].") ";
            }
          } 
        }

        echo $ln_order;
        ?>  
        </td>
      </tr>
      <tr>
        <td>PM Snack:</td>
      </tr>
      <tr>
        <td><?php 
         $ps_order = "";
        $results_bs = mysqli_query($db, "SELECT * FROM bs_reservation WHERE order_number=$order_number");
        $j=0;
        while ($row_bs = mysqli_fetch_array($results_bs)){
          for ($i=1;$i<=12;$i++){
            if ($row_bs['bs_s'.$i]>0 && $row_bs['bs_s'.$i.'name']!="None" && $row_bs['bs_o'.$i]=="PM Snack"){
              $ps_order = $ps_order.$row_bs['bs_s'.$i.'name']." (".$row_bs['bs_s'.$i].") ";
            }
          } 
        }

        $results_sn = mysqli_query($db, "SELECT * FROM sn_reservation WHERE order_number=$order_number");
        $j=0;
        while ($row_sn = mysqli_fetch_array($results_sn)){
          for ($i=1;$i<=23;$i++){
            if ($row_sn['sn_s'.$i]>0 && $row_sn['sn_s'.$i.'name']!="None" && $row_sn['sn_o'.$i]=="PM Snack"){
              $ps_order = $ps_order.$row_sn['sn_s'.$i.'name']." (".$row_sn['sn_s'.$i].") ";
            }
          } 
        }

        echo $ps_order;
        ?>  
        </td>
      </tr>
      <tr>
        <td>Dinner:</td>
      </tr>
      <tr>
        <td><?php 
        $dn_order = "";
        $results_bs = mysqli_query($db, "SELECT * FROM bs_reservation WHERE order_number=$order_number");
        $j=0;
        while ($row_bs = mysqli_fetch_array($results_bs)){
          for ($i=1;$i<=12;$i++){
            if ($row_bs['bs_s'.$i]>0 && $row_bs['bs_s'.$i.'name']!="None" && $row_bs['bs_o'.$i]=="Dinner"){
              $dn_order = $dn_order.$row_bs['bs_s'.$i.'name']." (".$row_bs['bs_s'.$i].") ";
            }
          } 
        }

        $results_ch = mysqli_query($db, "SELECT * FROM ch_reservation WHERE order_number=$order_number");
        $j=0;
        while ($row_ch = mysqli_fetch_array($results_ch)){
          for ($i=1;$i<=18;$i++){
            if ($row_ch['ch_s'.$i]>0 && $row_ch['ch_s'.$i.'name']!="None" && $row_ch['ch_o'.$i]=="Dinner"){
              $dn_order = $dn_order.$row_ch['ch_s'.$i.'name']." (".$row_ch['ch_s'.$i].") ";
            }
          } 
        }

        $results_p = mysqli_query($db, "SELECT * FROM p_reservation WHERE order_number=$order_number");
        $j=0;
        while ($row_p = mysqli_fetch_array($results_p)){
          for ($i=1;$i<=17;$i++){
            if ($row_p['p_s'.$i]>0 && $row_p['p_s'.$i.'name']!="None" && $row_p['p_o'.$i]=="Dinner"){
              $dn_order = $dn_order.$row_p['p_s'.$i.'name']." (".$row_p['p_s'.$i].") ";
            }
          } 
        }

        $results_f = mysqli_query($db, "SELECT * FROM f_reservation WHERE order_number=$order_number");
        $j=0;
        while ($row_f = mysqli_fetch_array($results_f)){
          for ($i=1;$i<=10;$i++){
            if ($row_f['f_s'.$i]>0 && $row_f['f_s'.$i.'name']!="None" && $row_f['f_o'.$i]=="Dinner"){
              $dn_order = $dn_order.$row_f['f_s'.$i.'name']." (".$row_f['f_s'.$i].") ";
            }
          } 
        }

        $results_v = mysqli_query($db, "SELECT * FROM v_reservation WHERE order_number=$order_number");
        $j=0;
        while ($row_v = mysqli_fetch_array($results_v)){
          for ($i=1;$i<=14;$i++){
            if ($row_v['v_s'.$i]>0 && $row_v['v_s'.$i.'name']!="None" && $row_v['v_o'.$i]=="Dinner"){
              $dn_order = $dn_order.$row_v['v_s'.$i.'name']." (".$row_v['v_s'.$i].") ";
            }
          } 
        }

        $results_d = mysqli_query($db, "SELECT * FROM d_reservation WHERE order_number=$order_number");
        $j=0;
        while ($row_d = mysqli_fetch_array($results_d)){
          for ($i=1;$i<=10;$i++){
            if ($row_d['d_s'.$i]>0 && $row_d['d_s'.$i.'name']!="None" && $row_d['d_o'.$i]=="Dinner"){
              $dn_order = $dn_order.$row_d['d_s'.$i.'name']." (".$row_d['d_s'.$i].") ";
            }
          } 
        }

        echo $dn_order;
        ?>  
        </td>
      </tr>
      <tr>
        <td>Midnight Snack:</td>
      </tr>
      <tr>
        <td><?php
        $ms_order = "";
        $results_bs = mysqli_query($db, "SELECT * FROM bs_reservation WHERE order_number=$order_number");
        $j=0;
        while ($row_bs = mysqli_fetch_array($results_bs)){
          for ($i=1;$i<=12;$i++){
            if ($row_bs['bs_s'.$i]>0 && $row_bs['bs_s'.$i.'name']!="None" && $row_bs['bs_o'.$i]=="Midnight Snack"){
              $ms_order = $ms_order.$row_bs['bs_s'.$i.'name']." (".$row_bs['bs_s'.$i].") ";
            }
          } 
        }

        $results_sn = mysqli_query($db, "SELECT * FROM sn_reservation WHERE order_number=$order_number");
        $j=0;
        while ($row_sn = mysqli_fetch_array($results_sn)){
          for ($i=1;$i<=23;$i++){
            if ($row_sn['sn_s'.$i]>0 && $row_sn['sn_s'.$i.'name']!="None" && $row_sn['sn_o'.$i]=="Midnight Snack"){
              $ms_order = $ms_order.$row_sn['sn_s'.$i.'name']." (".$row_sn['sn_s'.$i].") ";
            }
          } 
        }

        echo $ms_order;


        ?>  
        <!-- </td>
      </tr>
      <tr>
        <td>Total Cost: <?php echo "₱"; $total_cost = $row['sum'] * 70;
          echo $total_cost;
        ?>
        </td>
      </tr> -->
  </table>

      </div><!--End Table-->
     <center> 
      <div id="legalcopy">
        <p class="legal">
         <button onclick="myFunction()" id="print">Print Order</button></p></div>
      <br>
      <div id="illegalcopy" >
        <p class="legal">   
        <?php if ($_SESSION['type']=="Member"): ?>
        <a href="dashboard.php" id="home"><button>Home</button></a>
        <?php else: ?>
        <a href="dashboard_admin.php" id="home"><button>Home</button></a>
        <?php endif ?></p></div>
      </div>
      </center>
    </div><!--End InvoiceBot-->
  </div><!--End Invoice-->
</div><!-- End Invoice Holder-->
<script>
function myFunction() {
    window.print();
}
</script>
</body>
</html>
