<?php

namespace Tests\Shared\Mtproto;

use App\Shared\Mtproto\TLSerializer;
use App\Task\Infrastructure\Mtproto\Requests\RequestCreateTask;
use PHPUnit\Framework\TestCase;
use Tests\Shared\Mtproto\Mocks\TL_updateNewMessage;

class TLSerializerTest extends TestCase
{
    public function test_with_extend_class()
    {
        $serialized = TLSerializer::toTL(new TL_updateNewMessage());
        $this->assertSame(
            'updateNewMessage message:Message pts:int pts_count:int = Update;',
            $serialized
        );
    }

    public function test_generate_constructor()
    {
        $serialized = TLSerializer::generateConstructor(new TL_updateNewMessage());
        $this->assertSame('0x1f2b0afd', $serialized);
    }
}
