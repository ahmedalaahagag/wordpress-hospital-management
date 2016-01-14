<?php
//This is Dashboard at admin side
$obj_user  = new Hmgtuser();
$obj_package  = new Hmgt_packages();
$obj_sesssion_duration  = new Hmgt_session_duration();
?>
<div class="panel-body" style="display: block;">
    <form name="income_form" action="" method="post" class="form-horizontal" id="income_form">
        <div class="form-group">
            <label class="col-sm-2 control-label" for="patient"><?php _e('Patient','hospital_mgt');?><span class="require-field"></span></label>
            <div class="col-sm-8">
                <?php if($edit){ $patient_id1=$result->party_name; }elseif(isset($_POST['patient'])){$patient_id1=$_POST['patient'];}else{ $patient_id1="";}?>
                <select name="party_name" class="form-control validate[]" id="patient">
                    <option value=""><?php _e('Select Patient','hospital_mgt');?></option>
                    <?php
                    $patients = hmgt_patientid_list();
                    //print_r($patient);
                    if(!empty($patients))
                    {
                        foreach($patients as $patient)
                        {
                            echo '<option value="'.$patient['id'].'" '.selected($patient_id1,$patient['id']).'>'.$patient['patient_id'].' - '.$patient['first_name'].' '.$patient['last_name'].'</option>';
                        }
                    }?>
                </select>*change patient to calculate his invoice
                <br><br>
                <button type="button" class="btn btn-success" id="calculate">Calculate Invoice</button>
            </div>
        </div>
        <script type="text/javascript">
            jQuery("#calculate").click(function() {
                if($("#patient").val())
                {
                $("#invoice").hide();
                $("#invoice").show();
                }
            });

        </script>

        <div  id="invoice" style="display: none;">
            <div class="form-group">

            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="invoice_date"><?php _e('Date','hospital_mgt');?><span class="require-field"></span></label>
                <div class="col-sm-8">
                    <input id="invoice_date" class="form-control " type="text"  value="<?php if($edit){ echo $result->income_create_date;}elseif(isset($_POST['invoice_date'])){ echo $_POST['invoice_date'];}else{ echo date("Y-m-d");}?>" name="invoice_date">
                </div>
            </div>
            <hr>

            <?php

            $session_price = $obj_sesssion_duration->get_duration_price($session->duration_id);
            $session_mins = $obj_sesssion_duration->get_duration_mins($session->duration_id);
            $session_discount =  $obj_sesssion_duration->has_discount(3);
            $sessions_total += $session_price;
            ?>
            <h2>Paid Sessions</h2>
            <div id="sesssion_entry_paid">
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="income_entry"><?php _e('Session Entry','hospital_mgt');?><span class="require-field"></span></label>
                    <div class="col-sm-4">
                        <input id="package_name" class="form-control validate[] text-input" type="text" readonly value="New Treatment Packages" name="[]">
                    </div>
                    <div class="col-sm-2">
                        <input id="session_duration" class="form-control validate[] text-input" type="text" readonly value="60 Mins" name="[]">
                    </div>
                    <div class="col-sm-2">
                        <input id="session_price" class="form-control validate[] text-input" type="text" readonly value="100" name="[]">
                    </div>


                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="income_entry"><?php _e('Session Entry','hospital_mgt');?><span class="require-field"></span></label>
                    <div class="col-sm-4">
                        <input id="package_name" class="form-control validate[] text-input" type="text" readonly value="New Treatment Packages" name="[]">
                    </div>
                    <div class="col-sm-2">
                        <input id="session_duration" class="form-control validate[] text-input" type="text" readonly value="45 Mins" name="[]">
                    </div>
                    <div class="col-sm-2">
                        <input id="session_price" class="form-control validate[] text-input" type="text" readonly value="50" name="[]">
                    </div>


                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="income_entry"><?php _e('Session Entry','hospital_mgt');?><span class="require-field"></span></label>
                    <div class="col-sm-4">
                        <input id="package_name" class="form-control validate[] text-input" type="text" readonly value="New Treatment Packages" name="[]">
                    </div>
                    <div class="col-sm-2">
                        <input id="session_duration" class="form-control validate[] text-input" type="text" readonly value="35 Mins" name="[]">
                    </div>
                    <div class="col-sm-2">
                        <input id="session_price" class="form-control validate[] text-input" type="text" readonly value="150" name="[]">
                    </div>


                </div>

                <div id="discount" class="form-group">
                    <label class="col-sm-2 control-label" for="invoice_date"><?php _e('Discount','hospital_mgt');?><span class="require-field"></span></label>
                    <div class="col-sm-8">
                        <input id="invoice_date" class="form-control " type="text"  value="0%" name="invoice_date">
                    </div>
                </div>
                <hr>
        </div>

    </form>
    <h2 id="UnpaidTitle">Unpaid Sessions</h2>
    <div id="sesssion_entry_unpaid">
        <div class="form-group" id="unpaid_1">
            <label class="col-sm-2 control-label" for="income_entry"><?php _e('Session Entry','hospital_mgt');?><span class="require-field"></span></label>
            <div class="col-sm-4">
                <input id="package_name_1" class="form-control validate[] text-input" type="text" readonly value="New Treatment Packages" name="[]">
            </div>
            <div class="col-sm-2">
                <input id="session_duration_1" class="form-control validate[] text-input" type="text" readonly value="60 Mins" name="[]">
            </div>
            <div class="col-sm-2">
                <input id="session_price_1" class="form-control validate[] text-input" type="text" readonly value="100" name="[]">
            </div>
            <button type="button" class="btn btn-success" id="Paid_1">Paid</button>
        </div>
        <script>
            $("#Paid_1").on('click',function(){
                var r = confirm("Are you sure you want to pay this ?");
                if(r == true){
                $(this).remove();
                var clone = $("#unpaid_1").html();
                var total = $("#invoice_total").val();
                var session = $("#session_price_1").val();
                $("#unpaid_1").remove();
                total = parseInt(total) + parseInt(session);
                $("#invoice_total").val(total);
                $("#discount").before(clone+"<br><br><br>");
                }
            });
        </script>
        <div class="form-group" id="unpaid_2">
            <label class="col-sm-2 control-label" for="income_entry"><?php _e('Session Entry','hospital_mgt');?><span class="require-field"></span></label>
            <div class="col-sm-4">
                <input id="package_name_2" class="form-control validate[] text-input" type="text" readonly value="New Treatment Packages" name="[]">
            </div>
            <div class="col-sm-2">
                <input id="session_duration_2" class="form-control validate[] text-input" type="text" readonly value="45 Mins" name="[]">
            </div>
            <div class="col-sm-2">
                <input id="session_price_2" class="form-control validate[] text-input" type="text" readonly value="50" name="[]">
            </div>
            <button type="button" class="btn btn-success" id="Paid_2">Paid</button>
            <script>
                $("#Paid_2").on('click',function(){
                    var r = confirm("Are you sure you want to pay this ?");
                    if(r == true) {
                        $(this).remove();
                        var clone = $("#unpaid_2").html();
                        var total = $("#invoice_total").val();
                        var session = $("#session_price_2").val();
                        $("#unpaid_2").remove();
                        total = parseInt(total) + parseInt(session);
                        $("#invoice_total").val(total);
                        $("#discount").before(clone + "<br><br><br>");
                    }
                });
            </script>

        </div>
        <div class="form-group" id="unpaid_3">
            <label class="col-sm-2 control-label" for="income_entry"><?php _e('Session Entry','hospital_mgt');?><span class="require-field"></span></label>
            <div class="col-sm-4">
                <input id="package_name_3" class="form-control validate[] text-input" type="text" readonly value="New Treatment Packages" name="[]">
            </div>
            <div class="col-sm-2">
                <input id="session_duration_3" class="form-control validate[] text-input" type="text" readonly value="35 Mins" name="[]">
            </div>
            <div class="col-sm-2">
                <input id="session_price_3" class="form-control validate[] text-input" type="text" readonly value="150" name="[]">
            </div>
            <button type="button" class="btn btn-success" id="Paid_3">Paid</button>
            <script>
                $("#Paid_3").on('click',function(){
                    var r = confirm("Are you sure you want to pay this ?");
                    if(r == true) {
                        $(this).remove();
                        var clone = $("#unpaid_3").html();
                        var total = $("#invoice_total").val();
                        var session = $("#session_price_3").val();
                        $("#unpaid_3").remove();
                        total = parseInt(total) + parseInt(session);
                        $("#invoice_total").val(total);
                        $("#discount").before(clone + "<br><br><br>");
                        $("#UnpaidTitle").remove();
                        $("#unpaiddiscount").remove();
                    }
                });
            </script>
        </div>
        <div class="form-group" id="unpaiddiscount">
            <label class="col-sm-2 control-label" for="invoice_date"><?php _e('Discount','hospital_mgt');?><span class="require-field"></span></label>
            <div class="col-sm-8">
                <input id="invoice_date" class="form-control " type="text"  value="0%" name="invoice_date">
            </div>
        </div>
        <hr>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="invoice_date"><?php _e('Total paid','hospital_mgt');?><span class="require-field"></span></label>
            <div class="col-sm-8">
                <input id="invoice_total" class="form-control " type="text"  value="300" name="invoice_date">
            </div>
        </div>

        </form>
    </div>
</div>
<script>




    // CREATING BLANK INVOICE ENTRY
    var blank_income_entry ='';
    $(document).ready(function() {
        blank_income_entry = $('#income_entry').html();
        //alert("hello" + blank_invoice_entry);
    });

    function add_entry()
    {
        $("#income_entry").append(blank_income_entry);
        //alert("hellooo");
    }

    // REMOVING INVOICE ENTRY
    function deleteParentElement(n){
        n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
    }
</script>
