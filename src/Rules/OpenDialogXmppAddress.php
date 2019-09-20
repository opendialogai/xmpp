<?php

declare(strict_types=1);

namespace OpenDialogAi\Xmpp\Rules;

use Illuminate\Contracts\Validation\Rule;

class OpenDialogXmppAddress implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $pattern = config('opendialog.xmpp.address_format');
        return (preg_match("/^.+{$pattern}/", $value) === 1);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The :attribute address must be a correctly formed Open Dialog XMPP address.';
    }
}
