yii-signature-pad
=================

Widget to add a signature pad to your form  
  
View  
``` php
echo $form->labelEx($model,"signatureJSON");
$this->widget('ext.signature-pad.SignaturePad',array(
			'model'=>$model,
			'attribute'=>"signatureJSON",
			'value'=>$model->signatureJSON,
			'options'=>array(
				'penColour'=>'#000',
				'validateFields'=>false,
			)
))?>
echo $form->error($model,"signatureJSON");
```
Controller  

``` php
Yii::import('ext.signature-pad.SignatureToImage');
//transforms json response into image
$image=SignatureToImage::fromJSON($model->signatureJSON,array(
	'imageSize'=>array(300,100)
));
//saves image to disk
if(!imagepng($image, $model->signaturePath))
	throw new Exception('Error saving signature');
//clear memory
imagedestroy($image);
```

MORE DOCUMENTATION TBD  