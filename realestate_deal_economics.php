<div class="row">
<div class="col">

  <div class="row header-row">
    <div class="col">Year 1 Capital Expenditures</div>
    <div class="col">Total</div>
    <div class="col">PSF</div>
  </div>

  <div class="row">
    <div class="col">CapEx Gross</div>
    <div class="col"><a href="#" data-type="text" data-table="realestate" data-name="capex_y1_total" class="edit-money"><?=$realestate['capex_y1_total'];?></a></div>
    <div class="col"><a href="#" data-type="text" data-table="realestate" data-name="capex_y1_psf" class="edit-money"><?=$realestate['capex_y1_psf'];?></a></div>
  </div>

  <div class="row">
    <div class="col">Tenant Allowance</div>
    <div class="col"><a href="#" data-type="text" data-table="realestate" data-name="tenant_allowance_y1_total" class="edit-money"><?=$realestate['tenant_allowance_y1_total'];?></a></div>
    <div class="col"><a href="#" data-type="text" data-table="realestate" data-name="tenant_allowance_y1_psf" class="edit-money"><?=$realestate['tenant_allowance_y1_psf'];?></a></div>
  </div>
  <p>&nbsp;</p>  

  <div class="row header-row">    
    <div class="col">Current Occupancy Costs</div>
  </div>

  <div class="row header-row">
    <div class="col"></div>
    <div class="col">Annual Amount</div>
    <div class="col">Cost PSF</div>
  </div>

  <div class="row">
    <div class="col">Base Rent</div>
    <div class="col">
      <a href="#" data-table="re_deal_economics" data-name="cur_base_rent_amt" data-type="text" class="edit-money" data-value="<?=$deal_economics['cur_base_rent_amt']?>"></a>
    </div>
    <div class="col">
      <a href="#" data-table="re_deal_economics" data-name="cur_base_rent_psf" data-type="text" class="edit-money" data-value="<?=$deal_economics['cur_base_rent_psf']?>"></a>
    </div>
  </div>

  <div class="row">
    <div class="col">% Rent</div>
    <div class="col">
      <a href="#" data-table="re_deal_economics" data-name="cur_pct_rent_amt" data-type="text" class="edit-money" data-value="<?=$deal_economics['cur_pct_rent_amt']?>"></a>
    </div>
    <div class="col">
      <a href="#" data-table="re_deal_economics" data-name="cur_pct_rent_psf" data-type="text" class="edit-money" data-value="<?=$deal_economics['cur_pct_rent_psf']?>"></a>
    </div>
  </div>

  <div class="row">
    <div class="col">Base Rent Comments</div>
    <div class="col">
      <a href="#" data-table="re_deal_economics" data-name="cur_base_rent_comments" data-type="textarea" class="edit" data-value="<?=$deal_economics['cur_base_rent_comments']?>"></a>
    </div>
    <div class="col"></div>
  </div>

  <div class="row">
    <div class="col">RE Taxes</div>
    <div class="col">
      <a href="#" data-table="re_deal_economics" data-name="cur_re_taxes_amt" data-type="text" class="edit-money" data-value="<?=$deal_economics['cur_re_taxes_amt']?>"></a>
    </div>
    <div class="col">
      <a href="#" data-table="re_deal_economics" data-name="cur_re_taxes_psf" data-type="text" class="edit-money" data-value="<?=$deal_economics['cur_re_taxes_psf']?>"></a>
    </div>
  </div>

  <div class="row">
    <div class="col">Electric</div>
    <div class="col">
      <a href="#" data-table="re_deal_economics" data-name="cur_electric_amt" data-type="text" class="edit-money" data-value="<?=$deal_economics['cur_electric_amt']?>"></a>
    </div>
    <div class="col">
      <a href="#" data-table="re_deal_economics" data-name="cur_electric_psf" data-type="text" class="edit-money" data-value="<?=$deal_economics['cur_electric_psf']?>"></a>
    </div>
  </div>

  <div class="row">
    <div class="col">Water &amp; Sewer</div>
    <div class="col">
      <a href="#" data-table="re_deal_economics" data-name="cur_water_amt" data-type="text" class="edit-money" data-value="<?=$deal_economics['cur_water_amt']?>"></a>
    </div>
    <div class="col">
      <a href="#" data-table="re_deal_economics" data-name="cur_water_psf" data-type="text" class="edit-money" data-value="<?=$deal_economics['cur_water_psf']?>"></a>
    </div>
  </div>

  <div class="row">
    <div class="col">HVAC</div>
    <div class="col">
      <a href="#" data-table="re_deal_economics" data-name="cur_hvac_amt" data-type="text" class="edit-money" data-value="<?=$deal_economics['cur_hvac_amt']?>"></a>
    </div>
    <div class="col">
      <a href="#" data-table="re_deal_economics" data-name="cur_hvac_psf" data-type="text" class="edit-money" data-value="<?=$deal_economics['cur_hvac_psf']?>"></a>
    </div>
  </div>

  <div class="row">
    <div class="col">Trash</div>
    <div class="col">
      <a href="#" data-table="re_deal_economics" data-name="cur_trash_amt" data-type="text" class="edit-money" data-value="<?=$deal_economics['cur_trash_amt']?>"></a>
    </div>
    <div class="col">
      <a href="#" data-table="re_deal_economics" data-name="cur_trash_psf" data-type="text" class="edit-money" data-value="<?=$deal_economics['cur_trash_psf']?>"></a>
    </div>
  </div>

  <div class="row">
    <div class="col">Total Extras</div>
    <div class="col">
      <div data-table="re_deal_economics" data-name="cur_total_extras_amt"><?=$deal_economics['cur_total_extras_amt']?></div>
    </div>
    <div class="col">
      <div data-table="re_deal_economics" data-name="cur_total_extras_psf"><?=$deal_economics['cur_total_extras_psf']?></div>
    </div>
  </div>

  <div class="row">
    <div class="col">Total Occupancy</div>
    <div class="col">
      <div data-table="re_deal_economics" data-name="cur_total_occupancy_amt"><?=$deal_economics['cur_total_occupancy_amt']?></div>
    </div>
    <div class="col">
      <div data-table="re_deal_economics" data-name="cur_total_occupancy_psf"><?=$deal_economics['cur_total_occupancy_psf']?></div>
    </div>
  </div>

