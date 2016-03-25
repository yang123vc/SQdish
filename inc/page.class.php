<?php
/**
 * ��ҳ��
 * 
 * author  lynnluo
 * addtime 2011-08-15
 */


define( 'PAGE_NAME','page' );// url '....php?page=2'�е�page����
define( 'CR',"\n" );// ���з�

class page
{
	private $prev_label  = ' ��һҳ ';//
	private $next_label  = ' ��һҳ ';//
	private $first_label = ' ��ҳ ';//
	private $last_label  = ' βҳ ';// 
	
	private $per_page  	= 10;//ÿҳ��ʾ������
	private $adjacent_num	= 7;//��ǰҳ���ڵ���ʾ��
	private $page_num;  //��ҳ��
	private $page;  	//�ڼ�ҳ
	private $isw;  	//
	
	private $html;//���ط�ҳ��htmlԴ��
	//private  $isw;
	
	private $display_str_flag = true;//��ҳ��βҳ���ַ���or������ʾ  true���ַ���     ��������ʾ����
	
	/**
	 * ���캯��
	 * $per_page int ÿҳ��ʾ������
	 * $totle int 	������ 
	 */
	public function __construct( $per_page, $totle ,$isw)
	{
		$this->per_page = intval($per_page)<1 ? $this->per_page : intval($per_page);//ÿҳ��ʾ����С��1ʱ Ĭ��Ϊ10
		$this->page_num = ceil( $totle / $this->per_page );
		$this->page 	= isset($_GET[PAGE_NAME]) ? ( (intval($_GET[PAGE_NAME])>0) ? intval($_GET[PAGE_NAME]):1) : 1;//��ǰҳ��С��1ʱĬ��Ϊ��һҳ
		$this->page		= $this->page > $this->page_num ?  $this->page_num : $this->page;//��ǰҳ�볬�����ҳ��ʱĬ��Ϊ���һҳ
		
		$this->html		= '';
		//$isw;
	 $this->isw=$isw;
	}
	
	/**
	 * ˽�����Ը�ֵʱ�Ĵ�����
	 */	
	public function __set( $aa ,$bb){}
	
	/**
	 * �������˽������  
	 * $ary  array  ��ֵ�ԣ���Ϊ�����˽������
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
	 * ���ص�ǰURL
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
	private function getURLwjt($p=0)
	{ $tid= intval($_GET['tid']);
		if($tid){
			if($p){
			$url="/a/$tid/index_$p.html";
			}else{			
			$url="/a/$tid/index.html";
			}
		}else{
			if($p){
				$url="/a/index_$p.html";
			}else{			
				$url="/a/index.html";
			}			
		}
		
		
		return $url;
	}
	/**
	 * ��ҳ
	 */
	private function first_page()
	{		
		if( $this->page == 1 )
		{
			$this->html .= '<a>' . $this->first_label . '</a>'.CR;
		}
		else 
		{	
			if($isw){
				$this->html .= '<a href="'.$this->getURLwjt(0).'">' . $this->first_label . '</a>'.CR;
			}else{
				$this->html .= '<a href="' . $this->getURL() .PAGE_NAME.'=1">' . $this->first_label . '</a>'.CR;
			}
		}  
	}
	
	/**
	 * βҳ
	 */
	private function last_page()
	{
		if( $this->page == $this->page_num )
		{
			$this->html .= '<a>' . $this->last_label . '</a>'.CR;
		}
		else 
		{
			$this->html .= '<a href="' . $this->getURL() .PAGE_NAME.'='.$this->page_num.'">' . $this->last_label . '</a>'.CR;
		}
	}
	
	/**
	 * ��һҳ
	 */
	private function prev_page()
	{
		if( $this->page == 1 )
		{
			$this->html .= '<a>' . $this->prev_label . '</a>'.CR;
		}
		else 
		{	if($this->isw){
			$this->html .= '<a href="'.$this->getURLwjt($this->page - 1).'">' . $this->prev_label . '</a>'.CR;
		}else{
			$this->html .= '<a href="' . $this->getURL() .PAGE_NAME.'=' . ( $this->page - 1 ) . '">' . $this->prev_label . '</a>'.CR;
		}
			
		}
	}
	
