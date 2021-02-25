
<div class="row">
<div class="col">
  <div class="row">
    <div class="col" for="o-total_number_options">Total # Options</div>
    <div class="col">
      <a href="#" data-table="re_options" data-name="total_number_options" class="number edit" data-type="text" data-value="<?=$options['total_number_options']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="o-number_options_exercised"># Options Exercised</div>
    <div class="col">
      <a href="#" data-table="re_options" data-name="number_options_exercised" class="number edit" data-type="text" data-value="<?=$options['number_options_exercised']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="o-option_period">Option Period (Length in years)</div>
    <div class="col">
      <a href="#" data-table="re_options" data-name="option_period" class="number edit" data-type="text" data-value="<?=$options['option_period']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="o-min_sales_volume_required">Min Sales Vol Required?</div>
    <div class="col">
      <a href="#" data-table="re_options" data-name="min_sales_volume_required" class="edit" data-type="select" data-source="[{value:1,text:'Yes'},{value:0,text:'No'}.{value:'',text:''}]" data-value="<?=$options['min_sales_volume_required']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="o-min_sales_volume">Min Sales Volume</div>
    <div class="col">
      <a href="#" data-table="re_options" data-name="min_sales_volume" class="edit-money" data-type="text" data-value="<?=$options['min_sales_volume']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="o-min_sales_period_ends">Min Sales Vol Period Ends</div>
    <div class="col">
      <a href="#" data-table="re_options" data-name="min_sales_period_ends" class="edit-date" data-type="date" data-value="<?=$options['min_sales_period_ends']?>"></a>
    </div>
  </div>
</div>
<div class="col">
  <div class="row">
    <div class="col" for="o-sales_status">Sales Status</div>
    <div class="col">
      <a href="#" data-table="re_options" data-name="sales_status" class="string edit" data-type="text" data-value="<?=$options['sales_status']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="o-option_notice_period">Option Notice Period</div>
    <div class="col">
      <a href="#" data-table="re_options" data-name="option_notice_period" class="string edit" data-type="text" data-value="<?=$options['option_notice_period']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="o-first_day_option_notice_period">First Day of Option Notice Period</div>
    <div class="col">
      <a href="#" data-table="re_options" data-name="first_day_option_notice_period" class="edit-date" data-type="date" data-value="<?=$options['first_day_option_notice_period']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="o-last_day_option_notice_period">Last Day of Option Notice Period</div>
    <div class="col">
      <a href="#" data-table="re_options" data-name="last_day_option_notice_period" class="edit-date" data-type="date" data-value="<?=$options['last_day_option_notice_period']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="o-option_comments">Option Comments</div>
    <div class="col">
      <a href="#" data-table="re_options" data-name="option_comments" data-type="textarea" class="edit" data-value="<?=$options['option_comments']?>"></a>
    </div>
  </div>
</div>
</div>