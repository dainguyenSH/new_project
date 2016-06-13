<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\NhanhService;

class CrawlingData extends Model
{
    protected $table = 'crawling_data';
    public    $incrementing = true;
    protected $primaryKey   = 'id';

    /**
     * DATNT - 301215
     */
    public static function getData($userId = 0, $partner = 0) 
    {
        $data = self::where("user_id", $userId)->where("partner", $partner)->get();
        return $data->first();
    }
    
    public static function updateData($userId = 0, $partner = 0, $data = array()) 
    {
        // check exist data
        $check = 0;
        $status = 0;
        $value = self::where("user_id", $userId)->where("partner", $partner)->get();
        if (count($value)) {
            $value = $value->first();
        } else {
            $value = new CrawlingData;
        }
        $value->user_id = $userId;
        $value->partner = $partner;
        $value->data = serialize($data);
        if ($value->save()) {
            $status = 1; 
        }
        return $status;
        
    }
    
    public static function getCgv($userId = 0, $username = '', $password = 0)
    {
        $user = array(
                'profile'=> array(
                        'firstName' => '',
                        'middleName' => '',
                        'lastName' => '',
                        'birthday' => '',
                        'gender' => '',
                        'phone' => '',
                        'pointAvailable' => 0,
                        'pointTotal' => 0,
                        'overview' => array(), 
                ),
                'historyEarn' => array (),
                'historyConvert' => array (),
                
            );
        /**** Login request ****/
        $postdata = array(
            "login" => array(
                        "username" => $username,
                        "password" => $password
                    )
        );
        $data = http_build_query($postdata);
        $cookie = storage_path()."/cookie/CGV/$userId.txt"; 
        if (file_exists($cookie)) {
            unlink($cookie);
        }
        
        $url = 'https://www.cgv.vn/vn/customer/account/loginPost/';    
        // Process login request - double for CGV
        self::sendRequestPost($url, $data, $cookie, 0);
        self::sendRequestPost($url, $data, $cookie, 0);
        
        /**** Get data request - basic info ****/
        $url = 'https://www.cgv.vn/vn/customer/account/edit/';  
        // Process get data request   
        $result = self::sendRequestGet($url, $cookie, 0);
        
        //check login
        preg_match('/<a href="https:\/\/www.cgv.vn\/vn\/customer\/account\/logout\/"(?:.*?)>(.*?)<\/a>/s', $result, $login);
        if (!isset($login) || empty($login)) {
            return array('status' => 'fail', 'error' => 'login fail');
        }
        // end check login
        preg_match('/<input type="text" id="firstname" name=\"firstname\" value=\"(.*?)\"/i', $result, $firstName);
        if (isset($firstName) && !empty($firstName)) {
            $firstName = $firstName[1];
            $user['profile']['firstName'] = $firstName;
        }
        preg_match('/<input type="text" id="lastname" name=\"lastname\" value=\"(.*?)\"/i', $result, $lastName);
        if (isset($lastName) && !empty($lastName)) {
            $lastName = $lastName[1];
            $user['profile']['lastName'] = $lastName;
        }
        preg_match('/<input type="tel" name=\"telephone\" value=\"(.*?)\"/i', $result, $phone);
        if (isset($phone) && !empty($phone)) {
            $phone = $phone[1];
            $user['profile']['phone'] = $phone;
        }
        preg_match('/<select id="gender"(.*?)">(.*?)<\/select>/s', $result, $select);
        if (isset($select) && !empty($select)) {
            preg_match('/<option(?:.*?)selected="selected">(.*?)<\/option>/s', $select[0], $gender);
            if (isset($gender) && !empty($gender)) {
                if (strpos($gender[1],'Nam') !== false) {
                    $gender = 'Male';
                } elseif ($gender[1]==NULL) {
                    $gender = 'Unknown';
                } else {
                    $gender = 'Female';
                }
            }
            $user['profile']['gender'] = $gender;
            
        }
        
        $day = '00';
        $month = '00';
        $year = '0000';
        preg_match('/<input type="text" id="day" name="day" value=\"(.*?)\"/i', $result, $day);
        if (isset($day) && !empty($day)) {
            $day = $day[1];
        }
        preg_match('/<input type="text" id="month" name="month" value=\"(.*?)\"/i', $result, $month);
        if (isset($month) && !empty($month)) {
            $month = $month[1];
        }
        preg_match('/<input type="text" id="year" name="year" value=\"(.*?)\"/i', $result, $year);
        if (isset($year) && !empty($year)) {
            $year = $year[1];
        }
        $user['profile']['birthday'] =  $day.'/'.$month.'/'.$year;
        
        /**** Get data request ****/
        $url = 'https://www.cgv.vn/vn/sales/order/history/';  
        // Process get data request   
        $result = self::sendRequestGet($url, $cookie, 0);
        $listTd = array();
        preg_match('/<table id=\"my\-orders\-table" class=\"data\-table orders">(.*?)<\/table>/s', $result, $tableTags);
        if (isset($tableTags) && !empty($tableTags)) {
            preg_match('/<tbody>(.*?)<\/tbody>/s', $tableTags[0], $tbodyTags);
        }
        if (isset($tbodyTags) && !empty($tbodyTags)) {
            preg_match_all('/<tr>(.*?)<\/tr>/s', $tbodyTags[0], $trTags);
        }
        if (isset($trTags) && !empty($trTags)) {
            foreach ($trTags[0] as $index=>$value) {
                preg_match_all('/<td>(.*?)<\/td>/s', $value, $tdTags);
                $listTd[$index] = $tdTags;
            }
        }
        if (!empty($listTd)) {
            $countEarn = 0;
            $countConvert = 0;
            foreach ($listTd as $index=>$transaction) {
                $transaction = $transaction[0];
                
                $value = array(
                                'dateTime' => strip_tags($transaction[0]),
                                'place' => strip_tags($transaction[1]),
                                'item' => strip_tags($transaction[2]),
                                'type' => strip_tags($transaction[3]),
                                'pointConvert' => strip_tags($transaction[4]),
                                'pointEarned' => strip_tags($transaction[5]),
                                'spend' => strip_tags($transaction[6]),
                                'curentPointEarned' => 0,
                );
                
                if ($value['pointConvert'] != 0) {
                    $user['historyConvert'][$countConvert] = $value;
                    
                    $countConvert++;
                } else {
                    $user['historyEarn'][$countEarn] = $value;
                    $countEarn++;
                }
                $user['profile']['pointAvailable'] += (float) $value['pointEarned'] - (float) $value['pointConvert'];
                $user['profile']['pointTotal'] += (float) $value['pointEarned'] + (float) $value['pointConvert'];  
            }
        }
        $result = self::updateData($userId, 1, $user);
        if ($result) {
            return array('status' => 'success', 'data' => $user);
        }
        return array('status' => 'fail', 'error' => 'save data fail');
    }
    
