<?php

namespace app\models;

use Yii;
include_once(dirname(__FILE__)."/../common/interface.php");
namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

/**
 * UploadForm is the model behind the upload form.
 */
class UploadForm extends Model
{
    /**
     * @var UploadedFile file attribute
     */
    public $file;
    public $pool_group;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['file'], 'file'],
            [['pool_group'],'safe'],
        ];
    }
}
?>
