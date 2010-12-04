<div class="commandFrame inputFrameShadow popupFrame">
<?php
if(!empty($_oButtons))
{
//	$_oButtons = array_reverse($_oButtons);
	foreach ($_oButtons as $button)
	{
		$class = (empty($button->class))?null:sprintf('class="%s"',$button->class);
		$id = (empty($button->id))?null:sprintf('id="%s"',$button->id);
		$name = (empty($button->name))?null:sprintf('name="%s"',$button->name);
		$title = (empty($button->title))?null:sprintf('title="%s"',$button->title);
		$type = (empty($button->type))?null:sprintf('type="%s"',$button->type);
		printf("<button %s %s %s %s %s %s>%s</button>",$class,$id,$name,$title,$type,$button->events,$button->value);
	}
}
?>
</div>
