<?php

use Illuminate\Database\Seeder;
use App\OptionValue;
class OptionValueTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('optionvalues')->truncate();

		//linh vuc
		OptionValue::create(array(
			'options_id' =>1,
			'name'     => 'Food & Beverage',
		));
		OptionValue::create(array(
			'options_id' =>1,
			'name'     => 'Fashion',
		));
		OptionValue::create(array(
			'options_id' =>1,
			'name'     => 'Comestic',
		));
		OptionValue::create(array(
			'options_id' =>1,
			'name'     => 'Club & Entertaiment',
		));
		OptionValue::create(array(
			'options_id' =>1,
			'name'     => 'Spa',
		));

		//package
		$free = array();
		$free['stores_max'] = 1;
		$free['deals_max'] = 1;
		$free['messages_max'] = 0;
		$free['manager_feedback'] = 0;
		$free['budget'] = 0;

		OptionValue::create(array(
			'options_id' =>2,
			'name'     => 'Free',
			'info'	   => json_encode($free)
		));

		$premium1 = array();
		$premium1['stores_max'] = 2;
		$premium1['deals_max'] = 2;
		$premium1['messages_max'] = 2;
		$premium1['manager_feedback'] = 1;
		$premium1['budget'] = 199;
		OptionValue::create(array(
			'options_id' =>2,
			'name'     => 'Premium 1',
			'info'	   => json_encode($premium1)
		));
		
		$premium2 = array();
		$premium2['stores_max'] = 5;
		$premium2['deals_max'] = 5;
		$premium2['messages_max'] = 5;
		$premium2['manager_feedback'] = 1;
		$premium2['budget'] = 439;
		OptionValue::create(array(
			'options_id' =>2,
			'name'     => 'Premium 2',
			'info'	   => json_encode($premium2)
		));

		$premium3 = array();
		$premium3['stores_max'] = 999;
		$premium3['deals_max'] = 20;
		$premium3['messages_max'] = 20;
		$premium3['manager_feedback'] = 1;
		$premium3['budget'] = 9999;
		OptionValue::create(array(
			'options_id' =>2,
			'name'     => 'Premium 3',
			'info'	   => json_encode($premium3)
		));

		// card level
		
		$levelvang = array();
		$levelvang['type'] = 1; // loai vang bac dong
		$levelvang['note'] = 'Level: vang, bac, dong';
		$levelvang['background_color'] = '#ffff3e';
		$levelvang['text_color'] = '#000000';
		OptionValue::create(array(
			'options_id' =>3,
			'name'     => 'Vàng',
			'info'	   => json_encode($levelvang)
		));

		$levelbac = array();
		$levelbac['type'] = 1; // loai vang bac dong
		$levelbac['note'] = 'Level: vang, bac, dong';
		$levelbac['background_color'] = '#a4a6aa';
		$levelbac['text_color'] = '#000000';
		OptionValue::create(array(
			'options_id' =>3,
			'name'     => 'Bạc',
			'info'	   => json_encode($levelbac)
		));

		$leveldong = array();
		$leveldong['type'] = 1; // loai vang bac dong
		$leveldong['note'] = 'Level: vang, bac, dong';
		$leveldong['background_color'] = '#935d38';
		$leveldong['text_color'] = '#000000';
		OptionValue::create(array(
			'options_id' =>3,
			'name'     => 'Đồng',
			'info'	   => json_encode($leveldong)
		));

		$levelvip2 = array();
		$levelvip2['type'] = 2; // loai vip, thanh vien
		$levelvip2['note'] = 'Level: vip, thanh vien';
		$levelvip2['background_color'] = '#F8F5FA';
		$levelvip2['text_color'] = '#000000';
		OptionValue::create(array(
			'options_id' =>3,
			'name'     => 'VIP',
			'info'	   => json_encode($levelvip2)
		));

		$levelthanhvien2 = array();
		$levelthanhvien2['type'] = 2; // loai vip, thanh vien
		$levelthanhvien2['note'] = 'Level: vip, thanh vien';
		$levelthanhvien2['background_color'] = '#EA141E';
		$levelthanhvien2['text_color'] = '#000000';
		OptionValue::create(array(
			'options_id' =>3,
			'name'     => 'Thành Viên',
			'info'	   => json_encode($levelthanhvien2)
		));

		//card chops chops
		$chops1 = array();
		$chops1['type'] = 1;
		OptionValue::create(array(
			'options_id' =>4,
			'name'     => 'Tặng miễn phí 1 sản phẩm khi tích đủ Chops',
			'info'	   => json_encode($chops1)
		));
		$chops2 = array();
		$chops2['type'] = 2;
		OptionValue::create(array(
			'options_id' =>4,
			'name'     => 'Giảm giá (%) cho hóa đơn tiếp theo khi tích đủ Chops',
			'info'	   => json_encode($chops2)
		));

		//month
		
		$month2 = array();
		$month2['sale'] = 0;
		OptionValue::create(array(
			'options_id' =>5,
			'name'     => '3',
			'info'	   => json_encode($month2)
		));

		$month3 = array();
		$month3['sale'] = 5;
		OptionValue::create(array(
			'options_id' =>5,
			'name'     => '6',
			'info'	   => json_encode($month3)
		));

		$month4 = array();
		$month4['sale'] = 10;
		OptionValue::create(array(
			'options_id' =>5,
			'name'     => '12',
			'info'	   => json_encode($month4)
		));

		$month1 = array();
		$month1['sale'] = 15;
		OptionValue::create(array(
			'options_id' =>5,
			'name'     => '24',
			'info'	   => json_encode($month1)
		));

		//linh vuc
		OptionValue::create(array(
			'options_id' =>1,
			'name'     => 'Hotel',
		));
		OptionValue::create(array(
			'options_id' =>1,
			'name'     => 'Others',
		));

		//level VVIP, VIP, Thanh Vien

		$levelvvip = array();
		$levelvvip['type'] = 3; // VVIP, VIP, Thanh Vien
		$levelvvip['note'] = 'Level: vvip, vip, thanh vien';
		$levelvvip['background_color'] = '#E9001D';
		$levelvvip['text_color'] = '#000000';
		OptionValue::create(array(
			'options_id' =>3,
			'name'     => 'VVIP',
			'info'	   => json_encode($levelvvip)
		));

		$levelvip = array();
		$levelvip['type'] = 3; // VVIP, VIP, Thanh Vien
		$levelvip['note'] = 'Level: vvip, vip, thanh vien';
		$levelvip['background_color'] = '#F8F5FA';
		$levelvip['text_color'] = '#000000';
		OptionValue::create(array(
			'options_id' =>3,
			'name'     => 'VIP',
			'info'	   => json_encode($levelvip)
		));

		$levelthanhvien = array();
		$levelthanhvien['type'] = 3; 
		$levelthanhvien['note'] = 'Level: thanh vien';
		$levelthanhvien['background_color'] = '#EA141E';
		$levelthanhvien['text_color'] = '#000000';
		OptionValue::create(array(
			'options_id' =>3,
			'name'     => 'Thành Viên',
			'info'	   => json_encode($levelthanhvien)
		));

		$levelNewbe = array();
		$levelNewbe['type'] = 4; // Newbe
		$levelNewbe['note'] = 'Level: Newbie';
		$levelNewbe['background_color'] = '#f9658d';
		$levelNewbe['text_color'] = '#000000';
		OptionValue::create(array(
			'options_id' =>3,
			'name'     => 'Newbie',
			'info'	   => json_encode($levelNewbe)
		));

		$levelyoung = array();
		$levelyoung['type'] = 5; // young - cua cgv
		$levelyoung['note'] = 'Level: Young';
		$levelyoung['background_color'] = '#FFE5D0';
		$levelyoung['text_color'] = '#000000';
		OptionValue::create(array(
			'options_id' =>3,
			'name'     => 'Young',
			'info'	   => json_encode($levelyoung)
		));

		$levelmember = array();
		$levelmember['type'] = 5; // member - cua cgv
		$levelmember['note'] = 'Level: Member';
		$levelmember['background_color'] = '#EA141E';
		$levelmember['text_color'] = '#000000';
		OptionValue::create(array(
			'options_id' =>3,
			'name'     => 'Member',
			'info'	   => json_encode($levelmember)
		));

    }
}