</div>
</div>

<div class="row">
<div class="col">

  <div class="row header-row">    
    <div class="col">New Occupancy Costs</div>
  </div>

  <div class="row header-row">
    <div class="col"></div>
    <div class="col">Annual Amount</div>
    <div class="col">Cost PSF</div>
    <div class="col">Breakpoint</div>
    <div class="col">% Rent</div>
  </div>

  <div class="row">
    <div class="col">Year 1</div>
    <div class="col">
      <a href="#" data-table="re_deal_economics" data-name="new_y1_annual_amt" data-type="text" class="edit-money" data-value="<?=$deal_economics['new_y1_annual_amt']?>"></a>
    </div>
    <div class="col">
      <a href="#" data-table="re_deal_economics" data-name="new_y1_cost_psf" data-type="text" class="edit-money" data-value="<?=$deal_economics['new_y1_cost_psf']?>"></a>
    </div>
    <div class="col">
      <a href="#" data-table="re_deal_economics" data-name="new_y1_breakpoint" data-type="text" class="edit-money" data-value="<?=$deal_economics['new_y1_breakpoint']?>"></a>
    </div>
    <div class="col">
      <a href="#" data-table="re_deal_economics" data-name="new_y1_percent_rent" data-type="text" class="edit-percent" data-value="<?=$deal_economics['new_y1_percent_rent']?>"></a>
    </div>
  </div>

  <div class="row">
    <div class="col">% Rent</div>
    <div class="col">
      <a href="#" data-table="re_deal_economics" data-name="new_pct_rent_amt" data-type="text" class="edit-money" data-value="<?=$deal_economics['new_pct_rent_amt']?>"></a>
    </div>
    <div class="col">
      <a href="#" data-table="re_deal_economics" data-name="new_pct_rent_psf" data-type="text" class="edit-money" data-value="<?=$deal_economics['new_pct_rent_psf']?>"></a>
    </div>
    <div class="col"></div>
    <div class="col"></div>
  </div>

  <div class="row">
    <div class="col">Base Rent Comments</div>
    <div class="col">
      <a href="#" data-table="re_deal_economics" data-name="new_base_rent_comments" data-type="textarea" class="edit" data-value="<?=$deal_economics['new_base_rent_comments']?>"></a>
    </div>
    <div class="col"></div>
  </div>

</div>
</div>

