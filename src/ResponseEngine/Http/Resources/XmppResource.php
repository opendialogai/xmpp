<?php

namespace OpenDialogAi\Xmpp\ResponseEngine\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class XmppResource extends JsonResource
{
    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
