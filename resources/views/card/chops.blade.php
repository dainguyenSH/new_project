<div id="content-chops" style="display:none">
                <h1 class="pink">Chọn hình thức tặng thưởng cho thành viên</h1>
                <hr>
                <ul class="select-type">
                    <!-- SELECT OPTION CHOPS -->
                    <li class="select-chop-option-1" data-chops-option="1"><span class="current1"></span><a href="#">Tặng miễn phí 1 sản phẩm khi tích đủ Chops </a><a href="#" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="{{ trans('infomation.IF_03') }}"><i class="fa fa-info-circle default"></i></a>
                    </li>
                    <li class="select-chop-option-2" data-chops-option="0"><span></span><a href="#">Giảm giá (%) cho hóa đơn tiếp theo khi tích đủ Chops </a><a href="#" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="{{ trans('infomation.IF_04') }}"><i class="fa fa-info-circle default"></i></a>
                    </li>
                </ul>

                <h1 class="pink">Thanh toán bao nhiêu tiền thì được tặng 1 Chop?</h1>
                <hr>
                <div class="row">
                    <div class="col-lg-7">
                            <p class="align">Giá trị hóa đơn tương ứng 1 Chops:</p>
                    </div>
                    <div class="col-lg-5">
                        <div class="input-group">
                            <input type="hidden" id="value-discount-1" value="">
                            <input type="text" name="valuesetchop" data-v-min="0" data-v-max="1000000" id="value-discount" class="form-control currentcy" aria-label="Đơn vị VNĐ" placeholder="EX: 200,000" value="{{ $infoCard['value'][0]['value'][0]['unit'] or '' }}">
                            <span class="input-group-addon">VNĐ</span>
                        </div>
                    </div>
                </div>
                


                

                <div class="chops-option-gift-1">
                    <h1 class="pink">Quy định số Chops cần tích lũy & giá trị SP được đổi</h1>
                    <hr>
                    <div class="table-responsive" style="overflow-y: hidden; ">
                        <table class="table" id="list-gift-1">
                            <thead>
                                <tr>
                                    <th style="width:40%">Số Chops cần tích lũy
                                    </th>
                                    <th style="width:40%">Giá trị SP được đổi
                                    </th>
                                    <th style="width:20%"></th>
                                </tr>
                            </thead>
                            <tbody class="customize-input-point">
                                
                                    <tr>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control choice-stick-1" id="select-chop-gift-1">
                                                    <option value="">-- Số lượng Chops --</option>

                                                    @for ($i = 1; $i <= 15 ; $i++) { 
                                                        <option value="{{ $i }}">{{ $i }} Chops</option>
                                                    @endfor

                                                </select>
                                                <input type="hidden" id="select-chop-gift-1-1" value="" >
                                                <input type="hidden" id="select-chop-gift-1-2" value="" >
                                                <input type="hidden" id="select-chop-gift-1-3" value="" >
                                            </div>
                                        </td>
                                        <td>

                                            <div class="input-group">
                                                <input type="hidden" id="value-discount-gift-1-1" value="" >
                                                <input type="hidden" id="value-discount-gift-1-2" value="" >
                                                <input type="hidden" id="value-discount-gift-1-3" value="" >

                                                <input type="hidden" id="value-discount-1" value="">
                                                <input type="text" name="point" data-v-min="0" data-v-max="10000000" id="value-discount-gift-1" class="form-control currentcy" aria-label="Đơn vị VNĐ" placeholder="EX: 100,000">
                                                <span class="input-group-addon">VNĐ</span>
                                            </div>
                                            
                                        </td>

                                        <td style="position:relative">
                                            <input type="button" name="" id="create-option-gift-1" class="form-control btn btn-pink" value="Tạo">
                                        </td>
                                    </tr>
                                
                            </tbody>
                        </table>
                        <center>
                            <button type="button" style="display: none" class="btn btn-pink btn-sm destroy-create-chop">Quy định lại toàn bộ số Chops và giá trị SP</button>
                        </center>
                    </div>
                </div>
                <div class="chops-option-gift-2" style="display:none">
                    <h1 class="pink">Quy định số Chops cần tích lũy và (%) giảm giá</h1>
                    <hr>
                    <div class="table-responsive" style="overflow-y: hidden; ">

                    
                        <table class="table" id="list-gift-2">
                            <thead>
                                <tr>
                                    <th style="width:40%">Số Chops cần tích lũy
                                    </th>
                                    <th style="width:40%">Giá trị (%) giảm giá
                                    </th>
                                    <th style="width:20%"></th>
                                </tr>
                            </thead>
                            <tbody class="customize-input-point">
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control choice-stick" id="select-chop-gift-2">
                                                <option value="">-- Số lượng Chops --</option>

                                                @for ($i = 1; $i <= 15 ; $i++) { 
                                                    <option value="{{ $i }}">{{ $i }} Chops</option>
                                                @endfor

                                            </select>
                                            <input type="hidden" id="select-chop-gift-2-1" value="" >
                                            <input type="hidden" id="select-chop-gift-2-2" value="" >
                                            <input type="hidden" id="select-chop-gift-2-3" value="" >
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="hidden" id="value-discount-gift-2-1" value="" >
                                            <input type="hidden" id="value-discount-gift-2-2" value="" >
                                            <input type="hidden" id="value-discount-gift-2-3" value="" >

                                            <input type="text" name="point" data-v-min="0" data-v-max="100" id="value-discount-gift-2" class="form-control currentcy" aria-label="Đơn vị VNĐ" placeholder="EX: 10">
                                            <span class="input-group-addon">%</span>
                                        </div>
                                    </td>

                                    <td style="position:relative">
                                        <input type="submit" name="" id="create-option-gift-2" class="form-control btn btn-pink" value="Tạo">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <center>
                            <button type="button" style="display: none" class="btn btn-pink btn-sm destroy-create-chop-2">Quy định lại toàn bộ số Chops và % giảm giá</button>
                        </center>

                    </div>
                </div>
            </div>
