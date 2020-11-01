<?php

namespace OpenDialogAi\Xmpp\SensorEngine\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IncomingXmppMessage extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'notification' => [
                'required',
                'string',
                'in:message'
            ],
            'from' => [
                'required',
                'string',
                'email:rfc,filter'
            ],
            'to' => [
                'required',
                'string',
                'email:rfc,filter'
            ],
            'room' => [
                'string'
            ],
            'lang' => [
                'required',
                'string',
                'in:' . implode(',', config('opendialog.xmpp.supported_languages'))
            ],
            'content' => [
                'required',
            ],
            'content.type' => [
                'required',
                'string',
                'in:' . implode(',', config('opendialog.xmpp.allowed_message_content'))
            ],
            'content.author' => [
                'required',
                'string',
                'same:from'
            ],
            'content.data' => [
                'required',
            ],
            'content.data.text' => [
                'required',
                'string'
            ]
        ];
    }
}
