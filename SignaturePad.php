<?php
class SignaturePad extends CInputWidget
{
	protected static $registered=false; 
	/**
	 * 
	 * @var string
	 */
	private $_assetsUrl;
	/**
	 * Label for the clear button
	 * @var string
	 */
	public $clearLabel;
	/**
	 * width for the signature canvas
	 * @var int
	 */
	public $width=300;
	/**
	 * height for the signature canvas
	 * @var int
	 */
	public $height=100;
	/**
	 * options for the plugin
	 * reference: http://thomasjbradley.ca/lab/signature-pad/#options-ref
	 * @var array
	 */
	public $options=array();
	/**
	 * (non-PHPdoc)
	 * @see CWidget::init()
	 */
	public function init()
	{
		if($this->clearLabel===null)
			$this->clearLabel=Yii::t('app','Clear');
	}
	/**
	 * (non-PHPdoc)
	 * @see CWidget::run()
	 */
	public function run()
	{
		list($name,$id)=$this->resolveNameID();

		if(isset($this->htmlOptions['id']))
			$id=$this->htmlOptions['id'];
		else
			$this->htmlOptions['id']=$id;
		if(isset($this->htmlOptions['name']))
			$name=$this->htmlOptions['name'];
		
		if(isset($this->htmlOptions['class']))
			$this->htmlOptions['class'].=' sigPad';
		else
			$this->htmlOptions['class']='sigPad';
		
		echo CHtml::openTag('div',$this->htmlOptions);
		
		echo CHtml::openTag('div',array('class'=>'sig sigWrapper'));
		
		echo CHtml::tag('canvas',array('class'=>'pad','width'=>$this->width,'height'=>$this->height,'style'=>"height:{$this->height}px;width:{$this->width}px;"),'');
		
		if($this->hasModel())
			echo CHtml::activeHiddenField($this->model, $this->attribute,array('class'=>'output'));
		else
			echo CHtml::hiddenField($name, $this->value,array('class'=>'output'));
		
		echo CHtml::closeTag('div');
		
		echo CHtml::tag('span',array('class'=>'clearButton'),
			CHtml::link($this->clearLabel,'#clear-it')
		);
		echo CHtml::closeTag('div');
		
		$this->registerAssets();
	}
	/**
	 * register required assets
	 */
	protected function registerAssets()
	{
		Yii::app()->clientScript
			->registerCoreScript('jquery')
			->registerCssFile($this->getAssetsUrl().'/jquery.signaturepad.css')
			->registerScriptFile($this->getAssetsUrl().'/jquery.signaturepad.min.js')
			->registerScriptFile($this->getAssetsUrl().'/json2.min.js');
			
		$options=$this->options;
		if(!isset($options['drawOnly']))
		{
			$options['drawOnly']=true;
		}
		if(!isset($options['lineTop']))
		{
			$options['lineTop']=$this->height-15;
		}
		$script='$("#'.$this->htmlOptions['id'].'").parent().signaturePad('.CJavaScript::encode($options).')';
		if(!empty($this->value))
		{
			$script.='.regenerate('.CJavaScript::encode($this->value).');';
		}
		echo CHtml::script("$(function(){{$script}});");
		if(!self::$registered)
		{
			echo '<!--[if lt IE 9]><script src="'.$this->getAssetsUrl().'/flashcanvas.js"></script><![endif]-->';
			self::$registered=true;
		}
	}
	/**
	 * 
	 * @return string
	 */
	protected function getAssetsUrl()
	{
		if($this->_assetsUrl===null)
		{
			$this->_assetsUrl=Yii::app()->assetManager->publish(dirname(__FILE__).DIRECTORY_SEPARATOR.'assets');
		}
		return $this->_assetsUrl;
	}
}