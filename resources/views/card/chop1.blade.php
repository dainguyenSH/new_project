<div class="chops-option-gift-1">
                    <h1 class="pink">Quy định số Chops cần tích lũy & giá trị SP được đổi</h1>
                    <hr>
                    <div class="table-responsive" style="overflow-y: hidden; ">
                        <table class="table" id="list-gift-1">
                                    @if(Auth::merchant()->get()->package == 0)
                                        <thead>
                                            <tr>
                                                <th style="width:40%">Số Chops cần tích lũy
                                                </th>
                                                <th style="width:40%">Giá trị SP được đổi
                                                </th>
                                                <th style="width:20%"></th>

                                            </tr>
                                        </thead>
                                    @endif
                            <tbody class="customize-input-point">
                                
                                    @if(Auth::merchant()->get()->package == 0)
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
                                    @endif
                                
                            </tbody>
                        </table>
                        
                    </div>
                </div>
