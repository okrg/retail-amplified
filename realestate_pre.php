<div class="row">
<div class="col">
  <div class="row">
    <div class="col" for="r-market_area">Market Area</div>
    <div class="col">
      <a href="#" data-table="realestate" data-name="market_area" data-type="text" class="edit" data-value="<?=$realestate['market_area']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="r-developer">Developer</div>
    <div class="col">
      <a href="#" data-table="realestate" data-name="developer" data-type="text" class="edit" data-value="<?=$realestate['developer']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="r-lease_sqft">Lease SQFT</div>
    <div class="col">
      <a href="#" data-table="realestate" data-name="lease_sqft" class="num edit arch" data-value="<?=$realestate['lease_sqft']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="r-sales_area_sqft">Sales Area SQFT</div>
    <div class="col">
      <a href="#" data-table="realestate" data-name="sales_area_sqft" class="num edit arch" data-value="<?=$realestate['sales_area_sqft']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="r-sales_projection">Approved Sales Projection</div>
    <div class="col">
      <a href="#" data-table="realestate" data-name="sales_projection" data-type="text" class="edit" data-value="<?=$realestate['sales_projection']?>"></a>
    </div>
  </div>
</div>
<div class="col">
  <div class="row">
    <div class="col" for="r-f">REC Approval Date</div>
    <div class="col">
      <a href="#" data-table="scheduled_dates" data-name="rec_approved" data-type="date" class="edit-date" data-value="<?=$scheduled['rec_approval']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="r-opening_date">Original Open Date</div>
    <div class="col">
      <a href="#" data-table="realestate" data-name="opening_date" data-type="date" class="edit-date" data-value="<?=$realestate['opening_date']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="r-remodel_date">Remodel Date</div>
    <div class="col">
      <a href="#" data-table="realestate" data-name="remodel_date" data-type="date" class="edit-date" data-value="<?=$realestate['remodel_date']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="r-expiry_date">Lease Expires</div>
    <div class="col">
      <a href="#" data-table="realestate" data-name="expiry_date" data-type="date" class="edit-date" data-value="<?=$realestate['expiry_date']?>"></a>
    </div>
  </div>
</div>
</div>

<div class="form-divider"></div>

<div class="row">
<div class="col">
  <div class="row">
    <div class="col">Deal Maker</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="deal_maker" class="edit" data-value="<?=$realestate['deal_maker']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col">Deal Type</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="deal_type" class="edit" data-value="<?=$realestate['deal_type']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col">Terms</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="terms" class="edit" data-value="<?=$realestate['terms']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col">Attorney Review</div>
    <div class="col">
      <a href="#" data-type="select" data-table="realestate" data-name="atty_review" class="edit" data-source="[{value:'MG',text:'MG'},{value:'AA',text:'AA'},{value:null,text:null}]" data-value="<?=$realestate['atty_review']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col">Linked?</div>
    <div class="col">
      <a href="#" data-type="select" data-table="realestate" data-name="linked" class="edit" data-source="[{value:'1',text:'Yes'},{value:'0',text:'No'},{value:null,text:null}]" data-value="<?=$realestate['linked']?>"></a>
    </div>
  </div>  
  <div class="row">
    <div class="col">Linked Comment</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="linked_comment" class="edit" data-value="<?=$realestate['linked_comment']?>"></a>
    </div>
  </div>  
  <div class="row">
    <div class="col">Sent Via</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="sent_via" class="edit" data-value="<?=$realestate['sent_via']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col">Ready for Signature?</div>
    <div class="col">
      <a href="#" data-type="select" data-table="realestate" data-name="ready_for_signature" class="edit" data-source="[{value:'1',text:'Yes'},{value:'0',text:'No'},{value:null,text:null}]" data-value="<?=$realestate['ready_for_signature']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="r-delivery_conditions">Delivery Conditions</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="delivery_conditions" class="edit" data-value="<?=$realestate['delivery_conditions']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="r-build_out_period">Build Out Period</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="build_out_period" class="edit" data-value="<?=$realestate['build_out_period']?>"></a>
    </div>
  </div>  
  <div class="row">
    <div class="col" for="r-space_redemise">Space Redemise</div>
    <div class="col">
      <a href="#" data-type="select" data-table="realestate" data-name="space_redemise" class="edit" data-source="[{value:'1',text:'Yes'},{value:'0',text:'No'},{value:null,text:null}]" data-value="<?=$realestate['space_redemise']?>"></a>
    </div>
  </div>  