    public static function getAlfresco($userId = 0, $username = '', $password = 0)
    {
        $user = array(
                'profile'=> array(
                        'firstName' => '',
                        'middleName' => '',
                        'lastName' => '',
                        'birthday' => '',
                        'gender' => '',
                        'phone' => '',
                        'pointAvailable' => 0,
                        'pointTotal' => 0,
                        'overview' => array(), 
                ),
                'historyEarn' => array (),
                'historyConvert' => array (),
                
        );
        
        $data = array(
                        'ac' => 'login',
                        'user' => $username,
                        'pass' => $password
            );
        $data = http_build_query($data);
        $cookie = storage_path()."/cookie/Alf/$userId.txt"; 
        if (file_exists($cookie)) {
            unlink($cookie);
        }
        
        $url = 'http://www.rewardsplus.vn/useraction.aspx';
        self::sendRequestPost($url, $data, $cookie, 0);
        
        $url = 'http://www.rewardsplus.vn/points.aspx';
        self::sendRequestGet($url, $cookie, 0);
        
        $url = 'http://www.rewardsplus.vn/useraction.aspx';
        $return = self::sendRequestPost($url, $data, $cookie, 0);
        
        if ($return == 'INVALID') {
            return array('status' => 'fail', 'error' => 'login fail');
        }
        $url = 'http://www.rewardsplus.vn/points.aspx';
        $return = self::sendRequestGet($url, $cookie, 0);

        preg_match('/<table width="98%" border="0" cellspacing="2" cellpadding="2">(.*?)<\/table>/s', $return, $table);
        if (isset($table) && !empty($table)) {
            preg_match_all('/<tr>(.*?)<\/tr>/s', $table[0], $trTags);
        }
        $overview = array();
        if (isset($trTags) && !empty($trTags)) {
            foreach ($trTags[0] as $index=>$tag) {
                preg_match('/<strong>(.*?)<\/strong>/s', $tag, $title);
                preg_match('/<strong style="color:#F37521;">(.*?)<\/strong>/s', $tag, $value);
                $title = strip_tags($title[0]);
                $value = strip_tags($value[0]);
                $overview[$title] = $value;
                $user['profile']['pointAvailable'] += (float) $value;
                $user['profile']['pointTotal'] += (float) $value;  
            }
        }
        
        preg_match('/<table width="100%" border="0" cellspacing="1" cellpadding="2" bgcolor="#f9f9f9" style="padding-bottom:10px;">(.*?)<\/table>/s', $return, $table);
        if (isset($table) && !empty($table)) {
            preg_match_all('/<tr>(.*?)<\/tr>/s', $table[0], $trTags);
        }
        $user['profile']['overview']  = $overview;
        $history = array();
        if (isset($trTags) && !empty($trTags)) {
            foreach ($trTags[0] as $index=>$tag) {
                //preg_match('/<strong>(.*?)<\/strong>/s', $tag, $title);
                //preg_match('/<strong style="color:#F37521;">(.*?)<\/strong>/s', $tag, $value);
                //$title = strip_tags($title[0]);
                //$value = strip_tags($value[0]);
                //$overview[$title] = $value;
                if ($index) {
                     $item = array(
                                'dateTime' => '',
                                'place' => '',
                                'item' => '',
                                'type' => '',
                                'pointConvert' => 0,
                                'pointEarned' => 0,
                                'spend' => 0,
                                'curentPointEarned' => 0,
                     );
                    
                     preg_match_all('/<div align="center">(.*?)<\/div>/s', $tag, $divs);
                     if (isset($divs[0][0])) {
                        $item['dateTime'] = strip_tags($divs[0][0]);
                     }
                     if (isset($divs[0][1])) {
                        $item['spend'] = strip_tags($divs[0][1]);
                     }
                     if (isset($divs[0][2])) {
                        $item['pointEarned'] = strip_tags($divs[0][2]);
                     }
                     if (isset($divs[0][3])) {
                        $item['place'] = strip_tags($divs[0][3]);
                     }
                     if (isset($divs[0][4])) {
                        $item['curentPointEarned'] = strip_tags($divs[0][4]);
                     }
                     $history[$index-1] = $item;
                }
            }
        }
        $user['historyEarn'] = $history;
        
        // Get personal info
        $url = 'http://www.rewardsplus.vn/home.aspx';
        $return = self::sendRequestGet($url, $cookie, 0);
        
        preg_match('/<div class="huho-layout-cell1">(.*?)<\/div>/s', $return, $divs);
        if (isset($divs) && !empty($divs)) {
            preg_match('/<a href="(.*?)">/s', $divs[0], $id);
        }
        preg_match('/<a href="(.*?)">/s', $divs[0], $id);
        if (isset($id) && !empty($id)) {
            $id = $id[1];
        }
        if (isset($id) && !empty($id)) {
            $url = 'http://www.rewardsplus.vn/'.$id;
            $result = self::sendRequestGet($url, $cookie, 0); 
            
            preg_match('/<input type="text" name="FirstName" placeholder="" value=\"(.*?)\"/i', $result, $firstName);
            if (isset($firstName) && !empty($firstName)) {
                $firstName = $firstName[1];
                $user['profile']['firstName'] = $firstName;
            } 
            preg_match('/<input type="text" placeholder="" size="23" id="editmiddle_name" value=\"(.*?)\"/i', $result, $middleName);
            if (isset($middleName) && !empty($middleName)) {
                $middleName = $middleName[1];
                $user['profile']['middleName'] = $middleName;
            } 
            preg_match('/<input type="text" name="LastName" placeholder="" value=\"(.*?)\"/i', $result, $lastName);
            if (isset($lastName) && !empty($lastName)) {
                $lastName = $lastName[1];
                $user['profile']['lastName'] = $lastName;
            } 
            preg_match('/<input type="text" name="Mobile" size="23" id="editmobile" value=\"(.*?)\"/i', $result, $phone);
            if (isset($phone) && !empty($phone)) {
                $phone = $phone[1];
                $user['profile']['phone'] = $phone;
            }
            preg_match('/<select name="Gender" style="width:8em;" id="editgender">(.*?)<\/select>/s', $result, $select);
            if (isset($select) && !empty($select)) {
                preg_match('/<option(?:.*?)selected="selected"(?:.*?)>(.*?)<\/option>/s', $select[0], $gender);
                if (isset($gender[1])) {
                    $user['profile']['gender'] = $gender[1];
                }
            }
        }
        
        $result = self::updateData($userId, 2, $user);
        if ($result) {
            return array('status' => 'success', 'data' => $user);
        }
        return array('status' => 'fail', 'error' => 'save data fail');

    }
    
