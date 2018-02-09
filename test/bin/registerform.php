<form class="form-horizontal">
<fieldset>

<!-- Form Name -->

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="FirstName">Full Name</label>  
  <div class="col-md-5">
  <input id="Fname" name="Fname" type="text" placeholder="Jenny" class="form-control input-md" required="" value="<?php echo $Fname; ?>">
  <?php if ($errFname!=""){ echo "<span class='text-danger'>$errFname</span>";} ?>
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="Phone">Contact Number</label>  
  <div class="col-md-4">
  <input id="Phone" name="Phone" type="text" placeholder="0401 234 567" class="form-control input-md" required="" value="<?php echo $Phone; ?>">
  <?php if ($errPhone!=""){ echo "<span class='text-danger'>$errPhone</span>";} ?>
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="OrgName">Organisation Name</label>  
  <div class="col-md-5">
  <input id="OrgName" name="OrgName" type="text" placeholder="Your church/ministry name" class="form-control input-md" required="" value="<?php echo $OrgName; ?>">
  <?php if ($errOrgName!=""){ echo "<span class='text-danger'>$errOrgName</span>";} ?>
  </div>
</div>

<!-- Select State  -->
<div class="form-group">
  <label class="col-md-4 control-label" for="SelectState">State</label>
  <div class="col-md-2">
    <select id="SelectState" name="SelectState" class="form-control">
      <option value="NSW">NSW</option>
      <option value="ACT">ACT</option>
      <option value="VIC">VIC</option>
      <option value="QLD">QLD</option>
      <option value="WA">WA</option>
      <option value="SA">SA</option>
      <option value="TAS">TAS</option>
      <option value="NT">NT</option>
      <option value="NZ">NZ</option>
      <option value="Other">Other</option>
    </select>
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" for="checkboxes"><a href="http://safeministrytraining.com.au/terms-conditions/" target="_blank">Terms and Conditions</a></label>
  <div class="col-md-6">
  <div class="checkbox">
    <label for="checkboxes-0">
      <input type="checkbox" name="TandCs" id="TandCs" required>
      I agree to the SafeMinistryTraining.com.au <a href="http://safeministrytraining.com.au/terms-conditions/" target="_blank">Terms and Conditions</a> on behalf of this organisation
    </label>
		<?php if ($errTandCs!=""){ echo "<span class='text-danger'>$errTandCs</span>";} ?>
	</div>
  </div>
</div>

</fieldset>
</form>