</div>
<div class="col">
  <div class="row">
    <div class="col" for="r-msa_population">MSA Population</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="msa_population" class="edit" data-value="<?=$realestate['msa_population']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="r-trade_area_population">Trade Area Population</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="trade_area_population" class="edit" data-value="<?=$realestate['trade_area_population']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="c-mall_sales">Center Sales</div>
    <div class="col">
      <a href="#" data-type="text" data-table="re_centerinfo" data-name="mall_sales" class="edit-money" data-value="<?=$centerinfo['mall_sales']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="c-mall_sales_psf">Center Sales PSF</div>
    <div class="col">
      <a href="#" data-type="text" data-table="re_centerinfo" data-name="mall_sales_psf" class="edit-money" data-value="<?=$centerinfo['mall_sales_psf']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="c-mall_sales_psf_date">Center Sales as of Date</div>
    <div class="col">
      <a href="#" data-type="date" data-table="re_centerinfo" data-name="mall_sales_psf_date" class="edit-date" data-value="<?=dateFormat($centerinfo['mall_sales_psf_date'])?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col">TTM Date</div>
    <div class="col">
      <a href="#" data-type="date" data-table="re_centerinfo" data-name="ttm_date" class="edit-date" data-value="<?=dateFormat($centerinfo['ttm_date'])?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="r-competitor_sales">Competitor Sales</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="competitor_sales" class="edit" data-value="<?=$realestate['competitor_sales']?>"></a>
    </div>
  </div>  
  <div class="row">
    <div class="col" for="r-peer_tenants">Peer Tenants</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="peer_tenants" class="edit" data-value="<?=$realestate['peer_tenants']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="r-condition_of_mall">Condition of Mall</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="condition_of_mall" class="edit" data-value="<?=$realestate['condition_of_mall']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="r-last_renovated">Last Renovated</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="last_renovated" class="edit" data-value="<?=$realestate['last_renovated']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="r-renovation_expansion_planned">Renovation/Expansion Planned</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="renovation_expansion_planned" class="edit" data-value="<?=$realestate['renovation_expansion_planned']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col">Level</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="location_description_level" class="edit" data-value="<?=$realestate['location_description_level']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col">Center Type</div>
    <div class="col">
      <a href="#" data-type="text" data-table="re_centerinfo" data-name="center_type" class="edit" data-value="<?=$centerinfo['center_type']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="c-center_rank">Center Rank</div>
    <div class="col">
      <a href="#" data-table="re_centerinfo" data-name="center_rank" data-type="text" class="edit" data-value="<?=$centerinfo['center_rank']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="c-mall_gla">Mall GLA</div>
    <div class="col">
      <a href="#" data-table="re_centerinfo" data-name="mall_gla" data-type="text" class="edit" data-value="<?=$centerinfo['mall_gla']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col">Anchor Wing</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="location_description_anchor_wing" class="edit" data-value="<?=$realestate['location_description_anchor_wing']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col">Placement</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="location_description_placement" class="edit" data-value="<?=$realestate['location_description_placement']?>"></a>
    </div>
  </div>
</div>
</div>

<div class="form-divider"></div>

