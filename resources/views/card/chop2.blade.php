<div class="chops-option-gift-2" @if(Auth::merchant()->get()->package == 0) style="display:none" @endif>
                    <h1 class="pink">Quy định số Chops cần tích lũy và (%) giảm giá</h1>
                    <hr>
                    <div class="table-responsive" style="overflow-y: hidden; ">

                    
                        @if(Auth::merchant()->get()->package == 0)
                            <table class="table" id="list-gift-2">
                                        @if(Auth::merchant()->get()->package == 0)
                                        <thead>
                                            <tr>
                                                <th style="width:40%">Số Chops cần tích lũy
                                                </th>
                                                <th style="width:40%">Giá trị (%) giảm giá
                                                </th>
                                                <th style="width:20%"></th>
                                            </tr>
                                        </thead>
                                        @endif
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
                        @endif

                    </div>
                </div>
