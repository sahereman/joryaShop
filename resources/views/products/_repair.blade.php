{{-- 修复 --}}
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
          <span class="dynamic_name" data-paramid="Refilled hair color">Refilled hair color</span>
      </div>
      <div class="sku-select-module">
          <div data-paramid="undefined" data-name="undefined" class="sku-select-value">
              <input type="hidden" readonly="" data-paramid="Refilled hair color" value="I'll send in the hair sample(s)" name="I'll send in the hair sample(s)" photo-url="" delta-price="0.00">
              <span class="sku-select-value-show">I'll send in the hair sample(s)</span>
          </div>
          <div class="sku-select-options" style="display: none;">
              <ul data-paramid="undefined" data-name="undefined">
                <li data-paramid="Refilled hair color" 
                    data-valueid="I'll send in the hair sample(s)" 
                    photo-url="" delta-price="0.00">
                    <span class="text-span">I'll send in the hair sample(s)</span>
                </li>
                <li data-paramid="Refilled hair color"
                    data-valueid="Make colors same as my old system" 
                    photo-url="" delta-price="0.00">
                    <span class="text-span">Make colors same as my old system</span>
                </li>
              </ul>
          </div>
      </div>
  </div>
  <div class="sku-select">
      <div class="sku-select-name">
          <span class="dynamic_name" data-paramid="Refilled hair length">Refilled hair length</span>
      </div>
      <div class="sku-select-module">
          <div data-paramid="undefined" data-name="undefined" class="sku-select-value">
              <input type="hidden" readonly="" data-paramid="Refilled hair length" value="Up to 4''" name="Up to 4''" photo-url="" delta-price="0.00">
              <span class="sku-select-value-show">Up to 4''</span>
          </div>
          <div class="sku-select-options" style="display: none;">
              <ul data-paramid="undefined" data-name="undefined">
                <li data-paramid="Refilled hair length" data-valueid="Up to 4''" photo-url="" delta-price="0.00">
                  <span class="text-span">Up to 4''</span>
                </li>
                <li data-paramid="Refilled hair length" data-valueid="6''" photo-url="" delta-price="0.00">
                  <span class="text-span">6''</span>
                </li>
                <li data-paramid="Refilled hair length" data-valueid="Longest at 8''" photo-url="" 
                    delta-price="{{ get_current_price(29.00) }}"
                    delta-property="{{ get_current_price(29.00) }}">
                  <span class="text-span">Longest at 8''</span>
                  <span class="price-span"> +{{ get_global_symbol() }}{{ get_current_price(29.00) }}</span>
                </li>
                <li data-paramid="Refilled hair length" data-valueid="Longest at 10''" photo-url="" 
                    delta-price="{{ get_current_price(35.00) }}"
                    delta-property="{{ get_current_price(35.00) }}">
                  <span class="text-span">Longest at 10''</span>
                  <span class="price-span"> +{{ get_global_symbol() }}{{ get_current_price(35.00) }}</span>
                </li>
                <li data-paramid="Refilled hair length" data-valueid="Longest at 12''" photo-url="" 
                    delta-price="{{ get_current_price(45.00) }}"
                    delta-property="{{ get_current_price(45.00) }}">
                  <span class="text-span">Longest at 12''</span>
                  <span class="price-span"> +{{ get_global_symbol() }}{{ get_current_price(45.00) }}</span>
                </li>
                <li data-paramid="Refilled hair length" data-valueid="Longest at 14''" photo-url="" 
                    delta-price="{{ get_current_price(60.00) }}"
                    delta-property="{{ get_current_price(60.00) }}">
                  <span class="text-span">Longest at 14''</span>
                  <span class="price-span"> +{{ get_global_symbol() }}{{ get_current_price(60.00) }}</span>
                </li>
                <li data-paramid="Refilled hair length" data-valueid="Longest at 16''" photo-url="" 
                    delta-price="{{ get_current_price(80.00) }}"
                    delta-property="{{ get_current_price(80.00) }}">
                  <span class="text-span">Longest at 16''</span>
                  <span class="price-span"> +{{ get_global_symbol() }}{{ get_current_price(80.00) }}</span>
                </li>
                <li data-paramid="Refilled hair length" data-valueid="Longest at 18''" photo-url="" 
                    delta-price="{{ get_current_price(110.00) }}"
                    delta-property="{{ get_current_price(110.00) }}">
                  <span class="text-span">Longest at 18''</span>
                  <span class="price-span"> +{{ get_global_symbol() }}{{ get_current_price(110.00) }}</span>
                </li>
                <li data-paramid="Refilled hair length" data-valueid="Longest at 20''" photo-url=""
                    delta-price="{{ get_current_price(150.00) }}"
                    delta-property="{{ get_current_price(150.00) }}">
                  <span class="text-span">Longest at 20''</span>
                  <span class="price-span"> +{{ get_global_symbol() }}{{ get_current_price(150.00) }}</span>
                </li>
                <li data-paramid="Refilled hair length" data-valueid="Longest at 22''" photo-url="" 
                    delta-price="{{ get_current_price(200.00) }}"
                    delta-property="{{ get_current_price(200.00) }}">
                  <span class="text-span">Longest at 22''</span>
                  <span class="price-span"> +{{ get_global_symbol() }}{{ get_current_price(200.00) }}</span>
                </li>
              </ul>
          </div>
      </div>
  </div>
  <div class="sku-select">
      <div class="sku-select-name">
          <span class="dynamic_name" data-paramid="Replace new front">Replace new front</span>
      </div>
      <div class="sku-select-module">
          <div data-paramid="undefined" data-name="undefined" class="sku-select-value">
              <input type="hidden" readonly="" data-paramid="Replace new front" value="I want to make a change" name="I want to make a change" photo-url="" delta-price="0.00">
              <span class="sku-select-value-show">I want to make a change</span>
          </div>
          <div class="sku-select-options" style="display: none;">
              <ul data-paramid="undefined" data-name="undefined">
                <li data-paramid="Replace new front" data-valueid="No,I don't need a new front" photo-url="" delta-price="0.00">
                  <span class="text-span">No,I don't need a new front</span>
                </li>
                <li data-paramid="Replace new front" 
                    data-valueid="Replace a new front,same material as the old unit" 
                    photo-url="" delta-price="{{ get_current_price(19.00) }}"
                    delta-property="{{ get_current_price(19.00) }}">
                    <span class="text-span">Replace a new front,same material as the old unit</span>
                    <span class="price-span"> +{{ get_global_symbol() }}{{ get_current_price(19.00) }}</span>
                </li>
                <li data-paramid="Replace new front" data-valueid="I want to make a change" photo-url="" 
                    delta-price="{{ get_current_price(19.00) }}"
                    delta-property="{{ get_current_price(19.00) }}">
                  <span class="text-span">I want to make a change</span>
                  <span class="price-span"> +{{ get_global_symbol() }}{{ get_current_price(19.00) }}</span>
                </li>
              </ul>
          </div>
      </div>
  </div>
  <div class="sku-select">
      <div class="sku-select-name">
          <span class="dynamic_name" data-paramid="Repair minor tears">Repair minor tears</span>
      </div>
      <div class="sku-select-module">
          <div data-paramid="undefined" data-name="undefined" class="sku-select-value">
              <input type="hidden" readonly="" data-paramid="Repair minor tears" value="No need to repair tears" name="No need to repair tears" photo-url="" delta-price="75.00">
              <span class="sku-select-value-show">No need to repair tears</span>
          </div>
          <div class="sku-select-options" style="display: none;">
              <ul data-paramid="undefined" data-name="undefined">
                <li data-paramid="Repair minor tears" data-valueid="No need to repair tears" photo-url="" delta-price="0.00">
                  <span class="text-span">No need to repair tears</span>
                </li>
                <li data-paramid="Repair minor tears" data-valueid="Repair all tears" photo-url="" delta-price="0.00">
                  <span class="text-span">Repair all tears</span>
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
</div>