	/**
	 * ��һҳ
	 */
	private function next_page()
	{ 
		if( $this->page < $this->page_num) 
		{
			if($this->isw){
				$this->html .= '<a href="'.$this->getURLwjt($this->page+1).'">' . $this->next_label . '</a>'.CR;
			}else{
				$this->html .= '<a href="' . $this->getURL().PAGE_NAME.'=' . ( $this->page+1) . '">' . $this->next_label . '</a>'.CR;
			}
			
		}
		else 
		{
			$this->html .= '<a>' . $this->next_label . '</a>'.CR;
		}
	}
	
	/**
	 * ��һ��  ��һ��ʡ�Ժ�ǰ���ǿ�
	 */
	private function first_block()
	{
		if( $this->page > ( $this->adjacent_num+1 ) ) 
		{
			if($this->isw){
				$this->html.= '<a href="' . $this->getURLwjt(0).'">1</a>'.CR;
			}else{
				$this->html.= '<a href="' . $this->getURL().PAGE_NAME.'=1">1</a>'.CR;
			}
			
		}
		if( $this->page > ( $this->adjacent_num+2 ) ) 
		{
			$this->html.= ''.CR;
		}
	}
	
	/**
	 * �ڶ���  ����ʡ�Ժ��м���ǿ�
	 */
	private function middle_block()
	{
		$page_min = ( $this->page > $this->adjacent_num ) ? ( $this->page - $this->adjacent_num ) : 1;
		$page_max = ( $this->page < ($this->page_num-$this->adjacent_num)) ? ($this->page+$this->adjacent_num) : $this->page_num ;
		for( $i=$page_min; $i<=$page_max; $i++)
		{
			if( $i == $this->page ) 
			{
				$this->html .= '<a class="hover">' . $i . '</a>'.CR;
			}
			else
			{
				if($this->isw){
					$this->html .= '<a href="' . $this->getURLwjt($i).  '">' . $i . '</a>'.CR;
				}else{
					$this->html .= '<a href="' . $this->getURL() .PAGE_NAME.'=' . $i . '">' . $i . '</a>'.CR;
				}
				
			}
		}
	}

	/**
	 * ���һ��  ���һ��ʡ�Ժź�ĵ��ǿ�
	 */
	private function last_block()
	{
		if( $this->page < ($this->page_num - $this->adjacent_num - 1)) 
		{
			$this->html .= ''.CR;
		}
		if( $this->page < ($this->page_num-$this->adjacent_num )) 
		{	if($this->isw){
			$this->html .= '<a href="' . $this->getURLwjt($this->page_num) . '">' . $this->page_num . '</a>'.CR;
		}else{
			$this->html .= '<a href="' . $this->getURL() .PAGE_NAME.'=' . $this->page_num . '">' . $this->page_num . '</a>'.CR;
		}
			
		}
	}
	
	/**
	 * ��ʾ��ҳ
	 * 
	 * $out_flag bool ����ͷ��ط�ҳhtml�ı�־  true:ֱ�����   false:���ط�ҳ��html
	 * 
	 */
	public function display( $out_flag = false ) 
	{		
		$this->html = ''.CR;
		
		if( $this->display_str_flag === true )
		{//��ҳ��βҳ���ַ�����ʾ
			$this->first_page();  	//��ʾ��ҳ
			$this->prev_page();		//��ʾ��һҳ
			$this->middle_block();	//��ʾ�м��
			$this->next_page();		//��ʾ��һҳ 
			$this->last_page();		//��ʾ���һҳ 
		}
		else 
		{//��ҳ��βҳ��������ʾ
			$this->prev_page(); 	//��ʾ��һҳ
			$this->first_block();	//��ʾ��һ��
			$this->middle_block();	//��ʾ�м��
			$this->last_block();	//��ʾ���һ��
			$this->next_page();		//��ʾ��һҳ
		}
		
		$this->html .= '';
		
		if($out_flag === false)
		{//���ط�ҳ html��
			return $this->html;
		}
		else
		{//�����ҳ html��
			echo $html;
		}
	}
}


?>
