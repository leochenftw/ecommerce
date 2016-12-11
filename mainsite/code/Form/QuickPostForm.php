<?php use SaltedHerring\Debugger as Debugger;
/**
 * 
 * */
class QuickPostForm extends Form {
			
	public function __construct($controller) {
		
		$fields = new FieldList(
			$title		=	TextField::create('Title'),
			$content	=	TextareaField::create('Content'),
			$uploader	=	UploadField::create('Image', 'Image uploader')
		);
		
		$uploader->setFolderName('blog-images')
				->setCanAttachExisting(false)
				->setAllowedMaxFileNumber(9)
				->setAllowedExtensions(array('jpg', 'jpeg', 'png'))
				->setPreviewMaxWidth(280)
				->setPreviewMaxHeight(395)
				->setCanPreviewFolder(false)
				->setAutoUpload(false)
				->setFieldHolderTemplate('FrontendUploadField');
		
		
		$actions = new FieldList(
			$btnSubmit = FormAction::create('doSubmitForm','Create')
		);
		
		$btnSubmit->addExtraClass('button');
		
		$required = new RequiredFields(array(
			$title,
			$content
		));
		
		parent::__construct($controller, 'QuickPostForm', $fields, $actions, $required);
		$this->setFormMethod('POST', true)
			 ->setFormAction(Controller::join_links(BASE_URL, "fastpost", "QuickPostForm"));
	}
	
	public function doSubmitForm($data, $form) {
		$data = $form->getData();
		$blogentry = new BlogEntry();
		$blogentry->ParentID = 24;
		$blogentry->Title = $data['Title'];
		$blogentry->Content = $data['Content'];
		$blogentry->writeToStage('Stage');
		$blogentry->URLSegment = 'blog-entry-' . $blogentry->ID;
		if (!empty($data['Image']['Files'])) {
			$images = $data['Image']['Files'];
			foreach ($images as $image) {
				$photo = new ProductPhoto();
				$photo->PhotoID = $image;
				$photo->InBlogID = $blogentry->ID;
				$photo->write();
			}
		}
		$blogentry->writeToStage('Stage');
		$blogentry->doPublish();
		return Controller::curr()->redirectBack();
	}
}