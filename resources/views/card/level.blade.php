<div id="content-level">

                <h1 class="pink">Thanh toán bao nhiêu tiền thì được tặng 1 điểm?</h1>
                <hr>
                <div class="row">
                    <div class="col-lg-7">
                            <p class="align">Giá trị hóa đơn tương ứng 1 điểm:</p>
                    </div>
                    <div class="col-lg-5">
                        <div class="input-group">
                            <p id="value-discount-hidend" style="display:none"></p>
                            <input type="hidden" id="value-discount-1" value="">
                            <input type="text" name="valuediscountlevel" data-v-min="0" data-v-max="1000000" @if(Auth::merchant()->get()->package != 0) disabled="disabled" style="background: #eee" @endif id="value-discount-level" class="form-control currentcy" aria-label="Đơn vị VNĐ" placeholder="EX: 1,000" value="{{ $infoCard['value'][0]['value'][0]['unit'] or '' }}">
                            <span class="input-group-addon">VNĐ</span>
                        </div>
                    </div>
                </div>

                <h1 class="pink">Quy định số điểm cần tích lũy và (%) điểm thưởng cho mỗi hạng thẻ</h1>
                <hr>
                
                @if (Auth::merchant()->get()->package == 0)
                    @include('card.level1')
                    @include('card.level2')
                    @include('card.level3')
                @else
                    
                @endif

                <!-- <div class="box-ex" style="height:200px;"> -->
                @if (Auth::merchant()->get()->package == 0)
                    @include('card.ex')
                @endif
                <!-- </div> -->
            </div>
            <!-- END content For LEVEL -->
