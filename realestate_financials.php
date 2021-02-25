<?php
/*
THIS YEAR		LAST YEAR
TTM Sales		TTM Sales
Sales PSF		Sales PSF
TTM OP INCOME		TTM OP INCOME
TTM CF		TTM CF
		
SALES % ∆ VS LY	TTM R&O	TENANT ALLOWANCE PSF
TTM R&O	R&O % SALES	
*/

?>
<div id="project-store-financials">


<div class="row">
<div class="col">

<div class="row header-row">
  <div class="col">This Year</div>
</div>  
	
  <div class="row">
    <div class="col" for="r-ty_ttm_sales">TTM Sales</div>
    <div class="col">
      <a href="#" data-table="realestate" data-name="ty_ttm_sales" class="edit-money" data-value="<?=$realestate['ty_ttm_sales']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="r-ty_sales_psf">Sales PSF</div>
    <div class="col">
      <a href="#" data-table="realestate" data-name="ty_sales_psf" class="edit-money" data-value="<?=$realestate['ty_sales_psf']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="r-ty_ttm_op_inc">TTM OP Income</div>
    <div class="col">
      <a href="#" data-table="realestate" data-name="ty_ttm_op_inc" class="edit-money" data-value="<?=$realestate['ty_ttm_op_inc']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="r-ty_ttm_cf">TTM CF</div>
    <div class="col">
      <a href="#" data-table="realestate" data-name="ty_ttm_cf" class="edit-money" data-value="<?=$realestate['ty_ttm_cf']?>"></a>
    </div>
  </div>

</div>
<div class="col">

<div class="row header-row">
    <div class="col">Last Year</div>
</div>
  
	
  <div class="row">
    <div class="col" for="r-ly_ttm_sales">TTM Sales</div>
    <div class="col">
      <a href="#" data-table="realestate" data-name="ly_ttm_sales" class="edit-money" data-value="<?=$realestate['ly_ttm_sales']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="r-ly_sales_psf">Sales PSF</div>
    <div class="col">
      <a href="#" data-table="realestate" data-name="ly_sales_psf" class="edit-money" data-value="<?=$realestate['ly_sales_psf']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="r-ly_ttm_op_inc">TTM OP Income</div>
    <div class="col">
      <a href="#" data-table="realestate" data-name="ly_ttm_op_inc" class="edit-money" data-value="<?=$realestate['ly_ttm_op_inc']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="r-ly_ttm_cf">TTM CF</div>
    <div class="col">
      <a href="#" data-table="realestate" data-name="ly_ttm_cf" class="edit-money" data-value="<?=$realestate['ly_ttm_cf']?>"></a>
    </div>
  </div>	
	
</div>
</div>

<div class="form-divider"></div>

<div class="row">
<div class="col">

  <div class="row">
    <div class="col" for="r-sales_percentage_vs_ly">Sales % &Delta; vs. LY</div>
    <div class="col">
      <a href="#" data-table="realestate" data-name="sales_percentage_vs_ly" class="edit-percent" data-value="<?=$realestate['sales_percentage_vs_ly']?>"></a>
    </div>
  </div>

  <div class="row">
    <div class="col" for="r-ttm_r_and_o">TTM R&amp;O</div>
    <div class="col">
      <a href="#" data-table="realestate" data-name="ttm_r_and_o" class="edit-money" data-value="<?=$realestate['ttm_r_and_o']?>"></a>
    </div>
  </div>

</div>
<div class="col">
  <div class="row">
    <div class="col" for="r-tenant_allowance_psf">Tenant Allowance PSF</div>
    <div class="col">
      <a href="#" data-table="realestate" data-name="tenant_allowance_psf" class="edit-money" data-value="<?=$realestate['tenant_allowance_psf']?>"></a>
    </div>
  </div>  
  <div class="row">
    <div class="col" for="r-r_and_o_percent_sales">R&amp;O % Sales</div>
    <div class="col">
      <a href="#" data-table="realestate" data-name="r_and_o_percent_sales" class="edit-percent" data-value="<?=$realestate['r_and_o_percent_sales']?>"></a>
    </div>
  </div>
</div>
</div>


</div>