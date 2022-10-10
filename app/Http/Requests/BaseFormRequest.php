<?php

namespace App\Http\Requests;

use Illuminate\Http\File;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Http\FormRequest;

class BaseFormRequest extends FormRequest
{
    /**
     * Is it an update request?
     *
     * @var boolean
     */
    protected $updating = false;

    /**
     * The route model param name.
     *
     * @var string
     */
    protected $routeKey = '';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * Returns "required" when creating and "sometimes" when updating.
     */
    protected function requiredRule(): string
    {
        return $this->updating ? 'sometimes' : 'required';
    }

    /**
     * Get attribute from request or model when creating or updating respectively.
     */
    protected function attr(string $key): ?string
    {
        return $this->get(
            $key,
            $this->updating ? $this->getRouteModel()->$key : \null,
        );
    }

    /**
     * Get the model of the current route.
     */
    protected function getRouteModel()
    {
        return \optional($this->route($this->routeKey));
    }

    /**
     *  Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Add support for data URIs/base64 file uploads...
        foreach ($this->rules() as $field => $ruleSet) {
            $value         = $this->input($field);
            $ruleSet       = \is_array($ruleSet) ? $ruleSet : \explode('|', $ruleSet);
            $hasFileRules  = \in_array('file', $ruleSet) || \in_array('image', $ruleSet);

            if (
                $hasFileRules &&
                $value !== \null &&
                !$this->file($field) &&
                \is_string($value)
            ) {
                if (\strpos($value, ';base64') !== \false) {
                    [, $value] = \explode(';', $value);
                    [, $value] = \explode(',', $value);
                }

                $binaryData = \base64_decode($value);

                if ($binaryData === \false) {
                    continue;
                }

                $tmpFile = \tempnam(\sys_get_temp_dir(), Str::orderedUuid());

                if ($tmpFile === \false) {
                    continue;
                }

                if (\file_put_contents($tmpFile, $binaryData) === \false) {
                    continue;
                }

                $file = new File($tmpFile);

                if (!$file->isFile()) {
                    continue;
                }

                $uploadedFile = new UploadedFile(
                    $file->getPathname(),
                    $file->getBasename(),
                    $file->getMimeType(),
                    \UPLOAD_ERR_OK,
                    \true
                );

                if (!$uploadedFile->isValid()) {
                    continue;
                }

                $this->offsetSet($field, $uploadedFile);

                $this->files->set($field, $uploadedFile);
            }
        }

        // Continue the preparation..
        parent::prepareForValidation();
    }
}
