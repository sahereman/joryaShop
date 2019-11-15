{{-- 复制 --}}
<div id="sku-choose-store" class="sku-choose-store {{ $product->type == \App\Models\Product::PRODUCT_TYPE_CUSTOM ? ' dis_ni' : '' }}" price="{{ $product->price }}">
    <div class="sku-select">
        <div class="sku-select-name">
            <span class="dynamic_name">Condition</span>
        </div>
        <div class="sku-select-module">
            <div class="sku-select-value select-for-show">
                <span class="sku-select-value-show">New with tags</span>
            </div>
        </div>
    </div>
    <div class="sku-select">
        <div class="sku-select-name">
            <span class="dynamic_name" data-paramid="Hair Cut">Hair Cut</span>
        </div>
        <div class="sku-select-module">
            <div data-paramid="undefined" data-name="undefined" class="sku-select-value">
                <input type="hidden" readonly="" data-paramid="Hair Cut" value="No,I will have my hair cut-in and styled by my stylist" name="No,I will have my hair cut-in and styled by my stylist" photo-url="" delta-price="0.00">
                <span class="sku-select-value-show">No,I will have my hair cut-in and styled by my stylist</span>
            </div>
            <div class="sku-select-options" style="display: none;">
                <ul data-paramid="undefined" data-name="undefined">
                  <li data-paramid="Hair Cut" 
                      data-valueid="No,I will have my hair cut-in and styled by my stylist" 
                      photo-url="" delta-price="0.00">
                      <span class="text-span">No,I will have my hair cut-in and styled by my stylist</span>
                  </li>
                  <li data-paramid="Hair Cut" 
                      data-valueid="Yes, have hair cut-in and styled (need extra 3 working days)" 
                      photo-url="" delta-price="{{ get_current_price(20.00) }}"
                      delta-property="{{ get_current_price(20.00) }}">
                      <span class="text-span">Yes, have hair cut-in and styled (need extra 3 working days)</span>
                      <span class="price-span"> +{{ get_global_symbol() }}{{ get_current_price(20.00) }}</span>
                  </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="sku-select">
        <div class="sku-select-name">
            <span class="dynamic_name" data-paramid="Base size">Base size</span>
        </div>
        <div class="sku-select-module">
            <div data-paramid="undefined" data-name="undefined" class="sku-select-value">
                <input type="hidden" readonly="" data-paramid="Base size" value='Partial (size < 4"x4", or area < 16 square inches)' name='Partial (size < 4"x4", or area < 16 square inches)' photo-url="" delta-price="0.00">
                <span class="sku-select-value-show">Partial (size < 4"x4", or area < 16 square inches)</span>
            </div>
            <div class="sku-select-options" style="display: none;">
                <ul data-paramid="undefined" data-name="undefined">
                  <li data-paramid="Base size"
                    data-valueid='Regular (4"x4"≤ size ≤8"x10", or 16 square inches≤ area ≤80 square inches)' 
                    photo-url="" delta-price="0.00">
                    <span class="text-span">Regular (4"x4"≤ size ≤8"x10", or 16 square inches≤ area ≤80 square inches)</span>
                  </li>
                  <li data-paramid="Base size" 
                      data-valueid='Partial (size < 4"x4", or area < 16 square inches)' 
                      photo-url="" delta-price="-{{ get_current_price(19.00) }}" delta-property="{{ get_current_price(19.00) }}">
                      <span class="text-span">Partial (size < 4"x4", or area < 16 square inches)</span>
                      <span class="price-span"> -{{ get_global_symbol() }}{{ get_current_price(19.00) }}</span>
                  </li>
                  <li data-paramid="Base size"
                      data-valueid='Oversize (8"x10"< size <10"x10", or 80 square inches< area <100 square inches)' 
                      photo-url="" delta-price="-{{ get_current_price(59.00) }}" delta-property="{{ get_current_price(59.00) }}">
                      <span class="text-span">Oversize (8"x10"< size <10"x10", or 80 square inches< area <100 square inches)</span>
                      <span class="price-span"> -{{ get_global_symbol() }}{{ get_current_price(59.00) }}</span>
                  </li>
                  <li data-paramid="Base size"
                      data-valueid='Full cap (size ≥ 10"x10", or area ≥ 100 square inches)' 
                      photo-url="" delta-price="-{{ get_current_price(99.00) }}" delta-property="{{ get_current_price(99.00) }}">
                      <span class="text-span">Full cap (size ≥ 10"x10", or area ≥ 100 square inches)</span>
                      <span class="price-span"> -{{ get_global_symbol() }}{{ get_current_price(99.00) }}</span>
                  </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="sku-select">
        <div class="sku-select-name">
            <span class="dynamic_name" data-paramid="Hair color">Hair color</span>
        </div>
        <div class="sku-select-module">
            <div data-paramid="undefined" data-name="undefined" class="sku-select-value">
                <input type="hidden" readonly="" data-paramid="Hair color" value="Match the sample I′ll send in(Recommended)" name="Match the sample I′ll send in(Recommended)" photo-url="" delta-price="0.00">
                <span class="sku-select-value-show">Match the sample I′ll send in(Recommended)</span>
            </div>
            <div class="sku-select-options" style="display: none;">
                <ul data-paramid="undefined" data-name="undefined">
                  <li data-paramid="Hair color" data-valueid="Match the sample I′ll send in(Recommended)" photo-url="" delta-price="0.00">
                    <span class="text-span">Match the sample I′ll send in(Recommended)</span>
                  </li>
                  <li data-paramid="Hair color" data-valueid="I′ll send in an old system as sample" photo-url="" delta-price="0.00">
                    <span class="text-span">I′ll send in an old system as sample</span>
                  </li>
                  <li data-paramid="Hair color" data-valueid="Use my sample already on file" photo-url="" delta-price="0.00">
                    <span class="text-span">Use my sample already on file</span>
                  </li>
                  <li data-paramid="Hair color" data-valueid="Use your color code" photo-url="" delta-price="0.00">
                    <span class="text-span">Use your color code</span>
                  </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="sku-select">
        <div class="sku-select-name">
            <span class="dynamic_name" data-paramid="Hair length">Hair length</span>
        </div>
        <div class="sku-select-module">
            <div data-paramid="undefined" data-name="undefined" class="sku-select-value">
                <input type="hidden" readonly="" data-paramid="Hair length" value="Up to 4''" name="Up to 4''" photo-url="" delta-price="0.00">
                <span class="sku-select-value-show">Up to 4''</span>
            </div>
            <div class="sku-select-options" style="display: none;">
                <ul data-paramid="undefined" data-name="undefined">
                  <li data-paramid="Hair length" data-valueid="Up to 4''" photo-url="" delta-price="0.00">
                    <span class="text-span">Up to 4''</span>
                  </li>
                  <li data-paramid="Hair length" data-valueid="6''" photo-url="" delta-price="0.00">
                    <span class="text-span">6''</span>
                  </li>
                  <li data-paramid="Hair length" data-valueid="Longest at 8''" photo-url="" 
                      delta-price="{{ get_current_price(29.00) }}"
                      delta-property="{{ get_current_price(29.00) }}">
                    <span class="text-span">Longest at 8''</span>
                    <span class="price-span"> +{{ get_global_symbol() }}{{ get_current_price(29.00) }}</span>
                  </li>
                  <li data-paramid="Hair length" data-valueid="Longest at 10''" photo-url="" 
                      delta-price="{{ get_current_price(35.00) }}"
                      delta-property="{{ get_current_price(35.00) }}">
                    <span class="text-span">Longest at 10''</span>
                    <span class="price-span"> +{{ get_global_symbol() }}{{ get_current_price(35.00) }}</span>
                  </li>
                  <li data-paramid="Hair length" data-valueid="Longest at 12''" photo-url="" 
                      delta-price="{{ get_current_price(45.00) }}"
                      delta-property="{{ get_current_price(45.00) }}">
                    <span class="text-span">Longest at 12''</span>
                    <span class="price-span"> +{{ get_global_symbol() }}{{ get_current_price(45.00) }}</span>
                  </li>
                  <li data-paramid="Hair length" data-valueid="Longest at 14''" photo-url="" 
                      delta-price="{{ get_current_price(60.00) }}"
                      delta-property="{{ get_current_price(60.00) }}">
                    <span class="text-span">Longest at 14''</span>
                    <span class="price-span"> +{{ get_global_symbol() }}{{ get_current_price(60.00) }}</span>
                  </li>
                  <li data-paramid="Hair length" data-valueid="Longest at 16''" photo-url="" 
                      delta-price="{{ get_current_price(80.00) }}"
                      delta-property="{{ get_current_price(80.00) }}">
                    <span class="text-span">Longest at 16''</span>
                    <span class="price-span"> +{{ get_global_symbol() }}{{ get_current_price(80.00) }}</span>
                  </li>
                  <li data-paramid="Hair length" data-valueid="Longest at 18''" photo-url="" 
                      delta-price="{{ get_current_price(110.00) }}"
                      delta-property="{{ get_current_price(110.00) }}">
                    <span class="text-span">Longest at 18''</span>
                    <span class="price-span"> +{{ get_global_symbol() }}{{ get_current_price(110.00) }}</span>
                  </li>
                  <li data-paramid="Hair length" data-valueid="Longest at 20''" photo-url=""
                      delta-price="{{ get_current_price(150.00) }}"
                      delta-property="{{ get_current_price(150.00) }}">
                    <span class="text-span">Longest at 20''</span>
                    <span class="price-span"> +{{ get_global_symbol() }}{{ get_current_price(150.00) }}</span>
                  </li>
                  <li data-paramid="Hair length" data-valueid="Longest at 22''" photo-url="" 
                      delta-price="{{ get_current_price(200.00) }}"
                      delta-property="{{ get_current_price(200.00) }}">
                    <span class="text-span">Longest at 22''</span>
                    <span class="price-span"> +{{ get_global_symbol() }}{{ get_current_price(200.00) }}</span>
                  </li>
                  <li data-paramid="Hair length" data-valueid="Longest at 24''" photo-url="" 
                      delta-price="{{ get_current_price(250.00) }}"
                      delta-property="{{ get_current_price(250.00) }}">
                    <span class="text-span">Longest at 24''</span>
                    <span class="price-span"> +{{ get_global_symbol() }}{{ get_current_price(250.00) }}</span>
                  </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="sku-select">
        <div class="sku-select-name">
            <span class="dynamic_name" data-paramid="Hair type">Hair type</span>
        </div>
        <div class="sku-select-module">
            <div data-paramid="undefined" data-name="undefined" class="sku-select-value">
                <input type="hidden" readonly="" data-paramid="Hair type" value="Indian human hair (medium thickness)" 
                       name="Indian human hair (medium thickness)" photo-url="" delta-price="0.00">
                <span class="sku-select-value-show">Indian human hair (medium thickness)</span>
            </div>
            <div class="sku-select-options" style="display: none;">
                <ul data-paramid="undefined" data-name="undefined">
                  <li data-paramid="Hair type" data-valueid="Indian human hair (medium thickness)" photo-url="" delta-price="0.00">
                    <span class="text-span">Indian human hair (medium thickness)</span>
                  </li>
                  <li data-paramid="Hair type" 
                      data-valueid="Remy hair (best)" 
                      photo-url="" delta-price="{{ get_current_price(59.00) }}"
                      delta-property="{{ get_current_price(59.00) }}">
                      <span class="text-span">Remy hair (best)</span>
                      <span class="price-span"> +{{ get_global_symbol() }}{{ get_current_price(59.00) }}</span>
                  </li>
                  <li data-paramid="Hair type" data-valueid='European hair (fine, thin & soft, 7" and up is not available)' photo-url="" 
                      delta-price="{{ get_current_price(49.00) }}"
                      delta-property="{{ get_current_price(49.00) }}">
                    <span class="text-span">European hair (fine, thin & soft, 7" and up is not available)</span>
                    <span class="price-span"> +{{ get_global_symbol() }}{{ get_current_price(49.00) }}</span>
                  </li>
                  <li data-paramid="Hair type" data-valueid="Chinese hair (coarse, good for extramely straight)" photo-url="" delta-price="0.00">
                    <span class="text-span">Chinese hair (coarse, good for extramely straight)</span>
                  </li>
                  <li data-paramid="Hair type" data-valueid="Synthetic hair" photo-url="" delta-price="0.00">
                    <span class="text-span">Synthetic hair</span>
                  </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="sku-select">
        <div class="sku-select-name">
            <span class="dynamic_name" data-paramid="Your system age">Your system age</span>
        </div>
        <div class="sku-select-module">
            <div data-paramid="undefined" data-name="undefined" class="sku-select-value">
                <input type="hidden" readonly="" data-paramid="Your system age" value="Between 6 to 12 Months" name="Between 6 to 12 Months" photo-url="" delta-price="0.00">
                <span class="sku-select-value-show">Between 6 to 12 Months</span>
            </div>
            <div class="sku-select-options" style="display: none;">
                <ul data-paramid="undefined" data-name="undefined">
                  <li data-paramid="Your system age" data-valueid="Between 6 to 12 Months" photo-url="" delta-price="0.00">
                    <span class="text-span">Between 6 to 12 Months</span>
                  </li>
                  <li data-paramid="Your system age" data-valueid="Less than 6 months" photo-url="" delta-price="0.00">
                    <span class="text-span">Less than 6 months</span>
                  </li>
                  <li data-paramid="Your system age" data-valueid="Older than 1 year" photo-url="" delta-price="0.00">
                    <span class="text-span">Older than 1 year</span>
                  </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="sku-select">
      <div class="sku-select-name">
          <span class="dynamic_name" data-paramid="Rush service">Rush service</span>
      </div>
      <div class="sku-select-module">
          <div data-paramid="undefined" data-name="undefined" class="sku-select-value">
              <input type="hidden" readonly="" data-paramid="Rush service" value="Average service" name="Average service" photo-url="" delta-price="210.00">
              <span class="sku-select-value-show">Average service</span>
          </div>
          <div class="sku-select-options" style="display: none;">
              <ul data-paramid="undefined" data-name="undefined">
                <li data-paramid="Rush service" data-valueid="Average service" photo-url="" delta-price="0.00">
                  <span class="text-span">Average service</span>
                </li>
                <li data-paramid="Rush service" data-valueid="Rush service" photo-url="" delta-price="{{ get_current_price(59.00) }}">
                  <span class="text-span">Rush service</span>
                  <span class="price-span"> +{{ get_global_symbol() }}{{ get_current_price(59.00) }}</span>
                </li>
              </ul>
          </div>
      </div>
    </div>                
    <div class="sku-select">
      <div class="sku-select-name">
          <span class="dynamic_name" data-paramid="Sample unit keep on file?">Sample unit keep on file?</span>
      </div>
      <div class="sku-select-module">
          <div data-paramid="undefined" data-name="undefined" class="sku-select-value">
              <input type="hidden" readonly="" data-paramid="Sample unit keep on file?" value="keep on file" name="keep on file" photo-url="" delta-price="210.00">
              <span class="sku-select-value-show">keep on file</span>
          </div>
          <div class="sku-select-options" style="display: none;">
              <ul data-paramid="undefined" data-name="undefined">
                <li data-paramid="Sample unit keep on file?" data-valueid="keep on file" photo-url="" delta-price="0.00">
                  <span class="text-span">keep on file</span>
                </li>
                <li data-paramid="Sample unit keep on file?" data-valueid="Return with order" photo-url="" delta-price="0.00">
                  <span class="text-span">Return with order</span>
                </li>
                <li data-paramid="Sample unit keep on file?" data-valueid="Rush ship back" photo-url="" delta-price="{{ get_current_price(29.90) }}">
                  <span class="text-span">Rush ship back</span>
                  <span class="price-span"> +{{ get_global_symbol() }}{{ get_current_price(29.90) }}</span>
                </li>
              </ul>
          </div>
      </div>
    </div>                
  </div>