<?php

declare(strict_types=1);

namespace OpenDialogAi\Xmpp\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenDialogAi\SensorEngine\Contracts\IncomingMessageInterface;

class IncomingXmppMessage extends FormRequest implements IncomingMessageInterface
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
                'email:rfc,dns,filter'
            ],
            'to' => [
                'required',
                'string',
                'email:rfc,dns,filter'
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
