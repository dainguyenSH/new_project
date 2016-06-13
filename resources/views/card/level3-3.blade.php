<!-- Option type level 2 -->
                <div class="typel-level-option-3">
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
                                </tr>
                            </thead>
                            <tbody class="customize-input-point ex-value">
                                <tr>
                                    <td>
                                        <h4 class="pink">VIP</h4>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="text" name="vip33" id="point-1" data-v-min="0" data-v-max="100000" class="form-control currentcy" aria-label="VND" value="{{ $infoCard['value'][0]['value'][0]['point'] or '0' }}">
                                            <span class="input-group-addon">Điểm</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="text" name="vip4" id="bonus-point-1" data-v-min="0" data-v-max="100" class="form-control currentcy" aria-label="VND" value="{{ $infoCard['value'][0]['value'][0]['bouns'] or '0' }}">
                                            <span class="input-group-addon">%</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h4 class="pink">Thành viên</h4>
                                    </td>
                                    <td style="position:relative">
                                        <div class="input-group">
                                            <input type="text" name="mem3" id="point-2" data-v-min="0" data-v-max="100000" class="form-control currentcy" aria-label="VND" value="{{ $infoCard['value'][1]['value'][0]['point'] or '0' }}">
                                            <span class="input-group-addon">Điểm</span>
                                        </div>
                                    </td>
                                    <td style="position:relative">
                                        <div class="input-group">
                                            <input type="text" name="mem4" id="bonus-point-2" data-v-min="0" data-v-max="100" class="form-control currentcy" aria-label="VND" value="{{ $infoCard['value'][1]['value'][0]['bouns'] or '0' }}">
                                            <span class="input-group-addon">%</span>
                                        </div>
                                    </td>
                                </tr>
                                
                            </tbody>
                        </table>

                        <center>
                            <button type="button" data-merchant-id="{{$merchantId}}" data-card-type="2" class="btn btn-pink edit-card-info-admin">Chỉnh sửa hạng thẻ</button>
                        </center>
                    </div>
                </div>

                <!-- end option lv3 -->
                
