<?php

namespace WalkerChiu\MallMerchandise\Models\Forms;

use Illuminate\Support\Facades\Request;
use WalkerChiu\Core\Models\Forms\FormRequest;

class VariantFormRequest extends FormRequest
{
    /**
     * @Override Illuminate\Foundation\Http\FormRequest::getValidatorInstance
     */
    protected function getValidatorInstance()
    {
        $request = Request::instance();
        $data = $this->all();
        if (
            $request->isMethod('put')
            && empty($data['id'])
            && isset($request->id)
        ) {
            $data['id'] = (int) $request->id;
            $this->getInputSource()->replace($data);
        }

        return parent::getValidatorInstance();
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return Array
     */
    public function attributes()
    {
        return [
            'product_id'  => trans('php-mall-merchandise::variant.product_id'),
            'serial'      => trans('php-mall-merchandise::variant.serial'),
            'identifier'  => trans('php-mall-merchandise::variant.identifier'),
            'cost'        => trans('php-mall-merchandise::variant.cost'),
            'price'       => trans('php-mall-merchandise::variant.price'),
            'price_sale'  => trans('php-mall-merchandise::variant.price_sale'),
            'covers'      => trans('php-mall-merchandise::variant.covers'),
            'images'      => trans('php-mall-merchandise::variant.images'),
            'videos'      => trans('php-mall-merchandise::variant.videos'),
            'options'     => trans('php-mall-merchandise::variant.options'),
            'is_enabled'  => trans('php-mall-merchandise::variant.is_enabled'),
            'name'        => trans('php-mall-merchandise::variant.name'),
            'abstract'    => trans('php-mall-merchandise::variant.abstract'),
            'description' => trans('php-mall-merchandise::variant.description'),
            'keywords'    => trans('php-mall-merchandise::variant.keywords'),
            'remarks'     => trans('php-mall-merchandise::variant.remarks')
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return Array
     */
    public function rules()
    {
        $rules = [
            'product_id'  => ['required','int','exists:'.config('wk-core.table.mall-merchandise.products').',id'],
            'serial'      => '',
            'identifier'  => 'required|string|max:255',
            'cost'        => 'nullable|numeric|min:0|not_in:0',
            'price'       => 'nullable|numeric|min:0|not_in:0',
            'price_sale'  => 'nullable|numeric|min:0|not_in:0',
            'covers'      => 'nullable|json',
            'images'      => 'nullable|json',
            'videos'      => 'nullable|json',
            'options'     => 'nullable|json',
            'is_enabled'  => 'required|boolean',

            'name'        => 'required|string|max:255',
            'abstract'    => '',
            'description' => '',
            'keywords'    => '',
            'remarks'     => ''
        ];

        $request = Request::instance();
        if (
            $request->isMethod('put')
            && isset($request->id)
        ) {
            $rules = array_merge($rules, ['id' => ['required','string','exists:'.config('wk-core.table.mall-merchandise.products').',id']]);
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return Array
     */
    public function messages()
    {
        return [
            'id.required'         => trans('php-core::validation.required'),
            'id.exists'           => trans('php-core::validation.exists'),
            'product_id.required' => trans('php-core::validation.required'),
            'product_id.exists'   => trans('php-core::validation.exists'),
            'identifier.required' => trans('php-core::validation.required'),
            'identifier.max'      => trans('php-core::validation.max'),
            'cost.numeric'        => trans('php-core::validation.numeric'),
            'cost.min'            => trans('php-core::validation.min'),
            'cost.not_in'         => trans('php-core::validation.not_in'),
            'price.numeric'       => trans('php-core::validation.numeric'),
            'price.min'           => trans('php-core::validation.min'),
            'price.not_in'        => trans('php-core::validation.not_in'),
            'price_sale.numeric'  => trans('php-core::validation.numeric'),
            'price_sale.min'      => trans('php-core::validation.min'),
            'price_sale.not_in'   => trans('php-core::validation.not_in'),
            'covers.json'         => trans('php-core::validation.json'),
            'images.json'         => trans('php-core::validation.json'),
            'videos.json'         => trans('php-core::validation.json'),
            'options.json'        => trans('php-core::validation.json'),
            'is_enabled.required' => trans('php-core::validation.required'),
            'is_enabled.boolean'  => trans('php-core::validation.boolean'),

            'name.required'       => trans('php-core::validation.required'),
            'name.string'         => trans('php-core::validation.string'),
            'name.max'            => trans('php-core::validation.max')
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after( function ($validator) {
            $data = $validator->getData();
            if (isset($data['identifier'])) {
                $result = config('wk-core.class.mall-merchandise.variant')::where('identifier', $data['identifier'])
                                ->when(isset($data['id']), function ($query) use ($data) {
                                    return $query->where('id', '<>', $data['id']);
                                  })
                                ->exists();
                if ($result)
                    $validator->errors()->add('identifier', trans('php-core::validation.unique', ['attribute' => trans('php-mall-merchandise::variant.identifier')]));
            }
        });
    }
}