<div class="row">
<div class="col">
  <div class="row">
    <div class="col" for="r-proposed_space_vacant ">Proposed Space Vacant?</div>
    <div class="col">
      <a href="#" data-type="select" data-table="realestate" data-name="proposed_space_vacant" class="edit" data-source="[{value:'1',text:'Yes'},{value:'0',text:'No'},{value:'',text:''}]" data-value="<?=$realestate['proposed_space_vacant']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="r-tt_occupying_space ">TT Occupying Space</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="tt_occupying_space" class="edit" data-value="<?=$realestate['tt_occupying_space']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="r-current_tt_vacate_space_date ">Date Current TT to Vacate Space</div>
    <div class="col">
      <a href="#" data-type="date" data-table="realestate" data-name="current_tt_vacate_space_date" class="edit-date" data-value="<?=dateFormat($realestate['current_tt_vacate_space_date'])?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="r-vacate_current_space_by_date ">CR Must Vacate Current Space By Date</div>
    <div class="col">
      <a href="#" data-type="date" data-table="realestate" data-name="vacate_current_space_by_date" class="edit-date" data-value="<?=dateFormat($realestate['vacate_current_space_by_date'])?>"></a>
    </div>
  </div>

</div>
<div class="col">  
  <div class="row">
    <div class="col" for="r-rent_summary">Rent Summary</div>
    <div class="col">
      <a href="#" data-type="textarea" data-table="realestate" data-name="rent_summary" class="edit" data-value="<?=$realestate['rent_summary']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="r-rent_period">Rent Period</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="rent_period" class="edit" data-value="<?=$realestate['rent_period']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="r-projected_expiration_date">Projected Expiration Date</div>
    <div class="col">
      <a href="#" data-type="date" data-table="realestate" data-name="projected_expiration_date" class="edit-date" data-value="<?=dateFormat($realestate['projected_expiration_date'])?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="r-renewal_option">Renewal Option</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="renewal_option" class="edit" data-value="<?=$realestate['renewal_option']?>"></a>
    </div>
  </div>
</div>
</div>

  <div class="form-divider"></div>

  

<div class="row">
<div class="col">
  <div class="row">
    <div class="col" for="r-lease_provisions">Lease Provisions</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="lease_provisions" class="edit" data-value="<?=$realestate['lease_provisions']?>"></a>
    </div>
  </div> 
  <div class="row">
    <div class="col" for="r-kiosk_restriction">Kiosk Restriction</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="kiosk_restriction" class="edit" data-value="<?=$realestate['kiosk_restriction']?>"></a>
    </div>
  </div> 
  <div class="row">
    <div class="col" for="r-advertising_requirements">Advertising Requirements</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="advertising_requirements" class="edit" data-value="<?=$realestate['advertising_requirements']?>"></a>
    </div>
  </div> 
  <div class="row">
    <div class="col" for="r-exclusives">Exclusives</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="exclusives" class="edit" data-value="<?=$realestate['exclusives']?>"></a>
    </div>
  </div> 
  <div class="row">
    <div class="col" for="r-other_deal_terms">Other</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="other_deal_terms" class="edit" data-value="<?=$realestate['other_deal_terms']?>"></a>
    </div>
  </div>
</div>
<div class="col">
  <div class="row">
    <div class="col" for="r-radius_restriction">Radius Restriction</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="radius_restriction" class="edit" data-value="<?=$realestate['radius_restriction']?>"></a>
    </div>
  </div> 
  <div class="row">
    <div class="col" for="r-distance_miles">Distance (miles)</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="distance_miles" class="edit" data-value="<?=$realestate['distance_miles']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="r-co_tenancy">Co-tenancy</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="co_tenancy" class="edit" data-value="<?=$realestate['co_tenancy']?>"></a>
    </div>
  </div>
</div>
</div>


  <div class="form-divider"></div>
  

<div class="row">
<div class="col">
  <div class="row">
    <div class="col" for="r-kickout_y_n">Kickout</div>
    <div class="col">
      <a href="#" data-type="select" data-table="realestate" data-name="kickout_y_n" class="edit" data-source="[{value:'1',text:'Yes'},{value:'0',text:'No'},{value:'',text:''}]" data-value="<?=$realestate['kickout_y_n']?>"></a>
    </div>
  </div> 
  <div class="row">
    <div class="col" for="r-kickout_threshold">Kickout Threshold</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="kickout_threshold" class="edit" data-value="<?=$realestate['kickout_threshold']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="k-notice_date">Kickout Notice Window</div>
    <div class="col">
      <a href="#" data-table="re_kickouts" data-name="notice_date" class="edit" data-type="textarea" data-value="<?=$kickouts['notice_date']?>"></a>
    </div>
  </div>
