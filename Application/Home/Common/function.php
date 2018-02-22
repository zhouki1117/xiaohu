<?php 
function getCarInfoByCarTypeID($id)
{
	return D('CarInfoView')->where('car_type.id='.$id)->find();
}

function getOilInfo($id)
{
	return M('car_oil')->find($id);
}

function totalPrice($data)
{
	return $data['total']+F('service_price')-$data['card_price'];
}

function jsonToHtml($json)
{
	$array = objectToArray(json_decode($json));
	$temp = array();

	if(array_key_exists('car_oil', $array)){
		$oil = getOilInfo($array['car_oil']);
		echo "<tr><td>机油</td><td>{$oil['title']} {$oil['price']}元</td></tr>";
	}
	if(array_key_exists('jilv', $array)){
		echo "<tr><td>机油滤清器</td><td>{$array['jilv']}元</td></tr>";
	}
	if(array_key_exists('qilv', $array)){
		echo "<tr><td>空气滤清器</td><td>{$array['qilv']}元</td></tr>";
	}
	if(array_key_exists('konglv', $array)){
		echo "<tr><td>空调滤清器</td><td>{$array['konglv']}元</td></tr>";
	}
	if(array_key_exists('front_pian', $array)){
		echo "<tr><td>前刹车片</td><td>{$array['front_pian']}元</td></tr>";
	}
	if(array_key_exists('back_pian', $array)){
		echo "<tr><td>后刹车片</td><td>{$array['back_pian']}元</td></tr>";
	}
	if(array_key_exists('front_pan', $array)){
		echo "<tr><td>前刹车盘</td><td>{$array['front_pan']}元</td></tr>";
	}
	if(array_key_exists('back_pan', $array)){
		echo "<tr><td>后刹车盘</td><td>{$array['back_pan']}元</td></tr>";
	}
	if(array_key_exists('huohuasai', $array)){
		echo "<tr><td>火花塞</td><td>{$array['huohuasai']}元</td></tr>";
	}
	if(array_key_exists('shacheyou', $array)){
		echo "<tr><td>刹车油</td><td>{$array['shacheyou']}元</td></tr>";
	}
	if(array_key_exists('neishi', $array)){
		echo "<tr><td>内饰清洗</td><td>{$array['neishi']}元</td></tr>";
	}
	if(array_key_exists('kt', $array)){
		echo "<tr><td>空调清洗</td><td>{$array['kt']}元</td></tr>";
	}
	if(array_key_exists('jicang', $array)){
		echo "<tr><td>发动机舱清洗</td><td>{$array['jicang']}元</td></tr>";
	}
	if(array_key_exists('lungu', $array)){
		echo "<tr><td>轮毂清洗</td><td>{$array['lungu']}元</td></tr>";
	}
}

/**
 * 对象转数组
 * @param  [type] $obj [description]
 * @return [type]      [description]
 */
function objectToArray($obj){
    $arr = is_object($obj) ? get_object_vars($obj) : $obj;
    if(is_array($arr)){
        return array_map(__FUNCTION__, $arr);
    }else{
        return $arr;
    }
}

/*
 * 经典的概率算法，
 * $proArr是一个预先设置的数组，
 * 假设数组为：array(100,200,300，400)，
 * 开始是从1,1000 这个概率范围内筛选第一个数是否在他的出现概率范围之内，
 * 如果不在，则将概率空间，也就是k的值减去刚刚的那个数字的概率空间，
 * 在本例当中就是减去100，也就是说第二个数是在1，900这个范围内筛选的。
 * 这样 筛选到最终，总会有一个数满足要求。
 * 就相当于去一个箱子里摸东西，
 * 第一个不是，第二个不是，第三个还不是，那最后一个一定是。
 * 这个算法简单，而且效率非常 高，
 * 关键是这个算法已在我们以前的项目中有应用，尤其是大数据量的项目中效率非常棒。
 */
function get_rand($proArr) {
    $result = '';
    //概率数组的总概率精度
    $proSum = array_sum($proArr);
    //概率数组循环
    foreach ($proArr as $key => $proCur) {
        $randNum = mt_rand(1, $proSum);
        if ($randNum <= $proCur) {
            $result = $key;
            break;
        } else {
            $proSum -= $proCur;
        }
    }
    unset ($proArr);
    return $result;
}

 ?>