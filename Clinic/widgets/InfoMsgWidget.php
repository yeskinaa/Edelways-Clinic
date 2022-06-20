<?php

namespace app\widgets;

class InfoMsgWidget extends \yii\bootstrap4\Widget
{
    public $title;

	public function init(){
        parent::init();
        if ($this->title === null) {
            $this->title = 'DELETED';
        }
    }

	public function run() {
		return $this->render('info-msg/view', [
			'title' => $this->title,
		]);
	}
}
?>