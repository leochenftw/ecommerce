<?php
use SaltedHerring\Debugger;

class StoristPhotoController extends Page_Controller
{
    /**
     * Defines methods that can be called directly
     * @var array
     */
    private static $allowed_actions = array(
        'UploadHandler'     =>  true
    );

    public function UploadHandler($request)
    {
        $id = $request->postVar('ref_id');
        $target = $request->postVar('attach_to');
        $file = $request->postVar('photos');
        $inner_path = 'products/storistuploads';

        if (!empty($file['tmp_name'])) {
            $photo = $file['tmp_name'][0];
            $dest = Folder::find_or_make($inner_path)->getFullPath();
            $name = $file["name"][0];
            $fn = sha1(mt_rand() . mt_rand() . (microtime(true) * 1000) . $name);
            $ext = end((explode(".", $name)));
            $dest .= $fn . '.' . $ext;
            move_uploaded_file($photo, $dest);

            $image = new Image();
            $image->Filename = 'assets/' . $inner_path . '/' . $fn . '.' . $ext;
            $image->Title = $fn;
            $image->ParentID = Folder::find_or_make($inner_path)->ID;
            $image->$target = $id;
            $image->write();

            if ($target == 'ProductPhotoID') {

                $path = $image->getFullPath();
                $imgFile = new Imagick($path);
                $d = $imgFile->getImageGeometry();
                $w = $d['width'];
                $h = $d['height'];

                $product = Versioned::get_by_stage('ProductPage', 'Stage')->byID($id);
                if ($w > $h) {
                    $product->PosterID = $image->ID;
                } elseif ($w < $h) {
                    $product->VPosterID = $image->ID;
                } elseif ($w == $h) {
                    $product->SquareID = $image->ID;
                }
                $product->writeToStage('Stage');
                //$product->writeToStage('Live');
            }

            return json_encode(array(
                'code'      =>  200,
                'message'   =>  'Photo attached'
            ));
        }

        return json_encode(array(
            'code'      =>  500,
            'message'   =>  'error'
        ));
    }

}
