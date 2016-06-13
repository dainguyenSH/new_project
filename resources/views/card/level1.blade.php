<!-- Option type level 1 -->
                <div class="typel-level-option-1">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width:25%">Hạng thẻ
                                    </th>
                                    <th>Cần tích lũy
                                    </th>
                                    <th>% giảm giá
                                    </th>
                                    <!-- <th style="width:15%"></th> -->
                                </tr>
                            </thead>
                            <tbody class="customize-input-point ex-value">
                                <tr>
                                    <td>
                                        <h4 class="pink">Vàng</h4>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <div class="clear-fix"></div>
                                            <input type="text" name="vang1" id="point-3-1" data-v-min="0" data-v-max="10000000" class="form-control currentcy" aria-label="VND" placeholder="EX: 1,000" value="{{ $infoCard['value'][0]['value'][0]['point'] or '0' }}">
                                            <span class="input-group-addon">Điểm</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="text" name="vang2" id="bonus-point-3-1" data-v-min="0" data-v-max="100" class="form-control currentcy" aria-label="VND" value="{{ $infoCard['value'][0]['value'][0]['bouns'] or '0' }}">
                                            <span class="input-group-addon">%</span>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <h4 class="pink">Bạc</h4>
                                    </td>
                                    <td style="position:relative">
                                        <div class="input-group">
                                            <input type="text" name="bac1" id="point-3-2" data-v-min="0" data-v-max="100000" class="form-control currentcy" aria-label="VND" value="{{ $infoCard['value'][1]['value'][0]['point'] or '0' }}">
                                            <span class="input-group-addon">Điểm</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="text" name="bac2" id="bonus-point-3-2" data-v-min="0" data-v-max="100" class="form-control currentcy" aria-label="VND" value="{{ $infoCard['value'][1]['value'][0]['bouns'] or '0' }}">
                                            <span class="input-group-addon">%</span>
                                        </div>
                                    </td>
                                    <!-- <td> -->
                                        <!-- <input type="button" name="" id="" class="form-control btn btn-pink value-config-stores" value="Lưu"> -->
                                    <!-- </td> -->
                                </tr>

                                <tr>
                                    <td>
                                        <h4 class="pink">Đồng</h4>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="text" name="dong1" id="point-3-3" data-v-min="0" data-v-max="100000" class="form-control currentcy" aria-label="VND" value="{{ $infoCard['value'][2]['value'][0]['point'] or '0' }}">
                                            <span class="input-group-addon">Điểm</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="text" name="dong2" id="bonus-point-3-3" data-v-min="0" data-v-max="100" class="form-control currentcy" aria-label="VND" value="{{ $infoCard['value'][2]['value'][0]['bouns'] or '0' }}">
                                            <span class="input-group-addon">%</span>
                                        </div>
                                    </td>
                                </tr>                                
                            </tbody>
                        </table>
                        
                    </div>
                </div>
                <!-- end type level -->