</div>
<div class="col">
  <div class="row">
    <div class="col" for="r-kickout_year">Kickout Year</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="kickout_year" class="edit" data-value="<?=$realestate['kickout_year']?>"></a>
    </div>
  </div> 
  <div class="row">
    <div class="col" for="r-kickout_penalty">Kickout Penalty</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="kickout_penalty" class="edit" data-value="<?=$realestate['kickout_penalty']?>"></a>
    </div>
  </div> 
</div>
</div>


  <div class="form-divider"></div>


  

<div class="row">
<div class="col">  
  <div class="row">
    <div class="col" for="r-construction_chargebacks">Construction Chargebacks</div>
    <div class="col">
      <a href="#" data-type="textarea" data-table="realestate" data-name="construction_chargebacks" class="edit" data-value="<?=$realestate['construction_chargebacks']?>"></a>
    </div>
  </div> 
  <div class="row">
    <div class="col" for="r-landlord_work">Scope of Landlord Work</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="landlord_work" class="edit" data-value="<?=$realestate['landlord_work']?>"></a>
    </div>
  </div> 
  <div class="row">
    <div class="col" for="r-est_ll_work_date">Estimated LL Work Completion Date</div>
    <div class="col">
      <a href="#" data-type="date" data-table="realestate" data-name="est_ll_work_date" class="edit-date" data-value="<?=dateFormat($realestate['est_ll_work_date'])?>"></a>
    </div>
  </div>   
  <div class="row">
    <div class="col" for="r-landlord_demo">Demo by Landlord</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="landlord_demo" class="edit" data-value="<?=$realestate['landlord_demo']?>"></a>
    </div>
  </div>   
  <div class="row">
    <div class="col" for="r-landlord_hvac">HVAC</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="landlord_hvac" class="edit" data-value="<?=$realestate['landlord_hvac']?>"></a>
    </div>
  </div>
</div>
<div class="col">
  <div class="row">
    <div class="col" for="r-landlord_level_slab">Level Slab</div>
    <div class="col">
      <a href="#" data-type="select" data-table="realestate" data-name="landlord_level_slab" class="edit" data-source="[{value:'1',text:'Yes'},{value:'0',text:'No'},{value:'',text:''}]" data-value="<?=$realestate['landlord_level_slab']?>"></a>
    </div>
  </div>  
  
  <div class="row">
    <div class="col" for="r-landlord_supplied_electrical">Electrical</div>
    <div class="col">
      <a href="#" data-type="select" data-table="realestate" data-name="landlord_supplied_electrical" class="edit" data-source="[{value:'1',text:'Yes'},{value:'0',text:'No'},{value:'',text:''}]" data-value="<?=$realestate['landlord_supplied_electrical']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="r-landlord_supplied_gas">Gas</div>
    <div class="col">
      <a href="#" data-type="select" data-table="realestate" data-name="landlord_supplied_gas" class="edit" data-source="[{value:'1',text:'Yes'},{value:'0',text:'No'},{value:'',text:''}]" data-value="<?=$realestate['landlord_supplied_gas']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="r-landlord_supplied_water_sewer">Water/Sewer</div>
    <div class="col">
      <a href="#" data-type="select" data-table="realestate" data-name="landlord_supplied_water_sewer" class="edit" data-source="[{value:'1',text:'Yes'},{value:'0',text:'No'},{value:'',text:''}]" data-value="<?=$realestate['landlord_supplied_water_sewer']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="r-location_meter">Location Meter</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="location_meter" class="edit" data-value="<?=$realestate['location_meter']?>"></a>
    </div>
  </div>
</div>
</div>


  <div class="form-divider"></div>  


<div class="row">
<div class="col">
  <div class="row">
    <div class="col" for="r-field_comments">Field Comments</div>
    <div class="col">
      <a href="#" data-type="textarea" data-table="realestate" data-name="field_comments" class="edit" data-value="<?=$realestate['field_comments']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="r-finance_comments">Finance Comments</div>
    <div class="col">
      <a href="#" data-type="textarea" data-table="realestate" data-name="finance_comments" class="edit" data-value="<?=$realestate['finance_comments']?>"></a>
    </div>
  </div>
  