    public static function getBooCustomerInfo($mobile = '00', $page = 1)
    {
        // header('Content-type: text/html; charset=utf-8');
        
        //require_once(storage_path().'\helpers\NhanhAPI\src\NhanhService.php');
        
        $data = array(
            'page' => $page,
        	'mobile' => $mobile
        );
        
        $service = new NhanhService();
        
        $response = $service->sendRequest(NhanhService::URI_CUSTOMER_SEARCH, $data);

        return $response;
    }
    
    public static function getBooCustomerBill($mobile = '00', $page = 1)
    {
        // header('Content-type: text/html; charset=utf-8');
        
        // require_once(storage_path().'\helpers\NhanhAPI\src\NhanhService.php');
        
        $data = array(
            'page' => $page,
        	'customerMobile' => $mobile
        );
        
        $service = new NhanhService();
        
        $response = $service->sendRequest(NhanhService::URI_BILL_SEARCH, $data);

        return $response;
    }
    
    public static function addBooCustomer($info = array())
    {
        // header('Content-type: text/html; charset=utf-8');
        
        // require_once(storage_path().'\helpers\NhanhAPI\src\NhanhService.php');
        
        $data = array();
        $data[0] = $info;
        $service = new NhanhService();
        
        $response = $service->sendRequest(NhanhService::URI_CUSTOMER_ADD, $data);

        return $response;
    }
    
