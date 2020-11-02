<?php

namespace OpenDialogAi\Xmpp\Helper;

final class UserHelper
{
    public const SEPARATOR = "?room=";
    public const ID_USER = 'id';
    public const ROOM = 'room';

    /**
     * @param string $id
     * @param string|null $room
     * @return string
     */
    public static function createUserId(string $id, string $room = null): string
    {
        return is_null($room) ? $id : $id . self::SEPARATOR . $room;
    }

    /**
     * @param string $id
     * @return array
     */
    public static function getUserId(string $id): array
    {
        if (!strpos($id, self::SEPARATOR)) {
            return ['id' => $id];
        }
        $id = explode(self::SEPARATOR, $id);

        return [
            self::ID_USER => $id[0],
            self::ROOM => $id[1]
        ];
    }
}