<div class="row">
<div class="col">

  <div class="row header-row">
    <div class="col"></div>
    <div class="col">Annual Amount</div>
    <div class="col">Cost PSF</div>
    <div class="col">Increases on Extras</div>
  </div>


  <div class="row">
    <div class="col">RE Taxes</div>
    <div class="col">
      <a href="#" data-table="re_deal_economics" data-name="new_re_taxes_amt" data-type="text" class="edit-money" data-value="<?=$deal_economics['new_re_taxes_amt']?>"></a>
    </div>
    <div class="col">
      <a href="#" data-table="re_deal_economics" data-name="new_re_taxes_psf" data-type="text" class="edit-money" data-value="<?=$deal_economics['new_re_taxes_psf']?>"></a>
    </div>
    <div class="col">
      <a href="#" data-table="re_deal_economics" data-name="new_re_taxes_increases" data-type="text" class="edit" data-value="<?=$deal_economics['new_re_taxes_increases']?>"></a>
    </div>
  </div>

  <div class="row">
    <div class="col">Electric</div>
    <div class="col">
      <a href="#" data-table="re_deal_economics" data-name="new_electric_amt" data-type="text" class="edit-money" data-value="<?=$deal_economics['new_electric_amt']?>"></a>
    </div>
    <div class="col">
      <a href="#" data-table="re_deal_economics" data-name="new_electric_psf" data-type="text" class="edit-money" data-value="<?=$deal_economics['new_electric_psf']?>"></a>
    </div>
    <div class="col">
      <a href="#" data-table="re_deal_economics" data-name="new_electric_increases" data-type="text" class="edit" data-value="<?=$deal_economics['new_electric_increases']?>"></a>
    </div>
  </div>

  <div class="row">
    <div class="col">Water &amp; Sewer</div>
    <div class="col">
      <a href="#" data-table="re_deal_economics" data-name="new_water_amt" data-type="text" class="edit-money" data-value="<?=$deal_economics['new_water_amt']?>"></a>
    </div>
    <div class="col">
      <a href="#" data-table="re_deal_economics" data-name="new_water_psf" data-type="text" class="edit-money" data-value="<?=$deal_economics['new_water_psf']?>"></a>
    </div>
    <div class="col">
      <a href="#" data-table="re_deal_economics" data-name="new_water_increases" data-type="text" class="edit" data-value="<?=$deal_economics['new_water_increases']?>"></a>
    </div>
  </div>

  <div class="row">
    <div class="col">HVAC</div>
    <div class="col">
      <a href="#" data-table="re_deal_economics" data-name="new_hvac_amt" data-type="text" class="edit-money" data-value="<?=$deal_economics['new_hvac_amt']?>"></a>
    </div>
    <div class="col">
      <a href="#" data-table="re_deal_economics" data-name="new_hvac_psf" data-type="text" class="edit-money" data-value="<?=$deal_economics['new_hvac_psf']?>"></a>
    </div>
    <div class="col">
      <a href="#" data-table="re_deal_economics" data-name="new_hvac_increases" data-type="text" class="edit" data-value="<?=$deal_economics['new_hvac_increases']?>"></a>
    </div>
  </div>


  <div class="row">
    <div class="col">Trash</div>
    <div class="col">
      <a href="#" data-table="re_deal_economics" data-name="new_trash_amt" data-type="text" class="edit-money" data-value="<?=$deal_economics['new_trash_amt']?>"></a>
    </div>
    <div class="col">
      <a href="#" data-table="re_deal_economics" data-name="new_trash_psf" data-type="text" class="edit-money" data-value="<?=$deal_economics['new_trash_psf']?>"></a>
    </div>
    <div class="col">
      <a href="#" data-table="re_deal_economics" data-name="new_trash_increases" data-type="text" class="edit" data-value="<?=$deal_economics['new_trash_increases']?>"></a>
    </div>
  </div>

  <div class="row">
    <div class="col">Total Extras</div>
    <div class="col">
      <div data-table="re_deal_economics" data-name="new_total_extras_amt"><?=$deal_economics['new_total_extras_amt']?></div>
    </div>
    <div clas="col">
      <div data-table="re_deal_economics" data-name="new_total_extras_psf"><?=$deal_economics['new_total_extras_psf']?></div>
    </div>
  </div>

  <div class="row">
    <div class="col">Total Occupancy</div>
    <div class="col">
      <div data-table="re_deal_economics" data-name="new_total_occupancy_amt"><?=$deal_economics['new_total_occupancy_amt']?></div>
    </div>
    <div class="col">
      <div data-table="re_deal_economics" data-name="new_total_occupancy_psf"><?=$deal_economics['new_total_occupancy_psf']?></div>
    </div>
  </div>

</div>
</div>

