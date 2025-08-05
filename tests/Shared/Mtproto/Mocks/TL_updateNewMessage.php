<?php

namespace Tests\Shared\Mtproto\Mocks;

use App\Shared\Base\Mtproto\InputSerializedData;
use App\Shared\Base\Mtproto\Message;
use App\Shared\Base\Mtproto\OutputSerializedData;

class TL_updateNewMessage extends Update
{
    const CONSTRUCTOR = 0x1f2b0afd;

    public Message $message;
    public int $pts;
    public int $pts_count;

    public function exposeParams(): array {
        return ['message', 'pts', 'pts_count'];
    }

    public function readParams(InputSerializedData $stream): void {
        $messageConstructor = $stream->readInt32();
        $this->message = Message::TLdeserialize($stream, $messageConstructor);
        $this->pts = $stream->readInt32();
        $this->pts_count = $stream->readInt32();
    }

    public function serializeToStream(OutputSerializedData $stream): void {
        $stream->writeInt32(self::CONSTRUCTOR);
        $this->message->serializeToStream($stream);
        $stream->writeInt32($this->pts);
        $stream->writeInt32($this->pts_count);
    }
}