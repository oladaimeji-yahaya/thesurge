$(document).ready(function(){

//Deposit Amount Conversion
$("#withdrawal_amount").keyup(function(e){
        e.preventDefault();
        var conv_amount = $("#withdrawal_amount").val();
        if(isNaN(conv_amount) || conv_amount <= 0 || conv_amount == ''){
          $("#withdraw_amount_btc").val(0);
          return false;
        }
        else{
          $.ajax({
            url: "assets/includes/validation.php",
          method: "post",
          data: "conv_amount=" + conv_amount,
          dataType: "text",
          cache: false,
          success:function(value){
            $("#withdraw_amount_btc").val(value);
          }
        });
          
        }
      });

//To Date For History

  $("#from_calendar").change(function(e){
    e.preventDefault();
     Date.prototype.toInputFormat = function() {
       var yyyy = this.getFullYear().toString();
       var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based
       var dd  = this.getDate().toString();
       return yyyy + "-" + (mm[1]?mm:"0"+mm[0]) + "-" + (dd[1]?dd:"0"+dd[0]); // padding
    };
    $('#to_calendar').val('');
    var date = new Date($('.date-from').val());
    var min_date = date.setDate(date.getDate() + 1);
    $('#to_calendar').attr({'min':date.toInputFormat(), 'disabled': false});
  });
  

  $("#psw_change").submit(function(e){
    e.preventDefault();
    var password = $("#password").val();
    var confirm_password = $("#confirm_password").val();
    if(password != confirm_password){
        toastr.error("Passwords Do Not Match!", "Error!");
        return false;
    }
        $("#psw_btn").html("Updating....");
        $("#psw_btn").attr("disabled", true);
        $.ajax({
            url: "assets/includes/validation.php",
            method: "post",
            data: $("#psw_change").serialize(),
            dataType: "text",
            success: function(security){
              var security = $.trim(security);
              if(security == "success")
              {
              $("#psw_change").trigger('reset');
              $("#psw_btn").html("Change Password");
              $("#psw_btn").attr("disabled", false);
              toastr.success("Password has been Updated!", "Success!");
              }
              else if(security == "incorrect"){
              $("#psw_btn").html("Change Password");
              $("#psw_btn").attr("disabled", false);
              toastr.error("Current Password is wrong, Please try Again!", "Error!");
              }
              else{
              $("#psw_btn").html("Change Password");
              $("#psw_btn").attr("disabled", false);
              toastr.error("Something Went Wrong, Please try Again!", "Error!");
              }
            }
        });
  });

  //wallet Address

    $("#wallet_change").submit(function(e){
    e.preventDefault();
    var wallet_address = $("#wallet_address").val();
    $("#wallet_btn").html("Updating....");
        $("#wallet_btn").attr("disabled", true);
        var data = "wallet_address=" + wallet_address;
        $.ajax({
            url: "assets/includes/validation.php",
            method: "post",
            data: data,
            dataType: "text",
            success: function(wallet_upd){
              var wallet_upd = $.trim(wallet_upd);
              if(wallet_upd == "success")
              {
              $("#wallet_address").val('');
              $("#wallet_btn").html("Update Wallet");
              $("#wallet_btn").attr("disabled", false);
              toastr.success("Wallet Address has been Updated!", "Success!");
              }
              else if(wallet_upd == "failed")
              {
              $("#wallet_btn").html("Update Wallet");
              $("#wallet_btn").attr("disabled", false);
              toastr.error("Something Went Wrong, Please try Again!", "Error!");
              }
            }
        });
  });

 //Profile Editing
  $("#user_profile").submit(function(e){
    e.preventDefault();
    var phone_number = $("#phone_number").val();
      var data = "phone_number=" + phone_number;
      if (isNaN(phone_number)) {
        toastr.error("Wrong Number Format!", "Error!");
        return false;
      }
        $("#phone_btn").html("Updating....");
        $("#phone_btn").attr("disabled", true);
        $.ajax({
          url: 'assets/includes/validation.php',
          method: 'post',
          data: data,
          dataType: 'text',
          cache: false,
          success: function(edited){
            var edited = $.trim(edited);
            if(edited == "success"){
              $("#phone_btn").html("Update Profile");
              $("#phone_btn").attr("disabled", false);
              toastr.success("Phone Number has been Updated!", "Success!");
               }
            else if(edited == "failed"){
              $("#phone_btn").html("Update Profile");
              $("#phone_btn").attr("disabled", false);
              toastr.error("Failed to UpdatePhone Number!", "Error!");
            }
          }
        });
  });

// Deposit Handler
$("#deposit_funds").submit(function(e){
    e.preventDefault();
    var method = $("#pay_method").val();
    var amount = $("#deposit_amount").val();
      $("#submit_request").attr('disabled', true);
      $("#submit_request").html("<i class='fa fa-plus'></i> Submitting...");
      var data = 'deposit_amount=' + amount + '&payment_option=' + method + "&allow_btc_txn="+'allow';
      $.ajax({
        url: 'assets/includes/validation.php',
        method: 'post',
        data: data,
        dataType: 'json',
        success:function(requests){
           if(method == 'btc'){
            var payment_address = $.trim(requests.btc_txn_address);
            var payment_amount = $.trim(requests.amount);
            if(requests.message == 'paymentok'){
              toastr.success("Payment Adress has been Generated!", "Success!");
              $("#pay_method").val('');
              $("#deposit_amount").val('');
              $("#submit_request").attr('disabled', false);
              $("#submit_request").html("<i class='fa fa-plus'></i> Process");
              $("#wallet_img").html('<img src="../images/'+method+'.png">');
              $('#wallet_addr').html('<div class="input-group"><input type="text" class="form-control" value="'+payment_address+'" id="copy_addr" readonly><div class="input-group-append"><button class="btn btn-danger btn-outline-secondary" id="copy_btn" data-clipboard-target="#copy_addr" type="button">Copy Address</button></div></div><br>');
              $('#qrcode').html('<img alt="Please Wait" src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=bitcoin:'+payment_address+'?amount='+payment_amount+'&choe=UTF-8"; /><br><br>');
          }
          }
          

        }
});

  });

//Withdraw Handler
  $("#withdraw_funds").submit(function(withdraw){
    withdraw.preventDefault();
    var withdrawal_amount = $("#withdrawal_amount").val();
    var wallet = $("#wallet").val();
    $("#withdraw_btn").attr("disabled",true);
    $("#withdraw_btn").html("Processing...");
      var data = "withdrawal_amount=" + withdrawal_amount + '&wallet=' + wallet;
      $.ajax({
        url: "assets/includes/validation.php",
        method: "post",
        data: data,
        dataType: "text",
        cache: false,
        success: function(msg){
          if(msg == "success"){
              $("#withdraw_btn").attr("disabled", false);
              $("#withdraw_btn").html("<i class='fa fa-bell'> </i>PROCESS WITHDRAWAL");
              toastr.success("Withdrawal request is pending approval", "Success");
              $("#withdraw_funds")[0].reset();  
            }
          else if(msg == "failed"){
              $("#withdraw_btn").attr("disabled", false);
              $("#withdraw_btn").html("<i class='fa fa-bell'> </i>PROCESS WITHDRAWAL"); 
              toastr.error("Withdrawal cannot be done until possible withdrawal date is reached", "Failed");
          }
          else if(msg == "over"){
              $("#withdraw_btn").attr("disabled", false);
              $("#withdraw_btn").html("PROCESS WITHDRAW"); 
              toastr.error("You have Exceeded withdrawal amount", "Failed");
          }
          else if(msg == "btc_notavail"){
              $("#withdraw_btn").attr("disabled", false);
              $("#withdraw_btn").html("<i class='fa fa-bell'> </i>PROCESS WITHDRAWAL");
              toastr.error("Provide a wallet address and try again", "Failed");
              
          }
        }
      });
  });

      //Update User Profile
    function populate_dashboard(){
     var pass = "balance_page=" + 'view';
    $.ajax({
      url: "assets/includes/validation.php",
      method: 'post',
      data: pass,
      dataType: 'json',
      cache: false,
      success:function(value){
        $("#account_balance").html("$"+value.account_balance);
        $("#curr_inv").html("$"+value.curr_inv);
        $("#last_deposit").html("$"+value.last_deposit);
        $("#last_withdrawal").html("$"+value.last_withdrawal);
        $("#today_profit").html("$"+value.today_profit);
        $("#avail_with").html("$"+value.avail_with);
        $("#total_profit").html("$"+value.total_profit);
        // $("#ti_profit").html("as of "+value.ti_profit);
        // $("#ti_deposit").html("as of "+value.ti_deposit);
        // $("#ti_amount").html("as of "+value.ti_amount);
        // $("#ti_withdraw").html("as of "+value.ti_withdraw);
      }

    });
}
  populate_dashboard();

//View Profile Handler
    //Update User Profile
    function populate_profile(){
    //Fetch User Profile Data
    if($("#identifier").val() === "p&$page"){
     var pass = "view_page=" + 'view';
    $.ajax({
      url: "assets/includes/validation.php",
      method: 'post',
      data: pass,
      dataType: 'json',
      cache: false,
      success:function(view){
        $("#fullname").val(view.fullname);
        $("#phone_number").val(view.phone_number);
        $("#email").val(view.email);
        $("#plan").val(view.package);
        $("#referral").val(view.referral);
         $("#btc_address").val(view.btc_address);
      }

    });
}
  }
  populate_profile();

// My Referral List Population
if($("#identifier").val() === "re&$page"){

  var query = 'referral_request=' + 'request_now';
  $.ajax({
  url: 'assets/includes/validation.php',
  method: 'post',
  data: query,
  dataType: 'json',
  success: function(referral_requested){
  // alert(referral_requested);

     var length = referral_requested.length;
       if(length > 0){
          var total = 0;
          for (var i=0; i<length; i++){
          var id = i+1;
            var row = $('<tr><td>'+id+'</td><td>'+referral_requested[i].fullname+'</td><td>'+referral_requested[i].date_joined+'</td><td>5%</td><td>'+referral_requested[i].bonus+' USD'+'</td></tr>');
                      $('#table_ref').append(row);
                      total = total + referral_requested[i].bonus;
                    }
                     var row = $('<tr><td>'+'</td><td>'+'</td><td>'+'</td><td>'+'</td><td style="text-align:left; color: #4cc6e0;">Total :: '+total.toFixed(2)+' USD'+'</td></tr>');
                      $('#table_ref').append(row);
                  }
                  else if(referral_requested.msg == 'failed'){
                    var row = $('<tr><td colspan="6" style="text-align:center; color: #d9534f;">No Referrals</td></tr>');
                      $('#table_ref').append(row);
                  }

                }

              });

              }


  //GET SPONSOR

  if($("#identifier").val() === "re&$page"){

  var query = 'sponsor_request=' + 'request_now';
  $.ajax({
  url: 'assets/includes/validation.php',
  method: 'post',
  data: query,
  dataType: 'json',
  success: function(sponsor_requested){
  // alert(sponsor_requested);

     var length = sponsor_requested.length;
       if(length > 0){
          var total = 0;
          for (var i=0; i<length; i++){
          var id = i+1;
            var row = $('<tr><td>'+sponsor_requested[i].sponsor_fullname+'</td><td>'+sponsor_requested[i].sponsor_date_joined+'</td></tr>');
                      $('#sponsor_table').append(row);
                    }
                  }
                   if(sponsor_requested.sponsor_msg == 'failed'){
                    var row = $('<tr><td colspan="2" style="text-align:center; color: #d9534f;">No Sponsor</td></tr>');
                      $('#sponsor_table').append(row);
                  }

                }

              });

              }
 
  //History Query
  $("#from_calendar").change(function(e){
    e.preventDefault();
  function getdate() {
    var tt = $("#from_calendar").val();

    var date = new Date(tt);
    var newdate = new Date(date);

    newdate.setDate(newdate.getDate() + 1);
    
    var dd = newdate.getDate();
    var mm = newdate.getMonth() + 1;
    var y = newdate.getFullYear();
    //Appending zero to date
    if(mm >= 0 && mm <= 10){
      mm = '0'+mm;
    }
    if(dd >= 0 && dd <= 10){
      dd = '0'+dd;
    }

    var min_to = y + '-' + mm + '-' + dd;
    //reset to-date
    $("#to_calendar").attr('disabled', false);
    $("#to_calendar").attr('min', min_to);
}
getdate();

  });

$("#hist_form").submit(function(e){
  e.preventDefault();
    //Loader Set
    $("#history_btn").html("Searching <i class='mdi mdi-spin mdi-loading'></i>");
    $("#history_btn").attr("disabled", true);
    //re-assign
    var identifier = $("#identifier").val();
    var page_tag;
    if(identifier == 'wh&$page'){
        page_tag = 'wh';
    }
    else if(identifier == 'th&$page'){
      page_tag = 'th';
    }
    else if(identifier == 'dh&$page'){
      page_tag = 'dh';
    }
    else if(identifier == 'bh&$page'){
      page_tag = 'bh';
    }
      
      $.ajax({
       url: "assets/includes/tbl.php",
       method: "post",
       data: $("#hist_form").serialize()+"&page_tag=" + page_tag,
       dataType: "json",
       cache: false,
       success: function(status){
        $("#history_btn").attr("disabled", false);
        $("#history_btn").html("<i class='mdi mdi-database-search'></i> Search Records");
        $('#hist_tbl').html('');
        // $("#hist_form")[0].reset(); 
         if (status.date != 'null') {
        var len = status.length;
           for (var i = 0; i<len; i++) {
          var id = i+1;
          if (page_tag == 'wh') {
            var row = $('<tr><td>'+id+'</td><td>'+status[i].date+'</td><td>'+status[i].amount+'</td><td>'+status[i].btc_withdrawal_address+'</td><td>'+status[i].status+'</td></tr>');
                    $('#hist_tbl').append(row);
                  } 
                  else if (page_tag == 'dh') {
                    var row = $('<tr><td>'+id+'</td><td>'+status[i].date+'</td><td>'+status[i].amount+'</td></tr>');
                    $('#hist_tbl').append(row);
                  }
                  else if (page_tag == 'th') {
                    var row = $('<tr><td>'+id+'</td><td>'+status[i].date+'</td><td>'+status[i].amount+'</td><td>'+status[i].profit+'</td><td>'+status[i].return+'</td></tr>');
                    $('#hist_tbl').append(row);
                  }
        }
      }if (status.msg == 'failed') {
            var row = $('<tr><td colspan="5" style="text-align:center; color: #d9534f;">No Records Available</td></tr>');
                      $('#hist_tbl').append(row);
            
          }
       }
      });
});


var clipboard = new ClipboardJS('#copy_btn');

    clipboard.on('success', function(e) {
        toastr.success("Copied", "Wallet address copied to clipboard");
    });

    clipboard.on('error', function(e) {
        toastr.error("Failed", "Failed to copy address");
    });


          
});