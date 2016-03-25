<?php
/**
 * 分页类
 * 
 * author  lynnluo
 * addtime 2011-08-15
 */


define( 'PAGE_NAME','page' );// url '....php?page=2'中的page名称
define( 'CR',"\n" );// 换行符

class page
{
	private $prev_label  = ' 上一页 ';//
	private $next_label  = ' 下一页 ';//
	private $first_label = ' 首页 ';//
	private $last_label  = ' 尾页 ';// 
	
	private $per_page  	= 10;//每页显示多少条
	private $adjacent_num	= 2;//当前页相邻的显示量
	private $page_num;  //总页数
	private $page;  	//第几页
	
	private $html;//返回分页的html源码
	
	private $display_str_flag = true;//首页和尾页用字符串or数字显示  true：字符串     其它则显示数字
	
	/**
	 * 构造函数
	 * $per_page int 每页显示多少条
	 * $totle int 	总数量 
	 */
	public function __construct( $per_page, $totle )
	{
		$this->per_page = intval($per_page)<1 ? $this->per_page : intval($per_page);//每页显示条数小于1时 默认为10
		$this->page_num = ceil( $totle / $this->per_page );
		$this->page 	= isset($_GET[PAGE_NAME]) ? ( (intval($_GET[PAGE_NAME])>0) ? intval($_GET[PAGE_NAME]):1) : 1;//当前页码小于1时默认为第一页
		$this->page		= $this->page > $this->page_num ?  $this->page_num : $this->page;//当前页码超过最大页码时默认为最后一页
		
		$this->html		= '';
	}
	
	/**
	 * 私有属性赋值时的错误处理
	 */	
	public function __set( $aa ,$bb){}
	
	/**
	 * 配置类的私有属性  
	 * $ary  array  键值对，键为该类的私有属性
	 */
	public function set( $ary = array(  'display_str_flag'=>false,
										'prev_label'=>'>>',
										'next_label'=>'>>',
										'first_label'=>'>>',
										'last_label'=>'>>',
										'adjacent_num'=>5))
	{
		foreach( $ary as $key=>$value )
		{
			$this->{$key} = $value;
		}
	}
	
	/**
	 * 返回当前URL
	 */
	private function getURL()
	{
		$url = $_SERVER["REQUEST_URI"].(strpos($_SERVER["REQUEST_URI"],'?')?'':"?");
		$parse=parse_url($url);
		if(isset($parse['query']))
		{
			parse_str($parse['query'],$params);
			unset($params[PAGE_NAME]);
			$url = $parse['path'].'?'.http_build_query($params);
		}
		$url .= strpos($url, '?') ? (strpos($url, '=')?'&':'') : '' ;
		return $url;
	}
	
	/**
	 * 首页
	 */
	private function first_page()
	{
		if( $this->page == 1 )
		{
			$this->html .= '<li><span>' . $this->first_label . '</span></li>'.CR;
		}
		else 
		{
			$this->html .= '<li><a href="' . $this->getURL() .PAGE_NAME.'=1">' . $this->first_label . '</a></li>'.CR;
		}
	}
	
	/**
	 * 尾页
	 */
	private function last_page()
	{
		if( $this->page == $this->page_num )
		{
			$this->html .= '<li><span>' . $this->last_label . '</span></li>'.CR;
		}
		else 
		{
			$this->html .= '<li><a href="' . $this->getURL() .PAGE_NAME.'='.$this->page_num.'">' . $this->last_label . '</a></li>'.CR;
		}
	}
	
	/**
	 * 上一页
	 */
	private function prev_page()
	{
		if( $this->page == 1 )
		{
			$this->html .= '<li><span>' . $this->prev_label . '</span></li>'.CR;
		}
		else 
		{
			$this->html .= '<li><a href="' . $this->getURL() .PAGE_NAME.'=' . ( $this->page - 1 ) . '">' . $this->prev_label . '</a></li>'.CR;
		}
	}
	
	/**
	 * 下一页
	 */
	private function next_page()
	{ 
		if( $this->page < $this->page_num) 
		{
			$this->html .= '<li><a href="' . $this->getURL().PAGE_NAME.'=' . ( $this->page+1) . '">' . $this->next_label . '</a></li>'.CR;
		}
		else 
		{
			$this->html .= '<li><span>' . $this->next_label . '</span></li>'.CR;
		}
	}
	
	/**
	 * 第一块  第一个省略号前的那块
	 */
	private function first_block()
	{
		if( $this->page > ( $this->adjacent_num+1 ) ) 
		{
			$this->html.= '<li><a href="' . $this->getURL().PAGE_NAME.'=1">1</a></li>'.CR;
		}
		if( $this->page > ( $this->adjacent_num+2 ) ) 
		{
			$this->html.= ''.CR;
		}
	}
	
	/**
	 * 第二块  两个省略号中间的那块
	 */
	private function middle_block()
	{
		$page_min = ( $this->page > $this->adjacent_num ) ? ( $this->page - $this->adjacent_num ) : 1;
		$page_max = ( $this->page < ($this->page_num-$this->adjacent_num)) ? ($this->page+$this->adjacent_num) : $this->page_num ;
		for( $i=$page_min; $i<=$page_max; $i++)
		{
			if( $i == $this->page ) 
			{
				$this->html .= '<li><span class="current">' . $i . '</span></li>'.CR;
			}
			else
			{
				$this->html .= '<li><a href="' . $this->getURL() .PAGE_NAME.'=' . $i . '">' . $i . '</a></li>'.CR;
			}
		}
	}

	/**
	 * 最后一块  最后一个省略号后的的那块
	 */
	private function last_block()
	{
		if( $this->page < ($this->page_num - $this->adjacent_num - 1)) 
		{
			$this->html .= ''.CR;
		}
		if( $this->page < ($this->page_num-$this->adjacent_num )) 
		{
			$this->html .= '<li><a href="' . $this->getURL() .PAGE_NAME.'=' . $this->page_num . '">' . $this->page_num . '</a></li>'.CR;
		}
	}
	
	/**
	 * 显示分页
	 * 
	 * $out_flag bool 输出和反回分页html的标志  true:直接输出   false:返回分页的html
	 * 
	 */
	public function display( $out_flag = false ) 
	{		
		$this->html = ''.CR;
		
		if( $this->display_str_flag === true )
		{//首页和尾页用字符串显示
			$this->first_page();  	//显示首页
			$this->prev_page();		//显示上一页
			$this->middle_block();	//显示中间块
			$this->next_page();		//显示下一页 
			$this->last_page();		//显示最后一页 
		}
		else 
		{//首页和尾页用数字显示
			$this->prev_page(); 	//显示上一页
			$this->first_block();	//显示第一块
			$this->middle_block();	//显示中间块
			$this->last_block();	//显示最后一块
			$this->next_page();		//显示下一页
		}
		
		$this->html .= '';
		
		if($out_flag === false)
		{//返回分页 html码
			return $this->html;
		}
		else
		{//输出分页 html码
			echo $html;
		}
	}
}


?>