    private static function sendRequestGet($url, $cookie, $type=0)
    {
        $ch = curl_init(); 
        curl_setopt ($ch, CURLOPT_URL, $url); 
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
        curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.73 Safari/537.36"); 
        curl_setopt ($ch, CURLOPT_TIMEOUT, 10); 
        curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 0); 
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
        //curl_setopt ($ch, CURLOPT_COOKIESESSION, true);
        //curl_setopt ($ch, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt ($ch, CURLOPT_COOKIEFILE, $cookie);  
        curl_setopt ($ch, CURLOPT_CAINFO, storage_path().'\SSL\cacert.pem');
        //curl_setopt ($ch, CURLOPT_POST, 1); 
        //curl_setopt ($ch, CURLOPT_POSTFIELDS, $data); 
        if ($type) {
            curl_setopt ($ch, CURLOPT_HTTPHEADER, array("content-type: application/json"));
        }
        
        $result = curl_exec ($ch); 
        curl_close($ch);  
        return $result;              
    }
    
    private static function sendRequestPost($url, $data, $cookie, $type=0)
    {
        $ch = curl_init(); 
        curl_setopt ($ch, CURLOPT_URL, $url); 
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
        curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.73 Safari/537.36"); 
        curl_setopt ($ch, CURLOPT_TIMEOUT, 10); 
        curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 0); 
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
        //curl_setopt ($ch, CURLOPT_COOKIESESSION, true);
        curl_setopt ($ch, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt ($ch, CURLOPT_COOKIEFILE, $cookie);  
        curl_setopt ($ch, CURLOPT_CAINFO, storage_path().'\SSL\cacert.pem');
        curl_setopt ($ch, CURLOPT_POST, 1); 
        curl_setopt ($ch, CURLOPT_POSTFIELDS, $data);
        if ($type) {
            curl_setopt ($ch, CURLOPT_HTTPHEADER, array("content-type: application/json"));
        } 
        
        $result = curl_exec ($ch); 
        curl_close($ch);  
        return $result;              
    } 
}
