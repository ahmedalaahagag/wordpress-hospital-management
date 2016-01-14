<div id="addtimeslot_dialog" title="Add/Edit TimeSlot" class="wrapper" style="display:none;z-index:5000">
            <form id="frmaddtimeslot_popup" method="post" action="" novalidate="novalidate">
            <table style="width:100%;" >
              <tr>
                <td><?php _e("Time Slot Name","appointgen_ustsappointment"); ?></td>
                <td><input type="text" name="timeslot_name" id="timeslot_name" value="" /> </td>
              </tr>
              <tr>
                <td><?php _e("Start Time","appointgen_ustsappointment"); ?></td>
                <td>
                	<input type="text" name="starttime" id="starttime" value="" /> <br>
                  <span class="timeslot_note"><?php _e("example: 09:30:00 (time in 24 hr format)","appointgen_ustsappointment"); ?></span>	
                </td>
              </tr>
              <tr>
                <td><?php _e("End Time","appointgen_ustsappointment"); ?></td>
                <td>
                	<input type="text" name="endtime" id="endtime" value="" /> <br>
                  <span class="timeslot_note"><?php _e("example: 20:30:00 (time in 24 hr format)","appointgen_ustsappointment"); ?></span>
                </td>
              </tr>
              <tr>
                <td><?php _e("Time Interval","appointgen_ustsappointment"); ?></td>
                <td>
                	<input type="text" name="timeinterval" id="timeinterval" value="" /> <br>
                	<span class="timeslot_note"><?php _e("Time interval must be in Minute","appointgen_ustsappointment"); ?></span>
                </td>
              </tr>
              <tr>
                <td></td>
                <td>
                	<input type="submit" id="btnaddtimeslot" name="btnaddtimeslot" value="Add Timeslot" style="width:150px;"/>
                  <input type="hidden" id="hdntimeslotid" name="hdntimeslotid" value="" style="width:150px;"/>
                </td>
              </tr>
            </table>
            </form>
        </div>