</div>
<div class="col">
  
  <div class="row">
    <div class="col" for="r-terms_options_comments">Terms &amp; Options Comments</div>
    <div class="col">
      <a href="#" data-type="textarea" data-table="realestate" data-name="terms_options_comments" class="edit" data-value="<?=$realestate['terms_options_comments']?>"></a>
    </div>
  </div> 
  <div class="row">
    <div class="col" for="r-extra_charges_comments">Extra Charges Comments</div>
    <div class="col">
      <a href="#" data-type="textarea" data-table="realestate" data-name="extra_charges_comments" class="edit" data-value="<?=$realestate['extra_charges_comments']?>"></a>
    </div>
  </div> 
</div>
</div>

<div class="row">
<div class="col">
  <div class="row">
    <div class="col">Editorial</div>
    <div class="col">
      <a href="#" data-type="textarea" data-table="realestate" data-name="editorial" class="edit" data-value="<?=$realestate['editorial']?>"></a>      
    </div>
  </div>
</div>
</div>

<div class="row">
<div class="col">
  <div class="row">
    <div class="col" for="r-lease_detail_comments">Lease Detail Comments</div>
    <div class="col">
      <a href="#" data-type="textarea" data-table="realestate" data-name="lease_detail_comments" class="edit" data-value="<?=$realestate['lease_detail_comments']?>"></a>      
    </div>
  </div>
  <div class="row">
    <div class="col" for="r-construction_cost">Construction Cost</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="construction_cost" class="edit-money" data-value="<?=$realestate['construction_cost']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="r-ta_cash">TA - Cash</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="ta_cash" class="edit-money" data-value="<?=$realestate['ta_cash']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="r-ta_rent_credit">TA - Rent Credit</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="ta_rent_credit" class="edit" data-value="<?=$realestate['ta_rent_credit']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="r-net_capex">Net CAPEX</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="net_capex" class="edit" data-value="<?=$realestate['net_capex']?>"></a>
    </div>
  </div>
</div>
<div class="col">
  <div class="row">
    <div class="col" for="r-financial_data_metrics">Financial Data Metrics</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="financial_data_metrics" class="edit" data-value="<?=$realestate['financial_data_metrics']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="r-key_indicators">Key Indicators</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="key_indicators" class="edit" data-value="<?=$realestate['key_indicators']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="r-sales_projection">Sales Projection</div>
    <div class="col">
      <a href="#" data-type="text" data-table="realestate" data-name="sales_projection" class="edit" data-value="<?=$realestate['sales_projection']?>"></a>
    </div>
  </div>
  <div class="row">
    <div class="col" for="r-key_indicator_comments">Key Indicator Comments</div>
    <div class="col">
      <a href="#" data-type="textarea" data-table="realestate" data-name="key_indicator_comments" class="edit" data-value="<?=$realestate['key_indicator_comments']?>"></a>
    </div>
  </div>
</div>
</div>




<?php
/*					
Deal Economics					
Rent - Years	Amount Per Sq Ft	Amount Per month	Amount Per Year	Breakpoint	Percentage Rent
1					
...
10

Terms & Options Comments				
				
Extra Charges				
Type	Amount Per Sq Ft	Amount Per month	Amount Per Year	Increases/Cap

Extra Charges Comments			
			
Lease Provisions			
Kiosk Restriction		Radius Restriction	
Advetising Requirements		Distance (miles)	
			
Kickout(Y/N)	K/O Threshold	K/O Year	K/O Penalty
			
Opening Co-tenancy	Co-tenancy		
			
Construction Chargebacks			
			
LL Work			
LL supplied Level Slab			Demo By LL
Demising Wall (Dry wall, Existing, Studs and Firetape, Studs only, other(input)			HVAC
			
Utilities By LL			
Electrical	Gas	Water/Sewer	
Does location require meter? (historically high utility cost)			
			
Lease Details Comments			
*/

?>
